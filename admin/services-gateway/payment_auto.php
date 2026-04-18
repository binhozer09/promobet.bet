<?php
// Melhor configuração de erros - Para produção, mantenha display_errors desativado
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

// Inclua o arquivo de conexão ao banco de dados
include_once('../services/database.php');
include_once('../services/funcao.php');
include_once('../services/crud.php'); // Se houver funções adicionais de segurança, como PHP_SEGURO()

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


// Obtendo a configuração
$qry = "SELECT * FROM config WHERE id=1";
$res = $mysqli->query($qry);
$data = $res->fetch_assoc();

// Obtendo as credenciais Suitpay
$sql = "SELECT client_id, client_secret FROM suitpay WHERE id = 1";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ci = $row['client_id'];
    $cs = $row['client_secret'];

    $chavepix1 = $_POST['chavepix'] ?? '';
    // Remover pontos e traços da chave pix
    // $chavepix = str_replace(['.', '-'], '', $chavepix1);
    $chavepix = localizarchavepix($chavepix1);
    $valor = floatval($_POST['valor'] ?? 0);  // Convertendo o valor para float
    $id = $_POST['id'] ?? '';
    $token = loginDigitoPay();

    if ($chavepix && $valor > 0 && $valor <= $data['saque_automatico']) {
        $valor = number_format($valor, 2, '.', '');
        $filename = 'used_ids.json';
        $used_ids = [];

        // Verificando e lendo o arquivo JSON
        if (file_exists($filename)) {
            $file_content = file_get_contents($filename);
            if ($file_content) {
                $used_ids = json_decode($file_content, true);
            }
        }

        // Verificando se o ID já foi usado
        if (in_array($id, $used_ids)) {
            die("Anti-fraude acionado: Este ID já foi usado.");
        }

        // Adicionando o novo ID e atualizando o arquivo
        if (!empty($id)) {
            $used_ids[] = $id;
            file_put_contents($filename, json_encode($used_ids, JSON_PRETTY_PRINT));
        } else {
            die("ID inválido.");
        }

        // Identificar o tipo da chave PIX automaticamente
        $tipoChavePix = identificarTipoChavePix($chavepix);

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
        curl_close($curl);

        // Verificando a resposta do pagamento
        if (strpos($enviarpagamento, '"success": true') !== false) {
            die("Pagamento realizado com sucesso");
        } else {
            die("Erro ao processar o pagamento: $enviarpagamento");
        }
    } else {
        echo "Chave Pix inválida, valor inválido ou valor fora do limite permitido." . $chavepix . $valor . $tipoChavePix;
        exit;
    }
} else {
    echo "Credenciais Suitpay não encontradas no banco de dados.";
    exit;
}
$mysqli->close();
?>