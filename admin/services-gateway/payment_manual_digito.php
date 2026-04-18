<?php
// Melhor configuração de erros - Para produção, mantenha display_errors desativado
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// Inclua o arquivo de conexão ao banco de dados
include_once('../services/database.php');
include_once('../services/funcao.php');
include_once('../services/crud.php');// Se houver funções adicionais de segurança, como PHP_SEGURO()

// Função para determinar o tipo de chave PIX
function identificarTipoChavePix($chavepix)
{
    // Verifica se é um número de telefone (10 ou 11 dígitos)
    if (preg_match('/^\d{10,11}$/', $chavepix)) {
        return 'phoneNumber'; // Telefone (10 ou 11 dígitos)
    } 
    // Verifica se é um CPF
    elseif (preg_match('/^\d{11}$/', $chavepix)) {
        return 'document'; // CPF
    } 
    // Verifica se é um CNPJ
    elseif (preg_match('/^\d{14}$/', $chavepix)) {
        return 'document'; // CNPJ
    } 
    // Verifica se é um e-mail
    elseif (filter_var($chavepix, FILTER_VALIDATE_EMAIL)) {
        return 'email'; // E-mail
    } 
    // Verifica se é uma chave aleatória
    elseif (preg_match('/^[0-9a-f]{32}$/i', $chavepix)) {
        return 'randomKey'; // Chave aleatória
    } 
    // Caso padrão se a chave não for identificada corretamente
    else {
        return 'invalid'; // Indica que a chave não é válida
    }
}

function loginDigitoPay()
{
    global $data_suitpay;
    // URL da API de login da Digito Pay
    $urlLogin = 'https://api.digitopayoficial.com.br/api/token/api';
    
    // Dados de login (substitua com suas credenciais)
    $dataLogin = array(
        "clientId" => $data_suitpay['client_id'],  // Coloque seu e-mail cadastrado na Digito Pay
        "secret" => $data_suitpay['client_secret']  // Coloque sua senha
    );

    // Requisição HTTP para obter o token
    $ch = curl_init($urlLogin);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataLogin));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar a resposta JSON
    $responseDecoded = json_decode($response, true);

    // Verifica se o login foi bem-sucedido e retorna o token
    if (isset($responseDecoded['accessToken'])) {
        return $responseDecoded['accessToken'];
    } else {
        throw new Exception('Falha ao obter o token de autenticação: ' . $response);
    }
}

// Verifica se o parâmetro 'id' foi passado corretamente
if (isset($_GET['id'])) {
    $id = PHP_SEGURO($_GET['id']); // Função para sanitização de entrada, se existente
    $token = loginDigitoPay();
    // Preparar a consulta para buscar o valor e a chave Pix
    $sql = "SELECT valor, pix FROM solicitacao_saques WHERE transacao_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($valor, $chavepix1);
        $stmt->fetch();
        $stmt->close();
        
        $chavepix = localizarchavepix($chavepix1);

        // Verifica se encontrou um saque válido
        if ($valor && $chavepix) {
            $valor = number_format($valor, 2, '.', '');

            // Identificar o tipo da chave PIX automaticamente
            $tipoChavePix = identificarTipoChavePix($chavepix);
            
            // Validar o tipo de chave
            if ($tipoChavePix === 'invalid') {
                echo json_encode(["success" => false, "message" => "Chave PIX inválida."]);
                exit;
            }

            // Consulta as credenciais do Suitpay
            $sql_credenciais = "SELECT client_id, client_secret FROM suitpay WHERE id = 1";
            if ($stmt_credenciais = $mysqli->prepare($sql_credenciais)) {
                $stmt_credenciais->execute();
                $stmt_credenciais->bind_result($ci, $cs);
                $stmt_credenciais->fetch();
                $stmt_credenciais->close();

                // Verifica se as credenciais foram encontradas
                if ($ci && $cs) {
                #===============================================#
                 $arraynome = array("Kauan Sousa Ferreira", "Martim Alves Cardoso", "Luiza Cardoso Cavalcanti", "Letícia Gomes Silva", "Erick Rodrigues Melo", "Brenda Ferreira Souza", "Vitor Silva Melo", "Isabella Pinto Souza");
                 $randomKey = array_rand($arraynome);
                 $nomealeatorio = $arraynome[$randomKey];
                 #===============================================#
                    // Inicializar o cURL para fazer o pagamento via Suitpay
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.digitopayoficial.com.br/api/withdraw',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => json_encode([
                            'paymentOptions' => array("PIX"),
                            'person' => array(
                                'name' => $nomealeatorio,
                                'pixKeyTypes' => $tipoChavePix,
                                "pixKey" => $chavepix
                            ),
                            'value' => $valor // O valor formatado
                        ]),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $token,
                            'Content-Type: application/json'
                        ),
                    ));

                    $enviarpagamento = curl_exec($curl);
                    $curl_error = curl_error($curl);
                    curl_close($curl);

                    // Verificar se houve erro no cURL
                    if ($curl_error) {
                        echo json_encode(["success" => false, "message" => "Erro na comunicação com o Suitpay: " . $curl_error]);
                        exit;
                    }

                    $responsejson = json_decode($enviarpagamento, true);
                    $message_gateway = $responsejson['message'] ?? '';
                    $response_gateway = $responsejson['success'] ?? '';

                    // Verificar se a resposta do gateway foi positiva
                    if ($response_gateway === true) {
                        $sql_update = "UPDATE solicitacao_saques SET status = 1 WHERE transacao_id = ?";
                        if ($stmt_update = $mysqli->prepare($sql_update)) {
                            $stmt_update->bind_param("s", $id);
                            $stmt_update->execute();

                            if ($stmt_update->affected_rows > 0) {
                                echo json_encode(["success" => true, "message" => "Saque aprovado com sucesso."]);
                                //header('Location: saques_pendentes');
                                exit;
                            } else {
                                echo json_encode(["success" => false, "message" => "Erro ao atualizar o status do pagamento no banco de dados."]);
                                exit;
                            }

                            $stmt_update->close();
                        } else {
                            echo json_encode(["success" => false, "message" => "Erro ao preparar a query de atualização."]);
                            exit;
                        }
                    } else {
                        echo json_encode(["success" => false, "message" => "Erro do gateway: $response_gateway - $message_gateway - $tipoChavePix"]);
                        exit;
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Credenciais 'ci' ou 'cs' não encontradas na tabela 'suitpay'."]);
                    exit;
                }
            } else {
                echo json_encode(["success" => false, "message" => "Erro ao preparar a query para buscar credenciais."]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "Saque não encontrado ou parâmetros inválidos."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao preparar a query de busca de saque."]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Parâmetro 'id' não fornecido."]);
    exit;
}
?>