<?php
// Verificar se o arquivo database.php existe antes de incluir
$databasePath = "services/database.php";

if (!file_exists($databasePath)) {
    error_log("ERRO: Arquivo database.php não encontrado em: " . $databasePath);
    die("Erro: Arquivo de configuração do banco não encontrado.");
}

// Incluir a conexão com o banco de dados
include_once $databasePath;

// Verificar se a conexão está disponível
if (!isset($mysqli) || !$mysqli) {
    error_log("ERRO: Conexão com banco de dados não disponível");
    die("Erro: Conexão com banco de dados não disponível.");
}

/**
 * Registra uma ação no log do sistema
 * 
 * @param mysqli $conn Conexão com o banco de dados
 * @param string $email Email do usuário
 * @param string $action Ação realizada
 * @return bool Retorna true se o log foi registrado com sucesso
 */
function registrarLog($conn, $email, $action) {
    // Validar parâmetros
    if (!$conn || empty($email) || empty($action)) {
        error_log("ERRO registrarLog: Parâmetros inválidos");
        return false;
    }
    
    // Verificar se a conexão está ativa
    if ($conn->connect_error) {
        error_log("ERRO registrarLog: Conexão com banco falhou - " . $conn->connect_error);
        return false;
    }
    
    // Preparar query com verificação de erro
    $sql = "INSERT INTO logs (email, action) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("ERRO registrarLog: Falha ao preparar statement - " . $conn->error);
        return false;
    }
    
    // Vincular parâmetros
    $stmt->bind_param("ss", $email, $action);
    
    // Executar query
    if (!$stmt->execute()) {
        error_log("ERRO registrarLog: Falha ao executar query - " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    // Sucesso
    $stmt->close();
    return true;
}

// Exemplo de uso (comentado para produção):
// if (registrarLog($mysqli, 'usuario@exemplo.com', 'Ação de exemplo')) {
//     echo "Log registrado com sucesso!";
// } else {
//     echo "Erro ao registrar log.";
// }
?>