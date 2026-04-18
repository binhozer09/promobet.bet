<?php
include 'partials/html.php';

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

//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_webhooks($limit, $offset)
{
    global $mysqli;
    $qry = "SELECT * FROM webhook LIMIT ? OFFSET ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $webhooks = [];
    while ($row = $result->fetch_assoc()) {
        $webhooks[] = $row;
    }
    return $webhooks;
}

function count_webhooks()
{
    global $mysqli;
    $qry = "SELECT COUNT(*) as total FROM webhook";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total'];
}

function get_webhook_stats()
{
    global $mysqli;
    $stats = [
        'total' => 0,
        'ativos' => 0,
        'inativos' => 0
    ];
    
    // Total de webhooks
    $qry = "SELECT COUNT(*) as total FROM webhook";
    $result = mysqli_query($mysqli, $qry);
    $stats['total'] = mysqli_fetch_assoc($result)['total'];
    
    // Webhooks ativos
    $qry = "SELECT COUNT(*) as ativos FROM webhook WHERE status = 1";
    $result = mysqli_query($mysqli, $qry);
    $stats['ativos'] = mysqli_fetch_assoc($result)['ativos'];
    
    // Webhooks inativos
    $stats['inativos'] = $stats['total'] - $stats['ativos'];
    
    return $stats;
}

function update_webhook($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE webhook SET 
        bot_id = ?, 
        chat_id = ?, 
        status = ? 
        WHERE id = ?");

    $qry->bind_param(
        "ssii",
        $data['bot_id'],
        $data['chat_id'],
        $data['status'],
        $data['id']
    );
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id' => intval($_POST['id']),
        'bot_id' => trim($_POST['bot_id']),
        'chat_id' => trim($_POST['chat_id']),
        'status' => intval($_POST['status']),
    ];

    if (update_webhook($data)) {
        $toastType = 'success';
        $toastMessage = 'Webhook atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o webhook. Tente novamente.';
    }
}

$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$total_webhooks = count_webhooks();
$total_pages = ceil($total_webhooks / $limit);
$webhook_stats = get_webhook_stats();

$webhooks = get_webhooks($limit, $offset);
?>

