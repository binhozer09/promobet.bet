<?php include 'partials/html.php' ?>

<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
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
checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_banners()
{
    global $mysqli;
    $qry = "SELECT * FROM mensagens ORDER BY criado_em DESC";
    $result = mysqli_query($mysqli, $qry);
    $banners = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }
    return $banners;
}

function update_banner($id, $titulo, $content, $status = null)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE mensagens SET titulo = ?, content = ?, status = ? WHERE id = ?");
    $qry->bind_param("ssii", $titulo, $content, $status, $id);
    return $qry->execute();
}

function insert_banner($titulo, $content, $status)
{
    global $mysqli;
    $qry = $mysqli->prepare("INSERT INTO mensagens (titulo, content, status) VALUES (?, ?, ?)");
    $qry->bind_param("ssi", $titulo, $content, $status);
    return $qry->execute();
}

function delete_banner($id)
{
    global $mysqli;
    $qry = $mysqli->prepare("DELETE FROM mensagens WHERE id = ?");
    $qry->bind_param("i", $id);
    return $qry->execute();
}

$toastType = '';
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        $id = intval($_POST['id']);
        if (delete_banner($id)) {
            $toastType = 'success';
            $toastMessage = 'Mensagem excluída com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao excluir a mensagem.';
        }
    } else {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $titulo = trim(htmlspecialchars($_POST['titulo']));
        $content = trim(htmlspecialchars($_POST['content']));
        $status = intval($_POST['status']);

        if ($id) {
            if (update_banner($id, $titulo, $content, $status)) {
                $toastType = 'success';
                $toastMessage = 'Mensagem atualizada com sucesso!';
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao atualizar a mensagem.';
            }
        } else {
            if (insert_banner($titulo, $content, $status)) {
                $toastType = 'success';
                $toastMessage = 'Nova mensagem criada com sucesso!';
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao criar a mensagem.';
            }
        }
    }
}

$banners = get_banners();
?>

