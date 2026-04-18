<?php
session_start();

// Verificar se é uma ação de logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: login.php?msg=logout');
    exit;
}

// Se já estiver logado, redireciona para o painel
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Incluir a conexão com o banco de dados
include '../admin/services/database.php';

$error = '';
$success = '';
$step = 'login'; // 'login' ou 'verify'
$temp_user_data = null;
$verification_attempts = isset($_SESSION['verification_attempts']) ? $_SESSION['verification_attempts'] : 0;

// Verificar mensagens da URL
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'logout':
            $success = 'Logout realizado com sucesso!';
            break;
        case 'expired':
            $error = 'Sua sessão expirou. Faça login novamente.';
            break;
    }
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Etapa 1: Login inicial
    if (isset($_POST['step']) && $_POST['step'] === 'login') {
        $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($mobile) || empty($password)) {
            $error = "Por favor, preencha todos os campos.";
        } else {
            // Prepara a consulta para evitar SQL Injection - busca os campos necessários
            $stmt = $mysqli->prepare("SELECT id, password, statusaff, mobile, celular FROM usuarios WHERE mobile = ?");
            if (!$stmt) {
                $error = "Erro na preparação da consulta: " . $mysqli->error;
            } else {
                $stmt->bind_param("s", $mobile);
                $stmt->execute();
                $result = $stmt->get_result();

                // Verifica se o usuário foi encontrado
                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    // Verifica a senha utilizando password_verify
                    if (password_verify($password, $user['password'])) {
                        // Credenciais corretas - avança para verificação
                        $step = 'verify';
                        $temp_user_data = $user;
                        $_SESSION['temp_user_data'] = $user; // Armazena temporariamente
                        $_SESSION['verification_attempts'] = 0; // Reset tentativas
                    } else {
                        $error = "Senha incorreta.";
                    }
                } else {
                    $error = "Usuário não encontrado.";
                }
                $stmt->close();
            }
        }
    }
    
    // Etapa 2: Verificação do celular
    elseif (isset($_POST['step']) && $_POST['step'] === 'verify') {
        $phone_verification = isset($_POST['phone_verification']) ? trim($_POST['phone_verification']) : '';
        
        if (isset($_SESSION['temp_user_data'])) {
            $temp_user_data = $_SESSION['temp_user_data'];
            
            if (empty($phone_verification)) {
                $error = "Por favor, informe o número de celular.";
                $step = 'verify';
            } else {
                // Remove formatação do telefone digitado (parênteses, espaços, hífen)
                $phone_clean = preg_replace('/[^0-9]/', '', $phone_verification);
                
                // Pega o telefone da coluna 'celular'
                $phone_from_db = $temp_user_data['celular'];
                $phone_db_clean = preg_replace('/[^0-9]/', '', $phone_from_db);
                
                // Para debug (remova em produção)
                error_log("Digitado: $phone_clean | Banco: $phone_db_clean");
                
                // Verifica se o celular informado corresponde ao cadastrado
                if ($phone_clean === $phone_db_clean) {
                    // Verificar se o usuário tem afiliação ativa antes de criar a sessão
                    $statusaff = (int)$temp_user_data['statusaff'];
                    
                    if ($statusaff !== 1) {
                        // Usuário sem afiliação ativa - limpa dados temporários
                        unset($_SESSION['temp_user_data']);
                        unset($_SESSION['verification_attempts']);
                        
                        // Redireciona para uma página específica de erro de afiliação
                        header("Location: login.php?error=no_affiliation&status=" . $statusaff);
                        exit;
                    }
                    
                    // Autenticação completa bem-sucedida
                    $_SESSION['user_id'] = $temp_user_data['id'];
                    $_SESSION['statusaff'] = $temp_user_data['statusaff'];
                    $_SESSION['login_time'] = time(); // Para controle de sessão
                    
                    unset($_SESSION['temp_user_data']); // Remove dados temporários
                    unset($_SESSION['verification_attempts']); // Limpa tentativas
                    
                    header("Location: index.php");
                    exit;
                } else {
                    // Incrementa tentativas de verificação
                    $_SESSION['verification_attempts'] = $verification_attempts + 1;
                    
                    // Bloqueia após muitas tentativas
                    if ($_SESSION['verification_attempts'] >= 5) {
                        unset($_SESSION['temp_user_data']);
                        unset($_SESSION['verification_attempts']);
                        $error = "Muitas tentativas incorretas. Faça login novamente.";
                        $step = 'login';
                    } else {
                        $error = "Número de celular não confere com o cadastrado. Verifique se digitou corretamente.";
                        $step = 'verify';
                    }
                }
            }
        } else {
            $error = "Sessão expirada. Faça login novamente.";
            $step = 'login';
        }
    }
    
    // Limpar dados temporários via AJAX
    elseif (isset($_POST['action']) && $_POST['action'] === 'clear_temp_data') {
        unset($_SESSION['temp_user_data']);
        unset($_SESSION['verification_attempts']);
        echo json_encode(['status' => 'success']);
        exit;
    }
}

