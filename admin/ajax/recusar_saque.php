<?php
#======================================#
ini_set('display_errors', 0);
error_reporting(E_ALL);
#======================================#
session_start();
include_once('../services/database.php');
include_once('../services/funcao.php');
include_once('../services/crud-adm.php');
include_once('../services/crud.php');
include_once('../logs/registrar_logs.php');
include_once('../services/checa_login_adm.php');
include_once("../services/CSRF_Protect.php");
$csrf = new CSRF_Protect();
#======================================#
#expulsa user
checa_login_adm();
#======================================#
if (isset($_POST['att-pay']) && isset($_POST['_csrf']) && isset($_POST['id_pay']) && isset($_POST['email_reprovado']) && isset($_POST['valor_reprovado'])) {
    #----------------------------------------------#
    $id_pay = PHP_SEGURO($_POST['id_pay']);
    $email_pay = PHP_SEGURO($_POST['email_reprovado']);
    $valor_pay = PHP_SEGURO($_POST['valor_reprovado']);
    $CSRF = PHP_SEGURO($_POST['_csrf']);
    $data = date('Y-m-d H:i:s');
    #----------------------------------------------#
    // Verifica se o CSRF está vazio
    if (empty($CSRF)) {
        echo json_encode(['status' => 'error', 'message' => 'Houve um erro ao obter dados. Atualize sua página.']);
        exit;
    }
    
    // Executa a query de atualização
    $sql = $mysqli->prepare("UPDATE solicitacao_saques SET data_att=?, status=2 WHERE id=?");
    $sql->bind_param("si", $data, $id_pay);
    
    if ($sql->execute()) {
        // Buscar o ID do usuário, valor e tipo do saque
        $query = $mysqli->prepare("SELECT id_user, valor, tipo_saque FROM solicitacao_saques WHERE id = ?");
        $query->bind_param("i", $id_pay);
        $query->execute();
        $res = $query->get_result();
        $row = $res->fetch_assoc();
    
        if ($row) {
            $user_id = $row['id_user'];
            $valor_saque = floatval($row['valor']);
            $tipo_saque = intval($row['tipo_saque']);
    
            // Devolver o valor para a carteira correta baseado no tipo_saque
            if ($tipo_saque === 1) {
                // Saque de afiliados (tipo_saque = 1) → devolver para saldo_afiliados
                $update_saldo = $mysqli->prepare("UPDATE usuarios SET saldo_afiliados = saldo_afiliados + ? WHERE id = ?");
                $tipo_carteira = "saldo de afiliados";
            } else {
                // Saque do cassino (tipo_saque = 0) → devolver para saldo normal
                $update_saldo = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
                $tipo_carteira = "saldo principal";
            }
            
            $update_saldo->bind_param("di", $valor_saque, $user_id);
            
            if ($update_saldo->execute()) {
                registrarLog($mysqli, $_SESSION['data_adm']['email'], 
                    'Recusou o saque ID #' . $id_pay . 
                    ' (tipo: ' . ($tipo_saque === 1 ? 'afiliados' : 'cassino') . ')' .
                    ' e devolveu R$ ' . number_format($valor_saque, 2, ',', '.') . 
                    ' para o ' . $tipo_carteira . ' do usuário ID ' . $user_id
                );
    
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Saque recusado e valor devolvido para o ' . $tipo_carteira . ' com sucesso!'
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao devolver o valor para a carteira.']);
            }
            
            $update_saldo->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Não foi possível localizar a solicitação de saque.']);
        }
        
        $query->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o status do saque.']);
    }
    
    $sql->close();
    $mysqli->close();
    exit;
}
?>