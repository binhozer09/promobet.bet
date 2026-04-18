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

# Função para buscar os dados atuais da tabela afiliados_config
function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM afiliados_config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

# Função para atualizar os dados da tabela afiliados_config
function update_afiliados_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE afiliados_config SET 
        cpaLvl1 = ?, 
        cpaLvl2 = ?, 
        cpaLvl3 = ?, 
        chanceCpa = ?, 
        revShareFalso = ?, 
        revShareLvl1 = ?, 
        revShareLvl2 = ?, 
        revShareLvl3 = ?, 
        minDepForCpa = ?, 
        minResgate = ? 
        WHERE id = 1");

    $qry->bind_param(
        "dddddddddd",
        $data['cpaLvl1'],
        $data['cpaLvl2'],
        $data['cpaLvl3'],
        $data['chanceCpa'],
        $data['revShareFalso'],
        $data['revShareLvl1'],
        $data['revShareLvl2'],
        $data['revShareLvl3'],
        $data['minDepForCpa'],
        $data['minResgate']
    );
    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados
$toastType = null; // Variável para definir o tipo de Toast
$toastMessage = ''; // Variável para definir a mensagem do Toast

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'cpaLvl1' => floatval($_POST['cpaLvl1']),
        'cpaLvl2' => floatval($_POST['cpaLvl2']),
        'cpaLvl3' => floatval($_POST['cpaLvl3']),
        'chanceCpa' => floatval($_POST['chanceCpa']),
        'revShareFalso' => floatval($_POST['revShareFalso']),
        'revShareLvl1' => floatval($_POST['revShareLvl1']),
        'revShareLvl2' => floatval($_POST['revShareLvl2']),
        'revShareLvl3' => floatval($_POST['revShareLvl3']),
        'minDepForCpa' => floatval($_POST['minDepForCpa']),
        'minResgate' => floatval($_POST['minResgate']),
    ];

    if (update_afiliados_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações de afiliados atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

# Buscar os dados atuais
$config = get_afiliados_config();
?>

<head>
    <?php $title = "Configurações de Afiliados";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #10b981;
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
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .affiliate-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .affiliate-header {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: var(--text-primary);
            position: relative;
            overflow: hidden;
        }

        .affiliate-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color), #059669);
        }

        .affiliate-header h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .affiliate-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Stats Overview */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), #059669);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--accent-color);
            font-size: 20px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Configuration Sections */
        .config-sections {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .config-section {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, var(--accent-color), #059669);
            padding: 20px 24px;
            color: white;
        }

        .section-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-subtitle {
            margin: 4px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .section-content {
            padding: 24px;
        }

        /* Form Groups */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-card {
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .form-card:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .form-label i {
            color: var(--accent-color);
            font-size: 16px;
        }

        .form-input {
            width: 100%;
            background: var(--secondary-bg);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 14px 16px;
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .input-suffix {
            position: absolute;
            right: 20px; /* Aumentar para dar mais espaço */
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 600;
            pointer-events: none;
        }

        .input-group {
            position: relative;
        }

        /* Form Help Text */
        .form-help {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 6px;
            font-weight: 500;
        }

        /* Level Indicators */
        .level-indicator {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
        }

        .level-1 { background: #3b82f6; }
        .level-2 { background: #f59e0b; }
        .level-3 { background: #ef4444; }

        /* Save Button */
        .save-section {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .save-btn {
            background: linear-gradient(135deg, var(--accent-color), #059669);
            color: white;
            border: none;
            padding: 16px 48px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
        }

        .save-btn:active {
            transform: translateY(0);
        }

        /* Configuration Tips */
        .config-tips {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .tips-title {
            color: var(--info-color);
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
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .tips-list li::before {
            content: '💡';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Animations */
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

        .config-section {
            animation: fadeInUp 0.5s ease-out;
        }

        .config-section:nth-child(odd) { animation-delay: 0.1s; }
        .config-section:nth-child(even) { animation-delay: 0.2s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .affiliate-layout {
                padding: 16px;
            }
            
            .affiliate-header {
                padding: 24px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-overview {
                grid-template-columns: 1fr;
            }
            
            .save-btn {
                width: 100%;
                padding: 16px 24px;
            }
        }

        /* Toast Improvements */
        .toast-container .toast {
            background: var(--secondary-bg);
            border: 1px solid var(--border-color);
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
        
        /* Esconder setas dos inputs number */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type="number"] {
            -moz-appearance: textfield;
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
        <div class="page-content affiliate-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="affiliate-header">
                    <h4>
                        <i class="bi bi-people"></i>
                        Configurações de Afiliados
                    </h4>
                    <p>Configure comissões, níveis e requisitos do programa de afiliados</p>
                </div>

                <!-- Stats Overview -->
                <div class="stats-overview">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($config['cpaLvl1'], 2, ',', '.') ?></div>
                        <div class="stat-label">CPA Nível 1</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-value">
                            <?php if ($config['chanceCpa'] == 1): ?>
                                Todas pagas
                            <?php else: ?>
                                <?= $config['chanceCpa'] - 1 ?> pagas a cada <?= number_format($config['chanceCpa'], 0) ?>
                            <?php endif; ?>
                        </div>
                        <div class="stat-label">Probabilidade de Indicação</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-wallet"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($config['minDepForCpa'], 2, ',', '.') ?></div>
                        <div class="stat-label">Depósito Mín. CPA</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-bank"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format($config['minResgate'], 2, ',', '.') ?></div>
                        <div class="stat-label">Resgate Mínimo</div>
                    </div>
                </div>

                <!-- Configuration Tips -->
                <div class="config-tips">
                    <div class="tips-title">
                        <i class="bi bi-lightbulb"></i>
                        Dicas de Configuração
                    </div>
                    <ul class="tips-list">
                        <li>Configure valores de CPA progressivos para incentivar afiliados de alto nível</li>
                        <li>Controle a probabilidade de indicação para manter custos sustentáveis</li>
                        <li>O manipulador reduz custos: com valor 4, apenas 3 de cada 4 indicações são creditadas ao influenciador</li>
                        <li>RevShare falso é exibido publicamente, mas valores reais são aplicados</li>
                        <li>Valores mínimos ajudam a filtrar transações legítimas</li>
                    </ul>
                </div>

                <form method="POST" action="" id="affiliateConfigForm">
                    <div class="config-sections">
                        
                        <!-- CPA Configuration -->
                        <div class="config-section">
                            <div class="section-header">
                                <div class="section-title">
                                    <i class="bi bi-cash-coin"></i>
                                    Configurações de CPA (Cost Per Acquisition)
                                </div>
                                <div class="section-subtitle">Valores fixos pagos por cada conversão de afiliado</div>
                            </div>
                            <div class="section-content">
                                <div class="form-grid">
                                    <div class="form-card">
                                        <div class="level-indicator level-1">1</div>
                                        <label class="form-label">
                                            <i class="bi bi-1-circle"></i>
                                            CPA Nível 1
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="cpaLvl1" class="form-input" 
                                                   value="<?= $config['cpaLvl1'] ?>" required min="0">
                                            <span class="input-suffix">R$</span>
                                        </div>
                                        <div class="form-help">Valor pago para afiliados iniciantes</div>
                                    </div>
                
                                    <div class="form-card">
                                        <div class="level-indicator level-2">2</div>
                                        <label class="form-label">
                                            <i class="bi bi-2-circle"></i>
                                            CPA Nível 2
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="cpaLvl2" class="form-input" 
                                                   value="<?= $config['cpaLvl2'] ?>" required min="0">
                                            <span class="input-suffix">R$</span>
                                        </div>
                                        <div class="form-help">Valor pago para afiliados intermediários</div>
                                    </div>
                
                                    <div class="form-card">
                                        <div class="level-indicator level-3">3</div>
                                        <label class="form-label">
                                            <i class="bi bi-3-circle"></i>
                                            CPA Nível 3
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="cpaLvl3" class="form-input" 
                                                   value="<?= $config['cpaLvl3'] ?>" required min="0">
                                            <span class="input-suffix">R$</span>
                                        </div>
                                        <div class="form-help">Valor pago para afiliados avançados</div>
                                    </div>
                
                                    <div class="form-card">
                                        <label class="form-label">
                                            <i class="bi bi-dice-6"></i>
                                            Probabilidade de Indicação
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="chanceCpa" class="form-input" 
                                                   value="<?= (int)$config['chanceCpa'] ?>" required min="1" max="20">
                                            <span class="input-suffix">indicações</span>
                                        </div>
                                        <div class="form-help">A cada X indicações, 1 não receberá indicação (ex: 4 = paga 3 de cada 4)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- RevShare Configuration -->
                        <div class="config-section">
                            <div class="section-header">
                                <div class="section-title">
                                    <i class="bi bi-percent"></i>
                                    Configurações de RevShare (Revenue Share)
                                </div>
                                <div class="section-subtitle">Percentuais de comissão sobre receita gerada</div>
                            </div>
                            <div class="section-content">
                                <div class="form-grid">
                                    <div class="form-card">
                                        <label class="form-label">
                                            <i class="bi bi-eye-slash"></i>
                                            RevShare Falso (Público)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="revShareFalso" class="form-input" 
                                                   value="<?= $config['revShareFalso'] ?>" required min="0" max="100">
                                            <span class="input-suffix">%</span>
                                        </div>
                                        <div class="form-help">Percentual exibido publicamente para marketing</div>
                                    </div>
                
                                    <div class="form-card">
                                        <div class="level-indicator level-1">1</div>
                                        <label class="form-label">
                                            <i class="bi bi-1-circle"></i>
                                            RevShare Nível 1
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="revShareLvl1" class="form-input" 
                                                   value="<?= $config['revShareLvl1'] ?>" required min="0" max="100">
                                            <span class="input-suffix">%</span>
                                        </div>
                                        <div class="form-help">Percentual real para afiliados nível 1</div>
                                    </div>
                
                                    <div class="form-card">
                                        <div class="level-indicator level-2">2</div>
                                        <label class="form-label">
                                            <i class="bi bi-2-circle"></i>
                                            RevShare Nível 2
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="revShareLvl2" class="form-input" 
                                                   value="<?= $config['revShareLvl2'] ?>" required min="0" max="100">
                                            <span class="input-suffix">%</span>
                                        </div>
                                        <div class="form-help">Percentual real para afiliados nível 2</div>
                                    </div>
                
                                    <div class="form-card">
                                        <div class="level-indicator level-3">3</div>
                                        <label class="form-label">
                                            <i class="bi bi-3-circle"></i>
                                            RevShare Nível 3
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="revShareLvl3" class="form-input" 
                                                   value="<?= $config['revShareLvl3'] ?>" required min="0" max="100">
                                            <span class="input-suffix">%</span>
                                        </div>
                                        <div class="form-help">Percentual real para afiliados nível 3</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Requirements Configuration -->
                        <div class="config-section mb-2">
                            <div class="section-header">
                                <div class="section-title">
                                    <i class="bi bi-shield-check"></i>
                                    Requisitos e Limites
                                </div>
                                <div class="section-subtitle">Valores mínimos e critérios de elegibilidade</div>
                            </div>
                            <div class="section-content">
                                <div class="form-grid">
                                    <div class="form-card">
                                        <label class="form-label">
                                            <i class="bi bi-wallet2"></i>
                                            Depósito Mínimo para CPA
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="minDepForCpa" class="form-input" 
                                                   value="<?= $config['minDepForCpa'] ?>" required min="0">
                                            <span class="input-suffix">R$</span>
                                        </div>
                                        <div class="form-help">Valor mínimo de depósito para gerar comissão CPA</div>
                                    </div>
                
                                    <div class="form-card">
                                        <label class="form-label">
                                            <i class="bi bi-bank2"></i>
                                            Valor Mínimo para Resgate
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="minResgate" class="form-input" 
                                                   value="<?= $config['minResgate'] ?>" required min="0">
                                            <span class="input-suffix">R$</span>
                                        </div>
                                        <div class="form-help">Saldo mínimo necessário para solicitar saque</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Save Section -->
                    <div class="save-section">
                        <button type="submit" class="save-btn" id="saveBtn">
                            <i class="bi bi-check-circle"></i>
                            Salvar Configurações
                        </button>
                        <div style="margin-top: 12px; color: var(--text-secondary); font-size: 14px;">
                            Todas as alterações serão aplicadas imediatamente
                        </div>
                    </div>
                </form>

            </div><!-- container -->
        </div><!-- page content -->
    </div><!-- page-wrapper -->

    <!-- Toast container -->

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
                    <strong class="me-auto">Sistema de Afiliados</strong>
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

        // Validação de formulário em tempo real
        function setupFormValidation() {
            const form = document.getElementById('affiliateConfigForm');
            const inputs = form.querySelectorAll('input[type="number"]');
            
            inputs.forEach(input => {
                // Validação em tempo real
                input.addEventListener('input', function() {
                    validateInput(this);
                });
                
                // Validação ao perder foco
                input.addEventListener('blur', function() {
                    validateInput(this);
                });
            });
            
            // Validação ao submeter
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
                
                // Mostrar loading
                const saveBtn = document.getElementById('saveBtn');
                const resetLoading = showButtonLoading(saveBtn);
                
                // Simular delay para feedback visual
                setTimeout(() => {
                    // O form será submetido normalmente
                }, 500);
            });
        }

        // Função de validação individual
        function validateInput(input) {
            const value = parseFloat(input.value);
            const min = parseFloat(input.getAttribute('min') || 0);
            const max = parseFloat(input.getAttribute('max') || Infinity);
            const card = input.closest('.form-card');
            
            // Remove classes anteriores
            card.classList.remove('error', 'success');
            
            // Validações
            if (isNaN(value) || value < min || value > max) {
                card.classList.add('error');
                input.style.borderColor = '#ef4444';
                return false;
            } else {
                card.classList.add('success');
                input.style.borderColor = '#10b981';
                return true;
            }
        }

            // Efeitos visuais e animações
            function setupVisualEffects() {
                // Animação dos contadores nas estatísticas
                const statValues = document.querySelectorAll('.stat-value');
                statValues.forEach(stat => {
                    const value = stat.textContent.trim();
            
                    // Verifica se é um valor monetário (ex: R$ 1.234,56)
                    if (value.includes('R$')) {
                        const number = parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.'));
                        if (!isNaN(number)) {
                            animateCounter(stat, number, true);
                        }
                    } 
                    // Verifica se é uma porcentagem (ex: 75%)
                    else if (value.includes('%')) {
                        const number = parseFloat(value.replace('%', ''));
                        if (!isNaN(number)) {
                            animateCounter(stat, number, false, '%');
                        }
                    }
                });
            
            // Hover effects nos cards
            const formCards = document.querySelectorAll('.form-card');
            formCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Efeitos nos botões
            const buttons = document.querySelectorAll('.save-btn, .stat-card');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            });
        }

        // Função para animar contadores
        function animateCounter(element, target, isCurrency = false, suffix = '') {
            let current = 0;
            const increment = target / 40;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (isCurrency) {
                    element.textContent = `R$ ${current.toFixed(2).replace('.', ',')}`;
                } else {
                    element.textContent = `${current.toFixed(1)}${suffix}`;
                }
            }, 50);
        }

        // Sistema de dicas contextuais
        function setupTooltips() {
            const formCards = document.querySelectorAll('.form-card');
            
            formCards.forEach(card => {
                const input = card.querySelector('input');
                const label = card.querySelector('.form-label');
                
                // Adicionar dicas específicas baseadas no nome do campo
                const fieldName = input.getAttribute('name');
                const tooltip = getTooltipForField(fieldName);
                
                if (tooltip) {
                    label.setAttribute('title', tooltip);
                    label.style.cursor = 'help';
                }
            });
        }

        // Função para obter dicas específicas
        function getTooltipForField(fieldName) {
            const tooltips = {
                'cpaLvl1': 'Valor fixo pago por cada novo jogador que faz seu primeiro depósito via afiliado nível 1',
                'cpaLvl2': 'Valor fixo pago por cada novo jogador que faz seu primeiro depósito via afiliado nível 2',
                'cpaLvl3': 'Valor fixo pago por cada novo jogador que faz seu primeiro depósito via afiliado nível 3',
                'chanceCpa': 'Percentual de chance de um depósito gerar comissão CPA (controla a frequência)',
                'revShareFalso': 'Percentual mostrado publicamente para atrair afiliados (valor de marketing)',
                'revShareLvl1': 'Percentual real da receita líquida pago aos afiliados nível 1',
                'revShareLvl2': 'Percentual real da receita líquida pago aos afiliados nível 2',
                'revShareLvl3': 'Percentual real da receita líquida pago aos afiliados nível 3',
                'minDepForCpa': 'Valor mínimo que um jogador deve depositar para que gere comissão CPA',
                'minResgate': 'Saldo mínimo que um afiliado deve ter para solicitar saque'
            };
            
            return tooltips[fieldName] || null;
        }

        // Auto-save draft (opcional)
        function setupAutoSave() {
            const form = document.getElementById('affiliateConfigForm');
            const inputs = form.querySelectorAll('input');
            
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Salvar no localStorage como draft
                    const drafts = JSON.parse(localStorage.getItem('affiliateConfigDrafts') || '{}');
                    drafts[this.name] = this.value;
                    localStorage.setItem('affiliateConfigDrafts', JSON.stringify(drafts));
                });
            });
            
            // Restaurar drafts ao carregar
            const drafts = JSON.parse(localStorage.getItem('affiliateConfigDrafts') || '{}');
            Object.keys(drafts).forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input && drafts[fieldName]) {
                    // Só aplica se o valor for diferente do atual
                    if (input.value !== drafts[fieldName]) {
                        input.style.backgroundColor = 'rgba(251, 191, 36, 0.1)';
                        input.setAttribute('title', `Valor alterado: ${drafts[fieldName]}`);
                    }
                }
            });
        }

        // Comparação de valores
        function setupValueComparison() {
            const cpaInputs = document.querySelectorAll('[name^="cpaLvl"]');
            const revShareInputs = document.querySelectorAll('[name^="revShareLvl"]');
            
            function checkProgression(inputs, type) {
                inputs.forEach((input, index) => {
                    input.addEventListener('input', function() {
                        const values = Array.from(inputs).map(inp => parseFloat(inp.value) || 0);
                        
                        // Verificar se os valores são progressivos
                        let isProgressive = true;
                        for (let i = 1; i < values.length; i++) {
                            if (values[i] <= values[i-1]) {
                                isProgressive = false;
                                break;
                            }
                        }
                        
                        // Aplicar feedback visual
                        inputs.forEach((inp, idx) => {
                            const card = inp.closest('.form-card');
                            card.classList.remove('warning');
                            
                            if (!isProgressive) {
                                card.classList.add('warning');
                            }
                        });
                        
                        if (!isProgressive) {
                            console.log(`⚠️ Valores de ${type} não são progressivos`);
                        }
                    });
                });
            }
            
            checkProgression(cpaInputs, 'CPA');
            checkProgression(revShareInputs, 'RevShare');
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            setupFormValidation();
            setupVisualEffects();
            setupTooltips();
            setupAutoSave();
            setupValueComparison();
            
            // Limpar drafts após salvar com sucesso
            <?php if ($toastType === 'success'): ?>
            localStorage.removeItem('affiliateConfigDrafts');
            <?php endif; ?>
        });

        // CSS adicional para estados de validação
        const additionalStyles = document.createElement('style');
        additionalStyles.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .form-card.error {
                border-color: #ef4444 !important;
                background: rgba(239, 68, 68, 0.05);
            }
            
            .form-card.success {
                border-color: #10b981 !important;
            }
            
            .form-card.warning {
                border-color: #f59e0b !important;
                background: rgba(245, 158, 11, 0.05);
            }
            
            .form-card.error::before {
                content: '⚠️';
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 16px;
            }
            
            .form-card.success::before {
                content: '✅';
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 16px;
            }
            
            .form-card.warning::before {
                content: '⚠️';
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 16px;
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