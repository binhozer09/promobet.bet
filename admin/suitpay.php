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

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_bullspay_config()
{
    global $mysqli;
    
    $bullspayQuery = "SELECT * FROM bullspay WHERE id = 1";
    $bullspayResult = mysqli_query($mysqli, $bullspayQuery);
    $bullspayConfig = mysqli_fetch_assoc($bullspayResult);

    return $bullspayConfig;
}

function update_bullspay_config($data)
{
    global $mysqli;

    $qry = $mysqli->prepare("UPDATE bullspay SET url = ?, client_id = ?, client_secret = ?, ativo = 1, atualizado = 1 WHERE id = 1");
    $qry->bind_param("sss", $data['url'], $data['client_id'], $data['client_secret']);
    $success = $qry->execute();

    if (!$success) {
        die("Erro ao atualizar credenciais: " . $qry->error);
    }

    return $success;
}

function is_bullspay_active($mysqli)
{
    $result = $mysqli->query("SELECT ativo FROM bullspay WHERE id = 1");
    if ($result) {
        $data = $result->fetch_assoc();
        return $data['ativo'] == 1;
    }
    return false;
}

// Função para verificar 2FA de retirada
function validate_2fa_withdraw($codigo_2fa) {
    global $mysqli;
    
    $user_id = $_SESSION['data_adm']['id'];
    
    $stmt = $mysqli->prepare("SELECT 2fa_auth_withdraw FROM admin_users WHERE id = ? AND status = 1");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return $user['2fa_auth_withdraw'] === $codigo_2fa;
    }
    
    return false;
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se tem código 2FA
    if (empty($_POST['codigo_2fa_withdraw'])) {
        $toastType = 'error';
        $toastMessage = 'Código de Segurança é obrigatório para alterações de gateway.';
    } else {
        $codigo_2fa = trim($_POST['codigo_2fa_withdraw']);
        
        // Validar código 2FA
        if (!validate_2fa_withdraw($codigo_2fa)) {
            $toastType = 'error';
            $toastMessage = 'Código de Segurança incorreto. Alteração negada.';
        } else {
            // 2FA válido, processar alteração
            $data = [
                'client_id' => $_POST['bullspay_client_id'],
                'client_secret' => $_POST['bullspay_client_secret'],
                'url' => $_POST['bullspay_url'],
            ];

            $update_success = update_bullspay_config($data);

            if ($update_success) {
                $toastType = 'success';
                $toastMessage = 'Gateway GGPix atualizado com sucesso!';
                
                // Registrar log da alteração
                $admin_email = $_SESSION['data_adm']['email'];
                registrarLog($mysqli, $admin_email, "Alterou configuração do gateway GGPix");
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao atualizar as credenciais. Tente novamente.';
            }
        }
    }
}

$config = get_bullspay_config();
$isActive = is_bullspay_active($mysqli);
?>

