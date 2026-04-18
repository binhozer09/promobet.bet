<?php 

include 'partials/html.php' ?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include_once "services/database.php";
//include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
$csrf = new CSRF_Protect();

checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
    echo "<script>setTimeout(function() { 
        var modal = new bootstrap.Modal(document.getElementById('modal2FA'));
        modal.show();
    }, 500);</script>";
}

# Debug: Log do início do processamento
error_log("DEBUG: Iniciando processamento da página de identidade visual");

# Verificar se as funções não existem antes de declará-las
if (!function_exists('upload_and_rename_as_png')) {
    function upload_and_rename_as_png($file, $target_name)
    {
        error_log("DEBUG: Iniciando upload_and_rename_as_png para: " . $target_name);
        
        $upload_dir = "../uploads/"; // Diretório de uploads
        
        // Verificar se o diretório existe
        if (!is_dir($upload_dir)) {
            error_log("DEBUG: Diretório de upload não existe, tentando criar: " . $upload_dir);
            if (!mkdir($upload_dir, 0755, true)) {
                error_log("ERROR: Não foi possível criar o diretório de upload");
                return false;
            }
        }
        
        // Verificar permissões de escrita
        if (!is_writable($upload_dir)) {
            error_log("ERROR: Diretório de upload não tem permissão de escrita: " . $upload_dir);
            return false;
        }
        
        $target_file = $upload_dir . $target_name . '.png.webp'; // Caminho final do arquivo
        
        error_log("DEBUG: Arquivo de destino: " . $target_file);

        // Verifica se o arquivo enviado é uma imagem
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            error_log("ERROR: Arquivo não é uma imagem válida");
            return false; // Não é uma imagem válida
        }
        
        error_log("DEBUG: Arquivo é uma imagem válida - " . $check['mime']);

        // Obter a extensão original do arquivo
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        error_log("DEBUG: Extensão do arquivo: " . $file_extension);

        // Mover o arquivo para o diretório de uploads com o novo nome
        if ($file_extension === 'png') {
            // Se já for PNG, apenas mover
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                error_log("DEBUG: Arquivo PNG movido com sucesso");
                return $target_name . '.png.webp';
            } else {
                error_log("ERROR: Falha ao mover arquivo PNG");
                return false;
            }
        } else {
            // Caso a imagem não seja PNG, converter para PNG
            $image = null;
            switch ($file_extension) {
                case 'jpg':
                case 'jpeg':
                    error_log("DEBUG: Convertendo JPEG para PNG");
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'gif':
                    error_log("DEBUG: Convertendo GIF para PNG");
                    $image = imagecreatefromgif($file['tmp_name']);
                    break;
                case 'webp':
                    error_log("DEBUG: Convertendo WebP para PNG");
                    $image = imagecreatefromwebp($file['tmp_name']);
                    break;
                default:
                    error_log("ERROR: Formato não suportado: " . $file_extension);
                    return false; // Formato não suportado
            }

            if ($image !== null) {
                // Salva a imagem como PNG
                if (imagepng($image, $target_file)) {
                    imagedestroy($image); // Libera a memória
                    error_log("DEBUG: Imagem convertida e salva com sucesso");
                    return $target_name . '.png.webp';
                } else {
                    error_log("ERROR: Falha ao salvar imagem convertida");
                    imagedestroy($image);
                    return false;
                }
            } else {
                error_log("ERROR: Falha ao criar recurso de imagem");
                return false;
            }
        }
    }
}

