<?php
// Inclua a conexão com o banco de dados
include_once "services/database.php";

function registrarLog($conn, $email, $action) {
    $sql = "INSERT INTO logs (email, action) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $email, $action);
        if ($stmt->execute()) {
            // Log registrado com sucesso, se necessário, você pode remover o echo abaixo
            //echo "Log registrado com sucesso.";
        } else {
            echo "Erro ao registrar o log: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erro ao preparar a declaração: " . $conn->error;
    }
}

//registrarLog($mysqli, 'usuario@exemplo.com', 'Ação de exemplo');
?>
