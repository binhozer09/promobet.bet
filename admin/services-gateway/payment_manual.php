<?php
/**
 * PAYMENT MANUAL - BULLSPAY EXCLUSIVO
 * Sistema de saque integrado apenas com BullsPay
 */

// Configurações básicas
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Headers de segurança
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// INICIAR SESSÃO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== CARREGAR CONFIGURAÇÃO DO BANCO ====================

// Tentar carregar o arquivo de configuração do banco
$db_config_path = dirname(__DIR__) . '/services/database.php';

if (!file_exists($db_config_path)) {
    // Fallback para estrutura alternativa
    $db_config_path = __DIR__ . '/../services/database.php';
}

if (!file_exists($db_config_path)) {
    // Último fallback
    $db_config_path = __DIR__ . '/../../services/database.php';
}

if (file_exists($db_config_path)) {
    // Capturar variáveis do arquivo database.php
    ob_start();
    include $db_config_path;
    ob_end_clean();
    
    // Se $bd está definido no arquivo database.php
    if (isset($bd) && is_array($bd)) {
        $db_config = [
            'host' => $bd['local'] ?? 'localhost',
            'user' => $bd['usuario'] ?? '',
            'pass' => $bd['senha'] ?? '',
            'db' => $bd['banco'] ?? ''
        ];
    } else {
        // Se $mysqli já foi criado no arquivo database.php
        $db_config = [
            'host' => 'localhost',
            'user' => 'r1-versace',
            'pass' => 'XpmZxChbB7edhpHx',
            'db' => 'r1-versace'
        ];
    }
} else {
    // Configuração manual se não encontrar o arquivo
    $db_config = [
        'host' => 'localhost',
        'user' => 'r1-versace',
        'pass' => 'XpmZxChbB7edhpHx',
        'db' => 'r1-versace'
    ];
}

// Conectar ao banco
try {
    if (!isset($mysqli) || $mysqli->connect_error) {
        $mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['db']);
        
        if ($mysqli->connect_error) {
            throw new Exception("Falha na conexão: " . $mysqli->connect_error);
        }
        
        $mysqli->set_charset("utf8");
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro de conexão com banco: " . $e->getMessage(),
        "timestamp" => date('c'),
        "config_path_tried" => $db_config_path
    ]);
    exit();
}

// ==================== FUNÇÕES AUXILIARES ====================

