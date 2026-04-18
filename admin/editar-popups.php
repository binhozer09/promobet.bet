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

# Função para buscar os popups
function get_banners()
{
    global $mysqli;
    $qry = "SELECT * FROM popups";
    $result = mysqli_query($mysqli, $qry);
    $banners = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }
    return $banners;
}

# Função para atualizar o popup
function update_banner($id, $titulo, $content, $status, $img = null)
{
    global $mysqli;

    if ($img) {
        $qry = $mysqli->prepare("UPDATE popups SET titulo = ?, content = ?, status = ?, img = ? WHERE id = ?");
        $qry->bind_param("ssisi", $titulo, $content, $status, $img, $id);
    } else {
        $qry = $mysqli->prepare("UPDATE popups SET titulo = ?, content = ?, status = ? WHERE id = ?");
        $qry->bind_param("ssii", $titulo, $content, $status, $id);
    }

    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

# Verificar modo demo (assumindo que existe uma variável global)
$modo_plataforma = $modo_plataforma ?? 1; // Default para modo ativo

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $status = intval($_POST['status']);
    $content = $_POST['content'];

    # Buscar a imagem atual no banco de dados
    $query = "SELECT img FROM popups WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banner = $result->fetch_assoc();
    $img = $banner['img']; // Manter o nome da imagem atual

    if (!empty($_FILES['img']['name'])) {
        $upload_dir = "../image/";
        $extensao = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $extensao; // Nome único
        $img_path = $upload_dir . $novo_nome;

        if (move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
            $img = $img_path; // Atualiza a variável para salvar no banco
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar a imagem. Tente novamente.';
        }
    }

    # Atualizar o popup no banco de dados
    if (update_banner($id, $titulo, $content, $status, $img)) {
        $toastType = 'success';
        $toastMessage = 'Popup atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o popup. Tente novamente.';
    }
}

# Buscar os popups atuais
$banners = get_banners();
?>

