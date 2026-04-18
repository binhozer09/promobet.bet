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

// Pegando a pesquisa do formulário, caso exista
$search = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
?>

<head>
    <?php $title = "Atividades Administrativas";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <!-- Card Principal -->
                    <div class="col-12">
                        <div class="card rounded-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="iconoir-activity me-2"></i>Registro de Atividades
                                        </h5>
                                        <p class="text-muted mb-0 small">Histórico completo das ações administrativas</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                            <i class="iconoir-eye me-1"></i>Monitoramento Ativo
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Formulário de Pesquisa -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="search-box rounded-3 p-3">
                                            <form method="get" action="" class="d-flex gap-3">
                                                <div class="flex-grow-1">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-transparent border-end-0 pe-3">
                                                            <i class="iconoir-search text-muted"></i>
                                                        </span>
                                                        <input type="text" name="search" class="form-control border-start-0 ps-3" 
                                                               placeholder="Pesquisar por ID, e-mail, atividade ou data..." 
                                                               value="<?= htmlspecialchars($search); ?>">
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary px-4" type="submit">
                                                    Pesquisar
                                                </button>
                                                <?php if($search): ?>
                                                <a href="?" class="btn btn-outline-secondary">
                                                    Limpar
                                                </a>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estatísticas Rápidas -->
                                <?php
                                global $mysqli;
                                
                                // Estatísticas básicas
                                $stats_query = "SELECT 
                                    COUNT(*) as total_logs,
                                    COUNT(DISTINCT email) as admins_ativos,
                                    COUNT(CASE WHEN DATE(timestamp) = CURDATE() THEN 1 END) as logs_hoje,
                                    COUNT(CASE WHEN timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as logs_semana
                                FROM logs";
                                $stats_result = mysqli_query($mysqli, $stats_query);
                                $stats = mysqli_fetch_assoc($stats_result);
                                ?>
                                
                                <div class="row mb-4">
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-primary-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-activity h3 text-primary mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['total_logs']); ?></h5>
                                            <p class="text-muted mb-0 small">Total de Atividades</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-success-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-user-crown h3 text-success mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['admins_ativos']); ?></h5>
                                            <p class="text-muted mb-0 small">Admins Ativos</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-warning-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-calendar h3 text-warning mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['logs_hoje']); ?></h5>
                                            <p class="text-muted mb-0 small">Atividades Hoje</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="stats-card bg-info-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-clock h3 text-info mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['logs_semana']); ?></h5>
                                            <p class="text-muted mb-0 small">Última Semana</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabela Responsiva -->
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0 text-center">
                                                    <span class="badge bg-primary-subtle text-primary">ID</span>
                                                </th>
                                                <th class="border-0">
                                                    <i class="iconoir-user me-1 text-muted"></i>Administrador
                                                </th>
                                                <th class="border-0">
                                                    <i class="iconoir-activity me-1 text-muted"></i>Ação Realizada
                                                </th>
                                                <th class="border-0">
                                                    <i class="iconoir-calendar me-1 text-muted"></i>Data & Hora
                                                </th>
                                                <th class="border-0 text-center">
                                                    <i class="iconoir-info-circle me-1 text-muted"></i>Status
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Definir a página atual
                                            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                            $qnt_result_pg = 20; // Reduzido para melhor UX
                                            $inicio = ($pagina - 1) * $qnt_result_pg;

                                            // Consultar com filtro de pesquisa
                                            $sql_condition = '';
                                            if ($search) {
                                                $sql_condition = "WHERE id LIKE '%$search%' OR email LIKE '%$search%' OR action LIKE '%$search%' OR timestamp LIKE '%$search%'";
                                            }

                                            // Contar total de registros
                                            $result_logs_count = "SELECT COUNT(*) AS total FROM logs $sql_condition";
                                            $resultado_logs_count = mysqli_query($mysqli, $result_logs_count);
                                            $row = mysqli_fetch_assoc($resultado_logs_count);
                                            $total_registros = $row['total'];

                                            // Calcular total de páginas
                                            $total_paginas = ceil($total_registros / $qnt_result_pg);

                                            // Consultar os dados
                                            $result_logs = "SELECT * FROM logs $sql_condition ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
                                            $resultado_logs = mysqli_query($mysqli, $result_logs);

                                            if ($resultado_logs && mysqli_num_rows($resultado_logs) > 0) {
                                                while ($log = mysqli_fetch_assoc($resultado_logs)) {
                                                    // Definir ícone e cor baseado na ação
                                                    $action_icon = 'iconoir-info-circle';
                                                    $action_class = 'text-info';
                                                    
                                                    if (stripos($log['action'], 'login') !== false) {
                                                        $action_icon = 'iconoir-log-in';
                                                        $action_class = 'text-success';
                                                    } elseif (stripos($log['action'], 'logout') !== false) {
                                                        $action_icon = 'iconoir-log-out';
                                                        $action_class = 'text-warning';
                                                    } elseif (stripos($log['action'], 'delete') !== false || stripos($log['action'], 'exclu') !== false) {
                                                        $action_icon = 'iconoir-trash';
                                                        $action_class = 'text-danger';
                                                    } elseif (stripos($log['action'], 'create') !== false || stripos($log['action'], 'cria') !== false) {
                                                        $action_icon = 'iconoir-plus';
                                                        $action_class = 'text-success';
                                                    } elseif (stripos($log['action'], 'update') !== false || stripos($log['action'], 'edit') !== false) {
                                                        $action_icon = 'iconoir-edit';
                                                        $action_class = 'text-primary';
                                                    }

                                                    // Calcular tempo decorrido
                                                    $timestamp = strtotime($log['timestamp']);
                                                    $now = time();
                                                    $diff = $now - $timestamp;
                                                    
                                                    if ($diff < 3600) {
                                                        $tempo_status = 'bg-success-subtle text-success';
                                                        $tempo_text = 'Recente';
                                                    } elseif ($diff < 86400) {
                                                        $tempo_status = 'bg-warning-subtle text-warning';
                                                        $tempo_text = 'Hoje';
                                                    } else {
                                                        $tempo_status = 'bg-secondary-subtle text-secondary';
                                                        $tempo_text = 'Antigo';
                                                    }
                                            ?>
                                                <tr class="table-row-hover">
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark fw-bold">#<?= $log['id']; ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="iconoir-user-crown text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium"><?= $log['email']; ?></div>
                                                                <div class="small text-muted">Administrador</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="<?= $action_icon; ?> <?= $action_class; ?> me-2"></i>
                                                            <div>
                                                                <div class="fw-medium"><?= $log['action']; ?></div>
                                                                <div class="small text-muted">Ação administrativa</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <div class="fw-medium"><?= date('d/m/Y', strtotime($log['timestamp'])); ?></div>
                                                            <div class="text-muted"><?= date('H:i:s', strtotime($log['timestamp'])); ?></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge <?= $tempo_status; ?> px-2 py-1">
                                                            <i class="iconoir-check-circle me-1"></i><?= $tempo_text; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center py-5'>";
                                                echo "<div class='empty-state'>";
                                                echo "<i class='iconoir-search text-muted' style='font-size: 3rem;'></i>";
                                                echo "<h5 class='text-muted mt-3'>Nenhum resultado encontrado</h5>";
                                                echo "<p class='text-muted'>Tente ajustar os termos de pesquisa ou remover filtros.</p>";
                                                echo "</div>";
                                                echo "</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginação Melhorada -->
                                <?php if($total_paginas > 1): ?>
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <div class="text-muted">
                                        <small>
                                            Mostrando <?= min($inicio + 1, $total_registros); ?> - <?= min($inicio + $qnt_result_pg, $total_registros); ?> 
                                            de <?= number_format($total_registros); ?> registros
                                        </small>
                                    </div>
                                    
                                    <nav aria-label="Navegação de páginas">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item <?= ($pagina <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?= $pagina - 1; ?>&search=<?= urlencode($search); ?>">
                                                    <i class="iconoir-nav-arrow-left"></i>
                                                </a>
                                            </li>

                                            <?php 
                                            $start = max(1, $pagina - 2);
                                            $end = min($total_paginas, $pagina + 2);
                                            
                                            if($start > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=1&search=<?= urlencode($search); ?>">1</a>
                                                </li>
                                                <?php if($start > 2): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                                <li class="page-item <?= ($i == $pagina) ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?pagina=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if($end < $total_paginas): ?>
                                                <?php if($end < $total_paginas - 1): ?>
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                <?php endif; ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=<?= $total_paginas; ?>&search=<?= urlencode($search); ?>"><?= $total_paginas; ?></a>
                                                </li>
                                            <?php endif; ?>

                                            <li class="page-item <?= ($pagina >= $total_paginas) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?pagina=<?= $pagina + 1; ?>&search=<?= urlencode($search); ?>">
                                                    <i class="iconoir-nav-arrow-right"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Função de Toast -->
    <script>
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
    </script>

    <!-- Exibir o Toast baseado nas ações do formulário -->
    <?php if (isset($toastType) && isset($toastMessage) && $toastType && $toastMessage): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

    <style>
    .stats-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .table-row-hover:hover {
        background-color: rgba(0,123,255,0.05);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
    }

    .input-group-text {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .form-control {
        padding-left: 15px;
    }
    
    .empty-state {
        padding: 3rem 0;
    }
    
    .page-link {
        border-radius: 6px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    </style>

</body>
</html>