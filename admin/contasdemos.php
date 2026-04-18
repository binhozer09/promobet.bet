<?php
// Processar requisições AJAX primeiro, antes de qualquer HTML
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    // Configurações de segurança e includes (como no seu código original)
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    include_once "services/database.php";
    include_once "services/funcao.php";
    include_once "services/crud.php";
    include_once "services/crud-adm.php";
    include_once 'services/checa_login_adm.php';
    include_once "services/CSRF_Protect.php";
    include_once "validar_2fa.php";

    $csrf = new CSRF_Protect();
    checa_login_adm(); // Verificar se é admin

    // Função para gerar telefone brasileiro aleatório
    function gerarTelefoneBrasileiro() {
        $ddds = ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '22', '24', '27', '28', 
                 '31', '32', '33', '34', '35', '37', '38', '41', '42', '43', '44', '45', '46', '47', 
                 '48', '49', '51', '53', '54', '55', '61', '62', '63', '64', '65', '66', '67', '68', 
                 '69', '71', '73', '74', '75', '77', '79', '81', '82', '83', '84', '85', '86', '87', 
                 '88', '89', '91', '92', '93', '94', '95', '96', '97', '98', '99'];
        
        $ddd = $ddds[array_rand($ddds)];
        $numero = '9' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        return $ddd . $numero; // Removido o +55
    }

    // Função para gerar senha baseada no tipo
    function gerarSenha($tipo, $senhaCustom = '') {
        switch($tipo) {
            case 'padrao':
                return 'demo123';
            case 'aleatoria':
                return str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            case 'personalizada':
                return $senhaCustom;
            default:
                return 'demo123';
        }
    }

    // Função para verificar se username já existe
    function verificarUsernameExiste($username, $mysqli) {
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE mobile = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }

    // Função para registrar conta demo na MaxApiGames
    function registrarDemoMaxApiGames($username, $mysqli) {
        // Buscar credenciais da MaxApiGames
        $res = mysqli_query($mysqli, "SELECT * FROM maxapigames WHERE id=1");
        if (!$res) return ['success' => false, 'error' => 'Tabela maxapigames não encontrada'];
        $maxconfig = mysqli_fetch_assoc($res);

        if (empty($maxconfig['agent_code']) || empty($maxconfig['agent_token'])) {
            return ['success' => false, 'error' => 'Credenciais MaxApiGames não configuradas'];
        }

        // 1. Criar jogador na MaxApiGames com is_demo = true
        $createRequest = json_encode([
            "method"      => "user_create",
            "agent_code"  => $maxconfig['agent_code'],
            "agent_token" => $maxconfig['agent_token'],
            "user_code"   => $username,
            "is_demo"     => true
        ]);
        $createResponse = enviarRequest($maxconfig['url'], $createRequest);
        $createData = json_decode($createResponse, true);

        // Se criou com sucesso e já é demo, retornar
        if (isset($createData['status']) && $createData['status'] == 1) {
            return ['success' => true, 'is_demo' => $createData['is_demo'] ?? true];
        }

        // Se o jogador já existe (DUPLICATED_USER), marcar como demo via set_demo
        if (isset($createData['msg']) && $createData['msg'] == 'DUPLICATED_USER') {
            $demoRequest = json_encode([
                "method"      => "set_demo",
                "agent_code"  => $maxconfig['agent_code'],
                "agent_token" => $maxconfig['agent_token'],
                "user_code"   => $username
            ]);
            $demoResponse = enviarRequest($maxconfig['url'], $demoRequest);
            $demoData = json_decode($demoResponse, true);

            if (isset($demoData['status']) && $demoData['status'] == 1) {
                return ['success' => true, 'is_demo' => true];
            } else {
                return ['success' => false, 'error' => 'Erro ao marcar como demo: ' . ($demoData['msg'] ?? 'desconhecido')];
            }
        }

        return ['success' => false, 'error' => 'Erro MaxApiGames: ' . ($createData['msg'] ?? 'resposta inválida')];
    }

    // Função para criar conta demo (baseada no seu código original)
    function criarContaDemo($username, $senha, $telefone, $saldo, $mysqli) {
        global $url_base;

        // Verificar se username já existe
        if (verificarUsernameExiste($username, $mysqli)) {
            return ['success' => false, 'error' => "Username '$username' já existe"];
        }

        $datadia = date("Y-m-d H:i:s");
        $token = md5($username . sha1(mt_rand()) . $datadia);
        $afinveted = str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT, ["cost" => 10]);
        $url = $url_base ?? '';
        $statusaff = 1; // Status demo conforme solicitado
        $tipo_pagamento = 1; // 1 SOMENTE CPA, 2 SOMENTE REV, 0 CPA+REV
        $ip_cadastro = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $invitation_code = null; // Definir como variável

        // Inserir usuário na tabela usuarios (usando a estrutura correta)
        $sql1 = $mysqli->prepare(
            "INSERT INTO usuarios (mobile, celular, password, real_name, spassword, url, token, data_cad, invite_code, invitation_code, tipo_pagamento, ip_cadastro, statusaff, saldo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $sql1->bind_param(
            "ssssssssssssid",
            $username,          // mobile
            $telefone,          // celular
            $senhaHash,         // password
            $username,          // real_name
            $senhaHash,         // spassword
            $url,               // url
            $token,             // token
            $datadia,           // data_cad
            $afinveted,         // invite_code
            $invitation_code,   // invitation_code (null)
            $tipo_pagamento,    // tipo_pagamento
            $ip_cadastro,       // ip_cadastro
            $statusaff,         // statusaff = 1 para demo
            $saldo              // saldo inicial
        );

        if ($sql1->execute()) {
            $userId = $mysqli->insert_id;

            // Criar registro na tabela 'bau'
            $sql2 = $mysqli->prepare("INSERT INTO bau (num, status, token) VALUES ('', 'user novo demo', ?)");
            $sql2->bind_param("s", $token);
            $sql2->execute();

            // Registrar como conta demo na MaxApiGames
            $maxResult = registrarDemoMaxApiGames($username, $mysqli);
            $maxStatus = $maxResult['success'] ? 'ok' : $maxResult['error'];

            return [
                'success' => true,
                'data' => [
                    'id' => $userId,
                    'username' => $username,
                    'senha' => $senha,
                    'telefone' => $telefone,
                    'saldo' => $saldo,
                    'token' => $token,
                    'statusaff' => $statusaff,
                    'data_criacao' => $datadia,
                    'invite_code' => $afinveted,
                    'maxapigames_demo' => $maxStatus
                ]
            ];
        } else {
            return ['success' => false, 'error' => 'Erro ao inserir no banco de dados: ' . $mysqli->error];
        }
    }

    // Inicializar variáveis para resposta
    $response = ['success' => false, 'message' => '', 'data' => []];

    // Processar requisição AJAX para gerar contas
    if ($_POST['action'] == 'gerar_contas') {
        
        // Limpar qualquer saída anterior
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Definir o cabeçalho de resposta como JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Validar dados de entrada
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        $saldo = (float)($_POST['saldo'] ?? 0);
        $prefixo = trim($_POST['prefixo'] ?? 'demo');
        $tipoSenha = $_POST['tipo_senha'] ?? 'padrao';
        $senhaCustom = $_POST['senha_custom'] ?? '';
        
        // Validações
        if ($quantidade < 1 || $quantidade > 1000) {
            $response['message'] = 'Quantidade deve ser entre 1 e 1000';
            echo json_encode($response);
            exit;
        }
        
        if ($saldo < 0) {
            $response['message'] = 'Saldo deve ser maior ou igual a 0';
            echo json_encode($response);
            exit;
        }
        
        if (empty($prefixo) || strlen($prefixo) > 10) {
            $response['message'] = 'Prefixo deve ter entre 1 e 10 caracteres';
            echo json_encode($response);
            exit;
        }
        
        if ($tipoSenha == 'personalizada' && empty($senhaCustom)) {
            $response['message'] = 'Senha personalizada é obrigatória';
            echo json_encode($response);
            exit;
        }
        
        // Array para armazenar contas criadas
        $contasCriadas = [];
        $contasComErro = [];
        
        // Gerar contas
        for ($i = 1; $i <= $quantidade; $i++) {
            $username = $prefixo . $i;
            $senha = gerarSenha($tipoSenha, $senhaCustom);
            $telefone = gerarTelefoneBrasileiro();
            
            $resultado = criarContaDemo($username, $senha, $telefone, $saldo, $mysqli);
            
            if ($resultado['success']) {
                $contasCriadas[] = $resultado['data'];
            } else {
                $contasComErro[] = [
                    'username' => $username,
                    'erro' => $resultado['error']
                ];
            }
            
            // Pequena pausa para evitar sobrecarga
            usleep(50000); // 50ms
        }
        
        // Preparar resposta
        $response['success'] = true;
        $response['message'] = count($contasCriadas) . ' contas criadas com sucesso';
        $response['data'] = [
            'contas_criadas' => $contasCriadas,
            'contas_erro' => $contasComErro,
            'total_criadas' => count($contasCriadas),
            'total_erro' => count($contasComErro),
            'saldo_total' => count($contasCriadas) * $saldo
        ];
        
        echo json_encode($response);
        exit;
    }

    // CORRIGIDO: Processar busca de contas demo existentes (APENAS DO GERADOR)
    if ($_POST['action'] == 'carregar_contas_demo') {
        
        // Limpar qualquer saída anterior
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Definir o cabeçalho de resposta como JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // QUERY CORRIGIDA: Buscar apenas contas que seguem o padrão demo + número
        $query = "SELECT id, mobile as username, celular as telefone, saldo, token, data_cad, invite_code, statusaff 
                  FROM usuarios 
                  WHERE statusaff = 1 
                  AND mobile REGEXP '^demo[0-9]+$'
                  ORDER BY CAST(SUBSTRING(mobile, 5) AS UNSIGNED), data_cad DESC 
                  LIMIT 50";
        $result = $mysqli->query($query);
        
        $contas = [];
        while ($row = $result->fetch_assoc()) {
            $contas[] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'senha' => 'demo123', // Senha padrão para exibição
                'telefone' => $row['telefone'],
                'saldo' => $row['saldo'],
                'token' => $row['token'],
                'statusaff' => $row['statusaff'],
                'data_criacao' => $row['data_cad'],
                'invite_code' => $row['invite_code']
            ];
        }
        
        $response['success'] = true;
        $response['data'] = [
            'contas_criadas' => $contas,
            'total_criadas' => count($contas)
        ];
        
        echo json_encode($response);
        exit;
    }

    // CORRIGIDO: Processar busca de estatísticas via AJAX
    if ($_POST['action'] == 'get_estatisticas') {
        
        // Limpar qualquer saída anterior
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Definir o cabeçalho de resposta como JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $stats = [];
        
        // Total de contas demo do gerador
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
        $result = $mysqli->query($query);
        $stats['total_demo'] = $result->fetch_assoc()['total'];
        
        // Saldo total das contas demo do gerador
        $query = "SELECT SUM(saldo) as saldo_total FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
        $result = $mysqli->query($query);
        $stats['saldo_total'] = $result->fetch_assoc()['saldo_total'] ?? 0;
        
        // Contas demo criadas hoje pelo gerador
        $query = "SELECT COUNT(*) as hoje FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$' AND DATE(data_cad) = CURDATE()";
        $result = $mysqli->query($query);
        $stats['criadas_hoje'] = $result->fetch_assoc()['hoje'];
        
        // Última conta demo criada pelo gerador
        $query = "SELECT data_cad FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$' ORDER BY data_cad DESC LIMIT 1";
        $result = $mysqli->query($query);
        $row = $result->fetch_assoc();
        $stats['ultima_criacao'] = $row ? $row['data_cad'] : 'Nunca';
        
        $response['success'] = true;
        $response['data'] = $stats;
        
        echo json_encode($response);
        exit;
    }

    // CORRIGIDO: Processar limpeza de contas demo
    if ($_POST['action'] == 'limpar_contas_demo') {
        
        // Limpar qualquer saída anterior
        if (ob_get_level()) {
            ob_clean();
        }
        
        // Definir o cabeçalho de resposta como JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Buscar apenas contas demo do gerador (padrão demo + número)
        $query = "SELECT COUNT(*) as total FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
        $result = $mysqli->query($query);
        $totalDemo = $result->fetch_assoc()['total'];
        
        if ($totalDemo == 0) {
            $response['success'] = false;
            $response['message'] = "Nenhuma conta demo criada pelo gerador foi encontrada";
            echo json_encode($response);
            exit;
        }
        
        // Deletar contas demo e dados relacionados
        $mysqli->begin_transaction();
        
        try {
            // 1. Primeiro, buscar os tokens das contas demo para deletar registros relacionados
            $queryTokens = "SELECT token FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
            $resultTokens = $mysqli->query($queryTokens);
            $tokens = [];
            while ($row = $resultTokens->fetch_assoc()) {
                $tokens[] = "'" . $mysqli->real_escape_string($row['token']) . "'";
            }
            
            if (!empty($tokens)) {
                $tokensStr = implode(',', $tokens);
                
                // Deletar registros relacionados na tabela bau
                $mysqli->query("DELETE FROM bau WHERE token IN ($tokensStr)");
            }
            
            // 2. Deletar transações relacionadas (se a tabela existir)
            $checkTable = $mysqli->query("SHOW TABLES LIKE 'transacoes'");
            if ($checkTable && $checkTable->num_rows > 0) {
                $mysqli->query("DELETE t FROM transacoes t INNER JOIN usuarios u ON t.usuario_id = u.id WHERE u.statusaff = 1 AND u.mobile REGEXP '^demo[0-9]+$'");
            }
            
            // 3. Deletar outros registros relacionados
            $otherTables = ['historico_play', 'user_activity', 'user_free_rounds'];
            foreach ($otherTables as $table) {
                $checkOtherTable = $mysqli->query("SHOW TABLES LIKE '$table'");
                if ($checkOtherTable && $checkOtherTable->num_rows > 0) {
                    $mysqli->query("DELETE t FROM $table t INNER JOIN usuarios u ON t.user_id = u.id WHERE u.statusaff = 1 AND u.mobile REGEXP '^demo[0-9]+$'");
                }
            }
            
            // 4. Por último, deletar os usuários demo do gerador
            $query = "DELETE FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
            $result = $mysqli->query($query);
            
            if ($result) {
                $mysqli->commit();
                
                $response['success'] = true;
                $response['message'] = "Todas as {$totalDemo} contas demo criadas pelo gerador foram removidas com sucesso";
            } else {
                throw new Exception("Erro ao deletar contas demo");
            }
            
        } catch (Exception $e) {
            $mysqli->rollback();
            $response['success'] = false;
            $response['message'] = "Erro ao limpar contas demo: " . $e->getMessage();
        }
        
        echo json_encode($response);
        exit;
    }
}