<head>
    <?php $title = "Configurações do Gateway GGPix";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #ff6b35;
            --secondary-accent: #e85a2b;
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

        .payment-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .payment-header {
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-accent));
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .payment-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 10s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-20px) rotate(360deg); }
        }

        .payment-header h4 {
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

        .payment-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Active Gateway Display */
        .active-gateway-section {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 2px solid var(--border-color);
            position: relative;
        }

        .active-gateway-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .active-gateway-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-accent));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .active-gateway-info h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .active-gateway-info p {
            margin: 4px 0 0 0;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .active-gateway-status {
            background: var(--success-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .active-gateway-status.inactive {
            background: var(--danger-color);
        }

        .active-gateway-value {
            background: var(--primary-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 16px;
        }

        /* Gateway Card */
        .gateway-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .gateway-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            border: 2px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .gateway-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(255, 107, 53, 0.2);
            border-color: var(--accent-color);
        }

        .gateway-header {
            background: linear-gradient(135deg, var(--secondary-bg), #3f3f3f);
            padding: 24px;
            border-bottom: 2px solid var(--border-color);
            position: relative;
        }

        .gateway-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .gateway-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-accent));
        }

        .gateway-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .gateway-description {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        .gateway-status {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .gateway-status.active {
            background: var(--success-color);
            color: white;
        }

        .gateway-status.inactive {
            background: var(--border-color);
            color: var(--text-secondary);
        }

        /* Form Styling */
        .gateway-form {
            padding: 28px;
        }

        .form-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-label i {
            color: var(--accent-color);
            font-size: 16px;
        }

        .form-input-group {
            position: relative;
            display: flex;
        }

        .form-input {
            flex: 1;
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 14px 16px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .form-input[type="password"] {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 48px;
            background: var(--border-color);
            border: none;
            border-radius: 0 8px 8px 0;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            background: var(--accent-color);
            color: white;
        }

        /* Save Button */
        .save-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-accent));
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Security Tips */
        .security-tips {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .security-title {
            color: var(--danger-color);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .security-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .security-list li {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }

        .security-list li::before {
            content: '🔐';
            position: absolute;
            left: 0;
            top: 0;
        }

        /* Modal 2FA */
        .modal-2fa {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .modal-2fa.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content-2fa {
            background: var(--secondary-bg);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            width: 90%;
            max-width: 420px;
            text-align: center;
            animation: slideUp 0.4s ease;
            position: relative;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .modal-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        .code-input-2fa {
            width: 100%;
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.5em;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .code-input-2fa:focus {
            outline: none;
            border-color: var(--danger-color);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-modal {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-confirm {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .btn-cancel {
            background: var(--border-color);
            color: var(--text-secondary);
        }

        .btn-cancel:hover {
            background: var(--primary-bg);
            color: var(--text-primary);
        }

        .close-modal {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: var(--danger-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-layout {
                padding: 16px;
            }
            
            .payment-header {
                padding: 24px;
            }
            
            .gateway-form {
                padding: 20px;
            }

            .modal-content-2fa {
                padding: 24px;
                margin: 20px;
            }
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gateway-card {
            animation: slideInUp 0.5s ease-out;
        }
    </style>
</head>

<body>

    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <!-- Modal 2FA -->
    <div class="modal-2fa" id="modal2FA">
        <div class="modal-content-2fa">
            <button class="close-modal" onclick="closeModal2FA()">&times;</button>
            
            <div class="modal-icon">
                <i class="bi bi-shield-lock text-white" style="font-size: 32px;"></i>
            </div>
            
            <h3 class="modal-title">Verificação de Segurança</h3>
            <p class="modal-subtitle">Digite o código de Segurança para autorizar a alteração do gateway GGPix</p>
            
            <form id="form2FA">
                <input type="text" 
                       id="codigo2FA" 
                       class="code-input-2fa" 
                       placeholder="000000" 
                       maxlength="6" 
                       pattern="[0-9]{6}" 
                       required>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-modal btn-cancel" onclick="closeModal2FA()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-modal btn-confirm">
                        Autorizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="page-content payment-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="payment-header">
                    <h4>
                        <i class="bi bi-currency-exchange"></i>
                        Configurações do Gateway GGPix
                    </h4>
                    <p>Configure e gerencie o gateway de pagamento GGPix</p>
                </div>

                <!-- Active Gateway Display -->
                <div class="active-gateway-section">
                    <div class="active-gateway-status <?= $isActive ? 'active' : 'inactive' ?>">
                        <?= $isActive ? 'ATIVO' : 'INATIVO' ?>
                    </div>
                    <div class="active-gateway-header">
                        <div class="active-gateway-icon">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="active-gateway-info">
                            <h5>Status do Gateway GGPix</h5>
                            <p>Estado atual do sistema de pagamentos</p>
                        </div>
                    </div>
                    <div class="active-gateway-value">
                        <?= $isActive ? 'GGPix - Sistema Ativo' : 'GGPix - Sistema Inativo' ?>
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="security-tips">
                    <div class="security-title">
                        <i class="bi bi-shield-exclamation"></i>
                        Dicas de Segurança
                    </div>
                    <ul class="security-list">
                        <li>Mantenha suas credenciais sempre atualizadas e seguras</li>
                        <li>Use URLs HTTPS para todos os endpoints</li>
                        <li>Monitore regularmente as transações para detectar anomalias</li>
                        <li>Verifique se o endpoint está funcionando corretamente</li>
                        <li>📱 Código de Segurança é necessário para alterações</li>
                    </ul>
                </div>

                <!-- Feedback Section -->
                <div id="feedback-container"></div>

                <!-- Gateway Container -->
                <div class="gateway-container">
                    <!-- GGPix Gateway -->
                    <div class="gateway-card">
                        <div class="gateway-header">
                            <div class="gateway-status <?= $isActive ? 'active' : 'inactive' ?>">
                                <?= $isActive ? 'Ativo' : 'Inativo' ?>
                            </div>
                            <div class="gateway-title">
                                <div class="gateway-icon">
                                    <i class="bi bi-currency-exchange"></i>
                                </div>
                                <div>
                                    <h3 class="gateway-name">GGPix</h3>
                                    <p class="gateway-description">Gateway GGPix - Pagamentos PIX</p>
                                </div>
                            </div>
                        </div>
                        <div class="gateway-form">
                            <form class="gateway-form-element" data-gateway="GGPix">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-key"></i>
                                            API Key
                                        </label>
                                        <div class="form-input-group">
                                            <input type="password" 
                                                   id="bullspay_client_id" 
                                                   name="bullspay_client_id"
                                                   class="form-input"
                                                   value="<?= htmlspecialchars($config['client_id']) ?>" 
                                                   required>
                                            <button type="button" class="password-toggle" onclick="togglePassword('bullspay_client_id', this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="bullspay_client_secret" value="<?= htmlspecialchars($config['client_secret']) ?>">

                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="bi bi-link-45deg"></i>
                                            Endpoint
                                        </label>
                                        <input type="text" 
                                               name="bullspay_url" 
                                               class="form-input"
                                               value="<?= htmlspecialchars($config['url']) ?>" 
                                               required
                                               placeholder="https://ggpixapi.com/api/v1">
                                    </div>
                                </div>
                                <button type="submit" class="save-btn">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    Salvar Configurações
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'partials/endbar.php' ?>
    </div>

    <!-- Form oculto para envio real -->
    <form id="hiddenForm" method="POST" action="" style="display: none;">
        <input type="hidden" name="bullspay_client_id" id="hidden_bullspay_client_id">
        <input type="hidden" name="bullspay_client_secret" id="hidden_bullspay_client_secret">
        <input type="hidden" name="bullspay_url" id="hidden_bullspay_url">
        <input type="hidden" name="codigo_2fa_withdraw" id="hidden_codigo_2fa">
        <?php $csrf->echoInputField(); ?>
    </form>

    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        let currentFormData = null;

        // Função para abrir modal 2FA
        function openModal2FA(formData) {
            currentFormData = formData;
            document.getElementById('modal2FA').classList.add('show');
            document.getElementById('codigo2FA').focus();
            document.body.style.overflow = 'hidden';
        }

        // Função para fechar modal 2FA
        function closeModal2FA() {
            document.getElementById('modal2FA').classList.remove('show');
            document.getElementById('codigo2FA').value = '';
            document.body.style.overflow = 'auto';
            currentFormData = null;
        }

        // Função para mostrar feedback global
        function showGlobalFeedback(type, message) {
            const existingFeedback = document.querySelector('.global-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            const feedback = document.createElement('div');
            feedback.className = 'global-feedback';
            feedback.style.cssText = `
                margin-bottom: 24px;
                padding: 16px 20px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                text-align: center;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                ${type === 'success' 
                    ? 'background: rgba(16, 185, 129, 0.1); color: #10b981; border: 2px solid rgba(16, 185, 129, 0.2);' 
                    : 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 2px solid rgba(239, 68, 68, 0.2);'
                }
            `;
            
            const icon = type === 'success' ? '✅' : '❌';
            feedback.innerHTML = `${icon} ${message}`;
            
            const gatewayContainer = document.querySelector('.gateway-container');
            gatewayContainer.parentNode.insertBefore(feedback, gatewayContainer);
            
            feedback.style.opacity = '0';
            feedback.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                feedback.style.opacity = '1';
                feedback.style.transform = 'translateY(0)';
            }, 100);
            
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.style.opacity = '0';
                    feedback.style.transform = 'translateY(-20px)';
                    setTimeout(() => feedback.remove(), 300);
                }
            }, 5000);
        }

        // Função para alternar visibilidade da senha
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                button.style.background = 'var(--accent-color)';
                button.style.color = 'white';
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                button.style.background = 'var(--border-color)';
                button.style.color = 'var(--text-secondary)';
            }
        }

        // Inicialização principal
        document.addEventListener('DOMContentLoaded', function() {
            // Capturar envio do formulário GGPix
            const gatewayForm = document.querySelector('.gateway-form-element');
            
            gatewayForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Coletar dados do formulário
                const formData = new FormData(this);
                
                // Preparar dados para o modal
                const dataForModal = {};
                
                // Coletar todos os campos do formulário
                for (let [key, value] of formData.entries()) {
                    dataForModal[key] = value;
                }
                
                // Abrir modal 2FA
                openModal2FA(dataForModal);
            });

            // Envio do formulário 2FA
            document.getElementById('form2FA').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const codigo2FA = document.getElementById('codigo2FA').value.trim();
                
                if (!codigo2FA || codigo2FA.length !== 6) {
                    showGlobalFeedback('error', 'Código 2FA deve ter 6 dígitos');
                    return;
                }
                
                if (!currentFormData) {
                    showGlobalFeedback('error', 'Erro: dados do formulário não encontrados');
                    return;
                }
                
                // Preencher formulário oculto
                document.getElementById('hidden_bullspay_client_id').value = currentFormData.bullspay_client_id || '';
                document.getElementById('hidden_bullspay_client_secret').value = currentFormData.bullspay_client_secret || '';
                document.getElementById('hidden_bullspay_url').value = currentFormData.bullspay_url || '';
                document.getElementById('hidden_codigo_2fa').value = codigo2FA;
                
                // Enviar formulário
                document.getElementById('hiddenForm').submit();
            });

            // Máscara para código 2FA
            document.getElementById('codigo2FA').addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').substring(0, 6);
            });

            // Fechar modal ao clicar fora
            document.getElementById('modal2FA').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal2FA();
                }
            });

            // Tecla ESC para fechar modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('modal2FA').classList.contains('show')) {
                    closeModal2FA();
                }
            });

            // Destacar gateway se ativo
            const isActive = <?= $isActive ? 'true' : 'false' ?>;
            if (isActive) {
                const gatewayCard = document.querySelector('.gateway-card');
                gatewayCard.style.borderColor = 'var(--accent-color)';
                gatewayCard.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.3)';
            }
        });
    </script>

    <!-- Exibir feedback baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showGlobalFeedback('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                
                <?php if ($toastType === 'success'): ?>
                const gatewayCard = document.querySelector('.gateway-card');
                if (gatewayCard) {
                    gatewayCard.style.borderColor = 'var(--accent-color)';
                    gatewayCard.style.boxShadow = '0 0 25px rgba(255, 107, 53, 0.4)';
                    gatewayCard.style.transform = 'scale(1.02)';
                    
                    gatewayCard.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    
                    setTimeout(() => {
                        gatewayCard.style.transform = 'scale(1)';
                        gatewayCard.style.borderColor = 'var(--accent-color)';
                        gatewayCard.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.3)';
                    }, 3000);
                }
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>
    </body>
    </html>