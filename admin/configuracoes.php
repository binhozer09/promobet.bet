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
        minsaque = ?, 
        maxsaque = ?, 
        saque_automatico = ?, 
        rollover = ?,
        minimo_deposito = ?,
        mindep = ?, 
        jackpot = ?,
        limite_saque = ?
        WHERE id = 1");

    $qry->bind_param(
        "dddddsdd",
        $data['minsaque'],
        $data['maxsaque'],
        $data['saque_automatico'],
        $data['rollover'],
        $data['minimo_deposito'],
        $data['mindep'],
        $data['jackpot'],
        $data['limite_saque']
    );
    return $qry->execute();
}

# Inicializar variáveis para toast
$toastType = '';
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'minsaque' => floatval($_POST['minsaque']),
        'maxsaque' => floatval($_POST['maxsaque']),
        'saque_automatico' => floatval($_POST['saque_automatico']),
        'mindep' => $_POST['mindep'],
        'minimo_deposito' => floatval($_POST['minimo_deposito']),
        'jackpot' => floatval($_POST['jackpot']),
        'rollover' => floatval($_POST['rollover']),
        'limite_saque' => floatval($_POST['limite_saque']),
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações da plataforma atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

# Buscar os dados atuais
$config = get_afiliados_config();
?>

<head>
    <?php $title = "Configurações da Plataforma";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .config-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 16px;
            background: #303231;
        }
        
        .config-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
            border-color: rgba(13, 110, 253, 0.2);
        }
        
        .config-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 16px;
        }
        
        .icon-withdrawal {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }
        
        .icon-deposit {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
        }
        
        .icon-automatic {
            background: linear-gradient(135deg, #45b7d1 0%, #3742fa 100%);
            color: white;
        }
        
        .icon-jackpot {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
            color: white;
        }
        
        .icon-rollover {
            background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%);
            color: white;
        }
        
        .icon-limit {
            background: linear-gradient(135deg, #26a69a 0%, #00695c 100%);
            color: white;
        }
        
        .config-input {
            border: 2px solid rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .config-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: center;
        }
        
        .save-button {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 16px;
            padding: 16px 48px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(86, 171, 47, 0.3);
        }
        
        .save-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(86, 171, 47, 0.4);
            background: linear-gradient(135deg, #4a9a2a 0%, #98d6bf 100%);
        }
        
        .save-button:disabled {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
        }
        
        .value-display {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .currency-symbol {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-weight: 600;
        }
        
        .currency-input {
            padding-left: 40px;
        }
        
        .section-divider {
            border: none;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 48px 0;
            border-radius: 2px;
        }
        
        .stats-overview {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 20px;
            padding: 24px;
            color: white;
            margin-bottom: 32px;
        }
        
        .demo-mode-warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .config-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .config-card:nth-child(odd) { animation-delay: 0.1s; }
        .config-card:nth-child(even) { animation-delay: 0.2s; }
        
        .tooltip-help {
            cursor: help;
            border-bottom: 1px dotted #6c757d;
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

                    <!-- Overview de Estatísticas -->
                    <div class="col-12">
                        <div class="stats-overview">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-coins h2 mb-2"></i>
                                        <h5 class="mb-1">R$ <?= number_format($config['jackpot'], 2, ',', '.'); ?></h5>
                                        <p class="mb-0 opacity-75">Jackpot Atual</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-percentage h2 mb-2"></i>
                                        <h5 class="mb-1"><?= $config['rollover']; ?>x</h5>
                                        <p class="mb-0 opacity-75">Rollover</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-bank h2 mb-2"></i>
                                        <h5 class="mb-1">R$ <?= number_format($config['limite_saque'], 2, ',', '.'); ?></h5>
                                        <p class="mb-0 opacity-75">Limite Diário</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-credit-card h2 mb-2"></i>
                                        <h5 class="mb-1">R$ <?= number_format($config['minimo_deposito'], 2, ',', '.'); ?></h5>
                                        <p class="mb-0 opacity-75">Depósito Mínimo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verificar se está em modo demo -->
                    <?php 
                    $modo_plataforma = $modo_plataforma ?? 0; // Definir default se não existir
                    if ($modo_plataforma == 0): ?>
                    <div class="col-12">
                        <div class="demo-mode-warning">
                            <div class="d-flex align-items-center">
                                <i class="iconoir-warning-triangle h3 text-dark me-3 mb-0"></i>
                                <div>
                                    <h6 class="text-dark mb-1">Modo Demonstração Ativo</h6>
                                    <p class="text-dark mb-0 opacity-75">As configurações não podem ser alteradas no modo demo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="">
                            
                            <!-- Seção de Saques -->
                            <div class="section-header">
                                <h4 class="mb-2">
                                    <i class="iconoir-bank me-2"></i>Configurações de Saque
                                </h4>
                                <p class="mb-0 opacity-75">Defina os limites e valores para operações de saque</p>
                            </div>

                            <div class="row mb-5">
                                <!-- Saque Mínimo -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-withdrawal">
                                            <i class="iconoir-download"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valor mínimo que um usuário pode sacar">
                                                Saque Mínimo
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="minsaque" class="form-control config-input currency-input" 
                                                   value="<?= $config['minsaque'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['minsaque'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Saque Máximo -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-withdrawal">
                                            <i class="iconoir-upload"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valor máximo que um usuário pode sacar por operação">
                                                Saque Máximo
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="maxsaque" class="form-control config-input currency-input" 
                                                   value="<?= $config['maxsaque'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['maxsaque'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Saque Automático -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-automatic">
                                            <i class="iconoir-automation"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valor máximo para saques automáticos sem aprovação manual">
                                                Saque Automático
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="saque_automatico" class="form-control config-input currency-input" 
                                                   value="<?= $config['saque_automatico'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['saque_automatico'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <!-- Seção de Depósitos -->
                            <div class="section-header">
                                <h4 class="mb-2">
                                    <i class="iconoir-credit-card me-2"></i>Configurações de Depósito
                                </h4>
                                <p class="mb-0 opacity-75">Configure valores e opções para depósitos na plataforma</p>
                            </div>

                            <div class="row mb-5">
                                <!-- Depósito Mínimo -->
                                <div class="col-md-6 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-deposit">
                                            <i class="iconoir-plus"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valor mínimo para realizar um depósito">
                                                Depósito Mínimo
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="minimo_deposito" class="form-control config-input currency-input" 
                                                   value="<?= $config['minimo_deposito'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['minimo_deposito'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Valores de Depósito -->
                                <div class="col-md-6 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-deposit">
                                            <i class="iconoir-coins"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valores pré-definidos para depósito rápido (separados por vírgula)">
                                                Valores de Depósito
                                            </span>
                                        </h6>
                                        <input type="text" name="mindep" class="form-control config-input" 
                                               value="<?= $config['mindep'] ?>" 
                                               placeholder="Ex: 10,20,50,100" required>
                                        <div class="value-display">
                                            Opções atuais: <?= $config['mindep']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <!-- Seção de Sistema -->
                            <div class="section-header">
                                <h4 class="mb-2">
                                    <i class="iconoir-settings me-2"></i>Configurações do Sistema
                                </h4>
                                <p class="mb-0 opacity-75">Parâmetros gerais de funcionamento da plataforma</p>
                            </div>

                            <div class="row mb-5">
                                <!-- Jackpot -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-jackpot">
                                            <i class="iconoir-trophy"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Valor atual do jackpot da plataforma">
                                                Valor do Jackpot
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="jackpot" class="form-control config-input currency-input" 
                                                   value="<?= $config['jackpot'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['jackpot'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rollover -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-rollover">
                                            <i class="iconoir-percentage"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Multiplicador de rollover para bônus e promoções">
                                                Rollover
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">×</span>
                                            <input type="number" name="rollover" class="form-control config-input currency-input" 
                                                   value="<?= $config['rollover'] ?>" step="0.1" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Multiplicador atual: <?= $config['rollover']; ?>x
                                        </div>
                                    </div>
                                </div>

                                <!-- Limite Diário -->
                                <div class="col-md-4 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-limit">
                                            <i class="iconoir-calendar"></i>
                                        </div>
                                        <h6 class="fw-bold mb-2">
                                            <span class="tooltip-help" title="Limite máximo de saque por usuário por dia">
                                                Limite Diário de Saque
                                            </span>
                                        </h6>
                                        <div class="position-relative">
                                            <span class="currency-symbol">R$</span>
                                            <input type="number" name="limite_saque" class="form-control config-input currency-input" 
                                                   value="<?= $config['limite_saque'] ?>" step="0.01" min="0" required>
                                        </div>
                                        <div class="value-display">
                                            Atual: R$ <?= number_format($config['limite_saque'], 2, ',', '.'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Salvar -->
                            <div class="text-center">
                                <?php if ($modo_plataforma == 0): ?>
                                    <button type="button" class="save-button" disabled>
                                        <i class="iconoir-lock me-2"></i>Salvar Configurações (MODO DEMO ATIVADO)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="save-button">
                                        <i class="iconoir-check me-2"></i>Salvar Configurações
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Scripts customizados -->
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
                    <h5 class="me-auto my-0">Phillyps</h5>
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

        // Formatação de moeda em tempo real
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2);
            input.value = value;
            
            // Atualizar display do valor atual
            const valueDisplay = input.closest('.config-card').querySelector('.value-display');
            if (valueDisplay && value) {
                const formattedValue = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(value);
                valueDisplay.textContent = `Novo valor: ${formattedValue}`;
                valueDisplay.style.color = '#007bff';
                valueDisplay.style.fontWeight = '600';
            }
        }

        // Validação em tempo real
        function setupValidation() {
            const inputs = document.querySelectorAll('.config-input');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.type === 'number') {
                        const value = parseFloat(this.value);
                        if (value < 0) {
                            this.value = 0;
                        }
                        
                        // Formatação visual para campos de moeda
                        if (this.classList.contains('currency-input')) {
                            formatCurrency(this);
                        }
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.closest('.config-card').style.borderColor = '#667eea';
                    this.closest('.config-card').style.boxShadow = '0 0 0 4px rgba(102, 126, 234, 0.1)';
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.config-card').style.borderColor = 'rgba(0,0,0,0.08)';
                    this.closest('.config-card').style.boxShadow = 'none';
                });
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.config-card');
            
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
                
                // Animação de entrada escalonada
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Tooltips
        function setupTooltips() {
            const tooltips = document.querySelectorAll('.tooltip-help');
            tooltips.forEach(tooltip => {
                tooltip.addEventListener('mouseenter', function() {
                    this.style.borderBottom = '2px solid #667eea';
                    this.style.color = '#667eea';
                });
                
                tooltip.addEventListener('mouseleave', function() {
                    this.style.borderBottom = '1px dotted #6c757d';
                    this.style.color = 'inherit';
                });
            });
        }

        // Validação de formulário
        function setupFormValidation() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                const minsaque = parseFloat(document.querySelector('input[name="minsaque"]').value);
                const maxsaque = parseFloat(document.querySelector('input[name="maxsaque"]').value);
                const saqueAuto = parseFloat(document.querySelector('input[name="saque_automatico"]').value);
                const minDeposito = parseFloat(document.querySelector('input[name="minimo_deposito"]').value);
                const rollover = parseFloat(document.querySelector('input[name="rollover"]').value);
                
                // Validações de negócio
                if (minsaque >= maxsaque) {
                    e.preventDefault();
                    showToast('error', 'O saque mínimo deve ser menor que o saque máximo!');
                    return;
                }
                
                if (saqueAuto > maxsaque) {
                    e.preventDefault();
                    showToast('error', 'O saque automático não pode ser maior que o saque máximo!');
                    return;
                }
                
                if (rollover < 1) {
                    e.preventDefault();
                    showToast('error', 'O rollover deve ser maior que 1!');
                    return;
                }
                
                if (minDeposito <= 0) {
                    e.preventDefault();
                    showToast('error', 'O depósito mínimo deve ser maior que zero!');
                    return;
                }
                
                // Adicionar loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Salvando...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Atualização em tempo real dos valores de overview
        function updateOverview() {
            const jackpotInput = document.querySelector('input[name="jackpot"]');
            const rolloverInput = document.querySelector('input[name="rollover"]');
            const limiteSaqueInput = document.querySelector('input[name="limite_saque"]');
            const minimoDepositoInput = document.querySelector('input[name="minimo_deposito"]');
            
            function updateValue(input, selector, formatter = null) {
                input.addEventListener('input', function() {
                    const element = document.querySelector(selector);
                    if (element) {
                        const value = parseFloat(this.value) || 0;
                        element.textContent = formatter ? formatter(value) : value;
                    }
                });
            }
            
            // Formatador de moeda
            const currencyFormatter = (value) => {
                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(value);
            };
            
            // Conectar inputs com overview
            updateValue(jackpotInput, '.stats-overview .col-md-3:nth-child(1) h5', currencyFormatter);
            updateValue(rolloverInput, '.stats-overview .col-md-3:nth-child(2) h5', (value) => `${value}x`);
            updateValue(limiteSaqueInput, '.stats-overview .col-md-3:nth-child(3) h5', currencyFormatter);
            updateValue(minimoDepositoInput, '.stats-overview .col-md-3:nth-child(4) h5', currencyFormatter);
        }

        // Função para destacar mudanças
        function highlightChanges() {
            const inputs = document.querySelectorAll('.config-input');
            
            inputs.forEach(input => {
                const originalValue = input.value;
                
                input.addEventListener('input', function() {
                    const card = this.closest('.config-card');
                    if (this.value !== originalValue) {
                        card.style.borderColor = '#ffc107';
                        card.style.backgroundColor = 'rgba(255, 193, 7, 0.05)';
                        
                        // Adicionar indicador visual
                        let indicator = card.querySelector('.change-indicator');
                        if (!indicator) {
                            indicator = document.createElement('div');
                            indicator.className = 'change-indicator position-absolute top-0 end-0 m-2';
                            indicator.innerHTML = '<i class="iconoir-edit text-warning"></i>';
                            indicator.style.cssText = 'background: white; border-radius: 50%; padding: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);';
                            card.style.position = 'relative';
                            card.appendChild(indicator);
                        }
                    } else {
                        card.style.borderColor = 'rgba(0,0,0,0.08)';
                        card.style.backgroundColor = '';
                        
                        const indicator = card.querySelector('.change-indicator');
                        if (indicator) {
                            indicator.remove();
                        }
                    }
                });
            });
        }

       document.addEventListener('DOMContentLoaded', function() {
        setupValidation();
        setupAnimations();
        setupTooltips();
        setupFormValidation();
        updateOverview();
        highlightChanges();
        
        // Efeito de digitação nos inputs
        const configInputs = document.querySelectorAll('.config-input');
        configInputs.forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const nextInput = this.closest('.config-card').parentElement.nextElementSibling?.querySelector('.config-input');
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
        });
    
        // Auto-save draft (salvar em localStorage)
        const form = document.querySelector('form');
        const formInputs = form.querySelectorAll('input');
        
        formInputs.forEach(input => {
            // Carregar valor salvo
            const savedValue = localStorage.getItem(`config_${input.name}`);
            if (savedValue && savedValue !== input.value) {
                input.style.backgroundColor = '#fff3cd';
                input.title = 'Valor alterado não salvo';
            }
    
            // Salvar ao alterar
            input.addEventListener('input', function() {
                localStorage.setItem(`config_${this.name}`, this.value);
            });
        });
    
        // Limpar localStorage quando salvar
        form.addEventListener('submit', function() {
            formInputs.forEach(input => {
                localStorage.removeItem(`config_${input.name}`);
            });
        });
    });
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