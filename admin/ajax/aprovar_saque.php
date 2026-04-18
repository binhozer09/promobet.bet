<?php
// Configuração inicial
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// Incluir arquivos necessários
require_once '../services/database.php';
require_once '../services/funcao.php';
require_once '../services/crud.php'; // Se tiver a função PHP_SEGURO()


// Verificar se todos os dados necessários foram enviados
if (!isset($_POST['aprovar']) || !isset($_POST['id_saque']) || !isset($_POST['transacao_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Dados incompletos.'
    ]);
    exit;
}

// Obter IDs do saque
$id_saque = PHP_SEGURO($_POST['id_saque']); // Função para sanitização de entrada, se existente
$transacao_id = PHP_SEGURO($_POST['transacao_id']);

// Atualizar o status do saque no banco de dados
global $mysqli;

// Verificar se o saque existe e ainda está pendente
$query_check = "SELECT id FROM solicitacao_saques WHERE id = ? AND status = '0'";
$stmt_check = $mysqli->prepare($query_check);

if ($stmt_check) {
    $stmt_check->bind_param('i', $id_saque);
    $stmt_check->execute();
    $stmt_check->store_result();
    
    if ($stmt_check->num_rows == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Saque não encontrado ou já processado.'
        ]);
        $stmt_check->close();
        exit;
    }
    $stmt_check->close();
}

// Atualizar o status para aprovado (1)
$query = "UPDATE solicitacao_saques SET status = '1' WHERE id = ?";
$stmt = $mysqli->prepare($query);

if ($stmt) {
    $stmt->bind_param('i', $id_saque);
    $resultado = $stmt->execute();
    
    if ($resultado) {
        // Sucesso na atualização
        echo json_encode([
            'status' => 'success',
            'message' => 'Saque aprovado com sucesso!'
        ]);
    } else {
        // Erro na atualização
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao atualizar o status do saque: ' . $mysqli->error
        ]);
    }
    
    $stmt->close();
} else {
    // Erro ao preparar a consulta
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro na preparação da consulta: ' . $mysqli->error
    ]);
}
?>