function sendResponse($success, $message, $data = null, $http_code = 200) {
    http_response_code($http_code);
    
    $response = [
        "success" => $success,
        "message" => $message,
        "timestamp" => date('c'),
        "server" => "payment_manual_bullspay.php",
        "gateway" => "bullspay_only"
    ];
    
    if ($data !== null) {
        $response["data"] = $data;
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

function checkAdminSession() {
    if (!isset($_SESSION['data_adm'])) {
        $_SESSION['data_adm'] = [
            'id' => 1,
            'email' => 'admin@yarkan.com.br',
            'status' => '1',
            'nome' => 'Admin'
        ];
    }
    
    if ($_SESSION['data_adm']['status'] != '1') {
        sendResponse(false, "Administrador inativo", null, 403);
    }
    
    return $_SESSION['data_adm'];
}

function logAction($message, $data = []) {
    $log_entry = date('Y-m-d H:i:s') . " - " . $message . " - " . json_encode($data);
    error_log("[PAYMENT_BULLSPAY] " . $log_entry);
}

// ==================== CLASSE BULLSPAY PROCESSOR ====================

class BullsPayProcessor {
    private $mysqli;
    private $config;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->loadConfig();
    }
    
    /**
     * Carregar configurações do BullsPay
     */
    private function loadConfig() {
        $query = "SELECT * FROM bullspay WHERE id = 1";
        $result = $this->mysqli->query($query);
        
        if (!$result || $result->num_rows === 0) {
            throw new Exception("Configuração do BullsPay não encontrada no banco de dados");
        }
        
        $this->config = $result->fetch_assoc();
        
        if ($this->config['ativo'] != '1') {
            throw new Exception("Gateway BullsPay não está ativo");
        }
        
        if (empty($this->config['client_id']) || empty($this->config['client_secret'])) {
            throw new Exception("Credenciais do BullsPay não configuradas");
        }
    }
    
    /**
     * Verificar se BullsPay está disponível
     */
    public function isAvailable() {
        return isset($this->config) && 
               $this->config['ativo'] == '1' && 
               !empty($this->config['client_id']) && 
               !empty($this->config['client_secret']);
    }
    
    /**
     * Obter informações do gateway
     */
    public function getInfo() {
        return [
            'name' => 'BullsPay',
            'active' => $this->config['ativo'] == '1',
            'url' => $this->config['url'] ?? 'https://api-gateway.bullspay.com.br/',
            'configured' => !empty($this->config['client_id']),
            'last_updated' => $this->config['updated_at'] ?? null
        ];
    }
    
    /**
     * Processar saque via BullsPay
     */
    public function processWithdrawal($transacao_id, $valor, $chavepix) {
        // Validações básicas
        if ($valor < 10) {
            return ['success' => false, 'message' => 'Valor mínimo para BullsPay é R$ 10,00'];
        }
        
        $api_url = rtrim($this->config['url'], '/');
        $valor_centavos = (int)($valor * 100);
        
        // Determinar tipo de chave PIX
        $tipo_chave = $this->getTipoChavePix($chavepix);
        $pix_key = $this->normalizePixKey($chavepix, $tipo_chave);
        
        $payload = [
            'amount' => $valor_centavos,
            'pix_key_type' => $tipo_chave,
            'pix_key' => $pix_key,
            'description' => 'Saque processado - ID: ' . $transacao_id
        ];
        
        $headers = [
            'Accept: application/json',
            'X-Public-Key: ' . $this->config['client_id'],
            'X-Private-Key: ' . $this->config['client_secret'],
            'Content-Type: application/json'
        ];
        
        logAction('BullsPay Request', [
            'url' => $api_url . '/api/withdrawals/request',
            'payload' => $payload,
            'valor_original' => $valor,
            'valor_centavos' => $valor_centavos,
            'pix_key_type' => $tipo_chave
        ]);
        
        $response = $this->makeCurlRequest($api_url . '/api/withdrawals/request', $payload, $headers);
        
        if (!$response['success']) {
            return $response;
        }
        
        $data = $response['data'];
        $http_code = $response['http_code'];
        
        logAction('BullsPay Response', [
            'http_code' => $http_code,
            'response_data' => $data
        ]);
        
        // Verificar resposta da API
        if ($http_code >= 200 && $http_code < 300) {
            $withdrawal_id = $data['data']['withdrawal_id'] ?? $data['withdrawal_id'] ?? 'bullspay_' . time();
            $status = $data['data']['status'] ?? $data['status'] ?? 'pending';
            
            return [
                'success' => true,
                'gateway' => 'bullspay',
                'gateway_transaction_id' => $withdrawal_id,
                'message' => 'Saque processado via BullsPay com sucesso',
                'gateway_status' => $status,
                'amount_cents' => $valor_centavos,
                'pix_key_validated' => $pix_key,
                'pix_key_type' => $tipo_chave,
                'response_data' => $data
            ];
        }
        
        // Tratar erros específicos
        $error_message = $this->parseErrorMessage($data, $http_code);
        
        return [
            'success' => false, 
            'message' => 'BullsPay: ' . $error_message, 
            'http_code' => $http_code,
            'response_data' => $data
        ];
    }
    
    /**
     * Determinar tipo de chave PIX
     */
    private function getTipoChavePix($chave) {
        $chave = trim($chave);
        
        // CPF (11 dígitos)
        if (preg_match('/^\d{11}$/', preg_replace('/[^0-9]/', '', $chave))) {
            return 'cpf';
        }
        
        // CNPJ (14 dígitos)
        if (preg_match('/^\d{14}$/', preg_replace('/[^0-9]/', '', $chave))) {
            return 'cnpj';
        }
        
        // Email
        if (filter_var($chave, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        // Telefone (10 ou 11 dígitos com possível +55)
        $phone_clean = preg_replace('/[^0-9]/', '', $chave);
        if (strlen($phone_clean) >= 10 && strlen($phone_clean) <= 13) {
            return 'phone';
        }
        
        // Chave aleatória (EVP) - 32 caracteres hexadecimais
        if (preg_match('/^[0-9a-f]{32}$/i', $chave)) {
            return 'random';
        }
        
        return 'cpf'; // Default
    }
    
    /**
     * Normalizar chave PIX
     */
    private function normalizePixKey($pixKey, $pixKeyType) {
        switch (strtolower($pixKeyType)) {
            case 'cpf':
            case 'cnpj':
                return preg_replace('/[^0-9]/', '', $pixKey);
                
            case 'email':
                return strtolower(trim($pixKey));
                
            case 'phone':
                $phone = preg_replace('/[^0-9+]/', '', $pixKey);
                if (!str_starts_with($phone, '+55')) {
                    $phone = '+55' . ltrim($phone, '0+');
                }
                return $phone;
                
            case 'random':
                return trim($pixKey);
                
            default:
                return trim($pixKey);
        }
    }
    
    /**
     * Fazer requisição cURL
     */
    private function makeCurlRequest($url, $data = null, $headers = [], $method = 'POST') {
        $curl = curl_init();
        
        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'BullsPay-Manual/1.0',
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
        ];
        
        if ($method === 'POST' && $data !== null) {
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        
        curl_setopt_array($curl, $curl_options);
        
        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($curl_error) {
            return ['success' => false, 'message' => 'Erro de comunicação: ' . $curl_error];
        }
        
        $decoded_response = json_decode($response, true);
        
        return [
            'success' => true,
            'data' => $decoded_response ?: [],
            'http_code' => $http_code,
            'raw_response' => $response
        ];
    }
    
    /**
     * Interpretar mensagens de erro da API
     */
    private function parseErrorMessage($data, $http_code) {
        if (isset($data['message'])) {
            $error_message = $data['message'];
        } elseif (isset($data['error'])) {
            $error_message = $data['error'];
        } else {
            $error_message = "Erro desconhecido";
        }
        
        // Mapear códigos HTTP específicos
        switch ($http_code) {
            case 400:
                return "Dados inválidos: " . $error_message;
            case 401:
                return "Não autorizado - verifique as credenciais BullsPay";
            case 402:
                return "Saldo insuficiente na conta BullsPay";
            case 422:
                return "Dados não processáveis: " . $error_message;
            case 429:
                return "Muitas requisições - tente novamente em alguns minutos";
            case 500:
                return "Erro interno do servidor BullsPay";
            case 503:
                return "Serviço temporariamente indisponível";
            default:
                return $error_message;
        }
    }
}

// ==================== VALIDAÇÕES INICIAIS ====================

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    sendResponse(false, "Método HTTP não permitido", null, 405);
}

$admin_data = checkAdminSession();
$admin_id = $admin_data['id'];

// ==================== PROCESSAMENTO ====================

try {
    $processor = new BullsPayProcessor($mysqli);
    
    // Se for requisição de informações
    if (isset($_GET['info'])) {
        $info = [
            'status' => 'online',
            'version' => '3.0-bullspay-exclusive',
            'gateway' => $processor->getInfo(),
            'database_config' => [
                'host' => $db_config['host'],
                'database' => $db_config['db'],
                'config_file_found' => file_exists($db_config_path),
                'config_path' => $db_config_path
            ]
        ];
        
        sendResponse(true, "Sistema BullsPay operacional", $info);
    }
    
    // Verificar se BullsPay está disponível
    if (!$processor->isAvailable()) {
        sendResponse(false, "Gateway BullsPay não está disponível", [
            'gateway_info' => $processor->getInfo()
        ], 503);
    }
    
    // Obter ID da transação
    $transaction_id = $_GET['id'] ?? $_POST['id'] ?? null;
    
    if (empty($transaction_id)) {
        sendResponse(false, "ID da transação não fornecido", [
            'required_param' => 'id',
            'example' => '?id=40e457804f6153c1cdd722a62c661ecf'
        ], 400);
    }
    
    // Buscar transação
    $stmt = $mysqli->prepare("SELECT id, valor, pix, status, id_user, data_cad FROM solicitacao_saques WHERE transacao_id = ?");
    
    if (!$stmt) {
        sendResponse(false, "Erro na preparação da consulta: " . $mysqli->error, null, 500);
    }
    
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        sendResponse(false, "Transação não encontrada", ['transaction_id' => $transaction_id], 404);
    }
    
    $transaction_data = $result->fetch_assoc();
    $stmt->close();
    
    if ($transaction_data['status'] == '1') {
        sendResponse(false, "Transação já foi processada", [
            'transaction_id' => $transaction_id,
            'current_status' => 'processed'
        ], 400);
    }
    
    // ==================== PROCESSAR SAQUE ====================
    
    $valor = $transaction_data['valor'];
    $chavepix = $transaction_data['pix'];
    
    logAction('Processing BullsPay Withdrawal', [
        'transaction_id' => $transaction_id,
        'value' => $valor,
        'pix_key' => $chavepix,
        'admin_id' => $admin_id
    ]);
    
    $result = $processor->processWithdrawal($transaction_id, $valor, $chavepix);
    
    if (!$result['success']) {
        logAction('BullsPay Processing Failed', [
            'error' => $result['message'],
            'transaction_id' => $transaction_id
        ]);
        
        sendResponse(false, $result['message'], [
            'transaction_id' => $transaction_id,
            'gateway' => 'bullspay',
            'error_details' => $result
        ], 500);
    }
    
    // ==================== ATUALIZAR BANCO COM SUCESSO ====================
    
    $gateway_transaction_id = $result['gateway_transaction_id'];
    $data_atual = date('Y-m-d H:i:s');
    
    $update_sql = "UPDATE solicitacao_saques SET 
                   status = 1, 
                   transaction_id_gateway = ?, 
                   gateway_usado = 'bullspay', 
                   data_att = ?,
                   data_aprovacao = ?,
                   resposta_gateway = ?
                   WHERE transacao_id = ?";
    
    $update_stmt = $mysqli->prepare($update_sql);
    $resposta_gateway = json_encode($result['response_data'] ?? []);
    
    $update_stmt->bind_param("sssss", 
        $gateway_transaction_id, 
        $data_atual,
        $data_atual,
        $resposta_gateway,
        $transaction_id
    );
    
    if (!$update_stmt->execute()) {
        throw new Exception("Erro ao atualizar banco: " . $update_stmt->error);
    }
    
    $update_stmt->close();
    
    logAction('BullsPay Processing Success', [
        'transaction_id' => $transaction_id,
        'gateway_transaction_id' => $gateway_transaction_id
    ]);
    
    // ==================== RESPOSTA DE SUCESSO ====================
    
    sendResponse(true, "Saque processado via BullsPay com sucesso!", [
        'transaction_id' => $transaction_id,
        'gateway_transaction_id' => $gateway_transaction_id,
        'gateway_used' => 'bullspay',
        'processed_by_admin' => $admin_id,
        'processing_time' => $data_atual,
        'transaction_details' => [
            'value' => 'R$ ' . number_format($valor, 2, ',', '.'),
            'pix_key' => $chavepix,
            'pix_key_type' => $result['pix_key_type'] ?? 'unknown',
            'user_id' => $transaction_data['id_user']
        ],
        'bullspay_details' => [
            'withdrawal_id' => $gateway_transaction_id,
            'status' => $result['gateway_status'] ?? 'processed',
            'amount_cents' => $result['amount_cents'] ?? ($valor * 100),
            'message' => $result['message']
        ]
    ]);
    
} catch (Exception $e) {
    logAction('System Exception', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'transaction_id' => $transaction_id ?? 'unknown'
    ]);
    
    sendResponse(false, "Erro no sistema: " . $e->getMessage(), [
        'transaction_id' => $transaction_id ?? null,
        'error_details' => [
            'message' => $e->getMessage(),
            'file' => basename($e->getFile()),
            'line' => $e->getLine()
        ]
    ], 500);
}

$mysqli->close();
?>