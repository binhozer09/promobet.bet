<?php
include_once('../admin/services/database.php');
include_once('../admin/services/funcao.php');

# Função para registrar logs em um arquivo JSON
function registrarLog($requestData, $responseData) {
    // Caminho do arquivo de log
    $logFile = 'game_log.json';
    
    // Preparar os dados para registro
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'), // Adiciona um timestamp
        'request' => $requestData, // Dados da requisição
        'response' => $responseData // Resposta recebida
    ];

    // Verifica se o arquivo já existe para carregar os dados anteriores
    if (file_exists($logFile)) {
        $currentLogs = json_decode(file_get_contents($logFile), true);
        if (!is_array($currentLogs)) {
            $currentLogs = [];
        }
    } else {
        $currentLogs = [];
    }

    // Adiciona o novo log
    $currentLogs[] = $logData;

    // Salva os logs de volta no arquivo
    if (file_put_contents($logFile, json_encode($currentLogs, JSON_PRETTY_PRINT))) {
        error_log("Log registrado com sucesso: " . json_encode($logData));
    } else {
        error_log("Erro ao registrar log no arquivo: $logFile");
    }
}

# Função que será chamada quando a rota receber a requisição
function gameCallback($req) {
    global $mysqli;

    // Registrar a requisição recebida
    registrarLog(['action' => 'request_received', 'data' => $req], null);

    // Tentar realizar as operações e capturar erros
    try {
        // Extrair os dados enviados na requisição
        //$agent_code = $req["agent_code"];
        //$agent_secret = $req["agent_secret"];
        $user_code = $req["user_code"];
        $user_balance = $req["user_balance"];
        $user_total_credit = $req["user_total_credit"];
        $user_total_debit = $req["user_total_debit"];
        $game_type = $req["game_type"];

        // Verifica o tipo de jogo (neste caso, 'slot')
        if ($game_type == "slot") {
            // Extrair os dados do jogo 'slot'
            $slotData = $req["slot"];
            $provider_code = $slotData["provider_code"];
            $game_code = $slotData["game_code"];
            $round_id = $slotData["round_id"];
            $type = $slotData["type"];
            $bet = $slotData["bet"];
            $win = $slotData["win"];
            $txn_id = $slotData["txn_id"];
            $txn_type = $slotData["txn_type"];
            $is_buy = $slotData["is_buy"];
            $is_call = $slotData["is_call"];
            $user_before_balance = $slotData["user_before_balance"];
            $user_after_balance = $slotData["user_after_balance"];
            $agent_before_balance = $slotData["agent_before_balance"];
            $agent_after_balance = $slotData["agent_after_balance"];
            $created_at = $slotData["created_at"];

            // Consulta o usuário com base no `user_code`
            $sql = "SELECT id FROM usuarios WHERE mobile = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $user_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Usuário não encontrado
                $errorMsg = "INVALID_USER";
                $response = ["status" => 0, "msg" => $errorMsg];
                registrarLog(['action' => 'error', 'message' => $errorMsg, 'data' => $req], $response);
                return json_encode($response);
            }

            $row = $result->fetch_assoc();
            $id_user = $row["id"];

            // Inserir os dados no histórico de jogos (tabela `historico_play`)
            $sqlInsert = "INSERT INTO historico_play (id_user, nome_game, bet_money, win_money, txn_id, created_at, status_play) 
                          VALUES (?, ?, ?, ?, ?, NOW(), ?)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $nome_game = $game_code;  // Nome do jogo baseado no game_code
            $status_play = 1;  // Considera status como '1' para jogada bem-sucedida

            $stmtInsert->bind_param("isddsi", $id_user, $nome_game, $bet, $win, $txn_id, $status_play);
            $stmtInsert->execute();

            // Atualizar o saldo do usuário com o valor de user_after_balance
            $update_balance_query = "UPDATE usuarios SET saldo = '$user_after_balance' WHERE id = '$id_user'";

            if (mysqli_query($mysqli, $update_balance_query)) {
                $response = ["status" => 1, "user_balance" => floatval($user_after_balance)];
                registrarLog(['action' => 'update_balance_success', 'new_balance' => $user_after_balance, 'user_id' => $id_user], $response);
                return json_encode($response);
            } else {
                $response = ["status" => 0, "msg" => "FAILED_TO_UPDATE_BALANCE"];
                registrarLog(['action' => 'update_balance_failed', 'error' => mysqli_error($mysqli)], $response);
                return json_encode($response);
            }

            if ($stmtInsert->affected_rows > 0) {
                // Sucesso na inserção
                $response = ["status" => 1, "user_balance" => floatval($user_after_balance)];
                registrarLog(['action' => 'response_success', 'data' => $response], $response);
                return json_encode($response);
            } else {
                // Falha ao gravar o histórico
                $errorMsg = "DB_INSERT_ERROR";
                $response = ["status" => 0, "msg" => $errorMsg];
                registrarLog(['action' => 'error', 'message' => $errorMsg, 'data' => $req], $response);
                return json_encode($response);
            }
        } else {
            // Tipo de jogo não suportado
            $errorMsg = "UNSUPPORTED_GAME_TYPE";
            $response = ["status" => 0, "msg" => $errorMsg];
            registrarLog(['action' => 'error', 'message' => $errorMsg, 'data' => $req], $response);
            return json_encode($response);
        }

    } catch (Exception $e) {
        // Em caso de erro, registrar no log
        $response = ["status" => 0, "msg" => "ERROR", "error" => $e->getMessage()];
        registrarLog(['action' => 'exception', 'message' => $e->getMessage(), 'data' => $req], $response);
        return json_encode($response);
    }
}

# Configurar o recebimento de requisições POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegar o corpo da requisição (JSON)
    $input = file_get_contents('php://input');
    $reqData = json_decode($input, true);

    // Chamar a função `gameCallback` com os dados da requisição
    $response = gameCallback($reqData);

    // Retornar a resposta
    echo $response;
}
