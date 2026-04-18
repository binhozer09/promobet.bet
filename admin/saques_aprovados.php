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
    <?php $title = "Saques Aprovados";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --success-accent: #10b981;
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
        .withdrawals-header {
            background: linear-gradient(135deg, var(--success-accent), #059669);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .withdrawals-header::before {
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

        .withdrawals-header h4 {
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

        .withdrawals-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .withdrawals-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .stat-card.approved::before {
            background: linear-gradient(90deg, var(--success-accent), #059669);
        }

        .stat-card.total::before {
            background: linear-gradient(90deg, var(--accent-color), #7c3aed);
        }

        .stat-card.value::before {
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

        .stat-icon.approved {
            background: linear-gradient(135deg, var(--success-accent), #059669);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
        }

        .stat-icon.value {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary) !important;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary) !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Info Tips */
        .info-tips {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .tips-title {
            color: var(--success-accent) !important;
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
            color: var(--text-secondary) !important;
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .tips-list li::before {
            content: '💰';
            position: absolute;
            left: 0;
            top: 0;
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
            padding: 0 !important;
            border-radius: 20px !important;
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
            background: rgba(16, 185, 129, 0.05) !important;
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

        /* Code Preview */
        .code-preview {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: var(--text-secondary) !important;
            background: var(--primary-bg);
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            display: inline-block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Action Buttons */
        .btn-modern {
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .btn-success-modern {
            background: var(--info-color) !important;
            color: white !important;
            border: none !important;
        }

        .btn-success-modern:hover {
            background: #2563eb !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--success-accent), #059669);
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
            
            .withdrawals-header {
                padding: 24px;
            }
            
            .withdrawals-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-responsive {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .withdrawals-stats {
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

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">

                <?php
                global $mysqli;
                // Buscar estatísticas dos saques aprovados
                $query_stats = "SELECT 
                    COUNT(*) as total_approved,
                    SUM(valor) as total_value
                    FROM solicitacao_saques 
                    WHERE status = '1'";
                $result_stats = mysqli_query($mysqli, $query_stats);
                $stats = mysqli_fetch_assoc($result_stats);

                $query_total = "SELECT COUNT(*) as total_withdrawals FROM solicitacao_saques";
                $result_total = mysqli_query($mysqli, $query_total);
                $total_stats = mysqli_fetch_assoc($result_total);
                ?>

                <!-- Header -->
                <div class="withdrawals-header">
                    <h4>
                        <i class="bi bi-check-circle"></i>
                        Saques Aprovados
                    </h4>
                    <p>Visualize e gerencie todos os saques que foram aprovados e processados</p>
                </div>

                <!-- Stats Overview -->
                <div class="withdrawals-stats">
                    <div class="stat-card approved">
                        <div class="stat-icon approved">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-value"><?= $stats['total_approved'] ?? 0 ?></div>
                        <div class="stat-label">Saques Aprovados</div>
                    </div>
                    <div class="stat-card total">
                        <div class="stat-icon total">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-value"><?= $total_stats['total_withdrawals'] ?? 0 ?></div>
                        <div class="stat-label">Total de Saques</div>
                    </div>
                    <div class="stat-card value">
                        <div class="stat-icon value">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($stats['total_value'] ?? 0, 2, ',', '.') ?></div>
                        <div class="stat-label">Valor Total Aprovado</div>
                    </div>
                </div>

                <!-- Info Tips -->
                <div class="info-tips">
                    <div class="tips-title">
                        <i class="bi bi-info-circle"></i>
                        Informações sobre Saques
                    </div>
                    <ul class="tips-list">
                        <li>Todos os saques listados foram aprovados e estão prontos para processamento</li>
                        <li>Valores aprovados representam saques confirmados pelo sistema</li>
                        <li>Data/hora mostra quando o saque foi solicitado e aprovado</li>
                        <li>Transações aprovadas garantem a liberação dos fundos</li>
                    </ul>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col" style="display: flex;align-content: center;align-items: center;">
                                        <div class="tag"></div>
                                        <h4 class="card-title">Lista de Saques Aprovados</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID Usuário</th>
                                                <th>Ações</th>
                                                <th>Transação ID</th>
                                                <th>Valor</th>
                                                <th>Data/Hora</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Consultar os dados da tabela solicitacao_saques
                                            $query_usuarios = "SELECT * FROM solicitacao_saques WHERE status = '1' ORDER BY id DESC";
                                            $result_usuarios = mysqli_query($mysqli, $query_usuarios);

                                            if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
                                                while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
                                                    $cargo_badge = "<span class='badge bg-success'><i class='bi bi-check-circle me-1'></i>Aprovado</span>";
                                                    ?>
                                                    <tr>
                                                        <td><strong><?= $usuario['id_user']; ?></strong></td>
                                                        <td>
                                                            <a href="<?= $painel_adm_ver_usuarios . encodeAll($usuario['id_user']); ?>" 
                                                               class="btn-modern btn-success-modern">
                                                                <i class="bi bi-eye"></i> Detalhes
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="code-preview"><?= $usuario['transacao_id']; ?></span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--success-color); font-weight: 700;">
                                                                R$ <?= number_format($usuario['valor'], 2, ',', '.'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--text-secondary);">
                                                                <?= $usuario['data_cad']; ?> : <?= $usuario['data_hora']; ?>
                                                            </span>
                                                        </td>
                                                        <td><?= $cargo_badge; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='6' class='text-center' style='color: var(--text-secondary); padding: 40px;'>
                                                        <i class='bi bi-inbox' style='font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;'></i>
                                                        <strong>Nenhum saque aprovado encontrado</strong><br>
                                                        <small>Ainda não há saques processados</small>
                                                      </td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table><!--end /table-->
                                </div><!--end /tableresponsive-->
                            </div><!--end card-body-->
                        </div><!-- card -->
                    </div><!-- col -->
                </div><!-- row -->
                
                <?php include 'partials/endbar.php' ?>

            </div>
        </div>
    </div>

    <?php include 'partials/vendorjs.php' ?>

    <script src="assets/js/app.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animação inicial dos cards
            const cards = document.querySelectorAll('.stat-card, .card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animação dos contadores - CORRIGIDA
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const originalText = stat.textContent;
                
                if (originalText.includes('R$')) {
                    // Para valores monetários, extrair valor numérico corretamente
                    const cleanValue = originalText.replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
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
        });

        // Animação de contador - CORRIGIDA
        function animateCounter(element, target, originalText) {
            let current = 0;
            const isPrice = originalText.includes('R$');
            
            // Para valores monetários, extrair o valor numérico corretamente
            if (isPrice) {
                // Remove R$, espaços e converte vírgula para ponto
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
    </script>

</body>
<!--end body-->

</html>