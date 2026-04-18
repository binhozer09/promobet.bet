<?php include 'partials/html.php' ?>

<?php
#======================================#
ini_set('display_errors', 0);
error_reporting(E_ALL);
#======================================#
session_start();
include_once "services/database.php";
include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
include_once "validar_2fa.php";
$csrf = new CSRF_Protect();
#======================================#
#expulsa user
checa_login_adm();
#======================================#
//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

# Função para buscar os banners
function get_banners() {
    global $mysqli;
    $qry = "SELECT * FROM banner";
    $result = mysqli_query($mysqli, $qry);
    $banners = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }
    return $banners;
}

# Função para atualizar o banner
function update_banner($id, $titulo, $status, $img = null) {
    global $mysqli;

    if ($img) {
        $qry = $mysqli->prepare("UPDATE banner SET titulo = ?, status = ?, img = ? WHERE id = ?");
        $qry->bind_param("sisi", $titulo, $status, $img, $id);
    } else {
        $qry = $mysqli->prepare("UPDATE banner SET titulo = ?, status = ? WHERE id = ?");
        $qry->bind_param("sii", $titulo, $status, $id);
    }

    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $status = intval($_POST['status']);

    # Buscar a imagem atual no banco de dados
    $query = "SELECT img FROM banner WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banner = $result->fetch_assoc();
    $img = $banner['img']; // Manter o nome da imagem atual

    # Verificar se uma nova imagem foi enviada
    if (!empty($_FILES['img']['name'])) {
        $upload_dir = "../uploads/"; // A pasta uploads está um nível acima
        $new_img_name = time() . '_' . basename($_FILES['img']['name']); // Usa um nome único
        $img_path = $upload_dir . $new_img_name;

        # Verificar se o diretório existe, se não, criar
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        # Verificar tipo de arquivo
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $file_type = $_FILES['img']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $toastType = 'error';
            $toastMessage = 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou WebP.';
        } 
        # Verificar tamanho do arquivo (5MB máximo)
        elseif ($_FILES['img']['size'] > 5 * 1024 * 1024) {
            $toastType = 'error';
            $toastMessage = 'Arquivo muito grande. Tamanho máximo: 5MB.';
        }
        # Mover a nova imagem e atualizar o nome no banco de dados
        elseif (move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
            # Deletar imagem anterior se existir
            if ($banner['img'] && file_exists($upload_dir . $banner['img'])) {
                unlink($upload_dir . $banner['img']);
            }
            $img = $new_img_name; // Atualiza o nome da imagem a ser salvo no banco
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar a imagem. Verifique as permissões da pasta.';
        }
    }

    # Atualizar o banner no banco de dados se não houve erro de upload
    if (!$toastType) {
        if (update_banner($id, $titulo, $status, $img)) {
            $toastType = 'success';
            $toastMessage = 'Banner atualizado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar o banner. Tente novamente.';
        }
    }
}

# Buscar os banners atuais
$banners = get_banners();
?>