// Só carregar o HTML se não for uma requisição AJAX
include 'partials/html.php';

// Configurações de segurança e includes (como no seu código original)
ini_set('display_errors', 0);
error_reporting(E_ALL);
include_once "services/database.php";
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
include_once "validar_2fa.php";

$csrf = new CSRF_Protect();
checa_login_adm(); // Verificar se é admin

// CORRIGIDO: Função para buscar estatísticas de contas demo
function getEstatisticasDemo($mysqli) {
    $stats = [];
    
    // Total de contas demo criadas pelo gerador (padrão demo + número)
    $query = "SELECT COUNT(*) as total FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
    $result = $mysqli->query($query);
    $stats['total_demo'] = $result->fetch_assoc()['total'];
    
    // Saldo total das contas demo do gerador
    $query = "SELECT SUM(saldo) as saldo_total FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$'";
    $result = $mysqli->query($query);
    $stats['saldo_total'] = $result->fetch_assoc()['saldo_total'] ?? 0;
    
    // Contas demo criadas hoje pelo gerador
    $query = "SELECT COUNT(*) as hoje FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$' AND DATE(data_cad) = CURDATE()";
    $result = $mysqli->query($query);
    $stats['criadas_hoje'] = $result->fetch_assoc()['hoje'];
    
    // Última conta demo criada pelo gerador
    $query = "SELECT data_cad FROM usuarios WHERE statusaff = 1 AND mobile REGEXP '^demo[0-9]+$' ORDER BY data_cad DESC LIMIT 1";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $stats['ultima_criacao'] = $row ? $row['data_cad'] : 'Nunca';
    
    return $stats;
}