<!DOCTYPE html>
<html>
<head>
    <?php 
    $title = "Gerenciamento de Webhooks";
    include 'partials/title-meta.php'; 
    ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php'; ?>
    
    <style>
        :root {
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --info-accent: #3b82f6;
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
            color: var(--text-primary) !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page-wrapper {
            background-color: var(--primary-bg) !important;
        }

        .page-content {
            background-color: var(--primary-bg) !important;
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .webhooks-header {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .webhooks-header::before {
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

        .webhooks-header h4 {
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

        .webhooks-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .webhooks-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--secondary-bg) !important;
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
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.3);
            border-color: var(--info-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.total::before {
            background: linear-gradient(90deg, var(--info-color), #2563eb);
        }

        .stat-card.ativos::before {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .stat-card.inativos::before {
            background: linear-gradient(90deg, var(--danger-color), #dc2626);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 24px;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
        }

        .stat-icon.ativos {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.inativos {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary) !important;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary) !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Card Override */
        .card {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 16px !important;
            color: var(--text-primary) !important;
        }

        .card-header {
            background: var(--secondary-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 24px !important;
        }

        .card-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            background: var(--secondary-bg) !important;
            color: var(--text-primary) !important;
            padding: 24px !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            background: var(--primary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
        }

        .form-control:focus, .form-select:focus {
            background: var(--primary-bg) !important;
            border-color: var(--info-color) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary) !important;
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-text {
            color: var(--text-secondary) !important;
            font-size: 0.8rem;
        }

        /* Table Styling */
        .table-responsive {
            padding: 0;
            border-radius: 20px !important;
        }

        .table {
            margin: 0 !important;
            color: var(--text-primary) !important;
            background: transparent !important;
        }

        .table thead th {
            background: var(--primary-bg) !important;
            border: none !important;
            color: var(--text-primary) !important;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 16px !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        .table tbody td {
            background: var(--secondary-bg) !important;
            border: none !important;
            color: var(--text-primary) !important;
            padding: 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color) !important;
        }

        .table tbody tr:hover {
            background: rgba(59, 130, 246, 0.05) !important;
        }

        .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Badges */
        .badge {
            padding: 8px 16px !important;
            border-radius: 20px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-success {
            background: rgba(16, 185, 129, 0.2) !important;
            color: var(--success-color) !important;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .bg-danger {
            background: rgba(239, 68, 68, 0.2) !important;
            color: var(--danger-color) !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Buttons */
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s ease !important;
            padding: 12px 24px !important;
        }

        .btn-primary {
            background: var(--info-color) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: #2563eb !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }

        .btn-secondary {
            background: var(--border-color) !important;
            border: none !important;
            color: var(--text-primary) !important;
        }

        .btn-secondary:hover {
            background: #6b7280 !important;
            transform: translateY(-2px) !important;
        }

        /* Modal Override - Correção específica */
        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
            background-color: rgba(0, 0, 0, 0.7) !important;
        }

        .modal-dialog {
            z-index: 1060 !important;
            position: relative;
        }

        .modal-content {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 16px !important;
            position: relative;
            z-index: 1061 !important;
        }

        .modal-header {
            background: var(--secondary-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .modal-body {
            background: var(--secondary-bg) !important;
            color: var(--text-primary) !important;
        }

        .modal-footer {
            background: var(--secondary-bg) !important;
            border-top: 2px solid var(--border-color) !important;
        }

        .modal-title {
            color: var(--text-primary) !important;
            font-weight: 700;
        }

        .btn-close {
            background: transparent !important;
            border: none !important;
            color: var(--text-primary) !important;
            opacity: 0.8;
            font-size: 1.2rem;
        }

        .btn-close:hover {
            opacity: 1;
            color: var(--danger-color) !important;
        }

        /* Pagination */
        .pagination .page-link {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            margin: 0 2px !important;
            border-radius: 8px !important;
        }

        .pagination .page-link:hover {
            background: var(--info-color) !important;
            border-color: var(--info-color) !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--info-color) !important;
            border-color: var(--info-color) !important;
            color: white !important;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--info-color), #2563eb);
            border-radius: 2px;
            margin-right: 12px;
        }

        /* Toast Container */
        .toast {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .toast-header {
            background: var(--secondary-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .toast-body {
            color: var(--text-primary) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }
            
            .webhooks-header {
                padding: 24px;
            }
            
            .webhooks-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-responsive {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .webhooks-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/startbar.php'; ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="webhooks-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4>
                                <i class="bi bi-globe"></i>
                                Gerenciamento de Webhooks
                            </h4>
                            <p>Configure e monitore todos os webhooks do sistema • Total: <?= $total_webhooks; ?> webhooks</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="webhooks-stats">
                    <div class="stat-card total">
                        <div class="stat-icon total">
                            <i class="bi bi-globe"></i>
                        </div>
                        <div class="stat-value"><?= $webhook_stats['total']; ?></div>
                        <div class="stat-label">Total Webhooks</div>
                    </div>
                    <div class="stat-card ativos">
                        <div class="stat-icon ativos">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value"><?= $webhook_stats['ativos']; ?></div>
                        <div class="stat-label">Webhooks Ativos</div>
                    </div>
                    <div class="stat-card inativos">
                        <div class="stat-icon inativos">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-value"><?= $webhook_stats['inativos']; ?></div>
                        <div class="stat-label">Webhooks Inativos</div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col" style="display: flex;align-content: center;align-items: center;">
                                        <div class="tag"></div>
                                        <h4 class="card-title">
                                            <i class="bi bi-list-ul"></i>
                                            Lista de Webhooks
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Bot ID</th>
                                                <th>Chat ID</th>
                                                <th>Status</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($webhooks)): ?>
                                                <?php foreach ($webhooks as $webhook): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?= htmlspecialchars($webhook['nome']) ?></strong>
                                                        </td>
                                                        <td>
                                                            <code><?= htmlspecialchars($webhook['bot_id']) ?></code>
                                                        </td>
                                                        <td>
                                                            <code><?= htmlspecialchars($webhook['chat_id']) ?></code>
                                                        </td>
                                                        <td>
                                                            <?php if ($webhook['status'] == 1): ?>
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle me-1"></i>Ativo
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">
                                                                    <i class="bi bi-x-circle me-1"></i>Inativo
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editWebhookModal<?= $webhook['id'] ?>">
                                                                <i class="bi bi-pencil"></i> Editar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center" style="color: var(--text-secondary); padding: 40px;">
                                                        <i class="bi bi-globe" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
                                                        <strong>Nenhum webhook encontrado</strong><br>
                                                        <small>Configure seu primeiro webhook</small>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginação -->
                                <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Page navigation" class="mt-4">
                                        <ul class="pagination justify-content-center">
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modais de Edição -->
                <?php foreach ($webhooks as $webhook): ?>
                    <div class="modal fade" id="editWebhookModal<?= $webhook['id'] ?>" tabindex="-1" aria-labelledby="editWebhookModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editWebhookModalLabel">
                                        <i class="bi bi-pencil me-2"></i>Editar Webhook
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome</label>
                                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($webhook['nome']) ?>" readonly>
                                            <div class="form-text">O nome do webhook não pode ser alterado</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bot_id" class="form-label">Bot ID *</label>
                                            <input type="text" name="bot_id" class="form-control" value="<?= htmlspecialchars($webhook['bot_id']) ?>" required>
                                            <div class="form-text">Token do bot do Telegram</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="chat_id" class="form-label">Chat ID *</label>
                                            <input type="text" name="chat_id" class="form-control" value="<?= htmlspecialchars($webhook['chat_id']) ?>" required>
                                            <div class="form-text">ID do chat ou canal do Telegram</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status *</label>
                                            <select name="status" class="form-select" required>
                                                <option value="1" <?= $webhook['status'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                <option value="0" <?= $webhook['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                            </select>
                                            <div class="form-text">Define se o webhook está ativo ou inativo</div>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $webhook['id'] ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Salvar Alterações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <?php include 'partials/endbar.php'; ?>
        </div>
    </div>
    
    <!-- Toast Container -->

    <?php include 'partials/vendorjs.php'; ?>
    <script src="assets/js/app.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animação inicial dos cards
            const cards = document.querySelectorAll('.stat-card, .card, .webhooks-header');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animação dos contadores
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const value = parseInt(stat.textContent);
                if (!isNaN(value) && value > 0) {
                    animateCounter(stat, value);
                }
            });

            // Hover effects
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Corrigir modal
            document.addEventListener('shown.bs.modal', function(e) {
                const modal = e.target;
                modal.style.zIndex = '1055';
            });
        });

        // Animação de contador
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 30);
        }

    </script>

</body>
</html>