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
    <?php $title = "Saques Pendentes";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --warning-accent: #f59e0b;
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
            background: linear-gradient(135deg, var(--warning-accent), #d97706);
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

        .stat-card.pending::before {
            background: linear-gradient(90deg, var(--warning-accent), #d97706);
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

        .stat-icon.pending {
            background: linear-gradient(135deg, var(--warning-accent), #d97706);
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
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .tips-title {
            color: var(--warning-accent) !important;
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
            content: '⏳';
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
            background: rgba(245, 158, 11, 0.05) !important;
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

        .bg-danger {
            background: rgba(245, 158, 11, 0.2) !important;
            color: var(--warning-accent) !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
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

        /* Copy Button */
        .btn-clipboard {
            background: var(--accent-color) !important;
            border: none !important;
            color: white !important;
            padding: 6px 10px !important;
            border-radius: 6px !important;
            font-size: 12px !important;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-clipboard:hover {
            background: #7c3aed !important;
            color: white !important;
            transform: translateY(-1px);
        }

        /* Action Button */
        .btn-edit-saque {
            background: var(--warning-accent) !important;
            border: none !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-edit-saque:hover {
            background: #d97706 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        /* Modal Styling */
        .modal-content {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 16px !important;
            color: var(--text-primary) !important;
        }

        .modal-header {
            background: var(--secondary-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .modal-title {
            color: var(--text-primary) !important;
        }

        .btn-close {
            filter: invert(1);
        }

        .modal-body {
            background: var(--secondary-bg) !important;
            color: var(--text-primary) !important;
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .form-control {
            background: var(--primary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .form-control:focus {
            background: var(--primary-bg) !important;
            border-color: var(--accent-color) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        .modal-footer {
            background: var(--secondary-bg) !important;
            border-top: 2px solid var(--border-color) !important;
        }

        .btn-success {
            background: var(--success-color) !important;
            border: none !important;
        }

        .btn-success:hover {
            background: #059669 !important;
        }

        .btn-danger {
            background: var(--danger-color) !important;
            border: none !important;
        }

        .btn-danger:hover {
            background: #dc2626 !important;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: yellow !important;
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
                // Buscar estatísticas dos saques pendentes
                $query_stats = "SELECT 
                    COUNT(*) as total_pending,
                    SUM(valor) as total_value
                    FROM solicitacao_saques 
                    WHERE status = '0' AND tipo_saque = '0'";
                $result_stats = mysqli_query($mysqli, $query_stats);
                $stats = mysqli_fetch_assoc($result_stats);

                $query_total = "SELECT COUNT(*) as total_withdrawals FROM solicitacao_saques WHERE tipo_saque = '0'";
                $result_total = mysqli_query($mysqli, $query_total);
                $total_stats = mysqli_fetch_assoc($result_total);
                ?>

                <!-- Header -->
                <div class="withdrawals-header">
                    <h4>
                        <i class="bi bi-hourglass-split"></i>
                        Saques Pendentes
                    </h4>
                    <p>Analise e processe as solicitações de saque PIX pendentes</p>
                </div>

                <!-- Stats Overview -->
                <div class="withdrawals-stats">
                    <div class="stat-card pending">
                        <div class="stat-icon pending">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="stat-value"><?= $stats['total_pending'] ?? 0 ?></div>
                        <div class="stat-label">Saques Pendentes</div>
                    </div>
                    <div class="stat-card total">
                        <div class="stat-icon total">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-value"><?= $total_stats['total_withdrawals'] ?? 0 ?></div>
                        <div class="stat-label">Total de Saques</div>
                    </div>
                    <div class="stat-card value">
                        <div class="stat-icon value">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($stats['total_value'] ?? 0, 2, ',', '.') ?></div>
                        <div class="stat-label">Valor Pendente</div>
                    </div>
                </div>

                <!-- Info Tips -->
                <div class="info-tips">
                    <div class="tips-title">
                        <i class="bi bi-exclamation-circle"></i>
                        Ações de Aprovação
                    </div>
                    <ul class="tips-list">
                        <li>Revise cuidadosamente os dados antes de aprovar ou recusar</li>
                        <li>Chaves PIX são copiáveis para verificação externa</li>
                        <li>Aprovação processa o pagamento automaticamente</li>
                        <li>Recusa notifica o usuário sobre o motivo</li>
                    </ul>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col" style="display: flex;align-content: center;align-items: center;">
                                        <div class="tag"></div>
                                        <h4 class="card-title">Lista de Saques Pendentes</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Id</th>
                                                <th>Transação ID</th>
                                                <th>Valor</th>
                                                <th>Data/Hora</th>
                                                <th>Chave Pix</th>
                                                <th>Usuario</th>
                                                <th>Status</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Consultar os dados da tabela usuarios - filtrar apenas saques PIX (tipo_saque = '0')
                                            $query_usuarios = "SELECT * FROM solicitacao_saques WHERE status = '0' AND tipo_saque = '0' ORDER BY id DESC";
                                            $result_usuarios = mysqli_query($mysqli, $query_usuarios);

                                            if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
                                                while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
                                                    $cargo_badge = "<span class='badge bg-danger'><i class='bi bi-clock me-1'></i>Em Análise</span>";
                                                    
                                                    // ID do usuário para consulta
                                                    $id_user = $usuario['id_user'];
                                                    
                                                    // Consulta para buscar informações do usuário
                                                    $query_puxarmobile = "SELECT mobile FROM usuarios WHERE id = ?";
                                                    $stmt = $mysqli->prepare($query_puxarmobile);

                                                    if ($stmt) {
                                                        $stmt->bind_param("i", $id_user);
                                                        $stmt->execute();
                                                        $result_puxarmobile = $stmt->get_result();

                                                        // Verifica se houve resultado
                                                        if ($result_puxarmobile && $result_puxarmobile->num_rows > 0) {
                                                            $user_data = $result_puxarmobile->fetch_assoc();
                                                            $mobile = $user_data['mobile'];
                                                        } else {
                                                            $mobile = "N/A"; // Caso o usuário não seja encontrado
                                                        }
                                                        
                                                        $stmt->close();
                                                    } else {
                                                        echo "Erro na preparação da consulta: " . $mysqli->error;
                                                    }
                                                    
                                                    $chaveatt = localizarchavepix($usuario['tipo']);
                                                    ?>
                                                    <tr>
                                                        <td><strong><?= $usuario['id_user']; ?></strong></td>
                                                        <td>
                                                            <span class="code-preview"><?= $usuario['transacao_id']; ?></span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--warning-accent); font-weight: 700;">
                                                                R$ <?= number_format($usuario['valor'], 2, ',', '.'); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span style="color: var(--text-secondary);">
                                                                <?= $usuario['data_cad']; ?> : <?= $usuario['data_hora']; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                                <span class="code-preview"><?= $chaveatt ?></span>
                                                                <button type="button" class="btn btn-primary btn-clipboard"
                                                                    data-clipboard-text="<?= $chaveatt; ?>">
                                                                    <i class="far fa-copy"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td><?= $mobile; ?></td>
                                                        <td><?= $cargo_badge; ?></td>
                                                        <td>
                                                            <button class="btn btn-warning btn-edit-saque"
                                                                data-id="<?= $usuario['id']; ?>"
                                                                data-transacao="<?= $usuario['transacao_id']; ?>"
                                                                data-valor="<?= number_format($usuario['valor'], 2, ',', '.'); ?>"
                                                                data-chave="<?= $chaveatt; ?>"
                                                                data-usuario="<?= $mobile; ?>"
                                                                data-status="<?= $usuario['status']; ?>">
                                                                <i class="bi bi-pencil"></i> Editar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='8' class='text-center' style='color: var(--text-secondary); padding: 40px;'>
                                                        <i class='bi bi-check-circle' style='font-size: 48px; opacity: 0.3; display: block; margin-bottom: 12px;'></i>
                                                        <strong>Nenhum saque pendente</strong><br>
                                                        <small>Todos os saques foram processados</small>
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
            </div><!-- container -->
        </div><!-- page content -->
    </div><!-- page-wrapper -->

    <!-- Modal Editar Saque -->
    <div class="modal fade" id="modalEditarSaque" tabindex="-1" aria-labelledby="modalEditarSaqueLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarSaqueLabel">
                        <i class="bi bi-pencil-square"></i> Editar Saque
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarSaque">
                        <input type="hidden" id="saqueId" name="saqueId">
                        <input type="hidden" id="_csrf" name="_csrf" value="<?= md5(uniqid()) ?>">
                        <div class="mb-3">
                            <label for="transacaoId" class="form-label">
                                <i class="bi bi-hash"></i> Transação ID
                            </label>
                            <input type="text" class="form-control" id="transacaoId" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="valorSaque" class="form-label">
                                <i class="bi bi-currency-dollar"></i> Valor
                            </label>
                            <input type="text" class="form-control" id="valorSaque" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="chavePix" class="form-label">
                                <i class="bi bi-credit-card"></i> Chave PIX
                            </label>
                            <input type="text" class="form-control" id="chavePix" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">
                                <i class="bi bi-person"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="usuario" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAprovarSaque">
                        <i class="bi bi-check-circle"></i> Aprovar
                    </button>
                    <button type="button" class="btn btn-danger" id="btnRecusarSaque">
                        <i class="bi bi-x-circle"></i> Recusar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Adicione um elemento de posicionamento para o Toast -->
    <div id="toastPlacement" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    <script src="assets/libs/clipboard/clipboard.min.js"></script>
    <script src="assets/js/pages/clipboard.init.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configurar clipboard
            const clipboard = new ClipboardJS('.btn-clipboard');
            
            clipboard.on('success', function(e) {
                const button = e.trigger;
                const originalContent = button.innerHTML;
                
                // Feedback visual
                button.innerHTML = '<i class="bi bi-check"></i>';
                button.style.background = 'var(--success-color)';
                
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.style.background = 'var(--accent-color)';
                }, 2000);
                
                e.clearSelection();
            });

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

            // Animação dos contadores
            const statValues = document.querySelectorAll('.stat-value');
            
            statValues.forEach(stat => {
                const originalText = stat.textContent;
            
                // Verifica se é um valor em reais (R$)
                if (originalText.includes('R$')) {
                    // Limpa e converte o valor monetário
                    const cleanValue = originalText
                        .replace('R$', '')
                        .trim()
                        .replace(/\./g, '') // Remove separadores de milhar
                        .replace(',', '.'); // Converte vírgula decimal para ponto
            
                    const value = parseFloat(cleanValue);
            
                    if (!isNaN(value) && value > 0) {
                        animateCounter(stat, value, originalText);
                    }
                } else {
                    // Para valores inteiros sem R$
                    const value = parseInt(originalText.replace(/[^\d]/g, ''), 10);
            
                    if (!isNaN(value) && value > 0) {
                        animateCounter(stat, value, originalText);
                    }
                }
            });

            // Evento para abrir o modal e preencher as informações do saque
            const btnsEdit = document.querySelectorAll('.btn-edit-saque');
            btnsEdit.forEach(btn => {
                btn.addEventListener('click', function () {
                    const saqueId = this.getAttribute('data-id');
                    const transacaoId = this.getAttribute('data-transacao');
                    const valor = this.getAttribute('data-valor');
                    const chavePix = this.getAttribute('data-chave');
                    const usuario = this.getAttribute('data-usuario');

                    // Preenche os campos do modal
                    document.getElementById('saqueId').value = saqueId;
                    document.getElementById('transacaoId').value = transacaoId;
                    document.getElementById('valorSaque').value = valor;
                    document.getElementById('chavePix').value = chavePix;
                    document.getElementById('usuario').value = usuario;

                    // Exibe o modal
                    const modal = new bootstrap.Modal(document.getElementById('modalEditarSaque'));
                    modal.show();
                });
            });

            // Evento para aprovar saque - Usando services-gateway/payment_manual.php
            document.getElementById('btnAprovarSaque').addEventListener('click', function () {
                const saqueId = document.getElementById('saqueId').value;
                const transacaoId = document.getElementById('transacaoId').value;
                const _csrf = document.getElementById('_csrf').value;

                // Envia a requisição para o services-gateway
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `services-gateway/payment_manual.php?id=${transacaoId}`, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log("Status:", xhr.status);
                        console.log("Response:", xhr.responseText);
                        
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                
                                if (response.success === true) {
                                    showToast('success', response.message || 'Saque aprovado com sucesso!');
                                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarSaque'));
                                    modal.hide();
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    showToast('danger', response.message || 'Erro ao aprovar o saque.');
                                }
                            } catch (e) {
                                console.error("JSON Parse Error:", e);
                                console.error("Response Text:", xhr.responseText);
                                showToast('danger', 'Resposta inválida do servidor. Verifique o console para detalhes.');
                            }
                        } else {
                            showToast('danger', `Erro HTTP ${xhr.status}: ${xhr.statusText}`);
                        }
                    }
                };

                xhr.send();
            });

            // Evento para recusar saque
            document.getElementById('btnRecusarSaque').addEventListener('click', function () {
                const saqueId = document.getElementById('saqueId').value;
                const _csrf = document.getElementById('_csrf').value;
                const email_reprovado = document.getElementById('chavePix').value;
                const valor_reprovado = document.getElementById('valorSaque').value;

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/recusar_saque.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                showToast('success', response.message);
                                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarSaque'));
                                modal.hide();
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showToast('danger', response.message);
                            }
                        } catch (e) {
                            showToast('danger', 'Erro ao processar a resposta do servidor.');
                            console.error("Erro ao parsear JSON:", e);
                        }
                    }
                };
                xhr.send(`att-pay=1&_csrf=${_csrf}&id_pay=${saqueId}&email_reprovado=${email_reprovado}&valor_reprovado=${valor_reprovado}`);
            });

            // Função para exibir toast
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

                setTimeout(function () {
                    bootstrapToast.hide();
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }
        });

        // Animação de contador
        function animateCounter(element, target, originalText) {
            let current = 0;
            const isPrice = originalText.includes('R$');
        
            if (isPrice) {
                const cleanValue = originalText
                    .replace('R$', '')
                    .trim()
                    .replace(/\./g, '')
                    .replace(',', '.');
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