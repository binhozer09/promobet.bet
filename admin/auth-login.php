<?php include 'partials/html.php' ?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include_once 'services/database.php';
include_once 'services/funcao.php';
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once "services/CSRF_Protect.php";
$csrf = new CSRF_Protect();

// Variáveis para controle da página
$show_2fa = false;
$temp_user_id = '';
$alert_message = '';
$alert_type = '';
$redirect_script = '';
$hide_login_form = false; // Nova variável para ocultar formulário

// Função para enviar notificação via Telegram
function enviarNotificacaoTelegram($email) {
    $tokenBot = '8097625521:AAGDHzAbsU1UfKpQhBn_H_dka2YqwaN-3oA';
    $chatId = '-1002302431099';

    $urlSite = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

    $mensagem = "Novo login no painel admin:\n\n";
    $mensagem .= "E-mail: $email\n";
    $mensagem .= "URL do site: $urlSite/dash";
    
    $url = "https://api.telegram.org/bot$tokenBot/sendMessage";
    
    $dados = [
        'chat_id' => $chatId,
        'text' => $mensagem,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);
    curl_close($ch);
}

// Função para completar o login
function completarLogin($row, $email, $CRSF) {
    global $mysqli, $painel_adm, $redirect_script;
    
    $user_idx = $row['id'];
    $emailbd = $row['email'];
    $data = date('Y-m-d H:i:s');
    $_token_easy = md5(sha1(mt_rand()) . $data . $emailbd . $user_idx);
    $_token = CRIPT_AES('encrypt', $_token_easy);
    
    // Definindo os dados da sessão
    $_SESSION['token_adm_encrypted'] = CRIPT_AES('encrypt', preg_replace("/[^0-9]+/", "", $user_idx));
    $_SESSION['crsf_token_adm'] = $_token;
    $_SESSION['anti_crsf_token_adm'] = $CRSF;

    // Registrando o login
//    include_once('logs/registrar_logs.php');
//    registrarLog($mysqli, $email, "<span class='status-badge green' style='display: inline-block;'><i class='fa fa-sign-out'></i></span> Logou no painel admin");

    // Enviando notificação para o Telegram
    enviarNotificacaoTelegram($emailbd);

    // Definir redirecionamento
    $redirect_script = "<script>setTimeout(() => window.location.href = '{$painel_adm}', 2000);</script>";
    
    return true;
}

