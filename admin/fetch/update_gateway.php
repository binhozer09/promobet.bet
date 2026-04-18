<?php
session_start();
include_once "../services/database.php";
include_once "../services/CSRF_Protect.php";
$csrf = new CSRF_Protect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gateway = $_POST['gateway'];
    $status = (int)$_POST['status'];

    // Função para atualizar o status
    update_gateway_status($gateway, $status);
}

function update_gateway_status($gateway, $status) {
    global $mysqli;

    if ($gateway === 'suitpay') {
        $qry = "UPDATE suitpay SET ativo = ? WHERE id = 1";
    } else {
        $qry = "UPDATE digitopay SET ativo = ? WHERE id = 1";
    }

    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param("i", $status);
    return $stmt->execute();
}