$estatisticas = getEstatisticasDemo($mysqli);
?>

<head>
    <?php $title = "Gerador de Contas Demo"; include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        .demo-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            background: #303231;
            backdrop-filter: blur(10px);
            color: #fff;
        }
        
        .demo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            color: white;
            padding: 24px;
            text-align: center;
        }
        
        .form-control, .form-select {
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        
        .btn-modern {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .loading-content {
            background: #303231;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .accounts-table {
            background: #303231;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .table {
            color: #fff;
            margin-bottom: 0;
        }
        
        .table td {
            padding: 12px 15px;
            border-color: rgba(255, 255, 255, 0.1);
            vertical-align: middle;
            background: transparent;
        }
        
        .table tbody tr {
            background: transparent;
        }
        
        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-content">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <h6>Gerando contas demo...</h6>
            <div class="progress mt-3" style="height: 8px;">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <p class="text-muted mt-2 mb-0">
                <span id="contadorContas">0</span> de <span id="totalContas">0</span> contas
            </p>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    
                    <!-- Header -->
                    <div class="col-12 mb-4">
                        <div class="text-center">
                            <h1 class="text-primary mb-3">
                                <i class="iconoir-users me-3"></i>Gerador de Contas Demo
                            </h1>
                            <p class="text-muted lead">Crie múltiplas contas demo com saldo personalizado</p>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="stats-card">
                                    <div class="h4 mb-1"><?= number_format($estatisticas['total_demo']) ?></div>
                                    <div>Total de Contas Demo</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <div class="h4 mb-1">R$ <?= number_format($estatisticas['saldo_total'], 2, ',', '.') ?></div>
                                    <div>Saldo Total</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <div class="h4 mb-1"><?= $estatisticas['criadas_hoje'] ?></div>
                                    <div>Criadas Hoje</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                    <div class="h6 mb-1"><?= $estatisticas['ultima_criacao'] !== 'Nunca' ? date('d/m/Y H:i', strtotime($estatisticas['ultima_criacao'])) : 'Nunca' ?></div>
                                    <div>Última Criação</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário -->
                    <div class="col-lg-8">
                        <div class="demo-card p-4 mb-4">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="text-white mb-3">
                                    <i class="iconoir-settings me-2"></i>Configurações da Geração
                                </h5>
                            </div>
                            
                            <form id="demoForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">
                                            <i class="iconoir-hash me-1"></i>Quantidade de Contas
                                        </label>
                                        <input type="number" id="quantidade" class="form-control" 
                                               min="1" max="1000" value="10" required>
                                        <div class="form-text text-white">Máximo: 1000 contas por vez</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">
                                            <i class="iconoir-dollar me-1"></i>Saldo Inicial (R$)
                                        </label>
                                        <input type="number" id="saldo" class="form-control" 
                                               min="0" step="0.01" value="100.00" required>
                                        <div class="form-text text-white">Saldo que cada conta receberá</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">
                                            <i class="iconoir-tag me-1"></i>Prefixo do Username
                                        </label>
                                        <input type="text" id="prefixo" class="form-control" 
                                               value="demo" maxlength="10" required>
                                        <div class="form-text text-white">Ex: demo1, demo2, demo3...</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">
                                            <i class="iconoir-lock me-1"></i>Tipo de Senha
                                        </label>
                                        <select id="tipoSenha" class="form-select">
                                            <option value="padrao">Padrão (demo123)</option>
                                            <option value="aleatoria">Aleatória (8 dígitos)</option>
                                            <option value="personalizada">Personalizada</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row" id="senhaPersonalizada" style="display: none;">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-white">
                                            <i class="iconoir-key me-1"></i>Senha Personalizada
                                        </label>
                                        <input type="text" id="senhaCustom" class="form-control" 
                                               placeholder="Digite a senha desejada">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-modern btn-lg w-100">
                                            <i class="iconoir-play me-2"></i>Gerar Contas Demo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Resultados -->
                        <div id="resultados" style="display: none;">
                            <!-- Ações -->
                            <div class="demo-card p-4 mb-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1 text-white">
                                            <i class="iconoir-download me-2"></i>Exportar Dados
                                        </h6>
                                        <p class="text-white opacity-75 mb-0 small">
                                            Baixe um arquivo .txt com todas as informações de acesso
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button id="downloadTxt" class="btn btn-success btn-modern">
                                            <i class="iconoir-download me-2"></i>Baixar .TXT
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabela de Contas -->
                            <div class="accounts-table">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th><i class="iconoir-hash me-1"></i>ID</th>
                                                <th><i class="iconoir-user me-1"></i>Username</th>
                                                <th><i class="iconoir-key me-1"></i>Senha</th>
                                                <th><i class="iconoir-phone me-1"></i>Telefone</th>
                                                <th><i class="iconoir-dollar me-1"></i>Saldo</th>
                                                <th><i class="iconoir-link me-1"></i>Link Afiliado</th>
                                                <th><i class="iconoir-check me-1"></i>Status</th>
                                                <th><i class="iconoir-server me-1"></i>MaxApiGames</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabelaContas">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    
    <script>
        let contasGeradas = [];

        // Configuração do tipo de senha
        document.getElementById('tipoSenha').addEventListener('change', function() {
            const senhaPersonalizada = document.getElementById('senhaPersonalizada');
            if (this.value === 'personalizada') {
                senhaPersonalizada.style.display = 'block';
                document.getElementById('senhaCustom').required = true;
            } else {
                senhaPersonalizada.style.display = 'none';
                document.getElementById('senhaCustom').required = false;
            }
        });

        // Função para mostrar loading
        function mostrarLoading(total) {
            document.getElementById('totalContas').textContent = total;
            document.getElementById('contadorContas').textContent = '0';
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        // Função para esconder loading
        function esconderLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Função para simular progresso (já que o backend processa tudo de uma vez)
        function simularProgresso(total, callback) {
            let atual = 0;
            const intervalo = setInterval(() => {
                atual += Math.floor(Math.random() * 3) + 1;
                if (atual >= total) {
                    atual = total;
                    clearInterval(intervalo);
                    callback();
                }
                
                const porcentagem = (atual / total) * 100;
                document.getElementById('contadorContas').textContent = atual;
                document.getElementById('progressBar').style.width = porcentagem + '%';
            }, 200);
        }

        // Função para gerar contas via AJAX
        async function gerarContas(formData) {
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });
                
                // Verificar se a resposta é válida
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Pegar o texto primeiro para debug
                const responseText = await response.text();
                
                // Tentar extrair JSON válido se houver HTML misturado
                let jsonStr = responseText;
                const jsonStart = responseText.indexOf('{');
                const jsonEnd = responseText.lastIndexOf('}') + 1;
                
                if (jsonStart >= 0 && jsonEnd > jsonStart) {
                    jsonStr = responseText.substring(jsonStart, jsonEnd);
                }
                
                // Tentar fazer parse do JSON
                let result;
                try {
                    result = JSON.parse(jsonStr);
                } catch (parseError) {
                    console.error('Resposta completa:', responseText);
                    console.error('Tentativa de JSON:', jsonStr);
                    throw new Error('Resposta do servidor não está em formato JSON válido');
                }
                
                return result;
            } catch (error) {
                console.error('Erro na requisição:', error);
                return { 
                    success: false, 
                    message: `Erro na comunicação: ${error.message}` 
                };
            }
        }

        // Função para exibir resultados
        function exibirResultados(data) {
            contasGeradas = data.contas_criadas;
            
            // Preencher tabela
            const tbody = document.getElementById('tabelaContas');
            tbody.innerHTML = '';
            
            contasGeradas.forEach((conta, index) => {
                // Gerar link de afiliado baseado no invite_code
                const linkAfiliado = `${window.location.origin}/?id=${conta.invite_code}`;
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>#${conta.id}</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">DEMO</span>
                            <strong>${conta.username}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="font-monospace">${conta.senha}</span>
                        <button class="btn btn-sm btn-outline-light ms-2" onclick="copiarTexto('${conta.senha}')">
                            <i class="iconoir-copy"></i>
                        </button>
                    </td>
                    <td>
                        <span class="font-monospace">${conta.telefone}</span>
                        <button class="btn btn-sm btn-outline-light ms-2" onclick="copiarTexto('${conta.telefone}')">
                            <i class="iconoir-copy"></i>
                        </button>
                    </td>
                    <td>
                        <span class="fw-bold text-success">R$ ${parseFloat(conta.saldo).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="font-monospace small text-white me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis;">${linkAfiliado}</span>
                            <button class="btn btn-sm btn-outline-success" onclick="copiarTexto('${linkAfiliado}')" title="Copiar link de afiliado">
                                <i class="iconoir-link"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-success">Demo Ativo</span>
                    </td>
                    <td>
                        ${conta.maxapigames_demo === 'ok'
                            ? '<span class="badge bg-success">Demo OK</span>'
                            : (conta.maxapigames_demo
                                ? '<span class="badge bg-warning" title="' + conta.maxapigames_demo + '">Aviso</span>'
                                : '<span class="badge bg-secondary">-</span>')}
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            // Mostrar seção de resultados
            document.getElementById('resultados').style.display = 'block';
            
            // Scroll suave para os resultados
            document.getElementById('resultados').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
            
            // Mostrar estatísticas de erro se houver
            if (data.contas_erro.length > 0) {
                let errosList = data.contas_erro.map(erro => `• ${erro.username}: ${erro.erro}`).join('\n');
                showToast('warning', 'Algumas contas não foram criadas', 
                    `${data.contas_erro.length} conta(s) falharam:\n${errosList}`, 8000);
            }
        }

        // Função para mostrar toast moderno
        function showToast(type, title, message, duration = 5000) {
            // Remover toasts existentes
            const existingToasts = document.querySelectorAll('.custom-toast');
            existingToasts.forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast toast-${type}`;
            
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };
            
            const colors = {
                success: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                error: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                info: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'
            };
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${colors[type]};
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 10000;
                min-width: 320px;
                max-width: 400px;
                animation: slideInRight 0.3s ease-out;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.2);
            `;
            
            toast.innerHTML = `
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="font-size: 18px; margin-top: 2px;">${icons[type]}</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">${title}</div>
                        <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">${message}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; padding: 0; margin-left: 8px; opacity: 0.7;"
                            onmouseover="this.style.opacity='1'" 
                            onmouseout="this.style.opacity='0.7'">×</button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto remove
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }

        // Adicionar animações CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .custom-toast:hover {
                transform: translateY(-2px);
                transition: transform 0.2s ease;
            }
        `;
        document.head.appendChild(style);

        // Função para copiar texto
        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                showToast('success', 'Copiado!', `${texto.length > 20 ? texto.substring(0, 20) + '...' : texto} foi copiado para a área de transferência`, 2000);
            }).catch(() => {
                showToast('error', 'Erro', 'Não foi possível copiar. Tente novamente.', 3000);
            });
        }

        // Função para download do arquivo TXT
        function downloadTXT() {
            if (contasGeradas.length === 0) return;
            
            let conteudo = '=== CONTAS DEMO GERADAS ===\n';
            conteudo += `Data de Geração: ${new Date().toLocaleString('pt-BR')}\n`;
            conteudo += `Total de Contas: ${contasGeradas.length}\n`;
            conteudo += `Saldo Total: R$ ${contasGeradas.reduce((sum, conta) => sum + parseFloat(conta.saldo), 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}\n\n`;
            
            contasGeradas.forEach((conta, index) => {
                // Gerar link de afiliado baseado no invite_code
                const linkAfiliado = `${window.location.origin}/?id=${conta.invite_code}`;
                
                conteudo += `=== CONTA ${index + 1} ===\n`;
                conteudo += `ID: ${conta.id}\n`;
                conteudo += `Username: ${conta.username}\n`;
                conteudo += `Senha: ${conta.senha}\n`;
                conteudo += `Telefone: ${conta.telefone}\n`;
                conteudo += `Saldo: R$ ${parseFloat(conta.saldo).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}\n`;
                conteudo += `Status Afiliado: ${conta.statusaff}\n`;
                conteudo += `Código de Convite: ${conta.invite_code}\n`;
                conteudo += `Link de Afiliado: ${linkAfiliado}\n`;
                conteudo += `Token: ${conta.token}\n`;
                conteudo += `MaxApiGames Demo: ${conta.maxapigames_demo || 'N/A'}\n`;
                conteudo += `Data de Criação: ${conta.data_criacao}\n\n`;
            });
            
            const blob = new Blob([conteudo], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `contas_demo_${new Date().toISOString().slice(0, 10)}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Event listener para o formulário
        document.getElementById('demoForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Prevenir múltiplos envios
            if (this.dataset.submitting === 'true') {
                return;
            }
            this.dataset.submitting = 'true';
            
            const quantidade = parseInt(document.getElementById('quantidade').value);
            const saldo = parseFloat(document.getElementById('saldo').value);
            const prefixo = document.getElementById('prefixo').value;
            const tipoSenha = document.getElementById('tipoSenha').value;
            const senhaCustom = document.getElementById('senhaCustom').value;
            
            // Validar formulário
            if (!validarFormulario(quantidade, saldo, prefixo, tipoSenha, senhaCustom)) {
                this.dataset.submitting = 'false';
                return;
            }
            
            // Desabilitar botão
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Gerando...';
            
            // Preparar dados do formulário
            const formData = new FormData();
            formData.append('action', 'gerar_contas');
            formData.append('quantidade', quantidade);
            formData.append('saldo', saldo);
            formData.append('prefixo', prefixo);
            formData.append('tipo_senha', tipoSenha);
            formData.append('senha_custom', senhaCustom);
            
            // Mostrar loading
            mostrarLoading(quantidade);
            
            try {
                const result = await gerarContas(formData);
                
                esconderLoading();
                
                if (result.success) {
                    exibirResultados(result.data);
                    
                    // Toast de sucesso moderno
                    showToast('success', 'Contas Geradas com Sucesso!', 
                        `${result.data.total_criadas} contas criadas • Saldo total: R$ ${result.data.saldo_total.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`, 
                        6000);
                    
                    // Atualizar estatísticas sem recarregar página
                    setTimeout(() => atualizarEstatisticas(), 1000);
                } else {
                    showToast('error', 'Erro na Geração', result.message, 8000);
                }
            } catch (error) {
                esconderLoading();
                showToast('error', 'Erro de Comunicação', 'Falha na comunicação com o servidor. Verifique sua conexão.', 8000);
            } finally {
                // Reabilitar botão
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                this.dataset.submitting = 'false';
            }
        });

        // Event listener para download
        document.getElementById('downloadTxt').addEventListener('click', downloadTXT);

        // Função para validar formulário
        function validarFormulario(quantidade, saldo, prefixo, tipoSenha, senhaCustom) {
            if (quantidade < 1 || quantidade > 1000) {
                showToast('error', 'Erro de Validação', 'A quantidade deve ser entre 1 e 1000 contas');
                return false;
            }
            
            if (saldo < 0) {
                showToast('error', 'Erro de Validação', 'O saldo deve ser maior ou igual a R$ 0,00');
                return false;
            }
            
            if (!prefixo || prefixo.length > 10) {
                showToast('error', 'Erro de Validação', 'O prefixo deve ter entre 1 e 10 caracteres');
                return false;
            }
            
            if (tipoSenha === 'personalizada' && !senhaCustom) {
                showToast('error', 'Campo Obrigatório', 'Digite a senha personalizada');
                return false;
            }
            
            return true;
        }

        // CORRIGIDO: Função para carregar contas demo existentes (APENAS DO GERADOR)
        async function carregarContasExistentes() {
            try {
                const formData = new FormData();
                formData.append('action', 'carregar_contas_demo');
                
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    const responseText = await response.text();
                    
                    // Extrair JSON da resposta
                    let jsonStr = responseText;
                    const jsonStart = responseText.indexOf('{');
                    const jsonEnd = responseText.lastIndexOf('}') + 1;
                    
                    if (jsonStart >= 0 && jsonEnd > jsonStart) {
                        jsonStr = responseText.substring(jsonStart, jsonEnd);
                    }
                    
                    const result = JSON.parse(jsonStr);
                    
                    if (result.success && result.data.contas_criadas.length > 0) {
                        contasGeradas = result.data.contas_criadas;
                        exibirResultados(result.data);
                        
                        // Mostrar toast informativo
                        showToast('info', 'Contas Demo Carregadas', 
                            `${result.data.total_criadas} conta(s) demo do gerador encontrada(s)`, 3000);
                    }
                }
            } catch (error) {
                console.log('Erro ao carregar contas existentes:', error);
            }
        }

        // CORRIGIDO: Função para atualizar estatísticas
        async function atualizarEstatisticas() {
            try {
                const formData = new FormData();
                formData.append('action', 'get_estatisticas');
                
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    const responseText = await response.text();
                    
                    // Extrair JSON da resposta
                    let jsonStr = responseText;
                    const jsonStart = responseText.indexOf('{');
                    const jsonEnd = responseText.lastIndexOf('}') + 1;
                    
                    if (jsonStart >= 0 && jsonEnd > jsonStart) {
                        jsonStr = responseText.substring(jsonStart, jsonEnd);
                    }
                    
                    const result = JSON.parse(jsonStr);
                    
                    if (result.success) {
                        const stats = result.data;
                        
                        // Atualizar cards de estatísticas
                        const statsCards = document.querySelectorAll('.stats-card .h4, .stats-card .h6');
                        if (statsCards.length >= 4) {
                            statsCards[0].textContent = stats.total_demo.toLocaleString('pt-BR');
                            statsCards[1].textContent = `R$ ${parseFloat(stats.saldo_total).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                            statsCards[2].textContent = stats.criadas_hoje.toString();
                            
                            if (stats.ultima_criacao !== 'Nunca') {
                                const dataFormatada = new Date(stats.ultima_criacao).toLocaleString('pt-BR');
                                statsCards[3].textContent = dataFormatada;
                            } else {
                                statsCards[3].textContent = 'Nunca';
                            }
                        }
                        
                        // Atualizar texto do botão de limpeza se existir
                        const btnLimpar = document.querySelector('button[onclick="limparContasDemo()"]');
                        if (btnLimpar && stats.total_demo > 0) {
                            btnLimpar.innerHTML = `<i class="iconoir-trash me-2"></i>Limpar Todas as Contas Demo (${stats.total_demo})`;
                            btnLimpar.style.display = 'block';
                        } else if (btnLimpar) {
                            btnLimpar.style.display = 'none';
                        }
                    }
                }
            } catch (error) {
                console.log('Erro ao atualizar estatísticas:', error);
            }
        }

        // Função melhorada para limpar contas demo
        function limparContasDemo() {
            showToast('warning', 'Confirmação Necessária', 'Você deseja deletar TODAS as contas demo criadas pelo gerador?', 8000);
            
            // Criar modal de confirmação personalizado
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                z-index: 10001;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            `;
            
            modal.innerHTML = `
                <div style="
                    background: #303231;
                    border-radius: 16px;
                    padding: 30px;
                    max-width: 500px;
                    width: 90%;
                    color: white;
                    border: 1px solid rgba(255,255,255,0.1);
                    animation: slideInUp 0.3s ease;
                ">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="font-size: 48px; margin-bottom: 10px;">⚠️</div>
                        <h3 style="margin: 0; color: #ef4444;">Deletar Contas Demo</h3>
                    </div>
                    
                    <p style="margin-bottom: 20px; text-align: center;">
                        Esta ação irá <strong>DELETAR PERMANENTEMENTE</strong> todas as contas demo criadas pelo gerador (padrão demo1, demo2, etc.)!
                    </p>
                    
                    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                        <strong>Esta ação não pode ser desfeita!</strong><br>
                        • Apenas contas com padrão demo + número<br>
                        • Dados relacionados serão deletados<br>
                        • Outras contas demo não serão afetadas
                    </div>
                    
                    <div style="text-align: center;">
                        <input type="text" id="confirmInput" placeholder="Digite CONFIRMAR para continuar" 
                               style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.3); 
                                      border-radius: 8px; background: rgba(255,255,255,0.1); color: white; text-align: center;">
                        
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button onclick="this.closest('div').parentElement.parentElement.remove()" 
                                    style="background: #6b7280; color: white; border: none; padding: 12px 24px; 
                                           border-radius: 8px; cursor: pointer; font-weight: 600;">
                                Cancelar
                            </button>
                            <button id="confirmDelete" 
                                    style="background: #ef4444; color: white; border: none; padding: 12px 24px; 
                                           border-radius: 8px; cursor: pointer; font-weight: 600; opacity: 0.5;" 
                                    disabled>
                                Deletar Contas Demo
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Controle de confirmação
            const input = modal.querySelector('#confirmInput');
            const btnConfirm = modal.querySelector('#confirmDelete');
            
            input.addEventListener('input', function() {
                if (this.value === 'CONFIRMAR') {
                    btnConfirm.disabled = false;
                    btnConfirm.style.opacity = '1';
                } else {
                    btnConfirm.disabled = true;
                    btnConfirm.style.opacity = '0.5';
                }
            });
            
            btnConfirm.addEventListener('click', function() {
                if (input.value === 'CONFIRMAR') {
                    modal.remove();
                    
                    // Fazer requisição para deletar
                    const formData = new FormData();
                    formData.append('action', 'limpar_contas_demo');
                    
                    showToast('info', 'Processando...', 'Deletando contas demo do gerador...', 3000);
                    
                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        const responseText = response.text();
                        return responseText.then(text => {
                            // Extrair JSON da resposta
                            let jsonStr = text;
                            const jsonStart = text.indexOf('{');
                            const jsonEnd = text.lastIndexOf('}') + 1;
                            
                            if (jsonStart >= 0 && jsonEnd > jsonStart) {
                                jsonStr = text.substring(jsonStart, jsonEnd);
                            }
                            
                            return JSON.parse(jsonStr);
                        });
                    })
                    .then(result => {
                        if (result.success) {
                            showToast('success', 'Sucesso!', result.message, 5000);
                            
                            // Limpar a tabela de resultados
                            document.getElementById('resultados').style.display = 'none';
                            contasGeradas = [];
                            
                            // Atualizar estatísticas
                            setTimeout(() => {
                                atualizarEstatisticas();
                            }, 1000);
                        } else {
                            showToast('error', 'Erro', result.message, 8000);
                        }
                    })
                    .catch(error => {
                        console.error('Erro detalhado:', error);
                        showToast('error', 'Erro de Comunicação', `Falha na comunicação: ${error.message}`, 8000);
                    });
                }
            });
            
            // Fechar ao clicar fora
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        // Adicionar evento para carregar contas existentes e criar botão de limpeza
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar contas existentes ao carregar a página
            carregarContasExistentes();
            
            // Criar botão de limpeza se houver contas demo
            if (<?= $estatisticas['total_demo'] ?> > 0) {
                const btnLimpar = document.createElement('button');
                btnLimpar.className = 'btn btn-danger btn-modern mt-3 w-100';
                btnLimpar.innerHTML = '<i class="iconoir-trash me-2"></i>Limpar Todas as Contas Demo (<?= $estatisticas['total_demo'] ?>)';
                btnLimpar.onclick = limparContasDemo;
                
                // Adicionar após o formulário
                const formCard = document.querySelector('.demo-card');
                if (formCard) {
                    formCard.appendChild(btnLimpar);
                }
            }
            
            // Verificar se há parâmetros de sucesso na URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === 'demo_created') {
                showToast('success', 'Operação Concluída', 'As contas demo foram geradas com sucesso!', 5000);
            }
            
            // Atualizar estatísticas a cada 30 segundos
            setInterval(atualizarEstatisticas, 30000);
        });
    </script>
</body>
</html>