if (!function_exists('update_config_images')) {
    function update_config_images($logo = null, $favicon = null, $baixar = null)
    {
        global $mysqli;
        
        error_log("DEBUG: Iniciando update_config_images");
        error_log("DEBUG: Logo: " . ($logo ? $logo : 'null'));
        error_log("DEBUG: Favicon: " . ($favicon ? $favicon : 'null'));
        error_log("DEBUG: Baixar: " . ($baixar ? $baixar : 'null'));

        $qry_string = "UPDATE config SET ";
        $params = [];
        $types = '';

        // Adicionar logo à consulta se estiver presente
        if ($logo !== null) {
            $qry_string .= "logo = ?, ";
            $params[] = $logo;
            $types .= 's';
        }

        // Adicionar favicon à consulta se estiver presente
        if ($favicon !== null) {
            $qry_string .= "favicon = ?, ";
            $params[] = $favicon;
            $types .= 's';
        }

        // Adicionar baixar à consulta se estiver presente
        if ($baixar !== null) {
            $qry_string .= "baixar = ?, ";
            $params[] = $baixar;
            $types .= 's';
        }

        // Remover a última vírgula e espaço
        $qry_string = rtrim($qry_string, ', ') . " WHERE id = 1";
        
        error_log("DEBUG: Query SQL: " . $qry_string);
        error_log("DEBUG: Parâmetros: " . implode(', ', $params));

        $qry = $mysqli->prepare($qry_string);
        if (!$qry) {
            error_log("ERROR: Falha ao preparar query: " . $mysqli->error);
            return false;
        }
        
        if (!empty($params)) {
            $qry->bind_param($types, ...$params);
        }
        
        $result = $qry->execute();
        if (!$result) {
            error_log("ERROR: Falha ao executar query: " . $qry->error);
            return false;
        }
        
        error_log("DEBUG: Query executada com sucesso");
        return $result;
    }
}

# Inicializar variáveis para toast
$toastType = '';
$toastMessage = '';

# Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("DEBUG: Formulário enviado via POST");
    error_log("DEBUG: Files enviados: " . print_r($_FILES, true));
    
    $logo = null;
    $favicon = null;
    $baixar = null;

    try {
        // Verificar se o logo foi enviado
        if (!empty($_FILES['logo']['name'])) {
            error_log("DEBUG: Processando logo");
            $logo = upload_and_rename_as_png($_FILES['logo'], 'logo');
            if (!$logo) {
                $toastType = 'error';
                $toastMessage = 'Erro ao enviar o logo. Verifique a extensão do arquivo.';
                error_log("ERROR: Falha no upload do logo");
            } else {
                error_log("DEBUG: Logo processado com sucesso: " . $logo);
            }
        }

        // Verificar se o favicon foi enviado
        if (!empty($_FILES['favicon']['name'])) {
            error_log("DEBUG: Processando favicon");
            $favicon = upload_and_rename_as_png($_FILES['favicon'], 'favicon');
            if (!$favicon) {
                $toastType = 'error';
                $toastMessage = 'Erro ao enviar o favicon. Verifique a extensão do arquivo.';
                error_log("ERROR: Falha no upload do favicon");
            } else {
                error_log("DEBUG: Favicon processado com sucesso: " . $favicon);
            }
        }

        // Verificar se o baixar foi enviado
        if (!empty($_FILES['baixar']['name'])) {
            error_log("DEBUG: Processando imagem de download");
            $baixar = upload_and_rename_as_png($_FILES['baixar'], 'baixar');
            if (!$baixar) {
                $toastType = 'error';
                $toastMessage = 'Erro ao enviar a imagem de download. Verifique a extensão do arquivo.';
                error_log("ERROR: Falha no upload da imagem de download");
            } else {
                error_log("DEBUG: Imagem de download processada com sucesso: " . $baixar);
            }
        }

        // Atualizar as imagens no banco de dados
        if ($logo || $favicon || $baixar) {
            error_log("DEBUG: Tentando atualizar banco de dados");
            if (update_config_images($logo, $favicon, $baixar)) {
                $toastType = 'success';
                $toastMessage = 'Identidade visual atualizada com sucesso!';
                error_log("DEBUG: Banco de dados atualizado com sucesso");
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao atualizar as configurações no banco de dados.';
                error_log("ERROR: Falha ao atualizar banco de dados");
            }
        } else {
            error_log("DEBUG: Nenhuma imagem foi processada");
        }
        
    } catch (Exception $e) {
        error_log("EXCEPTION: " . $e->getMessage());
        $toastType = 'error';
        $toastMessage = 'Erro interno: ' . $e->getMessage();
    }
}

