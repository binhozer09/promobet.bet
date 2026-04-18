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
?>

<head>
    <?php $title = "Todos Usuários";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
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
        .users-header {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .users-header::before {
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

        .users-header h4 {
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

        .users-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .users-header .btn {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }

        .users-header .btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px) !important;
        }

        /* Stats Overview */
        .users-stats {
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
        }

        .stat-card.saldo::before {
            background: linear-gradient(90deg, var(--accent-color), #7c3aed);
        }

        .stat-card.depositado::before {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .stat-card.sacado::before {
            background: linear-gradient(90deg, var(--danger-color), #dc2626);
        }

        .stat-card.media::before {
            background: linear-gradient(90deg, var(--info-color), #2563eb);
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

        .stat-icon.saldo {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
        }

        .stat-icon.depositado {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.sacado {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .stat-icon.media {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
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

        /* Search and Filter Section */
        .search-filter-section {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 2px solid var(--border-color);
        }

        .search-filter-title {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
            border-radius: 20px !important;
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
            border-color: var(--accent-color) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary) !important;
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
            background: rgba(139, 92, 246, 0.05) !important;
        }

        .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        .table-light {
            background: var(--primary-bg) !important;
        }

        /* Badges */
        .badge {
            padding: 6px 12px !important;
            border-radius: 16px !important;
            font-size: 11px !important;
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

        .bg-warning {
            background: rgba(245, 158, 11, 0.2) !important;
            color: var(--warning-color) !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Buttons */
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary {
            background: var(--accent-color) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: #7c3aed !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3) !important;
        }

        .btn-success {
            background: var(--success-color) !important;
            border: none !important;
        }

        .btn-success:hover {
            background: #059669 !important;
            transform: translateY(-2px) !important;
        }

        /* Dropdown */
        .dropdown-menu {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 12px !important;
        }

        .dropdown-item:hover {
            background: var(--hover-bg) !important;
            color: var(--text-primary) !important;
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
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 2px;
            margin-right: 12px;
        }

        /* Loading Animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .loading {
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }
            
            .users-header {
                padding: 24px;
            }
            
            .users-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-responsive {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .users-stats {
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

    <?php
    $search_query = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_query = mysqli_real_escape_string($mysqli, $_GET['search']);
    }
    
    $status_filter = '';
    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $status_filter = (int) $_GET['status'];
    }
    
    $limit = 50;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    
    // Contar o total de usuários, excluindo aqueles com statusaff = 1
    $query_total_usuarios = "SELECT COUNT(*) AS total_usuarios FROM usuarios WHERE statusaff != 1";
    if (!empty($search_query)) {
        $query_total_usuarios .= " AND (id LIKE '%$search_query%' OR mobile LIKE '%$search_query%')";
    }
    if ($status_filter !== '') {
        $query_total_usuarios .= " AND statusaff = $status_filter";
    }
    $result_total_usuarios = mysqli_query($mysqli, $query_total_usuarios);
    $total_usuarios = mysqli_fetch_assoc($result_total_usuarios)['total_usuarios'];
    
    $total_pages = ceil($total_usuarios / $limit);
    
    // Buscar os usuários, excluindo aqueles com statusaff = 1
    $query_usuarios = "SELECT * FROM usuarios WHERE statusaff != 1";
    if (!empty($search_query)) {
        $query_usuarios .= " AND (id LIKE '%$search_query%' OR mobile LIKE '%$search_query%')";
    }
    
    if ($status_filter !== '') {
        if ($status_filter == 2) {
            $query_usuarios .= " AND banido = 1";
        } else {
            $query_usuarios .= " AND statusaff = $status_filter";
        }
    }
    
    $query_usuarios .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $result_usuarios = mysqli_query($mysqli, $query_usuarios);
    ?>

    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">

                <!-- Header -->
                <div class="users-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4>
                                <i class="bi bi-people"></i>
                                Todos Usuários
                            </h4>
                            <p>Gerencie todos os usuários da plataforma • Total: <?= $total_usuarios; ?> usuários</p>
                        </div>
                        <div class="col-auto">
                            <a href="export/exportar_usuarios.php" class="btn btn-primary">
                                <i class="bi bi-download"></i> Exportar Dados
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="users-stats">
                    <div class="stat-card saldo">
                        <div class="stat-icon saldo">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_saldos_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Saldos</div>
                    </div>
                    <div class="stat-card depositado">
                        <div class="stat-icon depositado">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_dep_pagos_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Depositado</div>
                    </div>
                    <div class="stat-card sacado">
                        <div class="stat-icon sacado">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_saques_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Sacado</div>
                    </div>
                    <div class="stat-card media">
                        <div class="stat-icon media">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(media_saldo_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Saldo Médio</div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="search-filter-section">
                    <div class="search-filter-title">
                        <i class="bi bi-funnel"></i>
                        Filtros e Busca
                    </div>
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Buscar por ID ou Nome do Usuário"
                                    value="<?= htmlspecialchars($search_query) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="status" class="form-select">
                                    <option value="">Filtrar por Status</option>
                                    <option value="2" <?= (isset($_GET['status']) && $_GET['status'] == '2') ? 'selected' : ''; ?>>Banido</option>
                                    <option value="1" <?= (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : ''; ?>>Afiliado</option>
                                    <option value="0" <?= (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>Usuário</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-search"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col" style="display: flex;align-content: center;align-items: center;">
                                        <div class="tag"></div>
                                        <h4 class="card-title">Lista de Usuários (<?= $total_usuarios; ?> encontrados)</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->

                            <!-- Filtros e Busca -->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Usuário</th>
                                                <th>Saldo</th>
                                                <th>Depositado</th>
                                                <th>Sacado</th>
                                                <th>Cargo</th>
                                                <th>Nível</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
                                                while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
                                                    // Definir o cargo com base nos dados da tabela
                                                    if ($usuario['banido'] == 1) {
                                                        $cargo_badge = "<span class='badge bg-danger'><i class='bi bi-ban me-1'></i>Banido</span>";
                                                    } elseif ($usuario['statusaff'] == '1') {
                                                        $cargo_badge = "<span class='badge bg-warning'><i class='bi bi-people me-1'></i>Afiliado</span>";
                                                    } else {
                                                        $cargo_badge = "<span class='badge bg-success'><i class='bi bi-person me-1'></i>Usuário</span>";
                                                    }

                                                    // Consultar total sacado e depositado
                                                    $query_sacado = "SELECT SUM(valor) AS total_sacado FROM solicitacao_saques WHERE id_user = {$usuario['id']} AND status = 1";
                                                    $result_sacado = mysqli_query($mysqli, $query_sacado);
                                                    $sacado = ($result_sacado && mysqli_num_rows($result_sacado) > 0) ? mysqli_fetch_assoc($result_sacado)['total_sacado'] : 0;

                                                    $query_depositado = "SELECT SUM(valor) AS total_depositado FROM transacoes WHERE usuario = {$usuario['id']} AND status = 'pago'";
                                                    $result_depositado = mysqli_query($mysqli, $query_depositado);
                                                    $depositado = ($result_depositado && mysqli_num_rows($result_depositado) > 0) ? mysqli_fetch_assoc($result_depositado)['total_depositado'] : 0;
                                                    ?>
                                                    <tr>
                                                        <td><strong><?= $usuario['id']; ?></strong></td>
                                                        <td><?= $usuario['mobile']; ?></td>
                                                        <td>
                                                            <span style="color: var(--accent-color); font-weight: 700;">
                                                                R$ <?= number_format($usuario['saldo'], 2, ',', '.'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--success-color); font-weight: 700;">
                                                                R$ <?= number_format($depositado, 2, ',', '.'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--danger-color); font-weight: 700;">
                                                                R$ <?= number_format($sacado, 2, ',', '.'); ?>
                                                            </span>
                                                        </td>
                                                        <td><?= $cargo_badge; ?></td>
                                                        <td>
                                                            <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: var(--info-color); border: 1px solid rgba(59, 130, 246, 0.3);">
                                                                <i class="bi bi-star"></i> <?= $usuario['lvl_vip']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown d-inline-block">
                                                                <a class="dropdown-toggle arrow-none" id="dLabel11"
                                                                    data-bs-toggle="dropdown" href="#" role="button"
                                                                    aria-haspopup="false" aria-expanded="false">
                                                                    <i class="bi bi-three-dots-vertical fs-5 text-muted"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item"
                                                                        href="<?= $painel_adm_ver_usuarios . encodeAll($usuario['id']); ?>">
                                                                        <i class="bi bi-eye"></i> Ver Detalhes
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='8' class='text-center' style='color: var(--text-secondary); padding: 40px;'>
                                                        <i class='bi bi-people' style='font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;'></i>
                                                        <strong>Nenhum usuário encontrado</strong><br>
                                                        <small>Tente ajustar os filtros de busca</small>
                                                      </td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table><!--end /table-->
                                </div><!--end /tableresponsive-->

                                <!-- Paginação -->
                                <?php if ($total_pages > 1): ?>
                                    <nav class="mt-4">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search_query) ?>&status=<?= $status_filter ?>" aria-label="Anterior">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php 
                                            // Mostrar apenas algumas páginas ao redor da atual
                                            $start = max(1, $page - 2);
                                            $end = min($total_pages, $page + 2);
                                            
                                            for ($i = $start; $i <= $end; $i++): ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search_query) ?>&status=<?= $status_filter ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search_query) ?>&status=<?= $status_filter ?>" aria-label="Próximo">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!-- container -->

            <!--Start Rightbar-->
            <?php include 'partials/endbar.php' ?>

            <!--end footer-->
        </div><!-- end page content -->
    </div><!-- end page-wrapper -->

    <!-- Javascript  -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animação inicial dos cards
            const cards = document.querySelectorAll('.stat-card, .card, .search-filter-section, .users-header');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animação dos contadores nas estatísticas
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const originalText = stat.textContent;
            
                if (originalText.includes('R$')) {
                    // Para valores monetários, extrair valor numérico corretamente
                    const cleanValue = originalText
                        .replace('R$', '')         // Remove o R$
                        .trim()
                        .replace(/\./g, '')        // Remove pontos de milhar
                        .replace(',', '.');        // Troca vírgula decimal por ponto
                    const value = parseFloat(cleanValue);
                    if (!isNaN(value) && value > 0) {
                        animateCounter(stat, value, originalText);
                    }
                } else {
                    // Para números simples
                    const value = parseInt(originalText.replace(/[^\d]/g, ''));
                    if (!isNaN(value) && value > 0) {
                        animateCounter(stat, value, originalText);
                    }
                }
            });

            // Hover effects nas linhas da tabela
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.transition = 'all 0.3s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Efeito nos stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Auto-submit do formulário quando mudar o filtro de status
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    // Opcional: submit automático quando mudar o status
                    // this.form.submit();
                });
            }

            // Melhorar a experiência de busca com debounce
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Opcional: busca automática após 500ms de inatividade
                        // this.form.submit();
                    }, 500);
                });
            }
        });

        // Animação de contador
        function animateCounter(element, target, originalText) {
            let current = 0;
            const isPrice = originalText.includes('R$'); // Fechar as aspas e colocar o símbolo correto
            
            // Para valores monetários, extrair o valor numérico corretamente
            if (isPrice) {
                // Remove "R$", espaços, pontos e converte vírgula para ponto
                const cleanValue = originalText.replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
                target = parseFloat(cleanValue);
            }
            
            const increment = target / 50;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (isPrice) {
                    // Formatar corretamente como moeda brasileira
                    element.textContent = 'R$ ' + current.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 30);
        }

        // Função para destacar termos de busca na tabela
        function highlightSearchTerms() {
            const searchTerm = '<?= htmlspecialchars($search_query) ?>';
            if (searchTerm.length > 0) {
                const tableRows = document.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                            const regex = new RegExp(`(${searchTerm})`, 'gi');
                            cell.innerHTML = cell.innerHTML.replace(regex, '<mark style="background: rgba(139, 92, 246, 0.3); padding: 2px 4px; border-radius: 4px;">$1</mark>');
                        }
                    });
                });
            }
        }

        // Executar highlight após carregamento
        setTimeout(highlightSearchTerms, 1000);
    </script>

</body>
<!--end body-->

</html>

<?php
// Funções de totalização e média para o resumo financeiro
function total_dep_pagos_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE status = 'pago' AND tipo = 'deposito'";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function total_saldos_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(saldo) as total_saldo FROM usuarios";
    $result = mysqli_query($mysqli, $qry);
    
    return mysqli_fetch_assoc($result)['total_saldo'] ?? 0;
}

function total_saques_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function media_saldo_usuarios()
{
    global $mysqli;
    $qry = "SELECT AVG(saldo) as media_saldo FROM usuarios";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['media_saldo'] ?? 0;
}
?>