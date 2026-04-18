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
    <?php $title = "Depósitos Pendentes";
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
            --danger-accent: #ef4444;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
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
        .deposits-header {
            background: linear-gradient(135deg, var(--warning-accent), #d97706);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .deposits-header::before {
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

        .deposits-header h4 {
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

        .deposits-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .deposits-stats {
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
            background: linear-gradient(90deg, var(--success-color), #059669);
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
            background: linear-gradient(135deg, var(--success-color), #059669);
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

        /* Action Tips */
        .action-tips {
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
            content: '⚡';
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
            border-radius: 20px;
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

        /* Custom Checkbox */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent-color);
            cursor: pointer;
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

        /* Action Buttons - Melhorado */
        .btn-warning {
            background: var(--warning-accent) !important;
            border: none !important;
            color: white !important;
            padding: 12px 24px !important;
            border-radius: 10px !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-warning:hover {
            background: #d97706 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:disabled {
            background: var(--border-color) !important;
            color: var(--text-secondary) !important;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.6;
        }

        .selection-info {
            color: var(--text-secondary) !important;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .selection-info::before {
            content: "ℹ️";
            font-size: 16px;
        }

        /* Toast Styling */
        .toast {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 12px !important;
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

        /* Loading Animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .loading {
            animation: spin 1s linear infinite;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: yellow !important;
            border-radius: 2px;
            margin-right: 12px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }
            
            .deposits-header {
                padding: 24px;
            }
            
            .deposits-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .deposits-stats {
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

        <div class="toast-container position-absolute p-3 top-0 end-0" id="toastPlacement"
            data-original-class="toast-container position-absolute p-3">
            <!-- Os toasts serão inseridos dinamicamente aqui -->
        </div>

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">

                <?php
                global $mysqli;
                // Buscar estatísticas dos depósitos
                $query_stats = "SELECT 
                    COUNT(*) as total_pending,
                    SUM(valor) as total_value
                    FROM transacoes 
                    WHERE status = 'processamento'";
                $result_stats = mysqli_query($mysqli, $query_stats);
                $stats = mysqli_fetch_assoc($result_stats);

                $query_total = "SELECT COUNT(*) as total_deposits FROM transacoes";
                $result_total = mysqli_query($mysqli, $query_total);
                $total_stats = mysqli_fetch_assoc($result_total);
                ?>

                <!-- Header -->
                <div class="deposits-header">
                    <h4>
                        <i class="bi bi-clock-history"></i>
                        Depósitos Pendentes
                    </h4>
                    <p>Gerencie e processe os depósitos que aguardam confirmação</p>
                </div>

                <!-- Stats Overview -->
                <div class="deposits-stats">
                    <div class="stat-card pending">
                        <div class="stat-icon pending">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stat-value"><?= $stats['total_pending'] ?? 0 ?></div>
                        <div class="stat-label">Depósitos Pendentes</div>
                    </div>
                    <div class="stat-card total">
                        <div class="stat-icon total">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div class="stat-value"><?= $total_stats['total_deposits'] ?? 0 ?></div>
                        <div class="stat-label">Total de Depósitos</div>
                    </div>
                    <div class="stat-card value">
                        <div class="stat-icon value">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($stats['total_value'] ?? 0, 2, ',', '.') ?></div>
                        <div class="stat-label">Valor Pendente</div>
                    </div>
                </div>

                <!-- Action Tips -->
                <div class="action-tips">
                    <div class="tips-title">
                        <i class="bi bi-lightbulb"></i>
                        Ações Rápidas
                    </div>
                    <ul class="tips-list">
                        <li>Selecione os depósitos usando as caixas de seleção</li>
                        <li>Use "Marcar como Expirado" para processar múltiplos depósitos</li>
                        <li>Verifique sempre os detalhes antes de processar</li>
                    </ul>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col" style="display: flex;align-content: center;align-items: center;">
                                        <div class="tag"></div>
                                        <h4 class="card-title">Depósitos Pendentes</h4>
                                    </div><!--end col-->
                                </div> <!--end row-->
                            </div><!--end card-header-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th>
                                                <!-- Checkbox para selecionar todos -->
                                                <th>Id</th>
                                                <th>Transação ID</th>
                                                <th>Valor</th>
                                                <th>Data/Hora</th>
                                                <th>Código PIX</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Consultar os dados da tabela usuarios
                                            $query_usuarios = "SELECT * FROM transacoes WHERE status = 'processamento' ORDER BY id DESC";
                                            $result_usuarios = mysqli_query($mysqli, $query_usuarios);

                                            if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
                                                while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
                                                    $cargo_badge = "<span class='badge bg-danger'>Pendente</span>";
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="ids[]" value="<?= $usuario['id']; ?>">
                                                            <!-- Checkbox para cada registro -->
                                                        </td>
                                                        <td><?= $usuario['usuario']; ?></td>
                                                        <td><span class="code-preview"><?= $usuario['transacao_id']; ?></span></td>
                                                        <td style="color: var(--success-color); font-weight: 700;">R$ <?= number_format($usuario['valor'], 2, ',', '.'); ?></td>
                                                        <td style="color: var(--text-secondary);"><?= $usuario['data_hora']; ?></td>
                                                        <td>
                                                            <span class="code-preview"><?= substr($usuario['code'], 0, 40); ?>...</span>
                                                        </td>
                                                        <td><?= $cargo_badge; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center' style='color: var(--text-secondary); padding: 40px;'>Sem dados disponíveis!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <div class="mt-3" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border-top: 2px solid var(--border-color);">
                                        <button type="button" id="mark-expired" class="btn btn-warning" style="margin: 0;">
                                            <i class="bi bi-x-circle"></i>
                                            Marcar como Expirado
                                        </button>
                                        <span class="selection-info" id="selection-count" style="margin: 0;">Nenhum item selecionado</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <?php include 'partials/endbar.php' ?>

            </div>
        </div>
    </div>

    <?php include 'partials/vendorjs.php' ?>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/pages/toast.init.js"></script>
    
    <script>
        // Variáveis globais
        let selectionCount = 0;

        // Atualizar contador de seleção
        function updateSelectionCount() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
            selectionCount = checkboxes.length;
            const selectionInfo = document.getElementById('selection-count');
            const markExpiredBtn = document.getElementById('mark-expired');
            
            if (selectionCount === 0) {
                selectionInfo.textContent = 'Nenhum item selecionado';
                markExpiredBtn.disabled = true;
            } else {
                selectionInfo.textContent = `${selectionCount} item${selectionCount > 1 ? 's' : ''} selecionado${selectionCount > 1 ? 's' : ''}`;
                markExpiredBtn.disabled = false;
            }
        }

        // Configurar evento "selecionar todos"
        document.getElementById('select-all').onclick = function () {
            var checkboxes = document.querySelectorAll('input[name="ids[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
            updateSelectionCount();
        }

        // Configurar eventos individuais dos checkboxes
        document.querySelectorAll('input[name="ids[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', updateSelectionCount);
        });

        // Configurar botão "Marcar como Expirado" - SEM ALERTS/TOASTS
        document.getElementById('mark-expired').onclick = function () {
            var checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
            var ids = [];
            for (var checkbox of checkboxes) {
                ids.push(checkbox.value);
            }

            if (ids.length > 0) {
                // Mostrar loading no botão (sem confirmação)
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="bi bi-arrow-clockwise loading"></i> Processando...';
                this.disabled = true;

                // Fazer a requisição Ajax
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/att_status_depositos.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Recarregar página silenciosamente após sucesso
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            } else {
                                // Restaurar botão em caso de erro (sem toast)
                                document.getElementById('mark-expired').innerHTML = originalContent;
                                document.getElementById('mark-expired').disabled = false;
                                console.log('Erro ao atualizar status:', response);
                            }
                        } catch (e) {
                            // Restaurar botão em caso de erro (sem toast)
                            document.getElementById('mark-expired').innerHTML = originalContent;
                            document.getElementById('mark-expired').disabled = false;
                            console.log('Erro na resposta:', e);
                        }
                    } else if (xhr.readyState === 4) {
                        // Restaurar botão em caso de erro de conexão (sem toast)
                        document.getElementById('mark-expired').innerHTML = originalContent;
                        document.getElementById('mark-expired').disabled = false;
                        console.log('Erro de conexão:', xhr.status);
                    }
                };

                // Enviar IDs selecionados
                xhr.send("ids=" + JSON.stringify(ids));
            }
            // Remover else que mostrava toast de warning
        };

        // Função para exibir toast com o estilo fornecido
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

            // Inicializar o Toast do Bootstrap
            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function () {
                bootstrapToast.hide();  // Esconder o toast após 3 segundos
                setTimeout(() => toast.remove(), 500); // Remove o toast após ele sumir
            }, 3000);
        }

        // Animações iniciais
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar contador
            updateSelectionCount();
            
            // Animação dos cards
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