// Verificar se já existe sessão temporária de 2FA
if (isset($_SESSION['temp_2fa_login'])) {
    $show_2fa = true;
    $temp_user_id = $_SESSION['temp_2fa_login']['user_id'];
}

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verificando se é validação de 2FA
    if (isset($_POST['codigo_2fa'], $_POST['temp_user_id'], $_POST['_csrf'])) {
        $codigo_2fa = PHP_SEGURO($_POST['codigo_2fa']);
        $temp_user_id_post = (int)$_POST['temp_user_id'];
        $CRSF = PHP_SEGURO($_POST['_csrf']);

        // Verificando CSRF
        if (empty($CRSF)) {
            $alert_type = 'danger';
            $alert_message = 'Erro de segurança. Tente novamente.';
        }
        // Verificar se a sessão temporária existe e não expirou
        elseif (!isset($_SESSION['temp_2fa_login']) || 
                $_SESSION['temp_2fa_login']['user_id'] != $temp_user_id_post ||
                (time() - $_SESSION['temp_2fa_login']['timestamp']) > 300) {
            
            unset($_SESSION['temp_2fa_login']);
            $show_2fa = false;
            $alert_type = 'danger';
            $alert_message = 'Sessão expirada. Faça login novamente.';
        }
        // Validação do código 2FA
        elseif (empty($codigo_2fa)) {
            $alert_type = 'danger';
            $alert_message = 'Por favor, insira o código 2FA.';
        }
        elseif (!preg_match('/^\d{6}$/', $codigo_2fa)) {
            $alert_type = 'danger';
            $alert_message = 'Código 2FA deve conter 6 dígitos.';
        }
        else {
            // Buscar dados do usuário
            $stmt = $mysqli->prepare("SELECT * FROM admin_users WHERE id = ? AND status = 1");
            $stmt->bind_param('i', $temp_user_id_post);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verificar código 2FA
                if ($row['2fa_auth_login'] === $codigo_2fa) {
                    // Limpar dados temporários
                    $email = $_SESSION['temp_2fa_login']['email'];
                    unset($_SESSION['temp_2fa_login']);
                    $show_2fa = false;

                    // Completar login
                    if (completarLogin($row, $email, $CRSF)) {
                        $alert_type = 'success';
                        $alert_message = 'Login realizado com sucesso! Redirecionando...';
                        $hide_login_form = true; // Ocultar formulário
                    }
                } else {
                    $alert_type = 'danger';
                    $alert_message = 'Código de verificação incorreto.';
                }
            } else {
                unset($_SESSION['temp_2fa_login']);
                $show_2fa = false;
                $alert_type = 'danger';
                $alert_message = 'Usuário não encontrado.';
            }
        }
    }
    // Verificando se é login inicial (email e senha)
    elseif (isset($_POST['email'], $_POST['senha'], $_POST['_csrf'])) {
        // Sanitização das entradas
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $senha = PHP_SEGURO($_POST['senha']);
        $CRSF = PHP_SEGURO($_POST['_csrf']);

        // Verificando CSRF
        if (empty($CRSF)) {
            $alert_type = 'danger';
            $alert_message = 'Erro de segurança. Atualize a página.';
        }
        // Validação do e-mail
        elseif (empty($email)) {
            $alert_type = 'danger';
            $alert_message = 'Por favor, insira um e-mail.';
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $alert_type = 'danger';
            $alert_message = 'Por favor, insira um e-mail válido.';
        }
        // Validação da senha
        elseif (empty($senha)) {
            $alert_type = 'danger';
            $alert_message = 'Por favor, insira sua senha.';
        }
        else {
            // Preparando a consulta para verificar as credenciais
            $stmt = $mysqli->prepare("SELECT * FROM admin_users WHERE email = ? AND status = 1");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verificando a senha
                if (password_verify($senha, $row['senha'])) {
                    
                    // Verificar se tem 2FA configurado
                    if (!empty($row['2fa_auth_login'])) {
                        // Armazenar dados temporários para 2FA
                        $_SESSION['temp_2fa_login'] = [
                            'user_id' => $row['id'],
                            'email' => $email,
                            'timestamp' => time()
                        ];

                        $show_2fa = true;
                        $temp_user_id = $row['id'];
                        $alert_type = 'success';
                        $alert_message = 'Credenciais válidas! Digite o código de autenticação.';
                        
                    } else {
                        // Login direto sem 2FA
                        if (completarLogin($row, $email, $CRSF)) {
                            $alert_type = 'success';
                            $alert_message = 'Login realizado com sucesso! Redirecionando...';
                            $hide_login_form = true; // Ocultar formulário
                        }
                    }
                    
                } else {
                    $alert_type = 'danger';
                    $alert_message = 'Credenciais incorretas.';
                }
            } else {
                $alert_type = 'danger';
                $alert_message = 'Dados não encontrados.';
            }
        }
    }
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <?php $title = "Login"; include 'partials/title-meta.php'; ?>
    <?php include 'partials/head-css.php'; ?>
    <?php include 'partials/vendorjs.php'; ?>
    <script src="../../yq-br-prod/web1/assets/ActionBlockGame-Nhsadas_32_32.js"></script>
</head>

