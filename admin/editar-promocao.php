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

# Função para buscar as promoções
function get_promocoes() {
    global $mysqli;
    $qry = "SELECT * FROM promocoes";
    $result = mysqli_query($mysqli, $qry);
    $promocoes = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $promocoes[] = $row;
        }
    }
    return $promocoes;
}

# Função para atualizar a promoção
function update_promocao($id, $titulo, $status, $img = null) {
    global $mysqli;

    try {
        if ($img) {
            $qry = $mysqli->prepare("UPDATE promocoes SET titulo = ?, status = ?, img = ? WHERE id = ?");
            $qry->bind_param("sisi", $titulo, $status, $img, $id);
        } else {
            $qry = $mysqli->prepare("UPDATE promocoes SET titulo = ?, status = ? WHERE id = ?");
            $qry->bind_param("sii", $titulo, $status, $id);
        }

        return $qry->execute();
    } catch (Exception $e) {
        error_log("Erro ao atualizar promoção: " . $e->getMessage());
        return false;
    }
}

$toastType = null;
$toastMessage = '';

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $status = intval($_POST['status']);

    # Buscar a imagem atual no banco de dados
    $query = "SELECT img FROM promocoes WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $promocao = $result->fetch_assoc();
    $img = $promocao['img'];

    # Verificar se uma nova imagem foi enviada
    if (!empty($_FILES['img']['name'])) {
        $upload_dir = "../uploads/";
        $new_img_name = time() . '_' . basename($_FILES['img']['name']);
        $img_path = $upload_dir . $new_img_name;

        if (move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
            $img = $new_img_name;
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar a imagem. Tente novamente.';
            error_log("Erro ao mover o arquivo da imagem para $img_path");
        }
    }

    # Atualizar a promoção no banco de dados
    if (update_promocao($id, $titulo, $status, $img)) {
        $toastType = 'success';
        $toastMessage = 'Promoção atualizada com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar a promoção. Tente novamente.';
        error_log("Erro ao atualizar a promoção com ID $id");
    }
}

# Buscar as promoções atuais
$promocoes = get_promocoes();
?>