<head>
    <?php $title = "Gerenciamento de Mensagens";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .message-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
            margin-bottom: 24px;
        }
        
        .message-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .message-card.active::before {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .message-card.inactive::before {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        
        .message-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .message-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .icon-active {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
        }
        
        .icon-inactive {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }
        
        .icon-announcement {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.3);
        }
        
        .modern-input {
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .modern-input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            background: rgba(255,255,255,0.1);
            transform: scale(1.02);
        }
        
        .modern-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 40px;
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
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }
        
        .action-button {
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 0 4px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            box-shadow: 0 4px 15px rgba(78, 205, 196, 0.3);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .btn-create {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 20px;
            padding: 18px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(86, 171, 47, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .action-button::before, .btn-create::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before, .btn-create:hover::before {
            left: 100%;
        }
        
        .action-button:hover, .btn-create:hover {
            transform: translateY(-3px);
        }
        
        .btn-edit:hover {
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.4);
        }
        
        .btn-delete:hover {
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }
        
        .btn-create:hover {
            box-shadow: 0 12px 40px rgba(86, 171, 47, 0.4);
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            color: white;
        }
        
        .status-inactive {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }
        
        .modal-content {
            border-radius: 20px;
            background: #303231;
            color: white;
            border: none;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px 20px 0 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .modal-body {
            padding: 32px;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
            border-radius: 0 0 20px 20px;
        }
        
        .character-counter {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            text-align: right;
            margin-top: 8px;
        }
        
        .character-counter.warning {
            color: #ffc107;
        }
        
        .character-counter.danger {
            color: #ff6b6b;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255,255,255,0.6);
        }
        
        .empty-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 24px;
            opacity: 0.7;
        }
        
        .stats-card {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .stats-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .message-card:nth-child(1) { animation-delay: 0.1s; }
        .message-card:nth-child(2) { animation-delay: 0.2s; }
        .message-card:nth-child(3) { animation-delay: 0.3s; }
        .message-card:nth-child(4) { animation-delay: 0.4s; }
        .message-card:nth-child(5) { animation-delay: 0.5s; }
        
        .message-content {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 16px;
            margin: 16px 0;
            border-left: 4px solid #667eea;
        }
        
        .message-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 16px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
        }
        
        .search-box {
            position: relative;
            margin-bottom: 24px;
        }
        
        .search-input {
            width: 100%;
            padding: 16px 20px 16px 60px;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            background: rgba(255,255,255,0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
            font-size: 1.2rem;
        }
        
        .filter-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid rgba(255,255,255,0.2);
            background: transparent;
            color: rgba(255,255,255,0.8);
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .filter-btn.active, .filter-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.2);
            color: white;
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
                            <h3 class="mb-3">
                                <i class="iconoir-mail me-3"></i>Central de Mensagens
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Gerencie todas as mensagens e anúncios da plataforma de forma centralizada
                            </p>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="stats-number"><?= count($banners) ?></div>
                            <div class="stats-label">Total de Mensagens</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="stats-number"><?= count(array_filter($banners, fn($b) => $b['status'] == 1)) ?></div>
                            <div class="stats-label">Mensagens Ativas</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="stats-number"><?= count(array_filter($banners, fn($b) => $b['status'] == 0)) ?></div>
                            <div class="stats-label">Mensagens Inativas</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="stats-number"><?= date('d') ?></div>
                            <div class="stats-label">Dia do Mês</div>
                        </div>
                    </div>

                    <!-- Barra de Ações -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Barra de Pesquisa -->
                                <div class="search-box" style="width: 350px;">
                                    <i class="iconoir-search search-icon"></i>
                                    <input type="text" id="searchInput" class="search-input" placeholder="Pesquisar mensagens...">
                                </div>
                                
                                <!-- Filtros -->
                                <div class="filter-buttons">
                                    <button class="filter-btn active" data-filter="all">Todas</button>
                                    <button class="filter-btn" data-filter="active">Ativas</button>
                                    <button class="filter-btn" data-filter="inactive">Inativas</button>
                                </div>
                            </div>
                            
                            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createMessageModal">
                                <i class="iconoir-plus me-2"></i>Nova Mensagem
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Mensagens -->
                    <div class="col-12" id="messagesContainer">
                        <?php if (empty($banners)): ?>
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="iconoir-mail"></i>
                                </div>
                                <h4>Nenhuma mensagem encontrada</h4>
                                <p>Crie sua primeira mensagem para começar a se comunicar com os usuários</p>
                                <button class="btn-create mt-3" data-bs-toggle="modal" data-bs-target="#createMessageModal">
                                    <i class="iconoir-plus me-2"></i>Criar Primeira Mensagem
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="row" id="messagesList">
                                <?php foreach ($banners as $banner): ?>
                                    <div class="col-lg-6 mb-4 message-item" 
                                         data-status="<?= $banner['status'] == 1 ? 'active' : 'inactive' ?>"
                                         data-title="<?= strtolower($banner['titulo']) ?>"
                                         data-content="<?= strtolower($banner['content']) ?>">
                                        <div class="message-card <?= $banner['status'] == 1 ? 'active' : 'inactive' ?> h-100">
                                            <div class="p-4">
                                                <!-- Ícone e Status -->
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="message-icon <?= $banner['status'] == 1 ? 'icon-active' : 'icon-inactive' ?>">
                                                        <i class="iconoir-announcement"></i>
                                                    </div>
                                                    <span class="status-badge <?= $banner['status'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                                        <?= $banner['status'] == 1 ? 'Ativa' : 'Inativa' ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Título -->
                                                <h5 class="fw-bold mb-3"><?= htmlspecialchars($banner['titulo']) ?></h5>
                                                
                                                <!-- Conteúdo -->
                                                <div class="message-content">
                                                    <p class="mb-0"><?= htmlspecialchars($banner['content']) ?></p>
                                                </div>
                                                
                                                <!-- Meta informações -->
                                                <div class="message-meta">
                                                    <span><i class="iconoir-calendar me-1"></i><?= date('d/m/Y H:i', strtotime($banner['criado_em'])) ?></span>
                                                    <span><i class="iconoir-hashtag me-1"></i>ID: <?= $banner['id'] ?></span>
                                                </div>
                                                
                                                <!-- Ações -->
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button class="action-button btn-edit" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editMessageModal<?= $banner['id'] ?>">
                                                        <i class="iconoir-edit me-1"></i>Editar
                                                    </button>
                                                    <button class="action-button btn-delete" 
                                                            onclick="confirmDelete(<?= $banner['id'] ?>, '<?= addslashes($banner['titulo']) ?>')">
                                                        <i class="iconoir-trash me-1"></i>Excluir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Edição -->
                                    <div class="modal fade" id="editMessageModal<?= $banner['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="iconoir-edit me-2"></i>Editar Mensagem
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                                        
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">
                                                                <i class="iconoir-text me-2"></i>Título da Mensagem
                                                            </label>
                                                            <input type="text" name="titulo" class="form-control modern-input" 
                                                                   value="<?= htmlspecialchars($banner['titulo']) ?>" 
                                                                   placeholder="Digite o título da mensagem"
                                                                   maxlength="100" required>
                                                            <div class="character-counter">
                                                                <span class="titulo-count"><?= strlen($banner['titulo']) ?></span>/100 caracteres
                                                            </div>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">
                                                                <i class="iconoir-page me-2"></i>Conteúdo da Mensagem
                                                            </label>
                                                            <textarea name="content" class="form-control modern-input modern-textarea" 
                                                                      placeholder="Digite o conteúdo completo da mensagem"
                                                                      maxlength="500" required><?= htmlspecialchars($banner['content']) ?></textarea>
                                                            <div class="character-counter">
                                                                <span class="content-count"><?= strlen($banner['content']) ?></span>/500 caracteres
                                                            </div>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">
                                                                <i class="iconoir-switch me-2"></i>Status da Mensagem
                                                            </label>
                                                            <select name="status" class="form-select modern-input">
                                                                <option value="1" <?= $banner['status'] == 1 ? 'selected' : '' ?>>
                                                                    ✅ Ativa - Visível para usuários
                                                                </option>
                                                                <option value="0" <?= $banner['status'] == 0 ? 'selected' : '' ?>>
                                                                    ❌ Inativa - Oculta dos usuários
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="action-button" style="background: #6c757d;" data-bs-dismiss="modal">
                                                            <i class="iconoir-cancel me-1"></i>Cancelar
                                                        </button>
                                                        <button type="submit" class="action-button btn-edit">
                                                            <i class="iconoir-check me-1"></i>Salvar Alterações
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação -->
    <div class="modal fade" id="createMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="iconoir-plus me-2"></i>Nova Mensagem
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="createForm">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="iconoir-text me-2"></i>Título da Mensagem
                            </label>
                            <input type="text" name="titulo" class="form-control modern-input" 
                                   placeholder="Digite um título atrativo para sua mensagem"
                                   maxlength="100" required>
                            <div class="character-counter">
                                <span class="titulo-count-new">0</span>/100 caracteres
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="iconoir-page me-2"></i>Conteúdo da Mensagem
                            </label>
                            <textarea name="content" class="form-control modern-input modern-textarea" 
                                      placeholder="Escreva o conteúdo detalhado da sua mensagem..."
                                      maxlength="500" required></textarea>
                            <div class="character-counter">
                                <span class="content-count-new">0</span>/500 caracteres
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="iconoir-switch me-2"></i>Status da Mensagem
                            </label>
                            <select name="status" class="form-select modern-input" required>
                                <option value="1" selected>✅ Ativa - Visível para usuários</option>
                                <option value="0">❌ Inativa - Oculta dos usuários</option>
                            </select>
                        </div>

                        <!-- Preview da Mensagem -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="iconoir-eye me-2"></i>Prévia da Mensagem
                            </label>
                            <div class="message-content" id="messagePreview">
                                <strong id="previewTitle">Título aparecerá aqui...</strong>
                                <p id="previewContent" class="mb-0 mt-2">Conteúdo aparecerá aqui...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="action-button" style="background: #6c757d;" data-bs-dismiss="modal">
                            <i class="iconoir-cancel me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn-create">
                            <i class="iconoir-check me-1"></i>Criar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <h5 class="modal-title">
                        <i class="iconoir-warning-triangle me-2"></i>Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="message-icon icon-inactive mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="iconoir-trash"></i>
                        </div>
                        <h4>Tem certeza que deseja excluir?</h4>
                        <p class="text-muted">
                            A mensagem "<strong id="deleteMessageTitle"></strong>" será permanentemente removida.
                            <br><span class="text-danger">Esta ação não pode ser desfeita!</span>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-button" style="background: #6c757d;" data-bs-dismiss="modal">
                        <i class="iconoir-cancel me-1"></i>Cancelar
                    </button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteMessageId">
                        <button type="submit" class="action-button btn-delete">
                            <i class="iconoir-trash me-1"></i>Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

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
                    <img src="/uploads/logo.png.webp" alt="" height="20" class="me-1">
                    <h5 class="me-auto my-0">Mensagens</h5>
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

        // Função de confirmação de exclusão
        function confirmDelete(id, title) {
            document.getElementById('deleteMessageId').value = id;
            document.getElementById('deleteMessageTitle').textContent = title;
            
            var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        // Contador de caracteres
        function setupCharacterCounters() {
            // Para modais de edição
            document.querySelectorAll('[name="titulo"]').forEach(input => {
                const counter = input.closest('.modal-body')?.querySelector('.titulo-count');
                if (counter) {
                    function updateCount() {
                        const length = input.value.length;
                        counter.textContent = length;
                        counter.parentElement.className = 'character-counter';
                        if (length > 80) counter.parentElement.classList.add('warning');
                        if (length > 95) counter.parentElement.classList.add('danger');
                    }
                    input.addEventListener('input', updateCount);
                    updateCount();
                }
            });

            document.querySelectorAll('[name="content"]').forEach(textarea => {
                const counter = textarea.closest('.modal-body')?.querySelector('.content-count');
                if (counter) {
                    function updateCount() {
                        const length = textarea.value.length;
                        counter.textContent = length;
                        counter.parentElement.className = 'character-counter';
                        if (length > 400) counter.parentElement.classList.add('warning');
                        if (length > 475) counter.parentElement.classList.add('danger');
                    }
                    textarea.addEventListener('input', updateCount);
                    updateCount();
                }
            });

            // Para modal de criação
            const newTituloInput = document.querySelector('#createForm [name="titulo"]');
            const newContentTextarea = document.querySelector('#createForm [name="content"]');
            const newTituloCounter = document.querySelector('.titulo-count-new');
            const newContentCounter = document.querySelector('.content-count-new');

            if (newTituloInput && newTituloCounter) {
                newTituloInput.addEventListener('input', function() {
                    const length = this.value.length;
                    newTituloCounter.textContent = length;
                    newTituloCounter.parentElement.className = 'character-counter';
                    if (length > 80) newTituloCounter.parentElement.classList.add('warning');
                    if (length > 95) newTituloCounter.parentElement.classList.add('danger');
                });
            }

            if (newContentTextarea && newContentCounter) {
                newContentTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    newContentCounter.textContent = length;
                    newContentCounter.parentElement.className = 'character-counter';
                    if (length > 400) newContentCounter.parentElement.classList.add('warning');
                    if (length > 475) newContentCounter.parentElement.classList.add('danger');
                });
            }
        }

        // Preview em tempo real
        function setupPreview() {
            const tituloInput = document.querySelector('#createForm [name="titulo"]');
            const contentTextarea = document.querySelector('#createForm [name="content"]');
            const previewTitle = document.getElementById('previewTitle');
            const previewContent = document.getElementById('previewContent');

            if (tituloInput && previewTitle) {
                tituloInput.addEventListener('input', function() {
                    previewTitle.textContent = this.value || 'Título aparecerá aqui...';
                });
            }

            if (contentTextarea && previewContent) {
                contentTextarea.addEventListener('input', function() {
                    previewContent.textContent = this.value || 'Conteúdo aparecerá aqui...';
                });
            }
        }

        // Sistema de busca
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            const messageItems = document.querySelectorAll('.message-item');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    messageItems.forEach(item => {
                        const title = item.dataset.title || '';
                        const content = item.dataset.content || '';
                        
                        if (title.includes(searchTerm) || content.includes(searchTerm) || searchTerm === '') {
                            item.style.display = 'block';
                            item.style.animation = 'fadeInUp 0.5s ease-out';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Mostrar mensagem se nenhum resultado
                    const visibleItems = Array.from(messageItems).filter(item => item.style.display !== 'none');
                    updateEmptyState(visibleItems.length === 0 && searchTerm !== '');
                });
            }
        }

        // Sistema de filtros
        function setupFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const messageItems = document.querySelectorAll('.message-item');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    
                    messageItems.forEach(item => {
                        const status = item.dataset.status;
                        
                        if (filter === 'all' || filter === status) {
                            item.style.display = 'block';
                            item.style.animation = 'fadeInUp 0.5s ease-out';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Mostrar mensagem se nenhum resultado
                    const visibleItems = Array.from(messageItems).filter(item => item.style.display !== 'none');
                    updateEmptyState(visibleItems.length === 0);
                });
            });
        }

        // Atualizar estado vazio
        function updateEmptyState(isEmpty) {
            const container = document.getElementById('messagesContainer');
            const messagesList = document.getElementById('messagesList');
            
            if (isEmpty) {
                if (!document.getElementById('noResultsState')) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.id = 'noResultsState';
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = `
                        <div class="empty-icon">
                            <i class="iconoir-search"></i>
                        </div>
                        <h4>Nenhuma mensagem encontrada</h4>
                        <p>Tente ajustar seus filtros ou termos de busca</p>
                    `;
                    container.appendChild(emptyDiv);
                }
                if (messagesList) messagesList.style.display = 'none';
            } else {
                const noResults = document.getElementById('noResultsState');
                if (noResults) noResults.remove();
                if (messagesList) messagesList.style.display = 'block';
            }
        }

        // Validação de formulários
        function setupFormValidation() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const titulo = this.querySelector('[name="titulo"]')?.value.trim();
                    const content = this.querySelector('[name="content"]')?.value.trim();
                    
                    if (titulo && titulo.length < 3) {
                        e.preventDefault();
                        showToast('error', 'O título deve ter pelo menos 3 caracteres!');
                        return;
                    }
                    
                    if (content && content.length < 10) {
                        e.preventDefault();
                        showToast('error', 'O conteúdo deve ter pelo menos 10 caracteres!');
                        return;
                    }
                    
                    // Loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Processando...';
                        submitBtn.disabled = true;
                        
                        // Restaurar após um tempo se não houver redirecionamento
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 3000);
                    }
                });
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.message-card');
            
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        // Auto-save em localStorage para modal de criação
        function setupAutoSave() {
            const createForm = document.getElementById('createForm');
            if (!createForm) return;

            const inputs = createForm.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                // Carregar valor salvo
                const savedValue = localStorage.getItem(`create_message_${input.name}`);
                if (savedValue && input.value === '') {
                    input.value = savedValue;
                    input.style.borderColor = '#ffc107';
                    input.title = 'Valor recuperado automaticamente';
                    
                    // Disparar eventos para atualizar contadores e preview
                    input.dispatchEvent(new Event('input'));
                }
                
                // Salvar ao alterar
                input.addEventListener('input', function() {
                    localStorage.setItem(`create_message_${this.name}`, this.value);
                });
            });
            
            // Limpar localStorage quando modal fechar ou form for enviado
            const modal = document.getElementById('createMessageModal');
            modal.addEventListener('hidden.bs.modal', function() {
                inputs.forEach(input => {
                    localStorage.removeItem(`create_message_${input.name}`);
                });
            });
            
            createForm.addEventListener('submit', function() {
                inputs.forEach(input => {
                    localStorage.removeItem(`create_message_${input.name}`);
                });
            });
        }

        // Atalhos de teclado
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Ctrl + N para nova mensagem
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    const modal = new bootstrap.Modal(document.getElementById('createMessageModal'));
                    modal.show();
                }
                
                // Esc para fechar modais
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        bootstrap.Modal.getInstance(modal)?.hide();
                    });
                }
                
                // Ctrl + F para focar na busca
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('searchInput')?.focus();
                }
            });
        }

        // Ordenação e organização
        function setupSorting() {
            // Adicionar botões de ordenação se necessário
            const container = document.querySelector('.d-flex.justify-content-between.align-items-center.mb-4');
            if (container) {
                const sortDiv = document.createElement('div');
                sortDiv.className = 'dropdown';
                sortDiv.innerHTML = `
                    <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="iconoir-sort me-1"></i>Ordenar
                    </button>
                    <ul class="dropdown-menu" style="background: #303231; border: 1px solid rgba(255,255,255,0.1);">
                        <li><a class="dropdown-item" href="#" data-sort="date-desc" style="color: white;">Mais recentes</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="date-asc" style="color: white;">Mais antigas</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="title" style="color: white;">Por título</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="status" style="color: white;">Por status</a></li>
                    </ul>
                `;
                
                const filterButtons = container.querySelector('.filter-buttons');
                filterButtons.appendChild(sortDiv);
                
                // Implementar ordenação
                const sortItems = sortDiv.querySelectorAll('[data-sort]');
                sortItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const sortType = this.dataset.sort;
                        sortMessages(sortType);
                    });
                });
            }
        }

        function sortMessages(type) {
            const container = document.getElementById('messagesList');
            const items = Array.from(container.children);
            
            items.sort((a, b) => {
                switch(type) {
                    case 'date-desc':
                        return new Date(b.querySelector('.message-meta span').textContent.split('ID:')[0].trim()) - 
                               new Date(a.querySelector('.message-meta span').textContent.split('ID:')[0].trim());
                    case 'date-asc':
                        return new Date(a.querySelector('.message-meta span').textContent.split('ID:')[0].trim()) - 
                               new Date(b.querySelector('.message-meta span').textContent.split('ID:')[0].trim());
                    case 'title':
                        return a.dataset.title.localeCompare(b.dataset.title);
                    case 'status':
                        return b.dataset.status.localeCompare(a.dataset.status);
                    default:
                        return 0;
                }
            });
            
            items.forEach(item => container.appendChild(item));
        }

        // Estatísticas em tempo real
        function updateStats() {
            const messageItems = document.querySelectorAll('.message-item');
            const total = messageItems.length;
            const active = Array.from(messageItems).filter(item => item.dataset.status === 'active').length;
            const inactive = total - active;
            
            // Atualizar números se existirem elementos de estatística
            const totalElement = document.querySelector('.stats-number');
            if (totalElement) {
                const statsCards = document.querySelectorAll('.stats-card .stats-number');
                if (statsCards[0]) statsCards[0].textContent = total;
                if (statsCards[1]) statsCards[1].textContent = active;
                if (statsCards[2]) statsCards[2].textContent = inactive;
            }
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            setupCharacterCounters();
            setupPreview();
            setupSearch();
            setupFilters();
            setupFormValidation();
            setupAnimations();
            setupAutoSave();
            setupKeyboardShortcuts();
            setupSorting();
            updateStats();
            
            // Toast de boas-vindas
            setTimeout(() => {
                if (document.querySelectorAll('.message-item').length === 0) {
                    showToast('info', 'Bem-vindo! Crie sua primeira mensagem para começar.');
                }
            }, 1000);
            
            // Salvar estado dos filtros
            const activeFilter = localStorage.getItem('messages_active_filter');
            if (activeFilter && activeFilter !== 'all') {
                const filterBtn = document.querySelector(`[data-filter="${activeFilter}"]`);
                if (filterBtn) {
                    filterBtn.click();
                }
            }
            
            // Salvar filtro ativo ao mudar
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    localStorage.setItem('messages_active_filter', this.dataset.filter);
                });
            });
        });

        // Observador de mutações para atualizar estatísticas
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    updateStats();
                }
            });
        });

        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            observer.observe(messagesList, { childList: true });
        }
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