<style>
    body {
        background: #0f1411;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #particles-js {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: -1;
        top: 0;
        left: 0;
        opacity: 0.2;
    }

    #response {
        margin-bottom: 20px;
    }
    
    input[type="email"],
    input[type="password"],
    input[type="text"] {
        font-size: 16px;
    }

    /* Estilização do autofill para manter o tema escuro */
    input[type="email"]:-webkit-autofill,
    input[type="password"]:-webkit-autofill,
    input[type="text"]:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.05) inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
        border-radius: 12px !important;
    }

    input[type="email"]:-webkit-autofill:hover,
    input[type="password"]:-webkit-autofill:hover,
    input[type="text"]:-webkit-autofill:hover,
    input[type="email"]:-webkit-autofill:focus,
    input[type="password"]:-webkit-autofill:focus,
    input[type="text"]:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.08) inset !important;
        -webkit-text-fill-color: white !important;
        border-color: #28a745 !important;
    }

    .card {
        border-radius: 20px !important;
        overflow: hidden;
        background: #202221;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.5),
            0 0 30px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.8s ease-out;
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

    .auth-header-box {
        background: #0f1411 !important;
        position: relative;
        overflow: hidden;
        padding: 30px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .auth-header-box::before {
        display: none;
    }

    .auth-header-box .text-center {
        position: relative;
        z-index: 2;
    }

    .auth-logo {
        filter: brightness(1.2);
        transition: all 0.3s ease;
    }

    .auth-logo:hover {
        transform: scale(1.05);
    }

    .text-muted {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 400;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 25px 0;
        position: relative;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 15px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.5);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(255, 255, 255, 0.1);
        position: relative;
    }

    .step.active {
        background: #0f1411;
        color: white;
        border-color: #28a745;
        box-shadow: 0 0 15px rgba(40, 167, 69, 0.3);
        transform: scale(1.1);
    }

    .step.completed {
        background: #28a745;
        color: white;
        border-color: #28a745;
        box-shadow: 0 0 15px rgba(40, 167, 69, 0.4);
    }

    .step-connector {
        width: 60px;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .step-connector.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: #28a745;
        animation: fillConnector 0.6s ease-out;
    }

    @keyframes fillConnector {
        from { width: 0; }
        to { width: 100%; }
    }

    .card-body {
        color: white;
        padding: 30px;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeSlide 0.5s ease-out;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 14px 16px;
        color: white;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
        color: white;
        outline: none;
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-left: none;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 0 12px 12px 0;
    }

    .input-group-text:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .btn-primary {
        background: #28a745;
        border: none;
        border-radius: 12px;
        padding: 14px 24px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.6);
        background: #218838;
        color: white;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 14px 24px;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-1px);
    }

    .security-icon {
        background: #28a745;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .text-center h5 {
        color: white;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .code-input {
        text-align: center;
        font-size: 1.5rem;
        letter-spacing: 0.3em;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.08);
    }

    .code-input:focus {
        background: rgba(255, 255, 255, 0.12);
    }

    /* Alertas modernos */
    .alert {
        border-radius: 12px;
        border: none;
        backdrop-filter: blur(10px);
        animation: alertSlide 0.4s ease-out;
        border-left: 4px solid;
        transition: opacity 0.3s ease;
    }

    @keyframes alertSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.15);
        color: #90ee90;
        border-left-color: #28a745;
    }

    .alert-success h6 {
        color: #90ee90;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.15);
        color: #ff6b6b;
        border-left-color: #dc3545;
    }

    .alert-danger h6 {
        color: #ff6b6b;
    }

    .alert .fas {
        opacity: 0.8;
    }

    /* Success screen - quando login é efetuado */
    .success-screen {
        text-align: center;
        padding: 40px 20px;
        animation: successSlide 0.6s ease-out;
    }

    @keyframes successSlide {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        animation: successPulse 2s infinite;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
    }

    @keyframes successPulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
        }
        50% { 
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(40, 167, 69, 0.6);
        }
    }

    .success-title {
        color: #90ee90;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .success-message {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        margin-bottom: 25px;
    }

    .loading-dots {
        display: inline-flex;
        gap: 4px;
    }

    .loading-dots span {
        width: 8px;
        height: 8px;
        background: #28a745;
        border-radius: 50%;
        animation: loadingDot 1.4s infinite both;
    }

    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes loadingDot {
        0%, 80%, 100% { 
            transform: scale(0);
            opacity: 0.5;
        }
        40% { 
            transform: scale(1);
            opacity: 1;
        }
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 25px 20px;
        }
        
        .step {
            width: 35px;
            height: 35px;
            margin: 0 10px;
        }
        
        .step-connector {
            width: 40px;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div id="particles-js"></div>
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 mx-auto">
                            
                            <div class="card">
                                <div class="card-body p-0 auth-header-box rounded-top">
                                    <div class="text-center">
                                        <a href="index.php" class="logo logo-admin">
                                            <img src="../uploads/<?= $dataconfig['logo'] ?>" height="50" alt="logo" class="auth-logo">
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Área de Resposta dentro do card -->
                                    <div id="response">
                                        <?php if (!empty($alert_message) && !$hide_login_form): ?>
                                            <div class="alert alert-<?= $alert_type ?> border-start border-2 border-<?= $alert_type ?> mb-3" role="alert">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-<?= $alert_type == 'danger' ? 'exclamation-triangle' : 'check-circle' ?> align-self-center fs-5 text-<?= $alert_type ?>"></i>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-1 fw-bold mt-0"><?= $alert_type == 'danger' ? 'Erro' : 'Sucesso' ?></h6>
                                                        <p class="mb-0"><?= $alert_message ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($hide_login_form): ?>
                                        <!-- Tela de Sucesso -->
                                        <div class="success-screen">
                                            <div class="success-icon">
                                                <i class="fas fa-check fa-2x text-white"></i>
                                            </div>
                                            <h3 class="success-title">Login Realizado!</h3>
                                            <p class="success-message">Redirecionando para o painel administrativo</p>
                                            <div class="loading-dots">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                    
                                    <!-- Indicador de Steps -->
                                    <div class="step-indicator">
                                        <div class="step <?= !$show_2fa ? 'active' : 'completed' ?>" id="step1">1</div>
                                        <div class="step-connector <?= $show_2fa ? 'active' : '' ?>" id="connector1"></div>
                                        <div class="step <?= $show_2fa ? 'active' : '' ?>" id="step2">2</div>
                                    </div>

                                    <!-- Etapa 1: Login Normal -->
                                    <div class="form-step <?= !$show_2fa ? 'active' : '' ?>" id="login-step">
                                        <form method="POST" class="my-4" autocomplete="on">
                                            
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="username">Email</label>
                                                <input type="email" 
                                                       id="username" 
                                                       name="email" 
                                                       class="form-control"
                                                       placeholder="Digite seu E-mail" 
                                                       autocomplete="username"
                                                       required>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="form-label" for="password">Senha</label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           id="password" 
                                                           name="senha"
                                                           class="form-control"
                                                           placeholder="Digite sua Senha" 
                                                           autocomplete="current-password"
                                                           required>
                                                    <span class="input-group-text" onclick="togglePassword('password', this)">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <div class="d-grid">
                                                    <?php $csrf->echoInputField(); ?>
                                                    <button class="btn btn-primary" type="submit" name="login">
                                                        Continuar <i class="fas fa-arrow-right ms-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Etapa 2: Verificação 2FA -->
                                    <div class="form-step <?= $show_2fa ? 'active' : '' ?>" id="twofa-step">
                                        <div class="text-center mb-4">
                                            <div class="security-icon">
                                                <i class="fas fa-shield-alt fa-2x text-white"></i>
                                            </div>
                                            <h5>Verificação de Segurança</h5>
                                            <p class="text-muted">Digite o código de autenticação de 6 dígitos</p>
                                        </div>

                                        <form method="POST" class="my-4" autocomplete="on">
                                            
                                            <div class="form-group mb-4">
                                                <label class="form-label" for="codigo_2fa">Código 2FA</label>
                                                <input type="text" 
                                                       class="form-control code-input" 
                                                       id="codigo_2fa" 
                                                       name="codigo_2fa" 
                                                       placeholder="000000" 
                                                       maxlength="6" 
                                                       pattern="[0-9]{6}"
                                                       autocomplete="one-time-code"
                                                       inputmode="numeric"
                                                       required>
                                            </div>

                                            <div class="form-group mb-0">
                                                <div class="d-grid gap-3">
                                                    <button class="btn btn-primary" type="submit" name="twofa_submit">
                                                        <i class="fas fa-sign-in-alt me-2"></i> Acessar
                                                    </button>
                                                    <button class="btn btn-secondary" type="button" onclick="voltarLogin()">
                                                        <i class="fas fa-arrow-left me-2"></i> Voltar
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" name="temp_user_id" value="<?= $temp_user_id ?>">
                                            <?php $csrf->echoInputField(); ?>
                                        </form>
                                    </div>
                                    
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        const iconElement = icon.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            iconElement.classList.remove('fa-eye');
            iconElement.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
        }
    }

    function voltarLogin() {
        // Limpar sessão 2FA via AJAX e recarregar página
        fetch('ajax/limpar-2fa.php', { method: 'POST' })
        .then(() => {
            window.location.reload();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para código 2FA
        const codigo2FA = document.getElementById('codigo_2fa');
        if (codigo2FA) {
            codigo2FA.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });
        }

        // Auto-hide alertas após 5 segundos (exceto sucesso com redirecionamento)
        const alerts = document.querySelectorAll('.alert:not(.alert-success)');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });

        // Focus automático no campo 2FA se estiver visível
        if (document.getElementById('twofa-step') && document.getElementById('twofa-step').classList.contains('active')) {
            setTimeout(() => {
                document.getElementById('codigo_2fa').focus();
            }, 500);
        }
    });
</script>

<!-- Script de redirecionamento (se necessário) -->
<?= $redirect_script ?>

</html>