<head>
    <?php $title = "Gerenciamento de Promoções";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #f97316;
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

        .promotions-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .promotions-header {
            background: linear-gradient(135deg, var(--accent-color), #ea580c);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .promotions-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,10 90,50 50,90 10,50" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: promotionSpin 20s linear infinite;
        }

        @keyframes promotionSpin {
            0% { transform: rotate(0deg) scale(1); }
            100% { transform: rotate(360deg) scale(1.1); }
        }

        .promotions-header h4 {
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

        .promotions-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .promotions-stats {
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
            box-shadow: 0 12px 40px rgba(249, 115, 22, 0.3);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #ea580c);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent-color), #ea580c);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 28px;
            position: relative;
        }

        .stat-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .stat-value {
            font-size: 32px;
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

        /* Promotion Tips */
        .promotion-tips {
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
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
            content: '🎯';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Feedback Container */
        #feedback-container {
            margin-bottom: 24px;
        }

        /* Promotions Grid */
        .promotions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 24px;
        }

        .promotion-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .promotion-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(249, 115, 22, 0.2);
            border-color: var(--accent-color);
        }

        .promotion-image-container {
            position: relative;
            height: 220px;
            background: var(--primary-bg);
            overflow: hidden;
        }

        .promotion-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .promotion-card:hover .promotion-image {
            transform: scale(1.05);
        }

        .promotion-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.4));
            display: flex;
            align-items: flex-end;
            padding: 16px;
        }

        .promotion-id {
            background: var(--accent-color);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            top: 16px;
            left: 16px;
        }

        .promotion-status {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .promotion-status.active {
            background: var(--success-color);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .promotion-status.inactive {
            background: var(--danger-color);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .promotion-content {
            padding: 24px;
        }

        .promotion-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 16px 0;
            line-height: 1.3;
        }

        .promotion-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
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
            position: relative;
            overflow: hidden;
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--accent-color), #ea580c);
            color: white;
        }

        .btn-edit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateY(-50%);
            transition: left 0.6s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
        }

        .btn-edit:hover::before {
            left: 100%;
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
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
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
            background: rgba(249, 115, 22, 0.05);
            transform: scale(1.02);
        }

        .file-upload-area.dragover {
            border-color: var(--accent-color);
            background: rgba(249, 115, 22, 0.1);
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
            background: linear-gradient(135deg, var(--accent-color), #ea580c);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ea580c, var(--accent-color));
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

        @keyframes promotionPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .promotion-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .promotion-card:nth-child(odd) { animation-delay: 0.1s; }
        .promotion-card:nth-child(even) { animation-delay: 0.2s; }

        .stat-icon:hover {
            animation: promotionPulse 0.6s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .promotions-layout {
                padding: 16px;
            }
            
            .promotions-header {
                padding: 24px;
            }
            
            .promotions-grid {
                grid-template-columns: 1fr;
            }
            
            .promotions-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .promotions-stats {
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
        <div class="page-content promotions-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="promotions-header">
                    <h4>
                        <i class="bi bi-megaphone"></i>
                        Gerenciamento de Promoções
                    </h4>
                    <p>Configure e gerencie campanhas promocionais e ofertas especiais da plataforma</p>
                </div>

                <!-- Stats Overview -->
                <div class="promotions-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="stat-value"><?= count($promocoes) ?></div>
                        <div class="stat-label">Total de Promoções</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($promocoes, function($p) { return $p['status'] == 1; })) ?></div>
                        <div class="stat-label">Promoções Ativas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-value"><?= count(array_filter($promocoes, function($p) { return $p['status'] == 0; })) ?></div>
                        <div class="stat-label">Promoções Inativas</div>
                    </div>
                </div>

                <!-- Promotion Tips -->
                <div class="promotion-tips">
                    <div class="tips-title">
                        <i class="bi bi-lightbulb"></i>
                        Dicas para Promoções Eficazes
                    </div>
                    <ul class="tips-list">
                        <li>Use imagens atrativas e de alta qualidade para chamar atenção</li>
                        <li>Mantenha títulos claros e objetivos sobre a oferta</li>
                        <li>Configure apenas promoções válidas como ativas</li>
                        <li>Atualize regularmente as imagens promocionais</li>
                        <li>Use cores vibrantes que destaquem a promoção</li>
                    </ul>
                </div>

                <!-- Feedback Container -->
                <div id="feedback-container"></div>

                <!-- Promotions Grid -->
                <?php if (empty($promocoes)): ?>
                    <div class="empty-state">
                        <i class="bi bi-megaphone"></i>
                        <h5>Nenhuma promoção encontrada</h5>
                        <p>Ainda não há promoções cadastradas no sistema</p>
                    </div>
                <?php else: ?>
                    <div class="promotions-grid">
                        <?php foreach ($promocoes as $promocao): ?>
                            <div class="promotion-card">
                                <div class="promotion-image-container">
                                    <div class="promotion-id">ID: <?= $promocao['id'] ?></div>
                                    <div class="promotion-status <?= $promocao['status'] == 1 ? 'active' : 'inactive' ?>">
                                        <?= $promocao['status'] == 1 ? 'Ativa' : 'Inativa' ?>
                                    </div>
                                    <img src="/uploads/<?= htmlspecialchars($promocao['img']) ?>?v=<?= time() ?>" 
                                         alt="<?= htmlspecialchars($promocao['titulo']) ?>" 
                                         class="promotion-image"
                                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzYwIiBoZWlnaHQ9IjIyMCIgdmlld0JveD0iMCAwIDM2MCAyMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjM2MCIgaGVpZ2h0PSIyMjAiIGZpbGw9IiMzNzQxNTEiLz48dGV4dCB4PSIxODAiIHk9IjExMCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE2IiBmaWxsPSIjOWNhM2FmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5Qcm9tb8Onw6NvIG7Do28gZW5jb250cmFkYTwvdGV4dD48L3N2Zz4='">
                                    <div class="promotion-overlay"></div>
                                </div>
                                <div class="promotion-content">
                                    <h3 class="promotion-title"><?= htmlspecialchars($promocao['titulo']) ?></h3>
                                    <div class="promotion-actions">
                                        <button class="action-btn btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPromocaoModal<?= $promocao['id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de Edição -->
                            <div class="modal fade" id="editPromocaoModal<?= $promocao['id'] ?>" tabindex="-1" aria-labelledby="editPromocaoLabel<?= $promocao['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPromocaoLabel<?= $promocao['id'] ?>">
                                                <i class="bi bi-pencil-square"></i>
                                                Editar Promoção #<?= $promocao['id'] ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" class="promotion-form">
                                                <input type="hidden" name="id" value="<?= $promocao['id'] ?>">
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-type"></i>
                                                        Título da Promoção
                                                    </label>
                                                    <input type="text" 
                                                           name="titulo" 
                                                           class="form-input" 
                                                           value="<?= htmlspecialchars($promocao['titulo']) ?>" 
                                                           required
                                                           placeholder="Digite o título da promoção">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-image"></i>
                                                        Imagem da Promoção
                                                    </label>
                                                    <div class="file-upload-area" onclick="document.getElementById('img_<?= $promocao['id'] ?>').click()">
                                                        <div class="upload-icon">
                                                            <i class="bi bi-cloud-upload"></i>
                                                        </div>
                                                        <div class="upload-text">Clique para selecionar uma imagem</div>
                                                        <div class="upload-subtext">ou arraste e solte aqui</div>
                                                        <input type="file" 
                                                               id="img_<?= $promocao['id'] ?>" 
                                                               name="img" 
                                                               class="file-input" 
                                                               accept="image/*"
                                                               onchange="previewImage(this, 'preview_<?= $promocao['id'] ?>')">
                                                    </div>
                                                    <div class="form-help">
                                                        Deixe em branco para manter a imagem atual. 
                                                        Formatos aceitos: JPG, PNG, WebP. Tamanho máximo: 5MB.
                                                    </div>
                                                    <div id="preview_<?= $promocao['id'] ?>" class="image-preview">
                                                        <img src="/uploads/<?= htmlspecialchars($promocao['img']) ?>?v=<?= time() ?>" 
                                                             alt="Preview" 
                                                             class="preview-image">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        <i class="bi bi-toggle-on"></i>
                                                        Status da Promoção
                                                    </label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="1" <?= $promocao['status'] == 1 ? 'selected' : '' ?>>Ativa</option>
                                                        <option value="0" <?= $promocao['status'] == 0 ? 'selected' : '' ?>>Inativa</option>
                                                    </select>
                                                    <div class="form-help">
                                                        Promoções ativas serão exibidas na plataforma
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
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="preview-image">`;
                    
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

        // Validação de formulário
        function setupFormValidation() {
            const forms = document.querySelectorAll('.promotion-form');
            
            forms.forEach(form => {
                const inputs = form.querySelectorAll('.form-input');
                
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
            
            // Validações básicas
            if (input.hasAttribute('required') && !value) {
                isValid = false;
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
            const promotionCards = document.querySelectorAll('.promotion-card');
            promotionCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efeito de brilho nos status badges
            const statusBadges = document.querySelectorAll('.promotion-status.active');
            statusBadges.forEach(badge => {
                setInterval(() => {
                    badge.style.boxShadow = '0 4px 20px rgba(16, 185, 129, 0.6)';
                    setTimeout(() => {
                        badge.style.boxShadow = '0 4px 12px rgba(16, 185, 129, 0.3)';
                    }, 1000);
                }, 3000);
            });

            // Efeito parallax sutil nas imagens
            promotionCards.forEach(card => {
                const image = card.querySelector('.promotion-image');
                if (image) {
                    card.addEventListener('mousemove', function(e) {
                        const rect = this.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        
                        const centerX = rect.width / 2;
                        const centerY = rect.height / 2;
                        
                        const deltaX = (x - centerX) / centerX;
                        const deltaY = (y - centerY) / centerY;
                        
                        image.style.transform = `scale(1.05) translate(${deltaX * 3}px, ${deltaY * 3}px)`;
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        image.style.transform = 'scale(1)';
                    });
                }
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

        // Sistema de filtros
        function setupFilters() {
            const promotionCards = document.querySelectorAll('.promotion-card');
            
            // Função para filtrar por status
            window.filterByStatus = function(status) {
                promotionCards.forEach(card => {
                    const statusElement = card.querySelector('.promotion-status');
                    const cardStatus = statusElement.classList.contains('active') ? 'active' : 'inactive';
                    
                    if (status === 'all' || cardStatus === status) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.5s ease-out';
                    } else {
                        card.style.display = 'none';
                    }
                });
            };
        }

        // Lazy loading para imagens
        function setupLazyLoading() {
            const images = document.querySelectorAll('.promotion-image');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.style.opacity = '0';
                        img.onload = function() {
                            this.style.transition = 'opacity 0.3s ease';
                            this.style.opacity = '1';
                        };
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        }

        // Efeitos de temporada (opcional)
        function setupSeasonalEffects() {
            const now = new Date();
            const month = now.getMonth();
            
            // Adicionar efeitos especiais em datas comemorativas
            if (month === 11) { // Dezembro
                addSnowEffect();
            } else if (month === 9) { // Outubro
                addHalloweenEffect();
            }
        }

        function addSnowEffect() {
            // Efeito de neve sutil (opcional)
            const snowflakes = ['❄️', '❅', '⭐'];
            setInterval(() => {
                const snowflake = document.createElement('div');
                snowflake.innerHTML = snowflakes[Math.floor(Math.random() * snowflakes.length)];
                snowflake.style.cssText = `
                    position: fixed;
                    top: -10px;
                    left: ${Math.random() * 100}%;
                    font-size: ${Math.random() * 10 + 10}px;
                    z-index: 1000;
                    pointer-events: none;
                    animation: snowfall ${Math.random() * 3 + 2}s linear forwards;
                `;
                document.body.appendChild(snowflake);
                
                setTimeout(() => snowflake.remove(), 5000);
            }, 2000);
            
            // CSS para animação de neve
            const snowCSS = document.createElement('style');
            snowCSS.textContent = `
                @keyframes snowfall {
                    to {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(snowCSS);
        }

        function addHalloweenEffect() {
            // Efeito de Halloween (opcional)
            document.documentElement.style.setProperty('--accent-color', '#ff6b00');
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            setupDragAndDrop();
            setupFormValidation();
            setupVisualEffects();
            setupFilters();
            setupLazyLoading();
            setupSeasonalEffects();
        });

        // CSS adicional para animações
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .form-input.error {
                border-color: var(--danger-color) !important;
                background: rgba(239, 68, 68, 0.05);
            }
            
            .form-input.success {
                border-color: var(--success-color) !important;
            }
            
            .file-upload-area.dragover {
                border-color: var(--accent-color) !important;
                background: rgba(249, 115, 22, 0.1) !important;
                transform: scale(1.02);
            }
            
            .promotion-image {
                transition: transform 0.3s ease, filter 0.3s ease;
            }
            
            .promotion-card:hover .promotion-image {
                filter: brightness(1.1) contrast(1.1);
            }
            
            .promotion-card:hover .promotion-overlay {
                background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.6));
            }
            
            .btn-edit:active {
                transform: translateY(0) scale(0.98);
            }
        `;
        document.head.appendChild(additionalStyles);
    </script>

    <!-- Exibir feedback baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showGlobalFeedback('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                
                // Destacar a promoção que foi atualizada se for sucesso
                <?php if ($toastType === 'success' && isset($_POST['id'])): ?>
                const updatedPromotionId = <?= intval($_POST['id']) ?>;
                const promotionCards = document.querySelectorAll('.promotion-card');
                promotionCards.forEach(card => {
                    const modal = card.querySelector('[id*="editPromocaoModal"]');
                    if (modal && modal.id.includes(updatedPromotionId)) {
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
                            card.style.boxShadow = '0 12px 40px rgba(249, 115, 22, 0.2)';
                        }, 3000);
                    }
                });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

</body>
</html>