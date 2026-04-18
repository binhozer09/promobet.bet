<?php
session_set_cookie_params(60 * 60 * 24 * 5); // 5 dias em segundos
session_start();
include_once('../logs/registrar_logs.php');
include_once('../services/database.php');
include_once('../services/funcao.php');

// Função para criar alertas que aparecem na sessão
function criarAlertaSessao($tipo, $titulo, $mensagem) {
    $_SESSION['alert'] = [
        'tipo' => $tipo,
        'titulo' => $titulo,
        'mensagem' => $mensagem
    ];
}

// Função para redirecionar com delay (para casos específicos)
function redirecionarComDelay($url, $delay = 2000) {
    echo "<script>setTimeout(() => window.location.href = '$url', $delay);</script>";
}

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
    global $mysqli, $painel_adm;
    
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
    registrarLog($mysqli, $email, "<span class='status-badge green' style='display: inline-block;'><i class='fa fa-sign-out'></i></span> Logou no painel admin");

    // Enviando notificação para o Telegram
    //enviarNotificacaoTelegram($emailbd);

    // Redirecionando para o painel
    criarAlertaSessao('success', 'Sucesso', 'Login realizado com sucesso! Redirecionando...');
    redirecionarComDelay($painel_adm, 2000);
}

// Verificando se é validação de 2FA
if (isset($_POST['codigo_2fa'], $_POST['temp_user_id'], $_POST['_csrf'])) {
    $codigo_2fa = PHP_SEGURO($_POST['codigo_2fa']);
    $temp_user_id = (int)$_POST['temp_user_id'];
    $CRSF = PHP_SEGURO($_POST['_csrf']);

    // Verificando CSRF
    if (empty($CRSF)) {
        criarAlertaSessao('danger', 'Erro', 'Erro de segurança. Tente novamente.');
        header('Location: ../login.php');
        exit;
    }

    // Verificar se a sessão temporária existe e não expirou
    if (!isset($_SESSION['temp_2fa_login']) || 
        $_SESSION['temp_2fa_login']['user_id'] != $temp_user_id ||
        (time() - $_SESSION['temp_2fa_login']['timestamp']) > 300) { // 5 minutos
        
        unset($_SESSION['temp_2fa_login']);
        criarAlertaSessao('danger', 'Erro', 'Sessão expirada. Faça login novamente.');
        header('Location: ../login.php');
        exit;
    }

    // Validação do código 2FA
    if (empty($codigo_2fa)) {
        criarAlertaSessao('danger', 'Erro', 'Por favor, insira o código 2FA.');
        header('Location: ../login.php');
        exit;
    }

    if (!preg_match('/^\d{6}$/', $codigo_2fa)) {
        criarAlertaSessao('danger', 'Erro', 'Código 2FA deve conter 6 dígitos.');
        header('Location: ../login.php');
        exit;
    }

    // Buscar dados do usuário
    $stmt = $mysqli->prepare("SELECT * FROM admin_users WHERE id = ? AND status = 1");
    $stmt->bind_param('i', $temp_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar código 2FA
        if ($row['2fa_auth_login'] === $codigo_2fa) {
            // Limpar dados temporários
            $email = $_SESSION['temp_2fa_login']['email'];
            unset($_SESSION['temp_2fa_login']);

            // Completar login
            completarLogin($row, $email, $CRSF);
        } else {
            criarAlertaSessao('danger', 'Erro', 'Código de verificação incorreto.');
            header('Location: ../login.php');
            exit;
        }
    } else {
        unset($_SESSION['temp_2fa_login']);
        criarAlertaSessao('danger', 'Erro', 'Usuário não encontrado.');
        header('Location: ../login.php');
        exit;
    }
    exit;
}

// Verificando se é login inicial (email e senha)
if (isset($_POST['email'], $_POST['senha'], $_POST['_csrf'])) {
    // Sanitização das entradas
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = PHP_SEGURO($_POST['senha']);
    $CRSF = PHP_SEGURO($_POST['_csrf']);

    // Verificando CSRF
    if (empty($CRSF)) {
        criarAlertaSessao('danger', 'Erro', 'Erro de segurança. Atualize a página.');
        header('Location: ../login.php');
        exit;
    }

    // Validação do e-mail
    if (empty($email)) {
        criarAlertaSessao('danger', 'Erro', 'Por favor, insira um e-mail.');
        header('Location: ../login.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        criarAlertaSessao('danger', 'Erro', 'Por favor, insira um e-mail válido.');
        header('Location: ../login.php');
        exit;
    }

    // Validação da senha
    if (empty($senha)) {
        criarAlertaSessao('danger', 'Erro', 'Por favor, insira sua senha.');
        header('Location: ../login.php');
        exit;
    }

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

                // Redirecionar de volta para a página de login (que vai mostrar o 2FA)
                header('Location: ../login.php');
                exit;
                
            } else {
                // Login direto sem 2FA
                completarLogin($row, $email, $CRSF);
            }
            
        } else {
            criarAlertaSessao('danger', 'Erro', 'Credenciais incorretas.');
            header('Location: ../login.php');
            exit;
        }
    } else {
        criarAlertaSessao('danger', 'Erro', 'Dados não encontrados.');
        header('Location: ../login.php');
        exit;
    }
} else {
    criarAlertaSessao('danger', 'Erro', 'Dados incompletos no formulário.');
    header('Location: ../login.php');
    exit;
}
?>