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

// Função para buscar todos os administradores
function get_admins()
{
    global $mysqli;
    $qry = "SELECT * FROM admin_users ORDER BY id DESC";
    $result = mysqli_query($mysqli, $qry);
    $admins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $admins[] = $row;
    }
    return $admins;
}

// Função para atualizar os dados de um administrador
function update_admin($data)
{
    global $mysqli;

    if (!empty($data['senha'])) {
        $senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT, array("cost" => 10));
        $qry = $mysqli->prepare("UPDATE admin_users SET nome = ?, email = ?, nivel = ?, status = ?, senha = ?, 2fa = ?, 2fa_auth_login = ?, 2fa_auth_withdraw = ? WHERE id = ?");
        $qry->bind_param(
            "ssisssssi",
            $data['nome'],
            $data['email'],
            $data['nivel'],
            $data['status'],
            $senha_hash,
            $data['2fa'],
            $data['2fa_auth_login'],
            $data['2fa_auth_withdraw'],
            $data['id']
        );
    } else {
        $qry = $mysqli->prepare("UPDATE admin_users SET nome = ?, email = ?, nivel = ?, status = ?, 2fa = ?, 2fa_auth_login = ?, 2fa_auth_withdraw = ? WHERE id = ?");
        $qry->bind_param(
            "ssissssi",
            $data['nome'],
            $data['email'],
            $data['nivel'],
            $data['status'],
            $data['2fa'],
            $data['2fa_auth_login'],
            $data['2fa_auth_withdraw'],
            $data['id']
        );
    }

    return $qry->execute();
}

function add_admin($data)
{
    global $mysqli;
    $senha_hash = password_hash($data['senha'], PASSWORD_DEFAULT, array("cost" => 10));
    $qry = $mysqli->prepare("INSERT INTO admin_users (nome, email, senha, nivel, status, 2fa, 2fa_auth_login, 2fa_auth_withdraw) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $qry->bind_param(
        "ssssisss",
        $data['nome'],
        $data['email'],
        $senha_hash,
        $data['nivel'],
        $data['status'],
        $data['2fa'],
        $data['2fa_auth_login'],
        $data['2fa_auth_withdraw']
    );
    return $qry->execute();
}

// Função para excluir administrador
function delete_admin($id)
{
    global $mysqli;
    $qry = $mysqli->prepare("DELETE FROM admin_users WHERE id = ?");
    $qry->bind_param("i", $id);
    return $qry->execute();
}

// Inicializar variáveis para toast
$toastType = '';
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_admin'])) {
    $data = [
        'id' => intval($_POST['id']),
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'], // Pode estar vazio
        '2fa' => $_POST['2fa'], // Atualiza o valor do 2FA
        '2fa_auth_login' => $_POST['2fa_auth_login'], // Novo campo
        '2fa_auth_withdraw' => $_POST['2fa_auth_withdraw'], // Novo campo
        'nivel' => intval($_POST['nivel']),
        'status' => intval($_POST['status'])
    ];

    if (update_admin($data)) {
        $toastType = 'success';
        $toastMessage = 'Administrador atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o administrador.';
    }
}

