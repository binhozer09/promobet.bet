<?php

session_start();
include_once('../admin/services/database.php');
include_once('../admin/services/funcao.php');
include_once('../admin/services/crud.php');
include_once('suitpay.php');
global $mysqli;

        function webhook() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo 'erro404';
            //show_404();
        }
        $json_data = file_get_contents('php://input');

        $data = json_decode($json_data, true);

        if (!isset($data['idTransaction']) || !isset($data['typeTransaction']) || !isset($data['statusTransaction'])) {
            echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
            return;
        }

        // Processa os dados conforme necessário
            $idTransaction = PHP_SEGURO($data['idTransaction']);     		 // id da transação
            $typeTransaction = PHP_SEGURO($data['typeTransaction']); 		// tipo de transação
            $statusTransaction = PHP_SEGURO($data['statusTransaction']);


        if ($statusTransaction === 'PAID_OUT') {

            $att_transacao = att_paymentpix($idTransaction);

            //$retorna_insert_saldo_suit_pay = enviarSaldo($retornaUSER['email'], $data['valor']);

        }

        echo json_encode(['status' => 'success']);
    }