<head>
    <?php $title = "Gerenciamento de Banners";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }

        body {
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .banner-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .banner-header {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .banner-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="0" y="0" width="20" height="20" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
            animation: slidePattern 20s linear infinite;
        }

        @keyframes slidePattern {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-20px) translateY(-20px); }
        }

        .banner-header h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .banner-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .banner-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.3);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #7c3aed);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 24px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Banner Management Tips */
        .banner-tips {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .tips-title {
            color: var(--accent-color);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tips-list li {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .tips-list li::before {
            content: '🎨';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Feedback Container */
        #feedback-container {
            margin-bottom: 24px;
        }

        /* Banner Grid */
        .banners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .banner-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .banner-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.2);
            border-color: var(--accent-color);
        }

        .banner-image-container {
            position: relative;
            height: 200px;
            background: var(--primary-bg);
            overflow: hidden;
        }

        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .banner-card:hover .banner-image {
            transform: scale(1.05);
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.3));
            display: flex;
            align-items: flex-end;
            padding: 16px;
        }

        .banner-id {
            background: var(--accent-color);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            position: absolute;
            top: 12px;
            left: 12px;
        }

        .banner-status {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .banner-status.active {
            background: var(--success-color);
            color: white;
        }

        .banner-status.inactive {
            background: var(--danger-color);
            color: white;
        }

        .banner-content {
            padding: 20px;
        }

        .banner-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 16px 0;
            line-height: 1.3;
        }

        .banner-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-edit {
            background: var(--accent-color);
            color: white;
        }

        .btn-edit:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }

        /* Modal Styling */
        .modal-content {
            background: var(--secondary-bg);
            border: none;
            border-radius: 16px;
            border: 2px solid var(--border-color);
        }

        .modal-header {
            background: var(--secondary-bg);
            border-bottom: 2px solid var(--border-color);
            border-radius: 16px 16px 0 0;
            padding: 24px;
        }

        .modal-title {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.7;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            background: var(--secondary-bg);
            color: var(--text-primary);
            padding: 28px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-label i {
            color: var(--accent-color);
            font-size: 16px;
        }

        .form-input, .form-select {
            width: 100%;
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 14px 16px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }

        .form-select option {
            background: var(--primary-bg);
            color: var(--text-primary);
        }

        .form-help {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 6px;
            font-weight: 500;
        }

        /* File Upload Styling - SIMPLIFICADO E FUNCIONAL */
        .file-upload-container {
            margin-bottom: 16px;
        }

        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: var(--primary-bg);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .file-upload-area:hover {
            border-color: var(--accent-color);
            background: rgba(139, 92, 246, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--accent-color);
            background: rgba(139, 92, 246, 0.1);
        }

        .file-upload-area.file-selected {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.05);
        }

        .upload-icon {
            font-size: 48px;
            color: var(--accent-color);
            margin-bottom: 12px;
        }

        .upload-text {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .upload-subtext {
            font-size: 12px;
            color: var(--text-secondary);
        }

        /* Input file estilizado para mobile */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-mobile {
            width: 100%;
            padding: 16px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            margin-top: 16px;
            transition: all 0.3s ease;
        }

        .file-input-mobile:hover {
            background: #7c3aed;
        }

        .file-input-hidden {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }

        /* Image Preview */
        .image-preview {
            margin-top: 16px;
            text-align: center;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        /* Current Image Display */
        .current-image-preview {
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin-bottom: 16px;
        }

        .current-image-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .current-image-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-footer {
            background: var(--secondary-bg);
            border-top: 2px solid var(--border-color);
            border-radius: 0 0 16px 16px;
            padding: 24px;
        }

        .btn-secondary {
            background: var(--border-color);
            border: none;
            color: var(--text-primary);
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-secondary:hover {
            background: #6b7280;
            color: var(--text-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #7c3aed, var(--accent-color));
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .banner-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .banner-card:nth-child(odd) { animation-delay: 0.1s; }
        .banner-card:nth-child(even) { animation-delay: 0.2s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .banner-layout {
                padding: 16px;
            }
            
            .banner-header {
                padding: 24px;
            }
            
            .banner-header h4 {
                font-size: 22px;
            }
            
            .banners-grid {
                grid-template-columns: 1fr;
            }
            
            .banner-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-btn {
                padding: 16px 20px !important;
                font-size: 16px !important;
                min-height: 48px;
            }
            
            .modal-body {
                padding: 20px !important;
            }
            
            .form-input, .form-select {
                padding: 16px !important;
                font-size: 16px !important;
                min-height: 48px;
            }

            .file-input-mobile {
                font-size: 18px;
                padding: 18px;
            }
        }

        @media (max-width: 480px) {
            .banner-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- Top Bar Start -->
    <?php include 'partials/topbar.php' ?>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <?php include 'partials/startbar.php' ?>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">
        <div class="page-content banner-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="banner-header">
                    <h4>
                        <i class="bi bi-image"></i>
                        Gerenciamento de Banners
                    </h4>
                    <p>Configure e gerencie todos os banners exibidos na plataforma</p>
                </div>

                <!-- Stats Overview -->
                <div class="banner-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-images"></i>
                        </div>
                        <div class="stat-value"><?= count($banners) ?></div>
                        <div class="stat-label">Total de Banners</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($banners, function($b) { return $b['status'] == 1; })) ?></div>
                        <div class="stat-label">Banners Ativos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($banners, function($b) { return $b['status'] == 0; })) ?></div>
                        <div class="stat-label">Banners Inativos</div>
                    </div>
                </div>

                <!-- Banner Management Tips -->
                <div class="banner-tips">
                    <div class="tips-title">
                        <i class="bi bi-lightbulb"></i>
                        Dicas para Banners
                    </div>
                    <ul class="tips-list">
                        <li>Use imagens com resolução mínima de 1200x400 pixels para melhor qualidade</li>
                        <li>Formatos recomendados: JPG, PNG ou WebP para otimização</li>
                        <li>Mantenha o texto dos banners legível e objetivo</li>
                        <li>Ative apenas os banners que devem ser exibidos no momento</li>
                        <li>Tamanho máximo por arquivo: 5MB</li>
                    </ul>
                </div>

                <!-- Feedback Container -->
                <div id="feedback-container"></div>

                <!-- Banners Grid -->
                <?php if (empty($banners)): ?>
                    <div class="empty-state">
                        <i class="bi bi-image"></i>
                        <h5>Nenhum banner encontrado</h5>
                        <p>Ainda não há banners cadastrados no sistema</p>
                    </div>
                <?php else: ?>
                    <div class="banners-grid">
                        <?php foreach ($banners as $banner): ?>
                            <div class="banner-card">
                                <div class="banner-image-container">
                                    <div class="banner-id">ID: <?= $banner['id'] ?></div>
                                    <div class="banner-status <?= $banner['status'] == 1 ? 'active' : 'inactive' ?>">
                                        <?= $banner['status'] == 1 ? 'Ativo' : 'Inativo' ?>
                                    </div>
                                    <img src="/uploads/<?= htmlspecialchars($banner['img']) ?>?v=<?= time() ?>" 
                                         alt="<?= htmlspecialchars($banner['titulo']) ?>" 
                                         class="banner-image"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjMwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiMzNzQxNTEiLz48dGV4dCB4PSIxNTAiIHk9IjEwMCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5JbWFnZW0gbsOjbyBlbmNvbnRyYWRhPC90ZXh0Pjwvc3ZnPg=='">
                                    <div class="banner-overlay"></div>
                                </div>
                                <div class="banner-content">
                                    <h3 class="banner-title"><?= htmlspecialchars($banner['titulo']) ?></h3>
                                    <div class="banner-actions">
                                        <button class="action-btn btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBannerModal<?= $banner['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Edição -->
                            <div class="modal fade" id="editBannerModal<?= $banner['id'] ?>" tabindex="-1" aria-labelledby="editBannerLabel<?= $banner['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editBannerLabel<?= $banner['id'] ?>">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar Banner #<?= $banner['id'] ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" class="banner-form">
                                                <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-type"></i>
                                                        Título do Banner
                                                    </label>
                                                    <input type="text" 
                                                           name="titulo" 
                                                           class="form-input" 
                                                           value="<?= htmlspecialchars($banner['titulo']) ?>" 
                                                           required
                                                           placeholder="Digite o título do banner">
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-image"></i>
                                                        Imagem Atual
                                                    </label>
                                                    <div class="current-image-preview">
                                                        <div class="current-image-label">Imagem atual do banner</div>
                                                        <img src="/uploads/<?= htmlspecialchars($banner['img']) ?>?v=<?= time() ?>" 
                                                             alt="<?= htmlspecialchars($banner['titulo']) ?>"
                                                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjMwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiMzNzQxNTEiLz48dGV4dCB4PSIxNTAiIHk9IjEwMCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5JbWFnZW0gbsOjbyBlbmNvbnRyYWRhPC90ZXh0Pjwvc3ZnPg=='">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-upload"></i>
                                                        Nova Imagem (Opcional)
                                                    </label>
                                                    
                                                    <div class="file-upload-container">
                                                        <!-- Área de upload tradicional para desktop -->
                                                        <div class="file-upload-area d-none d-md-flex" onclick="document.getElementById('img_<?= $banner['id'] ?>').click()">
                                                            <div class="upload-icon">
                                                                <i class="bi bi-cloud-upload"></i>
                                                            </div>
                                                            <div class="upload-text">Clique ou arraste uma imagem</div>
                                                            <div class="upload-subtext">JPG, PNG ou WebP • Máximo 5MB</div>
                                                        </div>
                                                        
                                                        <!-- Input file oculto -->
                                                        <input type="file" 
                                                               name="img" 
                                                               id="img_<?= $banner['id'] ?>"
                                                               class="file-input-hidden" 
                                                               accept="image/*"
                                                               onchange="previewImage(this, 'preview_<?= $banner['id'] ?>')">
                                                        
                                                        <!-- Botão simples para mobile -->
                                                        <button type="button" 
                                                                class="file-input-mobile d-md-none"
                                                                onclick="document.getElementById('img_<?= $banner['id'] ?>').click()">
                                                            <i class="bi bi-camera me-2"></i>Selecionar Imagem
                                                        </button>
                                                    </div>
                                                    
                                                    <div id="preview_<?= $banner['id'] ?>" class="image-preview"></div>
                                                    <div class="form-help">
                                                        Deixe em branco se não quiser alterar a imagem atual
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-toggle-on"></i>
                                                        Status do Banner
                                                    </label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="1" <?= $banner['status'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                        <option value="0" <?= $banner['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                    </select>
                                                    <div class="form-help">
                                                        Banners ativos serão exibidos na plataforma
                                                    </div>
                                                </div>
                                                
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-lg me-1"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div><!-- container -->
        </div><!-- page content -->
        
        <?php include 'partials/endbar.php' ?>
    </div><!-- page-wrapper -->

    <!-- Javascript -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        // Função para feedback global
        function showGlobalFeedback(type, message) {
            const existingFeedback = document.querySelector('.global-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            const feedback = document.createElement('div');
            feedback.className = 'global-feedback';
            feedback.style.cssText = `
                margin-bottom: 24px;
                padding: 16px 20px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                text-align: center;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                ${type === 'success' 
                    ? 'background: rgba(16, 185, 129, 0.1); color: #10b981; border: 2px solid rgba(16, 185, 129, 0.2);' 
                    : 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 2px solid rgba(239, 68, 68, 0.2);'
                }
            `;
            
            const icon = type === 'success' ? '✅' : '❌';
            feedback.innerHTML = `${icon} ${message}`;
            
            const feedbackContainer = document.getElementById('feedback-container');
            feedbackContainer.appendChild(feedback);
            
            // Animação de entrada
            feedback.style.opacity = '0';
            feedback.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                feedback.style.opacity = '1';
                feedback.style.transform = 'translateY(0)';
            }, 100);
            
            // Remove automaticamente após 5 segundos
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.style.opacity = '0';
                    feedback.style.transform = 'translateY(-20px)';
                    setTimeout(() => feedback.remove(), 300);
                }
            }, 5000);
        }

        // Função SIMPLIFICADA para preview de imagem
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                // Verificar tamanho do arquivo (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    showGlobalFeedback('error', 'Arquivo muito grande! Tamanho máximo: 5MB');
                    input.value = '';
                    return;
                }
                
                // Verificar tipo do arquivo
                if (!file.type.startsWith('image/')) {
                    showGlobalFeedback('error', 'Por favor, selecione apenas arquivos de imagem!');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div style="margin-top: 16px;">
                            <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Nova imagem selecionada
                            </div>
                            <img src="${e.target.result}" alt="Preview" class="preview-image">
                        </div>
                    `;
                    
                    // Feedback visual simples
                    showGlobalFeedback('success', `Imagem "${file.name}" selecionada com sucesso!`);
                };
                reader.readAsDataURL(file);
            }
        }

        // Configurar drag and drop APENAS para desktop
        function setupDragAndDrop() {
            if (window.innerWidth > 768) { // Apenas desktop
                const uploadAreas = document.querySelectorAll('.file-upload-area');
                
                uploadAreas.forEach(area => {
                    const parentContainer = area.closest('.file-upload-container');
                    const fileInput = parentContainer.querySelector('.file-input-hidden');
                    
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
                            fileInput.files = files;
                            const previewId = fileInput.id.replace('img_', 'preview_');
                            previewImage(fileInput, previewId);
                        }
                    });
                });
            }
        }

        // Validação de formulário
        function setupFormValidation() {
            const forms = document.querySelectorAll('.banner-form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const tituloInput = this.querySelector('input[name="titulo"]');
                    
                    if (!tituloInput.value.trim()) {
                        e.preventDefault();
                        showGlobalFeedback('error', 'Por favor, preencha o título do banner.');
                        tituloInput.focus();
                        return;
                    }
                    
                    // Mostrar loading no botão
                    const submitBtn = this.querySelector('.btn-primary');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2" style="animation: spin 1s linear infinite;"></i>Salvando...';
                    submitBtn.disabled = true;
                    
                    // Timeout para reativar botão em caso de erro
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                });
            });
        }

        // Efeitos visuais
        function setupVisualEffects() {
            // Animação dos contadores
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const value = parseInt(stat.textContent);
                if (!isNaN(value)) {
                    animateCounter(stat, value);
                }
            });

            // Hover effects nos cards
            const bannerCards = document.querySelectorAll('.banner-card');
            bannerCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (window.innerWidth > 768) { // Apenas desktop
                        this.style.transform = 'translateY(-6px)';
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    if (window.innerWidth > 768) { // Apenas desktop
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        }

        // Animação de contador
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 50);
        }

        // Reset quando modal fecha
        function setupModalReset() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Reset file inputs
                    const fileInputs = this.querySelectorAll('.file-input-hidden');
                    fileInputs.forEach(input => {
                        input.value = '';
                    });
                    
                    // Limpar previews
                    const previews = this.querySelectorAll('.image-preview');
                    previews.forEach(preview => {
                        preview.innerHTML = '';
                    });
                    
                    // Reset upload areas
                    const uploadAreas = this.querySelectorAll('.file-upload-area');
                    uploadAreas.forEach(area => {
                        area.classList.remove('dragover', 'file-selected');
                    });
                });
            });
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();
            setupFormValidation();
            setupVisualEffects();
            setupModalReset();
            
            console.log('Banner manager inicializado com sucesso!');
        });

        // CSS adicional para animações
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .file-upload-area.dragover {
                border-color: var(--accent-color) !important;
                background: rgba(139, 92, 246, 0.1) !important;
                transform: scale(1.02);
            }
            
            .banner-image {
                transition: transform 0.3s ease, opacity 0.3s ease;
            }
            
            .banner-card:hover .banner-overlay {
                background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.5));
            }
        `;
        document.head.appendChild(additionalStyles);
    </script>

    <!-- Exibir feedback baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showGlobalFeedback('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                
                // Destacar o banner que foi atualizado se for sucesso
                <?php if ($toastType === 'success' && isset($_POST['id'])): ?>
                const updatedBannerId = <?= intval($_POST['id']) ?>;
                const bannerCards = document.querySelectorAll('.banner-card');
                bannerCards.forEach(card => {
                    const modal = card.querySelector('[id*="editBannerModal"]');
                    if (modal && modal.id.includes(updatedBannerId)) {
                        card.style.borderColor = 'var(--success-color)';
                        card.style.boxShadow = '0 0 25px rgba(16, 185, 129, 0.4)';
                        card.style.transform = 'translateY(-6px) scale(1.02)';
                        
                        // Scroll suave para o card atualizado
                        card.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        
                        // Resetar após 3 segundos
                        setTimeout(() => {
                            card.style.transform = 'translateY(0) scale(1)';
                            card.style.borderColor = 'var(--border-color)';
                            card.style.boxShadow = '0 12px 40px rgba(139, 92, 246, 0.2)';
                        }, 3000);
                    }
                });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>
</html>