<head>
    <?php $title = "Gerenciamento de Popups";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #ec4899;
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

        .popup-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .popup-header {
            background: linear-gradient(135deg, var(--accent-color), #be185d);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .popup-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="3" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: popupFloat 15s linear infinite;
        }

        @keyframes popupFloat {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-30px) rotate(360deg); }
        }

        .popup-header h4 {
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

        .popup-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .popup-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            box-shadow: 0 12px 40px rgba(236, 72, 153, 0.3);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #be185d);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent-color), #be185d);
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

        /* Demo Mode Alert */
        .demo-alert {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 2px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            text-align: center;
        }

        .demo-alert-icon {
            font-size: 48px;
            color: var(--danger-color);
            margin-bottom: 12px;
        }

        .demo-alert-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--danger-color);
            margin-bottom: 8px;
        }

        .demo-alert-text {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Popup Management Tips */
        .popup-tips {
            background: rgba(236, 72, 153, 0.1);
            border: 1px solid rgba(236, 72, 153, 0.2);
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
            content: '💬';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Feedback Container */
        #feedback-container {
            margin-bottom: 24px;
        }

        /* Popup Grid */
        .popups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 24px;
        }

        .popup-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .popup-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(236, 72, 153, 0.2);
            border-color: var(--accent-color);
        }

        .popup-header-card {
            background: linear-gradient(135deg, var(--secondary-bg), #3f3f3f);
            padding: 20px;
            border-bottom: 2px solid var(--border-color);
            position: relative;
        }

        .popup-id {
            background: var(--accent-color);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            position: absolute;
            top: 16px;
            right: 16px;
        }

        .popup-status {
            position: absolute;
            top: 16px;
            left: 16px;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .popup-status.active {
            background: var(--success-color);
            color: white;
        }

        .popup-status.image {
            background: var(--info-color);
            color: white;
        }

        .popup-status.inactive {
            background: var(--danger-color);
            color: white;
        }

        .popup-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 25px 0 8px 0;
            line-height: 1.3;
        }

        .popup-date {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .popup-content-section {
            padding: 20px;
        }

        .popup-content-preview {
            background: var(--primary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--text-secondary);
            max-height: 80px;
            overflow: hidden;
            position: relative;
        }

        .popup-content-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: linear-gradient(transparent, var(--primary-bg));
        }

        .popup-image-container {
            position: relative;
            height: 120px;
            background: var(--primary-bg);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .popup-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .popup-card:hover .popup-image {
            transform: scale(1.05);
        }

        .popup-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .popup-actions {
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

        .btn-edit:hover:not(:disabled) {
            background: #be185d;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.3);
        }

        .btn-edit:disabled {
            background: var(--border-color);
            cursor: not-allowed;
            opacity: 0.6;
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

        .form-input, .form-select, .form-textarea {
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

        .form-textarea {
            min-height: 120px;
            resize: vertical;
            font-family: 'Courier New', monospace;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
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

        /* File Upload Styling */
        .file-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: var(--primary-bg);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: var(--accent-color);
            background: rgba(236, 72, 153, 0.05);
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

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
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
            background: linear-gradient(135deg, var(--accent-color), #be185d);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #be185d, var(--accent-color));
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

        .popup-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .popup-card:nth-child(odd) { animation-delay: 0.1s; }
        .popup-card:nth-child(even) { animation-delay: 0.2s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .popup-layout {
                padding: 16px;
            }
            
            .popup-header {
                padding: 24px;
            }
            
            .popups-grid {
                grid-template-columns: 1fr;
            }
            
            .popup-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .popup-stats {
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
        <div class="page-content popup-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="popup-header">
                    <h4>
                        <i class="bi bi-window-stack"></i>
                        Gerenciamento de Popups
                    </h4>
                    <p>Configure e gerencie popups promocionais e informativos da plataforma</p>
                </div>

                <!-- Demo Mode Alert -->
                <?php if ($modo_plataforma == 0): ?>
                    <div class="demo-alert">
                        <div class="demo-alert-icon">🚫</div>
                        <div class="demo-alert-title">Modo Demonstração Ativo</div>
                        <div class="demo-alert-text">
                            Os popups não podem ser editados no modo demo. 
                            Ative o modo produção para editar as configurações.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Stats Overview -->
                <div class="popup-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-collection"></i>
                        </div>
                        <div class="stat-value"><?= count($banners) ?></div>
                        <div class="stat-label">Total de Popups</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($banners, function($b) { return $b['status'] == 1; })) ?></div>
                        <div class="stat-label">Popups Ativos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-image"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($banners, function($b) { return $b['status'] == 2; })) ?></div>
                        <div class="stat-label">Apenas Imagem</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($banners, function($b) { return $b['status'] == 0; })) ?></div>
                        <div class="stat-label">Popups Inativos</div>
                    </div>
                </div>

                <!-- Popup Management Tips -->
                <div class="popup-tips">
                    <div class="tips-title">
                        <i class="bi bi-lightbulb"></i>
                        Dicas para Popups Eficazes
                    </div>
                    <ul class="tips-list">
                        <li>Use conteúdo HTML para formatação rica e links</li>
                        <li>Mantenha mensagens claras e objetivas</li>
                        <li>Configure imagens otimizadas para diferentes dispositivos</li>
                        <li>Use o modo "Apenas Imagem" para promoções visuais</li>
                        <li>Ative apenas os popups que devem ser exibidos no momento</li>
                    </ul>
                </div>

                <!-- Feedback Container -->
                <div id="feedback-container"></div>

                <!-- Popups Grid -->
                <?php if (empty($banners)): ?>
                    <div class="empty-state">
                        <i class="bi bi-window-stack"></i>
                        <h5>Nenhum popup encontrado</h5>
                        <p>Ainda não há popups cadastrados no sistema</p>
                    </div>
                <?php else: ?>
                    <div class="popups-grid">
                        <?php foreach ($banners as $banner): ?>
                            <div class="popup-card">
                                <div class="popup-header-card">
                                    <div class="popup-id">ID: <?= $banner['id'] ?></div>
                                    <div class="popup-status <?= $banner['status'] == 1 ? 'active' : ($banner['status'] == 2 ? 'image' : 'inactive') ?>">
                                        <?= $banner['status'] == 1 ? 'Ativo' : ($banner['status'] == 2 ? 'Imagem' : 'Inativo') ?>
                                    </div>
                                    <h3 class="popup-title"><?= htmlspecialchars($banner['titulo']) ?></h3>
                                    <div class="popup-date">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($banner['criado_em'])) ?>
                                    </div>
                                </div>
                                
                                <div class="popup-content-section">
                                    <div class="popup-content-preview">
                                        <?= htmlspecialchars(substr(strip_tags($banner['content']), 0, 150)) ?>...
                                    </div>
                                    
                                    <div class="popup-image-container">
                                        <?php if (!empty($banner['img'])): ?>
                                            <img src="<?= htmlspecialchars($banner['img']) ?>" 
                                                 alt="<?= htmlspecialchars($banner['titulo']) ?>" 
                                                 class="popup-image"
                                                 onerror="this.parentElement.innerHTML='<div class=&quot;popup-image-placeholder&quot;><i class=&quot;bi bi-image&quot;></i><br>Imagem não encontrada</div>'">
                                        <?php else: ?>
                                            <div class="popup-image-placeholder">
                                                <i class="bi bi-image"></i><br>
                                                Sem imagem
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="popup-actions">
                                        <button class="action-btn btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editBannerModal<?= $banner['id'] ?>"
                                                <?= $modo_plataforma == 0 ? 'disabled' : '' ?>>
                                            <i class="bi bi-pencil"></i>
                                            <?= $modo_plataforma == 0 ? 'Modo Demo' : 'Editar' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Edição -->
                            <div class="modal fade" id="editBannerModal<?= $banner['id'] ?>" tabindex="-1" aria-labelledby="editBannerLabel<?= $banner['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editBannerLabel<?= $banner['id'] ?>">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar Popup #<?= $banner['id'] ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" class="popup-form">
                                                <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                <i class="bi bi-type"></i>
                                                                Título do Popup
                                                            </label>
                                                            <input type="text" 
                                                                   name="titulo" 
                                                                   class="form-input" 
                                                                   value="<?= htmlspecialchars($banner['titulo']) ?>" 
                                                                   required
                                                                   placeholder="Digite o título do popup">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                <i class="bi bi-toggle-on"></i>
                                                                Status do Popup
                                                            </label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="2" <?= $banner['status'] == 2 ? 'selected' : '' ?>>Apenas Imagem</option>
                                                                <option value="1" <?= $banner['status'] == 1 ? 'selected' : '' ?>>Ativo (com conteúdo)</option>
                                                                <option value="0" <?= $banner['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                            </select>
                                                            <div class="form-help">
                                                                Escolha como o popup será exibido
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-code-slash"></i>
                                                        Conteúdo HTML
                                                    </label>
                                                    <textarea name="content" 
                                                              class="form-textarea" 
                                                              required
                                                              placeholder="Digite o conteúdo HTML do popup..."><?= htmlspecialchars($banner['content']) ?></textarea>
                                                    <div class="form-help">
                                                        Use HTML para formatação rica. Tags permitidas: &lt;p&gt;, &lt;br&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;a&gt;, etc.
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-image"></i>
                                                        Imagem do Popup
                                                    </label>
                                                    <div class="file-upload-area" onclick="document.getElementById('img_<?= $banner['id'] ?>').click()">
                                                        <div class="upload-icon">
                                                            <i class="bi bi-cloud-upload"></i>
                                                        </div>
                                                        <div class="upload-text">Clique para selecionar uma imagem</div>
                                                        <div class="upload-subtext">ou arraste e solte aqui</div>
                                                        <input type="file" 
                                                               id="img_<?= $banner['id'] ?>" 
                                                               name="img" 
                                                               class="file-input" 
                                                               accept="image/*"
                                                               onchange="previewImage(this, 'preview_<?= $banner['id'] ?>')">
                                                    </div>
                                                    <div class="form-help">
                                                        Deixe em branco para manter a imagem atual. 
                                                        Formatos aceitos: JPG, PNG, WebP. Tamanho máximo: 5MB.
                                                    </div>
                                                    <div id="preview_<?= $banner['id'] ?>" class="image-preview mt-3">
                                                        <?php if (!empty($banner['img'])): ?>
                                                            <img src="<?= htmlspecialchars($banner['img']) ?>" 
                                                                 alt="Preview" 
                                                                 class="preview-image"
                                                                 style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid var(--border-color);">
                                                        <?php endif; ?>
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

        // Função para preview de imagem
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            
            if (file) {
                // Verificar tamanho do arquivo (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Arquivo muito grande! Tamanho máximo: 5MB');
                    input.value = '';
                    return;
                }
                
                // Verificar tipo do arquivo
                if (!file.type.startsWith('image/')) {
                    alert('Por favor, selecione apenas arquivos de imagem!');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid var(--border-color);">`;
                    
                    // Adicionar efeito visual ao upload area
                    const uploadArea = input.closest('.file-upload-area');
                    uploadArea.style.borderColor = 'var(--success-color)';
                    uploadArea.style.background = 'rgba(16, 185, 129, 0.05)';
                };
                reader.readAsDataURL(file);
            }
        }

        // Configurar drag and drop para upload
        function setupDragAndDrop() {
            const uploadAreas = document.querySelectorAll('.file-upload-area');
            
            uploadAreas.forEach(area => {
                const fileInput = area.querySelector('.file-input');
                
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.style.borderColor = 'var(--accent-color)';
                    this.style.background = 'rgba(236, 72, 153, 0.1)';
                });
                
                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.style.borderColor = 'var(--border-color)';
                    this.style.background = 'var(--primary-bg)';
                });
                
                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.style.borderColor = 'var(--border-color)';
                    this.style.background = 'var(--primary-bg)';
                    
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        const previewId = fileInput.id.replace('img_', 'preview_');
                        previewImage(fileInput, previewId);
                    }
                });
            });
        }

        // Validação de formulário
        function setupFormValidation() {
            const forms = document.querySelectorAll('.popup-form');
            
            forms.forEach(form => {
                const inputs = form.querySelectorAll('.form-input, .form-textarea, .form-select');
                
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        validateInput(this);
                    });
                    
                    input.addEventListener('blur', function() {
                        validateInput(this);
                    });
                });
                
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    
                    inputs.forEach(input => {
                        if (!validateInput(input)) {
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        showFormFeedback(this, 'error', 'Por favor, corrija os erros antes de salvar.');
                        return;
                    }
                    
                    // Mostrar loading no botão
                    const submitBtn = this.querySelector('.btn-primary');
                    showButtonLoading(submitBtn);
                });
            });
        }

        // Validação individual de campo
        function validateInput(input) {
            const value = input.value.trim();
            let isValid = true;
            
            // Remove classes anteriores
            input.classList.remove('error', 'success');
            
            // Validações específicas
            if (input.hasAttribute('required') && !value) {
                isValid = false;
            } else if (input.name === 'content') {
                // Validação básica de HTML
                const tempDiv = document.createElement('div');
                try {
                    tempDiv.innerHTML = value;
                } catch (e) {
                    isValid = false;
                }
            }
            
            // Aplicar classes de feedback
            if (!isValid) {
                input.classList.add('error');
                input.style.borderColor = 'var(--danger-color)';
            } else if (value) {
                input.classList.add('success');
                input.style.borderColor = 'var(--success-color)';
            } else {
                input.style.borderColor = 'var(--border-color)';
            }
            
            return isValid;
        }

        // Mostrar loading no botão
        function showButtonLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="bi bi-arrow-clockwise me-2" style="animation: spin 1s linear infinite;"></i>Salvando...';
            button.disabled = true;
            
            // O formulário será submetido normalmente
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
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
            const popupCards = document.querySelectorAll('.popup-card');
            popupCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efeito de digitação para preview de conteúdo
            const contentPreviews = document.querySelectorAll('.popup-content-preview');
            contentPreviews.forEach(preview => {
                preview.addEventListener('mouseenter', function() {
                    this.style.maxHeight = 'none';
                    this.style.overflow = 'visible';
                    this.querySelector('::after')?.remove();
                });
                
                preview.addEventListener('mouseleave', function() {
                    this.style.maxHeight = '80px';
                    this.style.overflow = 'hidden';
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

        // Editor HTML simples
        function setupHTMLEditor() {
            const textareas = document.querySelectorAll('textarea[name="content"]');
            
            textareas.forEach(textarea => {
                // Adicionar toolbar básico
                const toolbar = document.createElement('div');
                toolbar.style.cssText = `
                    display: flex;
                    gap: 8px;
                    margin-bottom: 8px;
                    padding: 8px;
                    background: var(--secondary-bg);
                    border-radius: 8px;
                    border: 1px solid var(--border-color);
                `;
                
                const buttons = [
                    { text: '<b>B</b>', tag: 'strong' },
                    { text: '<i>I</i>', tag: 'em' },
                    { text: 'P', tag: 'p' },
                    { text: 'BR', tag: 'br' }
                ];
                
                buttons.forEach(btn => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.innerHTML = btn.text;
                    button.style.cssText = `
                        padding: 4px 8px;
                        background: var(--primary-bg);
                        border: 1px solid var(--border-color);
                        color: var(--text-primary);
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 12px;
                    `;
                    
                    button.addEventListener('click', function() {
                        insertHTMLTag(textarea, btn.tag);
                    });
                    
                    toolbar.appendChild(button);
                });
                
                textarea.parentNode.insertBefore(toolbar, textarea);
            });
        }

        function insertHTMLTag(textarea, tag) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            
            let replacement;
            if (tag === 'br') {
                replacement = '<br>';
            } else {
                replacement = selectedText 
                    ? `<${tag}>${selectedText}</${tag}>`
                    : `<${tag}></${tag}>`;
            }
            
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            
            // Posicionar cursor
            const newPos = tag === 'br' ? start + replacement.length : start + tag.length + 2;
            textarea.setSelectionRange(newPos, newPos);
        }

        // Filtros e busca
        function setupFilters() {
            const popupCards = document.querySelectorAll('.popup-card');
            
            // Função para filtrar por status
            window.filterByStatus = function(status) {
                popupCards.forEach(card => {
                    const statusElement = card.querySelector('.popup-status');
                    const cardStatus = statusElement.classList.contains('active') ? 'active' : 
                                     statusElement.classList.contains('image') ? 'image' : 'inactive';
                    
                    if (status === 'all' || cardStatus === status) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.5s ease-out';
                    } else {
                        card.style.display = 'none';
                    }
                });
            };
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();
            setupFormValidation();
            setupVisualEffects();
            setupHTMLEditor();
            setupFilters();
        });

        // CSS adicional para animações
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .form-input.error, .form-textarea.error {
                border-color: var(--danger-color) !important;
                background: rgba(239, 68, 68, 0.05);
            }
            
            .form-input.success, .form-textarea.success {
                border-color: var(--success-color) !important;
            }
            
            .file-upload-area:hover {
                transform: scale(1.02);
            }
            
            .popup-content-preview:hover {
                background: var(--secondary-bg);
                cursor: pointer;
            }
            
            .popup-image {
                transition: transform 0.3s ease, filter 0.3s ease;
            }
            
            .popup-card:hover .popup-image {
                filter: brightness(1.1);
            }
        `;
        document.head.appendChild(additionalStyles);
    </script>

    <!-- Exibir feedback baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showGlobalFeedback('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                
                // Destacar o popup que foi atualizado se for sucesso
                <?php if ($toastType === 'success' && isset($_POST['id'])): ?>
                const updatedPopupId = <?= intval($_POST['id']) ?>;
                const popupCards = document.querySelectorAll('.popup-card');
                popupCards.forEach(card => {
                    const modal = card.querySelector('[id*="editBannerModal"]');
                    if (modal && modal.id.includes(updatedPopupId)) {
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
                            card.style.boxShadow = '0 12px 40px rgba(236, 72, 153, 0.2)';
                        }, 3000);
                    }
                });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>
</html>