function clear_history()
{
    global $mysqli;
    $tables = ['historico_play', 'transacoes', 'usuarios', 'visita_site', 'bau', 'solicitacao_saques', 'logs', 'metodos_pagamentos', 'cupom_usados', 'historico_vip', 'user_activity', 'user_free_rounds', 'historico_comissoes', 'tarefas_concluidas'];
    foreach ($tables as $table) {
        $mysqli->query("DELETE FROM $table");
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['limpar_historico'])) {
    $tables = ['historico_play', 'transacoes', 'usuarios', 'visita_site', 'bau', 'solicitacao_saques', 'logs', 'metodos_pagamentos', 'cupom_usados', 'historico_vip', 'user_activity', 'user_free_rounds', 'historico_comissoes', 'tarefas_concluidas'];
    $errors = [];

    foreach ($tables as $table) {
        $query = "DELETE FROM $table";
        if (!$mysqli->query($query)) {
            $errors[] = "Erro ao limpar a tabela $table: " . $mysqli->error;
        }
    }

    if (empty($errors)) {
        $toastType = 'success';
        $toastMessage = 'Histórico limpo com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao limpar o histórico: ' . implode(', ', $errors);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $data = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        '2fa' => $_POST['2fa'],
        '2fa_auth_login' => $_POST['2fa_auth_login'], // Novo campo
        '2fa_auth_withdraw' => $_POST['2fa_auth_withdraw'], // Novo campo
        'nivel' => intval($_POST['nivel'] ?? 0),
        'status' => intval($_POST['status'] ?? 1)
    ];

    if (add_admin($data)) {
        $toastType = 'success';
        $toastMessage = 'Novo administrador adicionado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao adicionar o administrador.';
    }
}

// Processar exclusão de administrador
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_admin'])) {
    $admin_id = intval($_POST['admin_id']);
    
    // Verificar se não é o último administrador
    $count_query = "SELECT COUNT(*) as total FROM admin_users WHERE status = 1";
    $count_result = mysqli_query($mysqli, $count_query);
    $count_data = mysqli_fetch_assoc($count_result);
    
    // Verificar se não está tentando excluir a própria conta
    $current_admin_id = $_SESSION['data_adm']['id'] ?? 0;
    
    if ($admin_id == $current_admin_id) {
        $toastType = 'error';
        $toastMessage = 'Você não pode excluir sua própria conta!';
    } elseif ($count_data['total'] <= 1) {
        $toastType = 'error';
        $toastMessage = 'Não é possível excluir o último administrador ativo!';
    } else {
        if (delete_admin($admin_id)) {
            $toastType = 'success';
            $toastMessage = 'Administrador excluído com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao excluir o administrador.';
        }
    }
}

$admins = get_admins();
?>