// Verificar erro específico de afiliação da URL
if (isset($_GET['error']) && $_GET['error'] === 'no_affiliation') {
    $statusaff = isset($_GET['status']) ? (int)$_GET['status'] : 0;
    switch($statusaff) {
        case 0:
            $error = "Sua afiliação está inativa. Entre em contato com o suporte para ativá-la.";
            break;
        case 2:
            $error = "Sua afiliação foi suspensa. Entre em contato com o suporte.";
            break;
        case 3:
            $error = "Sua afiliação foi cancelada. Entre em contato para renovação.";
            break;
        default:
            $error = "Sua conta não possui afiliação ativa. Entre em contato com o suporte.";
    }
}

// Se há dados temporários na sessão, vai direto para verificação
if (isset($_SESSION['temp_user_data']) && $step === 'login') {
    $step = 'verify';
    $temp_user_data = $_SESSION['temp_user_data'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Afiliados - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --secondary-color: #764ba2;
            --success-color: #48bb78;
            --danger-color: #f56565;
            --warning-color: #ed8936;
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #262626;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-tertiary: #666666;
            --border-color: #333333;
            --shadow-color: rgba(0, 0, 0, 0.5);
            --glow-primary: 0 0 30px rgba(102, 126, 234, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(72, 187, 120, 0.05) 0%, transparent 50%);
            animation: backgroundShift 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes backgroundShift {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 
                0 20px 40px var(--shadow-color),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            padding: 3rem 2.5rem;
            width: 100%;
            position: relative;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: cardEntrance 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 24px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.5), rgba(118, 75, 162, 0.5));
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .auth-card:hover::before {
            opacity: 1;
        }

        .auth-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 32px 64px var(--shadow-color),
                var(--glow-primary),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .auth-header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: var(--glow-primary);
            animation: logoFloat 3s ease-in-out infinite;
        }

        .auth-header .logo i {
            font-size: 2rem;
            color: white;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .auth-header h1 {
            color: var(--text-primary);
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-header p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            font-weight: 400;
        }

        .step-indicator {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .step.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: var(--glow-primary);
            animation: stepPulse 2s ease-in-out infinite;
        }

        .step.completed {
            background: var(--success-color);
            color: white;
        }

        .step:not(.active):not(.completed) {
            background: var(--bg-tertiary);
            color: var(--text-tertiary);
            border: 2px solid var(--border-color);
        }

        @keyframes stepPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .step-connector {
            width: 40px;
            height: 2px;
            background: var(--border-color);
            transition: all 0.3s ease;
        }

        .step-connector.completed {
            background: var(--success-color);
        }

        .error-message {
            background: rgba(245, 101, 101, 0.1);
            color: var(--danger-color);
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border: 1px solid rgba(245, 101, 101, 0.2);
            animation: errorShake 0.5s ease-in-out;
        }

        .success-message {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border: 1px solid rgba(72, 187, 120, 0.2);
            animation: successSlide 0.5s ease-in-out;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @keyframes successSlide {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .form-group label {
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.25rem;
            color: var(--text-tertiary);
            z-index: 2;
            font-size: 1.125rem;
            transition: all 0.3s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 1rem 1.25rem 1rem 3.5rem;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-tertiary);
            color: var(--text-primary);
            position: relative;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: var(--bg-secondary);
            transform: translateY(-2px);
        }

        .input-wrapper input:focus + i {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .input-wrapper input::placeholder {
            color: var(--text-tertiary);
            font-weight: 400;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: var(--text-secondary);
            user-select: none;
            transition: color 0.3s ease;
        }

        .checkbox-container:hover {
            color: var(--text-primary);
        }

        .checkbox-container input {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: var(--primary-color);
            border-radius: 4px;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 32px rgba(102, 126, 234, 0.4),
                var(--glow-primary);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-block {
            width: 100%;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .verification-info {
            background: rgba(72, 187, 120, 0.1);
            border: 1px solid rgba(72, 187, 120, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: var(--success-color);
            font-size: 0.875rem;
            text-align: center;
        }

        .verification-info i {
            margin-right: 0.5rem;
        }

        .warning-info {
            background: rgba(237, 137, 54, 0.1);
            border: 1px solid rgba(237, 137, 54, 0.2);
            color: var(--warning-color);
        }

        /* Loading state */
        .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                max-width: 380px;
            }
            
            .auth-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 0.5rem;
            }

            .auth-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .auth-header h1 {
                font-size: 1.875rem;
            }

            .auth-header .logo {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .auth-header .logo i {
                font-size: 1.5rem;
            }

            .input-wrapper input {
                padding: 0.875rem 1rem 0.875rem 3rem;
                font-size: 0.9rem;
            }

            .input-wrapper i {
                left: 1rem;
                font-size: 1rem;
            }

            .btn {
                padding: 0.875rem 1.5rem;
                font-size: 0.9rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.25rem;
            }

            .auth-header {
                margin-bottom: 2rem;
            }

            .step-indicator {
                margin-bottom: 1.5rem;
            }

            .step {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }

            .step-connector {
                width: 30px;
            }
        }

        /* Acessibilidade */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h1>Acesso Seguro</h1>
                <p>Painel de Afiliados</p>
            </div>

            <!-- Indicador de Etapas -->
            <div class="step-indicator">
                <div class="step <?php echo $step === 'login' ? 'active' : ($step === 'verify' ? 'completed' : ''); ?>">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-connector <?php echo $step === 'verify' ? 'completed' : ''; ?>"></div>
                <div class="step <?php echo $step === 'verify' ? 'active' : ''; ?>">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>

            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($step === 'login'): ?>
                <!-- Formulário de Login -->
                <form class="auth-form" method="post" action="login.php" id="loginForm">
                    <input type="hidden" name="step" value="login">
                    
                    <div class="form-group">
                        <label for="mobile">Nome de Usuário</label>
                        <div class="input-wrapper">
                            <input type="text" id="mobile" name="mobile" required 
                                   placeholder="Digite seu nome de usuário" 
                                   value="<?php echo isset($mobile) ? htmlspecialchars($mobile) : ''; ?>">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required 
                                   placeholder="Digite sua senha">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            Manter conectado por 30 dias
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                        <i class="fas fa-arrow-right"></i>
                        Continuar
                    </button>
                </form>

            <?php else: ?>
                <!-- Formulário de Verificação -->
                <div class="verification-info">
                    <i class="fas fa-info-circle"></i>
                    Por segurança, confirme o número de celular <strong>exato</strong> vinculado à sua conta
                </div>

                <?php if ($verification_attempts >= 2 && isset($temp_user_data)): ?>
                    <?php 
                    // Pega o telefone da coluna 'celular'
                    $phone_for_hint = $temp_user_data['celular'];
                    // Remove formatação e pega só números
                    $phone_clean_hint = preg_replace('/[^0-9]/', '', $phone_for_hint);
                    ?>
                    <div class="verification-info warning-info">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Dica:</strong> Seu número termina em 
                        <strong><?php echo substr($phone_clean_hint, -4); ?></strong>
                    </div>
                <?php endif; ?>

                <?php if ($verification_attempts >= 3): ?>
                    <div class="verification-info warning-info">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atenção:</strong> Restam <?php echo 5 - $verification_attempts; ?> tentativas antes do bloqueio
                    </div>
                <?php endif; ?>

                <form class="auth-form" method="post" action="login.php" id="verifyForm">
                    <input type="hidden" name="step" value="verify">
                    
                    <div class="form-group">
                        <label for="phone_verification">Número de Celular</label>
                        <div class="input-wrapper">
                            <input type="text" id="phone_verification" name="phone_verification" required 
                                   placeholder="Digite exatamente como cadastrado"
                                   pattern="[0-9\-\(\)\s\+]*"
                                   title="Digite o número exato cadastrado na sua conta">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 0.8rem; margin-top: 0.25rem; display: block;">
                            <i class="fas fa-lightbulb" style="margin-right: 0.25rem;"></i>
                            Digite o número completo com DDD, exatamente como foi cadastrado
                        </small>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="backBtn" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </button>
                        <button type="submit" class="btn btn-primary" id="verifyBtn" style="flex: 1;">
                            <i class="fas fa-check"></i>
                            Verificar e Entrar
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- Link de suporte -->
            <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <p style="color: var(--text-tertiary); font-size: 0.875rem; margin-bottom: 1rem;">
                    Problemas para acessar?
                </p>
                <a href="mailto:suporte@seusite.com" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                    <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                    Contatar Suporte
                </a>
            </div>
        </div>
    </div>

    <script>
        // Função para voltar à etapa de login
        function goBack() {
            // Remove dados temporários da sessão via AJAX
            fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=clear_temp_data'
            }).then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload();
                }
            }).catch(() => {
                // Fallback se AJAX falhar
                window.location.href = 'login.php';
            });
        }

        // Animações e efeitos
        document.addEventListener('DOMContentLoaded', function() {
            // Remove mensagens após 5 segundos
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'all 0.5s ease';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 5000);
            });

            // Adiciona efeitos de loading nos botões
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        const icon = submitBtn.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-spinner';
                        }
                    }
                });
            });

            // Máscara para telefone
            const phoneInput = document.getElementById('phone_verification');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    
                    // Limita a 11 dígitos
                    if (value.length > 11) {
                        value = value.substring(0, 11);
                    }
                    
                    // Aplica a máscara
                    if (value.length >= 11) {
                        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    } else if (value.length >= 7) {
                        value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                    } else if (value.length >= 3) {
                        value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                    } else if (value.length > 0) {
                        value = value.replace(/(\d{0,2})/, '($1');
                    }
                    
                    e.target.value = value;
                });

                // Adiciona dica visual sobre a formatação
                phoneInput.addEventListener('focus', function() {
                    if (!this.value) {
                        this.placeholder = 'Ex: (84) 99999-1234';
                    }
                });

                phoneInput.addEventListener('blur', function() {
                    this.placeholder = 'Digite o celular cadastrado na conta';
                });
            }

            // Efeitos visuais nos inputs
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Animação de entrada dos elementos
            const elements = document.querySelectorAll('.form-group, .btn, .verification-info');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Prevenção de spam de cliques
        let isSubmitting = false;
        document.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            
            // Reset após 3 segundos
            setTimeout(() => {
                isSubmitting = false;
            }, 3000);
        });
    </script>
</body>
</html>