# Buscar o caminho atual das imagens
try {
    error_log("DEBUG: Buscando configurações atuais do banco");
    $query = "SELECT logo, favicon, baixar FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        error_log("ERROR: Falha na query SELECT: " . mysqli_error($mysqli));
        $config = ['logo' => '', 'favicon' => '', 'baixar' => ''];
    } else {
        $config = mysqli_fetch_assoc($result);
        error_log("DEBUG: Configurações carregadas: " . print_r($config, true));
    }
} catch (Exception $e) {
    error_log("EXCEPTION ao buscar config: " . $e->getMessage());
    $config = ['logo' => '', 'favicon' => '', 'baixar' => ''];
}

$modo_plataforma = $modo_plataforma ?? 0; // Definir default se não existir
error_log("DEBUG: Modo plataforma: " . $modo_plataforma);
?>

<head>
    <?php $title = "Gerenciamento de Identidade Visual";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .brand-card {
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .brand-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #667eea 100%);
        }
        
        .brand-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.2);
        }
        
        .brand-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        
        .brand-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: inherit;
            border-radius: inherit;
            filter: blur(20px);
            opacity: 0.3;
            transform: scale(1.2);
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }
        
        .favicon-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.4);
        }
        
        .download-icon {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            box-shadow: 0 15px 40px rgba(78, 205, 196, 0.4);
        }
        
        .upload-area {
            border: 2px dashed rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.02);
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: #667eea;
            background: rgba(255,255,255,0.05);
            transform: scale(1.02);
        }
        
        .upload-area.has-file {
            border-color: #22c55e;
            background: rgba(34, 197, 94, 0.1);
        }
        
        .upload-icon {
            font-size: 2.5rem;
            margin-bottom: 16px;
            opacity: 0.6;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover .upload-icon {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .section-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
        }
        
        .save-button {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 20px;
            padding: 20px 64px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            transition: all 0.4s ease;
            box-shadow: 0 10px 35px rgba(86, 171, 47, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .save-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .save-button:hover::before {
            left: 100%;
        }
        
        .save-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 50px rgba(86, 171, 47, 0.5);
        }
        
        .save-button:disabled {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
        }
        
        .demo-warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            border: none;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            color: #2d3436;
        }
        
        .preview-container {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 20px;
            margin-top: 16px;
            display: none;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 150px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        
        .preview-image:hover {
            transform: scale(1.05);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 12px;
        }
        
        .status-configured {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .status-empty {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .file-info {
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
        }
        
        .analytics-overview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 32px;
            color: white;
            margin-bottom: 40px;
        }
        
        .stat-item {
            text-align: center;
            padding: 16px;
        }
        
        .stat-item i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        
        .tips-container {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 6px solid #2196f3;
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            color: #1565c0;
        }
        
        .tips-container h6 {
            color: #0d47a1;
            margin-bottom: 16px;
        }
        
        .tips-list {
            margin: 0;
            padding-left: 24px;
        }
        
        .tips-list li {
            margin-bottom: 12px;
            line-height: 1.6;
        }
        
        .remove-btn {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 12px;
        }
        
        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }
        
        .progress-upload {
            margin-top: 20px;
            display: none;
        }
        
        .progress-bar-custom {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
            height: 8px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: inherit;
            transition: width 0.3s ease;
            width: 0%;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .cache-buster {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            opacity: 0.8;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .brand-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .brand-card:nth-child(1) { animation-delay: 0.2s; }
        .brand-card:nth-child(2) { animation-delay: 0.4s; }
        .brand-card:nth-child(3) { animation-delay: 0.6s; }
        
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">

                    <!-- Header Principal -->
                    <div class="col-12">
                        <div class="section-header">
                            <div class="floating-particles">
                                <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
                                <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
                                <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
                                <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
                                <div class="particle" style="left: 60%; animation-delay: 4s;"></div>
                                <div class="particle" style="left: 70%; animation-delay: 5s;"></div>
                                <div class="particle" style="left: 80%; animation-delay: 2s;"></div>
                                <div class="particle" style="left: 90%; animation-delay: 1s;"></div>
                            </div>
                            <h3 class="mb-3">
                                <i class="iconoir-design-nib me-3"></i>Brand Assets Manager
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Configure e gerencie todos os elementos visuais da sua marca
                            </p>
                        </div>
                    </div>

                    <!-- Overview Analytics -->
                    <div class="col-12">
                        <div class="analytics-overview">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-design-nib"></i>
                                        <h6 class="mb-1">Logo Principal</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php if (!empty($config['logo'])): ?>
                                                <span class="status-configured">✓ Configurado</span>
                                            <?php else: ?>
                                                <span class="status-empty">✗ Não configurado</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-star"></i>
                                        <h6 class="mb-1">Favicon</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php if (!empty($config['favicon'])): ?>
                                                <span class="status-configured">✓ Configurado</span>
                                            <?php else: ?>
                                                <span class="status-empty">✗ Não configurado</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-download"></i>
                                        <h6 class="mb-1">Imagem Download</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php if (!empty($config['baixar'])): ?>
                                                <span class="status-configured">✓ Configurado</span>
                                            <?php else: ?>
                                                <span class="status-empty">✗ Não configurado</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-shield-check"></i>
                                        <h6 class="mb-1">Status Geral</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php 
                                            $total_configured = (!empty($config['logo']) ? 1 : 0) + (!empty($config['favicon']) ? 1 : 0) + (!empty($config['baixar']) ? 1 : 0);
                                            echo $total_configured . "/3 Configurados";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dicas Importantes -->
                    <div class="col-12">
                        <div class="tips-container">
                            <h6><i class="iconoir-light-bulb me-2"></i>Dicas para Otimização de Imagens</h6>
                            <ul class="tips-list">
                                <li><strong>Logo:</strong> Use formato PNG com fundo transparente para melhor qualidade</li>
                                <li><strong>Favicon:</strong> Recomendado tamanho 32x32px ou 16x16px para melhor compatibilidade</li>
                                <li><strong>Download:</strong> Optimize as imagens para web (compressão adequada)</li>
                                <li><strong>Cache:</strong> Após atualizar, pode levar alguns minutos para refletir globalmente</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Verificar se está em modo demo -->
                    <?php if ($modo_plataforma == 0): ?>
                    <div class="col-12">
                        <div class="demo-warning">
                            <div class="d-flex align-items-center">
                                <i class="iconoir-warning-triangle h3 me-3 mb-0" style="color: #e17055;"></i>
                                <div>
                                    <h6 class="mb-1" style="color: #2d3436;">Modo Demonstração Ativo</h6>
                                    <p class="mb-0 opacity-75">As imagens não podem ser alteradas no modo demo por segurança.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" enctype="multipart/form-data" id="brandForm">
                            <div class="row">
                                
                                <!-- Logo Principal -->
                                <div class="col-lg-4 mb-5">
                                    <div class="brand-card p-5 h-100">
                                        <div class="brand-icon logo-icon mx-auto">
                                            <i class="iconoir-design-nib" style="position: relative; z-index: 1;"></i>
                                        </div>
                                        
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold mb-2">Logo Principal</h4>
                                            <p class="text-light opacity-75 mb-0">
                                                Logotipo principal da sua marca
                                            </p>
                                        </div>

                                        <div class="upload-area" id="logoArea" onclick="document.getElementById('logoInput').click()">
                                            <i class="iconoir-cloud-upload upload-icon"></i>
                                            <div class="fw-medium mb-2">Clique ou arraste o logo aqui</div>
                                            <div class="small opacity-75">PNG, JPG, GIF, WebP (max: 5MB)</div>
                                        </div>

                                        <?php if (!empty($config['logo'])): ?>
                                        <div class="preview-container" id="logoPreview" style="display: block;">
                                            <div class="text-center">
                                                <img src="/uploads/<?= $config['logo']; ?>?v=<?= time(); ?>" class="preview-image" alt="Logo Preview" id="logoImage">
                                                <div class="cache-buster">v<?= time(); ?></div>
                                                <div class="status-badge status-configured mt-3">
                                                    <i class="iconoir-check-circle me-2"></i>Configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('logo')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="preview-container" id="logoPreview">
                                            <div class="text-center">
                                                <img class="preview-image" alt="Logo Preview" id="logoImage">
                                                <div class="status-badge status-empty mt-3">
                                                    <i class="iconoir-cancel me-2"></i>Não configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('logo')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="progress-upload" id="logoProgress">
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill"></div>
                                            </div>
                                            <div class="text-center mt-2 small opacity-75">Fazendo upload...</div>
                                        </div>

                                        <input type="file" name="logo" id="logoInput" accept="image/*" style="display: none;">
                                    </div>
                                </div>

                                <!-- Favicon -->
                                <div class="col-lg-4 mb-5">
                                    <div class="brand-card p-5 h-100">
                                        <div class="brand-icon favicon-icon mx-auto">
                                            <i class="iconoir-star" style="position: relative; z-index: 1;"></i>
                                        </div>
                                        
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold mb-2">Favicon</h4>
                                            <p class="text-light opacity-75 mb-0">
                                                Ícone que aparece na aba do navegador
                                            </p>
                                        </div>

                                        <div class="upload-area" id="faviconArea" onclick="document.getElementById('faviconInput').click()">
                                            <i class="iconoir-bookmark upload-icon"></i>
                                            <div class="fw-medium mb-2">Clique ou arraste o favicon aqui</div>
                                            <div class="small opacity-75">Preferencialmente 32x32px ou 16x16px</div>
                                        </div>

                                        <?php if (!empty($config['favicon'])): ?>
                                        <div class="preview-container" id="faviconPreview" style="display: block;">
                                            <div class="text-center">
                                                <img src="/uploads/<?= $config['favicon']; ?>?v=<?= time(); ?>" class="preview-image" alt="Favicon Preview" id="faviconImage">
                                                <div class="cache-buster">v<?= time(); ?></div>
                                                <div class="status-badge status-configured mt-3">
                                                    <i class="iconoir-check-circle me-2"></i>Configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('favicon')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="preview-container" id="faviconPreview">
                                            <div class="text-center">
                                                <img class="preview-image" alt="Favicon Preview" id="faviconImage">
                                                <div class="status-badge status-empty mt-3">
                                                    <i class="iconoir-cancel me-2"></i>Não configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('favicon')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="progress-upload" id="faviconProgress">
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill"></div>
                                            </div>
                                            <div class="text-center mt-2 small opacity-75">Fazendo upload...</div>
                                        </div>

                                        <input type="file" name="favicon" id="faviconInput" accept="image/*" style="display: none;">
                                    </div>
                                </div>

                                <!-- Imagem de Download -->
                                <div class="col-lg-4 mb-5">
                                    <div class="brand-card p-5 h-100">
                                        <div class="brand-icon download-icon mx-auto">
                                            <i class="iconoir-download" style="position: relative; z-index: 1;"></i>
                                        </div>
                                        
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold mb-2">Imagem Download</h4>
                                            <p class="text-light opacity-75 mb-0">
                                                Imagem para botões de download e CTAs
                                            </p>
                                        </div>

                                        <div class="upload-area" id="downloadArea" onclick="document.getElementById('downloadInput').click()">
                                            <i class="iconoir-download-circle upload-icon"></i>
                                            <div class="fw-medium mb-2">Clique ou arraste a imagem aqui</div>
                                            <div class="small opacity-75">PNG, JPG, GIF, WebP (max: 5MB)</div>
                                        </div>

                                        <?php if (!empty($config['baixar'])): ?>
                                        <div class="preview-container" id="downloadPreview" style="display: block;">
                                            <div class="text-center">
                                                <img src="/uploads/<?= $config['baixar']; ?>?v=<?= time(); ?>" class="preview-image" alt="Download Preview" id="downloadImage">
                                                <div class="cache-buster">v<?= time(); ?></div>
                                                <div class="status-badge status-configured mt-3">
                                                    <i class="iconoir-check-circle me-2"></i>Configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('download')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="preview-container" id="downloadPreview">
                                            <div class="text-center">
                                                <img class="preview-image" alt="Download Preview" id="downloadImage">
                                                <div class="status-badge status-empty mt-3">
                                                    <i class="iconoir-cancel me-2"></i>Não configurado
                                                </div>
                                                <div class="remove-btn" onclick="clearPreview('download')">
                                                    <i class="iconoir-trash me-2"></i>Remover
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="progress-upload" id="downloadProgress">
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill"></div>
                                            </div>
                                            <div class="text-center mt-2 small opacity-75">Fazendo upload...</div>
                                        </div>

                                        <input type="file" name="baixar" id="downloadInput" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Salvar -->
                            <div class="text-center mt-5">
                                <?php if ($modo_plataforma == 0): ?>
                                    <button type="button" class="save-button" disabled>
                                        <i class="iconoir-lock me-3"></i>Salvar Configurações (MODO DEMO ATIVADO)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="save-button">
                                        <i class="iconoir-check me-3"></i>Salvar Configurações
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Scripts customizados -->
    <script>
        // Função de Toast
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            toast.className = `toast align-items-center bg-light border-0 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header">
                    <img src="assets/images/logo-sm.png" alt="" height="20" class="me-1">
                    <h5 class="me-auto my-0">Dashboard</h5>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            toastPlacement.appendChild(toast);

            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function() {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Configuração de upload drag & drop
        function setupFileUpload() {
            const fileInputs = ['logoInput', 'faviconInput', 'downloadInput'];
            const uploadAreas = ['logoArea', 'faviconArea', 'downloadArea'];
            
            fileInputs.forEach((inputId, index) => {
                const input = document.getElementById(inputId);
                const area = document.getElementById(uploadAreas[index]);
                const type = inputId.replace('Input', '');
                
                // Drag & Drop events
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });
                
                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });
                
                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        handleFileSelect(files[0], type);
                    }
                });
                
                // File input change
                input.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        handleFileSelect(e.target.files[0], type);
                    }
                });
            });
        }

        // Processar arquivo selecionado
        function handleFileSelect(file, type) {
            // Validar arquivo
            if (!validateFile(file)) {
                return;
            }
            
            // Mostrar preview
            showPreview(file, type);
            
            // Marcar área como tendo arquivo
            const area = document.getElementById(type + 'Area');
            area.classList.add('has-file');
            
            // Simular upload (em produção, aqui faria o upload via AJAX)
            simulateUpload(type);
        }

        // Validar arquivo
        function validateFile(file) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            
            if (file.size > maxSize) {
                showToast('error', 'Arquivo muito grande! Máximo 5MB permitido.');
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                showToast('error', 'Tipo de arquivo não permitido! Use PNG, JPG, GIF ou WebP.');
                return false;
            }
            
            return true;
        }

        // Mostrar preview da imagem
        function showPreview(file, type) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(type + 'Image');
                const preview = document.getElementById(type + 'Preview');
                const statusBadge = preview.querySelector('.status-badge');
                
                img.src = e.target.result;
                preview.style.display = 'block';
                
                // Atualizar status
                statusBadge.className = 'status-badge status-configured mt-3';
                statusBadge.innerHTML = '<i class="iconoir-check-circle me-2"></i>Novo arquivo selecionado';
            };
            reader.readAsDataURL(file);
        }

        // Simular upload com progress bar
        function simulateUpload(type) {
            const progress = document.getElementById(type + 'Progress');
            const progressBar = progress.querySelector('.progress-fill');
            
            progress.style.display = 'block';
            
            let width = 0;
            const interval = setInterval(function() {
                width += Math.random() * 30;
                if (width >= 100) {
                    width = 100;
                    clearInterval(interval);
                    setTimeout(() => {
                        progress.style.display = 'none';
                        progressBar.style.width = '0%';
                    }, 1000);
                }
                progressBar.style.width = width + '%';
            }, 200);
        }

        // Limpar preview
        function clearPreview(type) {
            const input = document.getElementById(type + 'Input');
            const img = document.getElementById(type + 'Image');
            const preview = document.getElementById(type + 'Preview');
            const area = document.getElementById(type + 'Area');
            const statusBadge = preview.querySelector('.status-badge');
            
            input.value = '';
            img.src = '';
            area.classList.remove('has-file');
            
            // Atualizar status
            statusBadge.className = 'status-badge status-empty mt-3';
            statusBadge.innerHTML = '<i class="iconoir-cancel me-2"></i>Não configurado';
            
            showToast('success', 'Imagem removida com sucesso!');
        }

        // Atualizar overview em tempo real
        function updateOverview() {
            const types = ['logo', 'favicon', 'baixar'];
            const overviewStats = document.querySelectorAll('.analytics-overview .stat-item');
            
            types.forEach((type, index) => {
                const input = document.getElementById(type === 'baixar' ? 'downloadInput' : type + 'Input');
                const statusSpan = overviewStats[index].querySelector('p span');
                
                if (input.files.length > 0) {
                    statusSpan.className = 'status-configured';
                    statusSpan.textContent = '✓ Novo arquivo';
                } else {
                    // Manter status original baseado no PHP
                    statusSpan.className = statusSpan.textContent.includes('✓') ? 'status-configured' : 'status-empty';
                }
            });
            
            // Atualizar contador total
            const totalConfigured = Array.from(overviewStats.slice(0, 3)).reduce((count, stat) => {
                return count + (stat.querySelector('span').textContent.includes('✓') ? 1 : 0);
            }, 0);
            
            overviewStats[3].querySelector('p').textContent = `${totalConfigured}/3 Configurados`;
        }

        // Validação do formulário
        function setupFormValidation() {
            const form = document.getElementById('brandForm');
            
            form.addEventListener('submit', function(e) {
                const inputs = ['logoInput', 'faviconInput', 'downloadInput'];
                let hasFiles = false;
                
                inputs.forEach(inputId => {
                    const input = document.getElementById(inputId);
                    if (input.files.length > 0) {
                        hasFiles = true;
                    }
                });
                
                if (!hasFiles) {
                    e.preventDefault();
                    showToast('error', 'Selecione pelo menos uma imagem para fazer upload!');
                    return;
                }
                
                // Loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="iconoir-hourglass me-3"></i>Salvando Imagens...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.brand-card');
            
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-16px) scale(1.03)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        // Indicadores de mudança
        function setupChangeIndicators() {
            const inputs = document.querySelectorAll('input[type="file"]');
            
            function checkChanges() {
                let hasChanges = false;
                inputs.forEach(input => {
                    if (input.files.length > 0) {
                        hasChanges = true;
                    }
                });
                
                const saveButton = document.querySelector('.save-button');
                if (hasChanges && saveButton && !saveButton.disabled) {
                    saveButton.innerHTML = '<i class="iconoir-warning-triangle me-3"></i>Salvar Alterações';
                    saveButton.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%)';
                } else if (saveButton && !saveButton.disabled) {
                    saveButton.innerHTML = '<i class="iconoir-check me-3"></i>Salvar Configurações';
                    saveButton.style.background = 'linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%)';
                }
                
                updateOverview();
            }
            
            inputs.forEach(input => {
                input.addEventListener('change', checkChanges);
            });
        }

        // Cache busting para imagens
        function updateCacheTimestamp() {
            const cacheElements = document.querySelectorAll('.cache-buster');
            const timestamp = new Date().toLocaleString('pt-BR');
            
            cacheElements.forEach(element => {
                element.textContent = `Atualizado: ${timestamp}`;
            });
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            setupFileUpload();
            setupFormValidation();
            setupAnimations();
            setupChangeIndicators();
            
            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    const form = document.getElementById('brandForm');
                    if (form) form.submit();
                }
            });
            
            // Verificar modo demo
            <?php if ($modo_plataforma == 0): ?>
            document.getElementById('demoWarning').style.display = 'block';
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => input.disabled = true);
            const uploadAreas = document.querySelectorAll('.upload-area');
            uploadAreas.forEach(area => {
                area.style.pointerEvents = 'none';
                area.style.opacity = '0.5';
            });
            <?php endif; ?>
        });
    </script>

    <!-- Exibir Toast baseado nas ações -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            });
        </script>
    <?php endif; ?>

</body>
</html>