<head>
    <?php $title = "Gerenciamento de Administradores";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .admin-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 16px;
        }
        
        .admin-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .admin-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }
        
        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            color: white;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.08);
            border-radius: 16px 16px 0 0;
        }
        
        .form-control, .form-select {
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-modern {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }
        
        .btn-modern:hover {
            transform: translateY(-1px);
        }
        
        .danger-zone {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            padding: 20px;
        }
        
        .search-box {
            background: rgba(0,0,0,0.02);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }
        
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
        
        .admin-card {
            animation: fadeInUp 0.5s ease-out;
        }
        
        .admin-card:nth-child(odd) { animation-delay: 0.1s; }
        .admin-card:nth-child(even) { animation-delay: 0.2s; }

        .auth-status {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            margin: 1px 0;
            display: inline-block;
        }
        
        .auth-enabled {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }
        
        .auth-disabled {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>
    
    <?php if (!isset($_SESSION['2fa_validado']) || $_SESSION['2fa_validado'] !== true): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('modal2FA'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">

                    <!-- Estatísticas -->
                    <?php
                    $total_admins = count($admins);
                    $admins_ativos = count(array_filter($admins, function($admin) { return $admin['status'] == 1; }));
                    $admins_inativos = $total_admins - $admins_ativos;
                    $admins_2fa = count(array_filter($admins, function($admin) { return !empty($admin['2fa']); }));
                    ?>
                    
                    <div class="col-12">
                        <div class="row mb-4">
                            <div class="col-md-3 col-sm-6">
                                <div class="stats-card text-center">
                                    <div class="stats-icon mb-2">
                                        <i class="iconoir-group h3 mb-0"></i>
                                    </div>
                                    <h4 class="mb-1"><?= $total_admins; ?></h4>
                                    <p class="mb-0 opacity-75">Total de Admins</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="admin-card bg-success-subtle p-3 text-center">
                                    <div class="stats-icon mb-2">
                                        <i class="iconoir-check-circle h3 text-success mb-0"></i>
                                    </div>
                                    <h4 class="mb-1 text-success"><?= $admins_ativos; ?></h4>
                                    <p class="text-muted mb-0">Administradores Ativos</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <div class="admin-card bg-danger-subtle p-3 text-center">
                                    <div class="stats-icon mb-2">
                                        <i class="iconoir-cancel h3 text-danger mb-0"></i>
                                    </div>
                                    <h4 class="mb-1 text-danger"><?= $admins_inativos; ?></h4>
                                    <p class="text-muted mb-0">Administradores Inativos</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="admin-card bg-warning-subtle p-3 text-center">
                                    <div class="stats-icon mb-2">
                                        <i class="iconoir-shield-check h3 text-warning mb-0"></i>
                                    </div>
                                    <h4 class="mb-1 text-warning"><?= $admins_2fa; ?></h4>
                                    <p class="text-muted mb-0">Com 2FA Ativo</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Principal -->
                    <div class="col-12">
                        <div class="card rounded-4 shadow-sm border-0">
                            <div class="card-header bg-transparent border-bottom-0 pb-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="iconoir-users me-2"></i>Administradores do Sistema
                                        </h5>
                                        <p class="text-muted mb-0 small">Gerencie contas administrativas e suas permissões</p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                                <i class="iconoir-plus me-2"></i>Novo Admin
                                            </button>
                                    <button class="btn btn-danger btn-modern" data-bs-toggle="modal" data-bs-target="#limparHistoricoModal">
                                        <i class="iconoir-trash me-2"></i>Limpar Histórico
                                    </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Barra de Pesquisa -->
                                <div class="search-box">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="iconoir-search text-muted"></i>
                                        </span>
                                        <input type="text" id="searchAdmin" class="form-control border-start-0 border-end-0" 
                                               placeholder="Pesquisar por nome, email ou status...">
                                        <button class="btn btn-outline-primary border-start-0" type="button" id="searchButton">
                                            <i class="iconoir-search me-1"></i>Pesquisar
                                        </button>
                                        <button class="btn btn-outline-secondary ms-1" type="button" id="clearSearch" style="display: none;">
                                            <i class="iconoir-cancel me-1"></i>Limpar
                                        </button>
                                    </div>
                                </div>

                                <!-- Lista de Administradores -->
                                <div class="row" id="adminList">
                                    <?php foreach ($admins as $admin): ?>
                                        <div class="col-md-6 col-lg-4 mb-4 admin-item" 
                                             data-name="<?= strtolower($admin['nome']); ?>" 
                                             data-email="<?= strtolower($admin['email']); ?>" 
                                             data-status="<?= $admin['status'] == 1 ? 'ativo' : 'inativo'; ?>">
                                            <div class="admin-card p-4 h-100">
                                                <div class="d-flex align-items-start justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="admin-avatar me-3">
                                                            <?= strtoupper(substr($admin['nome'], 0, 2)); ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($admin['nome']); ?></h6>
                                                            <p class="text-muted mb-0 small"><?= htmlspecialchars($admin['email']); ?></p>
                                                        </div>
                                                    </div>
                                                    <span class="status-badge <?= $admin['status'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                                        <?= $admin['status'] == 1 ? 'Ativo' : 'Inativo'; ?>
                                                    </span>
                                                </div>

                                                <div class="admin-details mb-3">
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <small class="text-muted">ID:</small>
                                                            <div class="fw-medium">#<?= $admin['id']; ?></div>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Nível:</small>
                                                            <div class="fw-medium"><?= $admin['nivel'] ?? 0; ?></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block mb-1">Autenticação 2FA:</small>
                                                        <div class="d-flex flex-column gap-1">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="small">Principal:</span>
                                                                <span class="auth-status <?= !empty($admin['2fa']) ? 'auth-enabled' : 'auth-disabled'; ?>">
                                                                    <?= !empty($admin['2fa']) ? 'Ativo' : 'Inativo'; ?>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="small">Login:</span>
                                                                <span class="auth-status <?= !empty($admin['2fa_auth_login']) ? 'auth-enabled' : 'auth-disabled'; ?>">
                                                                    <?= !empty($admin['2fa_auth_login']) ? 'Ativo' : 'Inativo'; ?>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="small">Saque:</span>
                                                                <span class="auth-status <?= !empty($admin['2fa_auth_withdraw']) ? 'auth-enabled' : 'auth-disabled'; ?>">
                                                                    <?= !empty($admin['2fa_auth_withdraw']) ? 'Ativo' : 'Inativo'; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="admin-actions">
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-primary action-btn flex-fill" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editAdminModal<?= $admin['id'] ?>">
                                                            <i class="iconoir-edit me-2"></i>Editar
                                                        </button>
                                                        <?php 
                                                        // Verificar se não é a própria conta logada
                                                        $current_admin_id = $_SESSION['data_adm']['id'] ?? 0;
                                                        if ($admin['id'] != $current_admin_id): 
                                                        ?>
                                                        <button class="btn btn-danger action-btn" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteAdminModal<?= $admin['id'] ?>"
                                                                title="Excluir administrador">
                                                            <i class="iconoir-trash"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de Edição -->
                                        <div class="modal fade" id="editAdminModal<?= $admin['id'] ?>" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary" id="editAdminModalLabel">
                                                            <i class="iconoir-edit me-2"></i>Editar Administrador
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="nome" class="form-label fw-medium">
                                                                            <i class="iconoir-user me-1"></i>Nome Completo
                                                                        </label>
                                                                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($admin['nome']); ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="email" class="form-label fw-medium">
                                                                            <i class="iconoir-mail me-1"></i>Email
                                                                        </label>
                                                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']); ?>" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="nivel" class="form-label fw-medium">
                                                                            <i class="iconoir-star me-1"></i>Nível de Acesso
                                                                        </label>
                                                                        <select name="nivel" class="form-select">
                                                                            <option value="0" <?= ($admin['nivel'] ?? 0) == 0 ? 'selected' : '' ?>>Padrão (0)</option>
                                                                            <option value="1" <?= ($admin['nivel'] ?? 0) == 1 ? 'selected' : '' ?>>Moderador (1)</option>
                                                                            <option value="2" <?= ($admin['nivel'] ?? 0) == 2 ? 'selected' : '' ?>>Administrador (2)</option>
                                                                            <option value="3" <?= ($admin['nivel'] ?? 0) == 3 ? 'selected' : '' ?>>Super Admin (3)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="status" class="form-label fw-medium">
                                                                            <i class="iconoir-settings me-1"></i>Status da Conta
                                                                        </label>
                                                                        <select name="status" class="form-select">
                                                                            <option value="1" <?= $admin['status'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                                            <option value="0" <?= $admin['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <label for="senha" class="form-label fw-medium">
                                                                            <i class="iconoir-lock me-1"></i>Nova Senha
                                                                        </label>
                                                                        <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a senha atual">
                                                                        <small class="text-muted">Apenas preencha se desejar alterar a senha</small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Seção de Autenticação 2FA -->
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="bg-light p-3 rounded-3 mb-3">
                                                                        <h6 class="text-primary mb-3">
                                                                            <i class="iconoir-shield-check me-2"></i>Configurações de Autenticação 2FA
                                                                        </h6>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label for="2fa" class="form-label fw-medium">
                                                                                        <i class="iconoir-shield me-1"></i>2FA Principal
                                                                                    </label>
                                                                                    <input type="text" name="2fa" class="form-control" value="<?= htmlspecialchars($admin['2fa']); ?>" placeholder="Código 2FA principal">
                                                                                    <small class="text-muted">Autenticação geral do sistema</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label for="2fa_auth_login" class="form-label fw-medium">
                                                                                        <i class="iconoir-log-in me-1"></i>2FA Login
                                                                                    </label>
                                                                                    <input type="text" name="2fa_auth_login" class="form-control" value="<?= htmlspecialchars($admin['2fa_auth_login'] ?? ''); ?>" placeholder="Código 2FA para login">
                                                                                    <small class="text-muted">Autenticação para login</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label for="2fa_auth_withdraw" class="form-label fw-medium">
                                                                                        <i class="iconoir-credit-card me-1"></i>2FA para Atualizações
                                                                                    </label>
                                                                                    <input type="text" name="2fa_auth_withdraw" class="form-control" value="<?= htmlspecialchars($admin['2fa_auth_withdraw'] ?? ''); ?>" placeholder="Código 2FA para saques">
                                                                                    <small class="text-muted">Autenticação para saques</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 bg-light">
                                                            <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                                                                <i class="iconoir-cancel me-1"></i>Cancelar
                                                            </button>
                                                            <button type="submit" name="edit_admin" class="btn btn-primary btn-modern">
                                                                <i class="iconoir-check me-1"></i>Salvar Alterações
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de Exclusão -->
                                        <?php 
                                        // Só mostra modal de exclusão se não for a própria conta
                                        if ($admin['id'] != $current_admin_id): 
                                        ?>
                                        <div class="modal fade" id="deleteAdminModal<?= $admin['id'] ?>" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title text-danger" id="deleteAdminModalLabel">
                                                            <i class="iconoir-warning-triangle me-2"></i>Confirmar Exclusão
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="danger-zone">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <i class="iconoir-warning-triangle text-danger me-3 mt-1" style="font-size: 1.5rem;"></i>
                                                                <div>
                                                                    <h6 class="text-danger mb-2">Atenção: Ação Irreversível</h6>
                                                                    <p class="mb-3">Tem certeza que deseja excluir o administrador <strong><?= htmlspecialchars($admin['nome']); ?></strong>?</p>
                                                                    <div class="admin-info bg-light p-3 rounded-3 mb-3">
                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <small class="text-muted">Nome:</small>
                                                                                <div class="fw-medium"><?= htmlspecialchars($admin['nome']); ?></div>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <small class="text-muted">Email:</small>
                                                                                <div class="fw-medium"><?= htmlspecialchars($admin['email']); ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <ul class="text-muted small mb-0">
                                                                        <li>Esta ação não pode ser desfeita</li>
                                                                        <li>O administrador perderá acesso imediatamente</li>
                                                                        <li>Todos os dados associados serão removidos</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="alert alert-danger border-0 mb-0">
                                                                <strong>⚠️ Esta ação é permanente!</strong><br>
                                                                Certifique-se de que deseja continuar.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light">
                                                        <form method="POST" class="w-100">
                                                            <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                                                            <div class="d-flex gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                                                                    <i class="iconoir-cancel me-1"></i>Cancelar
                                                                </button>
                                                                <button type="submit" name="delete_admin" class="btn btn-danger btn-modern">
                                                                    <i class="iconoir-trash me-1"></i>Excluir Administrador
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Estado vazio -->
                                <div id="emptyState" class="text-center py-5" style="display: none;">
                                    <i class="iconoir-user text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                                    <h5 class="text-muted mt-3">Nenhum administrador encontrado</h5>
                                    <p class="text-muted">Tente ajustar os filtros de pesquisa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Limpar Histórico -->
    <div class="modal fade" id="limparHistoricoModal" tabindex="-1" aria-labelledby="limparHistoricoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="limparHistoricoModalLabel">
                        <i class="iconoir-warning-triangle me-2"></i>Zona de Perigo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="danger-zone">
                        <div class="d-flex align-items-start mb-3">
                            <i class="iconoir-warning-triangle text-danger me-3 mt-1" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="text-danger mb-2">Atenção: Ação Irreversível</h6>
                                <p class="mb-3">Tem certeza de que deseja limpar todo o histórico do sistema? Esta ação irá:</p>
                                <ul class="text-muted small mb-0">
                                    <li>Deletar permanentemente todos os registros de jogos</li>
                                    <li>Remover histórico de transações</li>
                                    <li>Limpar dados de usuários</li>
                                    <li>Apagar logs do sistema</li>
                                    <li>Remover todas as atividades registradas</li>
                                </ul>
                            </div>
                        </div>
                        <div class="alert alert-danger border-0 mb-0">
                            <strong>⚠️ Esta ação não pode ser desfeita!</strong><br>
                            Certifique-se de ter um backup antes de continuar.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <form method="POST" class="w-100">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                                <i class="iconoir-cancel me-1"></i>Cancelar
                            </button>
                            <button type="submit" name="limpar_historico" class="btn btn-danger btn-modern">
                                <i class="iconoir-trash me-1"></i>Limpar Histórico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Administrador -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="addAdminModalLabel">
                        <i class="iconoir-plus me-2"></i>Adicionar Novo Administrador
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="addAdminForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label fw-medium">
                                        <i class="iconoir-user me-1"></i>Nome Completo *
                                    </label>
                                    <input type="text" name="nome" class="form-control" required placeholder="Digite o nome completo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="iconoir-mail me-1"></i>Email *
                                    </label>
                                    <input type="email" name="email" class="form-control" required placeholder="admin@exemplo.com">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="senha" class="form-label fw-medium">
                                        <i class="iconoir-lock me-1"></i>Senha *
                                    </label>
                                    <input type="password" name="senha" class="form-control" required placeholder="Mínimo 8 caracteres" minlength="8">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nivel" class="form-label fw-medium">
                                        <i class="iconoir-star me-1"></i>Nível de Acesso
                                    </label>
                                    <select name="nivel" class="form-select">
                                        <option value="0" selected>Padrão (0)</option>
                                        <option value="1">Moderador (1)</option>
                                        <option value="2">Administrador (2)</option>
                                        <option value="3">Super Admin (3)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-medium">
                                        <i class="iconoir-settings me-1"></i>Status Inicial
                                    </label>
                                    <select name="status" class="form-select">
                                        <option value="1" selected>Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Seção de Autenticação 2FA para Adicionar -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded-3 mb-3">
                                    <h6 class="text-primary mb-3">
                                        <i class="iconoir-shield-check me-2"></i>Configurações de Autenticação 2FA
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="2fa" class="form-label fw-medium">
                                                    <i class="iconoir-shield me-1"></i>2FA Principal
                                                </label>
                                                <input type="text" name="2fa" class="form-control" placeholder="Código 2FA principal">
                                                <small class="text-muted">Autenticação geral do sistema</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="2fa_auth_login" class="form-label fw-medium">
                                                    <i class="iconoir-log-in me-1"></i>2FA Login
                                                </label>
                                                <input type="text" name="2fa_auth_login" class="form-control" placeholder="123456" maxlength="6" pattern="[0-9]{6}" inputmode="numeric">
                                                <small class="text-muted">Código de 6 dígitos numérico</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="2fa_auth_withdraw" class="form-label fw-medium">
                                                    <i class="iconoir-credit-card me-1"></i>2FA para Atualizações
                                                </label>
                                                <input type="text" name="2fa_auth_withdraw" class="form-control" placeholder="123456" maxlength="6" pattern="[0-9]{6}" inputmode="numeric">
                                                <small class="text-muted">Código de 6 dígitos numérico</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                            <i class="iconoir-cancel me-1"></i>Cancelar
                        </button>
                        <button type="submit" name="add_admin" class="btn btn-success btn-modern">
                            <i class="iconoir-plus me-1"></i>Adicionar Administrador
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    
    <!-- Scripts customizados -->
    <script>
        // Função de Toast simplificada (como no original)
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
                    <h5 class="me-auto my-0">Atualização</h5>
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

        // Validação para campos numéricos de 6 dígitos
        function setupNumericValidation() {
            const numericFields = document.querySelectorAll('input[pattern="[0-9]{6}"]');
            
            numericFields.forEach(field => {
                field.addEventListener('input', function(e) {
                    // Remove caracteres não numéricos
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    
                    // Limita a 6 dígitos
                    if (value.length > 6) {
                        value = value.slice(0, 6);
                    }
                    
                    e.target.value = value;
                });
                
                // Previne colar texto não numérico
                field.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const numericValue = paste.replace(/[^0-9]/g, '').slice(0, 6);
                    e.target.value = numericValue;
                });
            });
        }
        function setupSearch() {
            const searchInput = document.getElementById('searchAdmin');
            const searchButton = document.getElementById('searchButton');
            const clearButton = document.getElementById('clearSearch');
            const adminItems = document.querySelectorAll('.admin-item');
            const emptyState = document.getElementById('emptyState');

            console.log('Total de itens encontrados:', adminItems.length); // Debug

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleItems = 0;

                console.log('Pesquisando por:', searchTerm); // Debug

                adminItems.forEach((item, index) => {
                    const name = item.getAttribute('data-name') || '';
                    const email = item.getAttribute('data-email') || '';
                    const status = item.getAttribute('data-status') || '';
                    
                    console.log(`Item ${index}:`, {name, email, status}); // Debug
                    
                    const isVisible = searchTerm === '' || 
                                    name.includes(searchTerm) || 
                                    email.includes(searchTerm) || 
                                    status.includes(searchTerm);
                    
                    item.style.display = isVisible ? 'block' : 'none';
                    if (isVisible) visibleItems++;
                    
                    console.log(`Item ${index} visível:`, isVisible); // Debug
                });

                console.log('Itens visíveis:', visibleItems); // Debug
                emptyState.style.display = visibleItems === 0 && searchTerm !== '' ? 'block' : 'none';
                
                // Mostrar/ocultar botão limpar
                clearButton.style.display = searchTerm !== '' ? 'inline-block' : 'none';
            }

            // Pesquisa em tempo real enquanto digita
            searchInput.addEventListener('input', function() {
                console.log('Input evento disparado'); // Debug
                performSearch();
            });
            
            // Pesquisa ao clicar no botão
            searchButton.addEventListener('click', function() {
                console.log('Botão pesquisar clicado'); // Debug
                performSearch();
            });
            
            // Pesquisa ao pressionar Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    console.log('Enter pressionado'); // Debug
                    performSearch();
                }
            });
            
            // Limpar pesquisa
            clearButton.addEventListener('click', function() {
                console.log('Botão limpar clicado'); // Debug
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });
        }

        // Animações simples
        function setupAnimations() {
            // Hover effect nos cards
            const adminCards = document.querySelectorAll('.admin-card');
            adminCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Animação dos contadores
            const counters = document.querySelectorAll('.stats-card h4, .admin-card h4');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                if (!isNaN(target) && target > 0) {
                    animateCounter(counter, target);
                }
            });
        }

        // Função para animar contadores
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

        // Inicialização básica
        document.addEventListener('DOMContentLoaded', function() {
            setupNumericValidation();
            setupSearch();
            setupAnimations();

            // Limpar formulário quando modal for fechado
            const addModal = document.getElementById('addAdminModal');
            addModal.addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
            });

            // Confirmação simples para limpar histórico
            const limparBtn = document.querySelector('button[name="limpar_historico"]');
            if (limparBtn) {
                limparBtn.addEventListener('click', function(e) {
                    // Remove a confirmação dupla, deixa o modal fazer o trabalho
                });
            }
        });
    </script>
    
</body>
</html>