<?php
session_start();
include_once "services/database.php";

// Verificar se é admin logado (verificação simples)
if (!isset($_SESSION['data_adm']) || $_SESSION['data_adm']['status'] != '1') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit();
}

// Verificar se recebeu o código de convite
if (!isset($_POST['invite_code']) || empty($_POST['invite_code'])) {
    echo json_encode(['success' => false, 'message' => 'Código de convite não fornecido']);
    exit();
}

$invite_code = mysqli_real_escape_string($mysqli, $_POST['invite_code']);

try {
    // Buscar todos os indicados por este código
    $query = "SELECT id, mobile, celular, data_cad, ip_cadastro 
              FROM usuarios 
              WHERE invitation_code = '$invite_code' 
              ORDER BY data_cad DESC";
    
    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $indicados = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Formatar data para exibição
            $row['data_cad'] = date('d/m/Y H:i', strtotime($row['data_cad']));
            $indicados[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'total' => count($indicados),
            'indicados' => $indicados
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Erro na consulta: ' . mysqli_error($mysqli)
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Erro interno: ' . $e->getMessage()
    ]);
}
?>