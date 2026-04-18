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

# Função para buscar os dados atuais da tabela config
function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

# Função para atualizar os dados da tabela config
function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        qntsbaus = ?, 
        niveisbau = ?, 
        pessoasbau = ?
        WHERE id = 1");

    $qry->bind_param(
        "dsd",
        $data['qntsbaus'],
        $data['niveisbau'],
        $data['pessoasbau']
    );
    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados
$toastType = null; // Variável para definir o tipo de Toast
$toastMessage = ''; // Variável para definir a mensagem do Toast

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'qntsbaus' => floatval($_POST['qntsbaus']),
        'niveisbau' => $_POST['niveisbau'],
        'pessoasbau' => floatval($_POST['pessoasbau']),
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações de Baús atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

# Buscar os dados atuais
$config = get_afiliados_config();

# Verificar modo demo (assumindo que existe uma variável global)
$modo_plataforma = $modo_plataforma ?? 1; // Default para modo ativo
?>

<head>
    <?php $title = "Configurações de Baús";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #f59e0b;
            --secondary-accent: #10b981;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --treasure-gold: #fbbf24;
            --treasure-bronze: #d97706;
        }

        body {
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .treasure-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .treasure-header {
            background: linear-gradient(135deg, var(--accent-color), var(--treasure-bronze));
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .treasure-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: sparkle 3s linear infinite;
        }

        @keyframes sparkle {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-50px) translateY(-50px); }
        }

        .treasure-header h4 {
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

        .treasure-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .treasure-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .treasure-stat {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .treasure-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(245, 158, 11, 0.3);
            border-color: var(--accent-color);
        }

        .treasure-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--treasure-bronze));
        }

        .treasure-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent-color), var(--treasure-bronze));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 28px;
            position: relative;
        }

        .treasure-icon::after {
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

        .treasure-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .treasure-label {
            font-size: 14px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Configuration Section */
        .config-container {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .config-header {
            background: linear-gradient(135deg, var(--secondary-bg), #3f3f3f);
            padding: 24px;
            border-bottom: 2px solid var(--border-color);
        }

        .config-title {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .config-subtitle {
            margin: 8px 0 0 0;
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .config-content {
            padding: 32px;
        }

        /* Form Configuration */
        .config-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .config-card {
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .config-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.2);
        }

        .config-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--treasure-bronze));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .config-card:hover::before {
            opacity: 1;
        }

        .config-card:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .config-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .config-label i {
            color: var(--accent-color);
            font-size: 20px;
        }

        .config-input {
            width: 100%;
            background: var(--secondary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .config-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .config-input::placeholder {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .config-help {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 8px;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Special Input Types */
        .treasure-amount-input {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border-color: rgba(245, 158, 11, 0.3);
        }

        .treasure-values-input {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border-color: rgba(16, 185, 129, 0.3);
            min-height: 120px;
            resize: vertical;
        }

        .people-input {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
            border-color: rgba(59, 130, 246, 0.3);
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

        /* Save Button */
        .save-section {
            text-align: center;
            padding: 24px;
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
        }

        .save-btn {
            background: linear-gradient(135deg, var(--accent-color), var(--treasure-bronze));
            color: white;
            border: none;
            padding: 18px 48px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .save-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(245, 158, 11, 0.4);
        }

        .save-btn:disabled {
            background: var(--border-color);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .save-btn::before {
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

        .save-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        /* Treasure Info Cards */
        .treasure-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-card {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 20px;
        }

        .info-title {
            color: var(--info-color);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .info-list li::before {
            content: '🎁';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Animations */
        @keyframes treasureGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); }
            50% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.6); }
        }

        .treasure-stat:nth-child(1) { animation: treasureGlow 3s ease-in-out infinite; animation-delay: 0s; }
        .treasure-stat:nth-child(2) { animation: treasureGlow 3s ease-in-out infinite; animation-delay: 1s; }
        .treasure-stat:nth-child(3) { animation: treasureGlow 3s ease-in-out infinite; animation-delay: 2s; }

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

        .config-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .config-card:nth-child(odd) { animation-delay: 0.1s; }
        .config-card:nth-child(even) { animation-delay: 0.2s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .treasure-layout {
                padding: 16px;
            }
            
            .treasure-header {
                padding: 24px;
            }
            
            .config-content {
                padding: 24px;
            }
            
            .config-grid {
                grid-template-columns: 1fr;
            }
            
            .treasure-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .treasure-stats {
                grid-template-columns: 1fr;
            }
            
            .save-btn {
                width: 100%;
                padding: 16px 24px;
            }
        }

        /* Form Validation States */
        .config-card.success {
            border-color: var(--success-color) !important;
        }

        .config-card.error {
            border-color: var(--danger-color) !important;
            background: rgba(239, 68, 68, 0.05);
        }

        .config-card.error::after {
            content: '⚠️';
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 18px;
        }

        .config-card.success::after {
            content: '✅';
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 18px;
        }

        /* Toast Improvements */
        .toast-container .toast {
            background: var(--secondary-bg);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }

        .toast-header {
            background: var(--secondary-bg);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .toast-body {
            background: var(--secondary-bg);
            color: var(--text-primary);
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
        <div class="page-content treasure-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="treasure-header">
                    <h4>
                        <i class="bi bi-gift"></i>
                        Sistema de Baús de Recompensas
                    </h4>
                    <p>Configure quantidades, valores e critérios dos baús da plataforma</p>
                </div>

                <!-- Stats Overview -->
                <div class="treasure-stats">
                    <div class="treasure-stat">
                        <div class="treasure-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div class="treasure-value"><?= number_format($config['qntsbaus'], 0) ?></div>
                        <div class="treasure-label">Quantidade de Baús</div>
                    </div>
                    <div class="treasure-stat">
                        <div class="treasure-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="treasure-value"><?= count(explode(',', $config['niveisbau'])) ?></div>
                        <div class="treasure-label">Níveis Configurados</div>
                    </div>
                    <div class="treasure-stat">
                        <div class="treasure-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="treasure-value"><?= number_format($config['pessoasbau'], 0) ?></div>
                        <div class="treasure-label">Limite de Pessoas</div>
                    </div>
                </div>

                <!-- Information Cards -->
                <div class="treasure-info">
                    <div class="info-card">
                        <div class="info-title">
                            <i class="bi bi-info-circle"></i>
                            Como Funciona o Sistema
                        </div>
                        <ul class="info-list">
                            <li>Os baús são distribuídos periodicamente aos jogadores</li>
                            <li>Cada baú contém recompensas baseadas nos valores configurados</li>
                            <li>O limite de pessoas controla quantos usuários podem participar</li>
                            <li>Sistema automático de distribuição e coleta</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <div class="info-title">
                            <i class="bi bi-gear"></i>
                            Configurações Importantes
                        </div>
                        <ul class="info-list">
                            <li>Quantidade: Total de baús disponíveis no sistema</li>
                            <li>Valores: Lista de recompensas separadas por vírgula</li>
                            <li>Pessoas: Máximo de usuários que podem participar</li>
                            <li>Alterações são aplicadas imediatamente</li>
                        </ul>
                    </div>
                </div>

                <!-- Demo Mode Alert -->
                <?php if ($modo_plataforma == 0): ?>
                    <div class="demo-alert">
                        <div class="demo-alert-icon">🚫</div>
                        <div class="demo-alert-title">Modo Demonstração Ativo</div>
                        <div class="demo-alert-text">
                            As configurações não podem ser alteradas no modo demo. 
                            Ative o modo produção para editar as configurações dos baús.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Configuration Form -->
                <div class="config-container">
                    <div class="config-header">
                        <div class="config-title">
                            <i class="bi bi-sliders"></i>
                            Configurações dos Baús
                        </div>
                        <div class="config-subtitle">
                            Defina as regras e valores do sistema de recompensas
                        </div>
                    </div>
                    <div class="config-content">
                        <form method="POST" action="" id="treasureConfigForm">
                            <div class="config-grid">
                                
                                <!-- Quantidade de Baús -->
                                <div class="config-card">
                                    <label class="config-label">
                                        <i class="bi bi-gift"></i>
                                        Quantidade de Baús
                                    </label>
                                    <input type="number" 
                                           name="qntsbaus" 
                                           class="config-input treasure-amount-input" 
                                           value="<?= $config['qntsbaus'] ?>" 
                                           required 
                                           min="1" 
                                           step="1"
                                           placeholder="Ex: 100">
                                    <div class="config-help">
                                        Total de baús disponíveis no sistema. Quando esgotados, novos baús são gerados automaticamente.
                                    </div>
                                </div>

                                <!-- Valores dos Baús -->
                                <div class="config-card">
                                    <label class="config-label">
                                        <i class="bi bi-cash-stack"></i>
                                        Valores dos Baús
                                    </label>
                                    <textarea name="niveisbau" 
                                              class="config-input treasure-values-input" 
                                              required 
                                              placeholder="Ex: 5,10,25,50,100"><?= $config['niveisbau'] ?></textarea>
                                    <div class="config-help">
                                        Lista de valores possíveis para os baús, separados por vírgula. 
                                        O sistema escolherá aleatoriamente entre estes valores.
                                    </div>
                                </div>

                                <!-- Limite de Pessoas -->
                                <div class="config-card">
                                    <label class="config-label">
                                        <i class="bi bi-people"></i>
                                        Limite de Pessoas por Rodada
                                    </label>
                                    <input type="number" 
                                           name="pessoasbau" 
                                           class="config-input people-input" 
                                           value="<?= $config['pessoasbau'] ?>" 
                                           required 
                                           min="1" 
                                           step="1"
                                           placeholder="Ex: 50">
                                    <div class="config-help">
                                        Número máximo de pessoas que podem participar de cada rodada de distribuição de baús.
                                    </div>
                                </div>

                            </div>

                            <!-- Save Section -->
                            <div class="save-section">
                                <?php if ($modo_plataforma == 0): ?>
                                    <button type="button" class="save-btn" disabled>
                                        <i class="bi bi-shield-x"></i>
                                        Salvar Configurações (Modo Demo Ativo)
                                    </button>
                                    <div style="margin-top: 12px; color: var(--text-secondary); font-size: 14px;">
                                        Desative o modo demonstração para editar as configurações
                                    </div>
                                <?php else: ?>
                                    <button type="submit" class="save-btn" id="saveBtn">
                                        <i class="bi bi-check-circle"></i>
                                        Salvar Configurações
                                    </button>
                                    <div style="margin-top: 12px; color: var(--text-secondary); font-size: 14px;">
                                        As alterações serão aplicadas imediatamente no sistema
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- container -->
        </div><!-- page content -->
    </div><!-- page-wrapper -->

    <!-- Toast container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Javascript -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        // Função de Toast moderna
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            
            var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            var icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';
            
            toast.className = `toast align-items-center ${bgClass} text-white border-0 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header ${bgClass} text-white border-0">
                    <i class="bi ${icon} me-2"></i>
                    <strong class="me-auto">Sistema de Baús</strong>
                    <small>Agora</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            toastPlacement.appendChild(toast);

            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function () {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Função para mostrar loading no botão
        function showButtonLoading(button, text = 'Salvando...') {
            const originalText = button.innerHTML;
            button.innerHTML = `<i class="bi bi-arrow-clockwise me-2" style="animation: spin 1s linear infinite;"></i>${text}`;
            button.disabled = true;
            
            return function() {
                button.innerHTML = originalText;
                button.disabled = false;
            };
        }

        // Validação do formulário
        function setupFormValidation() {
            const form = document.getElementById('treasureConfigForm');
            const inputs = form.querySelectorAll('input, textarea');
            
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
                    showToast('error', 'Por favor, corrija os erros antes de salvar.');
                    return;
                }
                
                // Mostrar loading se não estiver em modo demo
                if (!this.querySelector('button[disabled]')) {
                    const saveBtn = document.getElementById('saveBtn');
                    showButtonLoading(saveBtn);
                }
            });
        }

        // Validação individual dos campos
        function validateInput(input) {
            const card = input.closest('.config-card');
            const value = input.value.trim();
            
            // Remove classes anteriores
            card.classList.remove('error', 'success');
            
            let isValid = true;
            
            // Validação específica por tipo de campo
            if (input.name === 'qntsbaus') {
                const num = parseInt(value);
                if (isNaN(num) || num < 1) {
                    isValid = false;
                }
            } else if (input.name === 'niveisbau') {
                // Validar formato de valores separados por vírgula
                if (!value || !isValidValuesList(value)) {
                    isValid = false;
                }
            } else if (input.name === 'pessoasbau') {
                const num = parseInt(value);
                if (isNaN(num) || num < 1) {
                    isValid = false;
                }
            }
            
            // Aplicar classes de feedback
            if (!isValid) {
                card.classList.add('error');
                input.style.borderColor = '#ef4444';
            } else {
                card.classList.add('success');
                input.style.borderColor = '#f59e0b';
            }
            
            return isValid;
        }

        // Validar lista de valores dos baús
        function isValidValuesList(valueString) {
            const values = valueString.split(',');
            
            // Deve ter pelo menos um valor
            if (values.length === 0) return false;
            
            // Cada valor deve ser um número positivo
            for (let value of values) {
                const num = parseFloat(value.trim());
                if (isNaN(num) || num <= 0) {
                    return false;
                }
            }
            
            return true;
        }

        // Efeitos visuais e animações
        function setupVisualEffects() {
            // Animação dos contadores
            const treasureValues = document.querySelectorAll('.treasure-value');
            treasureValues.forEach(element => {
                const value = parseInt(element.textContent.replace(/[^\d]/g, ''));
                if (!isNaN(value)) {
                    animateCounter(element, value);
                }
            });

            // Hover effects nos cards
            const configCards = document.querySelectorAll('.config-card');
            configCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efeito sparkle no header
            addSparkleEffect();
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
                element.textContent = Math.floor(current).toLocaleString('pt-BR');
            }, 60);
        }

        // Efeito sparkle no header
        function addSparkleEffect() {
            const header = document.querySelector('.treasure-header');
            
            function createSparkle() {
                const sparkle = document.createElement('div');
                sparkle.innerHTML = '✨';
                sparkle.style.position = 'absolute';
                sparkle.style.left = Math.random() * 100 + '%';
                sparkle.style.top = Math.random() * 100 + '%';
                sparkle.style.fontSize = '12px';
                sparkle.style.pointerEvents = 'none';
                sparkle.style.zIndex = '1';
                sparkle.style.animation = 'sparkleFloat 3s ease-out forwards';
                
                header.appendChild(sparkle);
                
                setTimeout(() => {
                    sparkle.remove();
                }, 3000);
            }
            
            // Criar sparkles periodicamente
            setInterval(createSparkle, 1000);
        }

        // Preview de valores dos baús
        function setupValuesPreview() {
            const valuesInput = document.querySelector('[name="niveisbau"]');
            const card = valuesInput.closest('.config-card');
            
            function updatePreview() {
                // Remove preview anterior
                const existingPreview = card.querySelector('.values-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                const values = valuesInput.value.split(',');
                if (values.length > 0 && isValidValuesList(valuesInput.value)) {
                    const preview = document.createElement('div');
                    preview.className = 'values-preview';
                    preview.style.cssText = `
                        margin-top: 12px;
                        padding: 12px;
                        background: rgba(245, 158, 11, 0.1);
                        border-radius: 8px;
                        border: 1px solid rgba(245, 158, 11, 0.2);
                    `;
                    
                    const previewValues = values.map(v => `R$ ${parseFloat(v.trim()).toFixed(2)}`).join(' • ');
                    preview.innerHTML = `
                        <div style="font-size: 12px; color: #f59e0b; font-weight: 600; margin-bottom: 4px;">
                            PREVIEW DOS VALORES:
                        </div>
                        <div style="font-size: 14px; color: var(--text-primary);">
                            ${previewValues}
                        </div>
                    `;
                    
                    card.appendChild(preview);
                }
            }
            
            valuesInput.addEventListener('input', updatePreview);
            updatePreview(); // Inicial
        }

        // Dicas contextuais
        function setupContextualTips() {
            const tips = {
                'qntsbaus': [
                    'Recomendado: Entre 50-200 baús para manter o interesse',
                    'Muito poucos baús podem frustrar usuários',
                    'Muitos baús podem impactar a economia do jogo'
                ],
                'niveisbau': [
                    'Use valores progressivos: 5,10,25,50,100',
                    'Balanceie valores baixos e altos',
                    'Máximo recomendado: 10 valores diferentes'
                ],
                'pessoasbau': [
                    'Considere o número total de usuários ativos',
                    'Muito restritivo pode gerar frustração',
                    'Muito liberal pode impactar a economia'
                ]
            };
            
            Object.keys(tips).forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                const card = input.closest('.config-card');
                
                input.addEventListener('focus', function() {
                    showTips(card, tips[fieldName]);
                });
                
                input.addEventListener('blur', function() {
                    hideTips(card);
                });
            });
        }

        function showTips(card, tipsList) {
            hideTips(card); // Remove tips anteriores
            
            const tipsContainer = document.createElement('div');
            tipsContainer.className = 'contextual-tips';
            tipsContainer.style.cssText = `
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                margin-top: 8px;
                background: var(--secondary-bg);
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 12px;
                z-index: 10;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            `;
            
            const tipsHTML = tipsList.map(tip => 
                `<div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 4px;">💡 ${tip}</div>`
            ).join('');
            
            tipsContainer.innerHTML = tipsHTML;
            card.style.position = 'relative';
            card.appendChild(tipsContainer);
        }

        function hideTips(card) {
            const existingTips = card.querySelector('.contextual-tips');
            if (existingTips) {
                existingTips.remove();
            }
        }

        // Sistema de auto-save
        function setupAutoSave() {
            const form = document.getElementById('treasureConfigForm');
            const inputs = form.querySelectorAll('input, textarea');
            
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    const drafts = JSON.parse(localStorage.getItem('treasureConfigDrafts') || '{}');
                    drafts[this.name] = this.value;
                    localStorage.setItem('treasureConfigDrafts', JSON.stringify(drafts));
                    
                    // Mostrar indicador de rascunho salvo
                    showDraftSaved();
                });
            });
            
            // Restaurar drafts
            const drafts = JSON.parse(localStorage.getItem('treasureConfigDrafts') || '{}');
            Object.keys(drafts).forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input && drafts[fieldName] && input.value !== drafts[fieldName]) {
                    input.style.background = 'rgba(245, 158, 11, 0.1)';
                    input.setAttribute('title', `Rascunho: ${drafts[fieldName]}`);
                }
            });
        }

        function showDraftSaved() {
            // Remove indicador anterior
            const existing = document.querySelector('.draft-indicator');
            if (existing) existing.remove();
            
            const indicator = document.createElement('div');
            indicator.className = 'draft-indicator';
            indicator.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: rgba(245, 158, 11, 0.9);
                color: white;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                z-index: 1000;
                transition: opacity 0.3s ease;
            `;
            indicator.textContent = '💾 Rascunho salvo';
            
            document.body.appendChild(indicator);
            
            setTimeout(() => {
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 2000);
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            setupFormValidation();
            setupVisualEffects();
            setupValuesPreview();
            setupContextualTips();
            setupAutoSave();
            
            // Limpar drafts após salvar com sucesso
            <?php if ($toastType === 'success'): ?>
            localStorage.removeItem('treasureConfigDrafts');
            <?php endif; ?>
        });

        // CSS adicional para animações e efeitos
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            @keyframes sparkleFloat {
                0% {
                    opacity: 0;
                    transform: translateY(20px) scale(0);
                }
                50% {
                    opacity: 1;
                    transform: translateY(-10px) scale(1);
                }
                100% {
                    opacity: 0;
                    transform: translateY(-30px) scale(0.5);
                }
            }
            
            @keyframes treasurePulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }
            
            .treasure-icon:hover {
                animation: treasurePulse 0.6s ease-in-out;
            }
            
            .config-card.error {
                border-color: #ef4444 !important;
                background: rgba(239, 68, 68, 0.05);
            }
            
            .config-card.success {
                border-color: #f59e0b !important;
            }
            
            .save-btn:not(:disabled):active {
                transform: translateY(0) scale(0.98);
            }
            
            /* Scrollbar customizado para textarea */
            .treasure-values-input::-webkit-scrollbar {
                width: 8px;
            }
            
            .treasure-values-input::-webkit-scrollbar-track {
                background: var(--border-color);
                border-radius: 4px;
            }
            
            .treasure-values-input::-webkit-scrollbar-thumb {
                background: var(--accent-color);
                border-radius: 4px;
            }
            
            .treasure-values-input::-webkit-scrollbar-thumb:hover {
                background: var(--treasure-bronze);
            }
        `;
        document.head.appendChild(additionalStyles);
    </script>

    <!-- Exibir o Toast baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            });
        </script>
    <?php endif; ?>

</body>
</html>