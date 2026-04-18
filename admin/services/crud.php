<?php
date_default_timezone_set('America/Sao_Paulo');
include_once('database.php');
include_once('funcao.php');
include_once('DemoUser.php');
#=====================================================#
# DATA CONFIG
function data_config()
{
	global $mysqli;
	$qry = "SELECT * FROM config WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$dataconfig = data_config();
#=====================================================#
# DATA POPUPS
function data_popups($id)
{
	global $mysqli;
	$qry = "SELECT * FROM popups WHERE id = '" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
#=====================================================#
# DATA CONFIG
function data_fiverscanPanel()
{
	global $mysqli;
	$qry = "SELECT * FROM fiverscan WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_fiverscanpanel = data_fiverscanPanel();

function data_expfypay()
{
	global $mysqli;
	$qry = "SELECT * FROM expfypay WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_expfypay = data_expfypay();

function data_igamewin()
{
	global $mysqli;
	$qry = "SELECT * FROM igamewin WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_igamewin = data_igamewin();

function data_apiplayfiver()
{
	global $mysqli;
	$qry = "SELECT * FROM playfiver WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_apiplayfiver = data_apiplayfiver();

function data_pgclone()
{
	global $mysqli;
	$qry = "SELECT * FROM pgclone WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_pgclone = data_pgclone();

function data_infinitysoft()
{
	global $mysqli;
	$qry = "SELECT * FROM infinitysoft WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_infinitysoft = data_infinitysoft();

function data_maxapigames()
{
	global $mysqli;
	try {
		$res = mysqli_query($mysqli, "SELECT * FROM maxapigames WHERE id=1");
		if (!$res) return null;
		return mysqli_fetch_assoc($res);
	} catch (Exception $e) {
		return null;
	}
}
$data_maxapigames = data_maxapigames();

function enviarSaldo($email, $saldo)
{
	global $mysqli;

	// Monta a query de atualização
	$qry = "UPDATE usuarios SET saldo = saldo + '" . $saldo . "' WHERE mobile = '" . $email . "'";

	// Executa a consulta
	if (mysqli_query($mysqli, $qry)) {
		return 1;  // Sucesso
	} else {
		return 0;  // Falha
	}
}

#diminuir saldo na api da fiverscan
function withdrawSaldo($email, $saldo)
{
    global $mysqli;

    // Verifica o saldo atual do usuário
    $qryCheckSaldo = "SELECT saldo FROM usuarios WHERE mobile = '" . $email . "'";
    $result = mysqli_query($mysqli, $qryCheckSaldo);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $saldoAtual = $row['saldo'];

        // Verifica se o saldo é suficiente para o saque
        if ($saldoAtual >= $saldo) {
            // Monta a query de atualização do saldo
            $qry = "UPDATE usuarios SET saldo = saldo - '" . $saldo . "' WHERE mobile = '" . $email . "'";

            // Executa a consulta de atualização
            if (mysqli_query($mysqli, $qry)) {
                return 1;  // Sucesso
            } else {
                return 0;  // Falha na execução da query
            }
        } else {
            return -1;  // Saldo insuficiente
        }
    } else {
        return 0;  // Falha ao buscar o saldo ou usuário não encontrado
    }
}


function pegarLinkJogoInfinitySoft($provedor, $game, $email, $saldo)
{
	global $data_infinitysoft, $ids;

	//var_dump($saldo);
	$keys = $data_infinitysoft;


	$data = array(
		//"method" => "POST",
		'agentToken' => $keys['agent_token'],
		'secretKey' => $keys['agent_secret'],
		'user_code' => $email,
		"provider_code" => $provedor,
		"game_code" => $game,
		"user_balance" => floatval($saldo)
		//"lang" => "pt"
	);
	$json_data = json_encode($data);

	$response = enviarRequest($keys['url'] . '/api/v1/game_launch', $json_data);
	$data = json_decode($response, true);


	$games = array('gameURL' => $data['launch_url']);

	return $games;
}

function pegarLinkJogo($provedor, $game, $email, $saldo)
{
	global $data_fiverscanpanel, $ids;

	$keys = $data_fiverscanpanel;
	// Dados para o corpo da requisição em formato JSON
	$data = array(
	    "method" => "game_launch",
		'agent_code' => $keys['agent_code'],
		'agent_token' => $keys['agent_token'],
		'user_code' => $email,
		"provider_code" => $provedor,
		"game_code" => $game,
		"lang" => "en"
		//"user_balance" => intval($saldo)
	);
	$json_data = json_encode($data);
	$response = enviarRequest($keys['url'], $json_data);
	$data = json_decode($response, true);
	//var_dump($data, $response);
	$games = array('gameURL' => $data['launch_url']);
	//$urlgamee = $games;
	return $games;
}

function cupom_usado($cupom, $user_id){
    global $mysqli;
    
    $sql = "SELECT id FROM cupom_usados WHERE id_cupom = '{$cupom}' AND id_user = '{$user_id}' AND tipo != 'cadastro'";
    $res = mysqli_query($mysqli, $sql);
	//$data = mysqli_fetch_assoc($res);
	$qtd = mysqli_num_rows($res);
	
	return $qtd;
}

function usar_cupom($user_id, $valor){
    global $mysqli;
    
    $sql = "SELECT * FROM cupom";
    $res = mysqli_query($mysqli, $sql);
	$data = mysqli_fetch_all($res);
	$cupons = [];
	
	foreach ($data as $i => $cupom){
	    $cupons[$cupom[0]] = [
	        "valor"    => $cupom[3],
	        "qtd"    => $cupom[4],
	        "qtd_insert"    => $cupom[5],
	        "status"    => $cupom[6],
 	    ];
	}
	

	foreach ($cupons as $id => $cupom) {
	    if (intval($valor) >= intval($cupom['valor']) && $cupom['status'] == 1 && $cupom['qtd'] > 0 && cupom_usado($id, $user_id) == 0) {
	        
	        $horario = date('Y-m-d H:m:s');

	        $insert_saldo = mysqli_query($mysqli, "UPDATE usuarios SET saldo = saldo + {$cupom['qtd_insert']} WHERE id = {$user_id}");
	        $remove_cupom_usado = mysqli_query($mysqli, "UPDATE cupom SET qtd = qtd - 1 WHERE id = {$id}");
	        $insert = mysqli_query($mysqli, "INSERT INTO cupom_usados SET id_user = {$user_id}, id_cupom = {$id}, valor = \"{$cupom['qtd_insert']}\", data_time = '{$horario}', tipo='deposito'");
	        
	        return [
	            "sucesso" => true,
	            "cupom_id" => $id,
	            "mensagem" => "Cupom válido encontrado.",
	        ];
	    }/*else{
	        return [
        	    "sucesso" => false,
        	    "mensagem" => "Cupom não encontrado ou já utilizado.",
        	];
	    }*/
	}
	
	return [
	    "sucesso" => false,
	    "mensagem" => "Nenhum cupom válido encontrado para o valor especificado.",
	];
}

/**
 * Verifica se usuário já recebeu bônus de uma configuração específica
 */
function bonus_ja_usado($user_id, $bonus_config_id) {
    global $mysqli;
    
    $sql = "SELECT COUNT(*) as total FROM bonus_historico WHERE user_id = {$user_id} AND bonus_config_id = {$bonus_config_id}";
    $result = mysqli_query($mysqli, $sql);
    $data = mysqli_fetch_assoc($result);
    
    return $data['total'] > 0;
}

/**
 * Busca configuração de bônus ativa
 */
function obter_bonus_ativo() {
    global $mysqli;
    
    $sql = "SELECT * FROM bonus_config 
            WHERE ativo = 1 
            AND (data_expiracao IS NULL OR data_expiracao > NOW())
            AND (limite_usos IS NULL OR usos_atuais < limite_usos)
            ORDER BY porcentagem DESC 
            LIMIT 1";
    
    $result = mysqli_query($mysqli, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Aplica bônus por porcentagem - APENAS UMA VEZ POR USUÁRIO
 */
function aplicar_bonus_primeiro_deposito($user_id, $valor_depositado) {
    global $mysqli;
    
    $bonus_config = obter_bonus_ativo();
    
    if (!$bonus_config) {
        return [
            'sucesso' => false,
            'mensagem' => 'Nenhum bônus disponível no momento.'
        ];
    }
    
    // Verifica se já usou este bônus
    if (bonus_ja_usado($user_id, $bonus_config['id'])) {
        return [
            'sucesso' => false,
            'mensagem' => 'Você já utilizou este bônus anteriormente.'
        ];
    }
    
    // Calcula valor do bônus
    $porcentagem = $bonus_config['porcentagem'];
    $valor_bonus = ($valor_depositado * $porcentagem) / 100;
    
    // Inicia transação
    mysqli_begin_transaction($mysqli);
    
    try {
        // Atualiza saldo do usuário
        $sql_saldo = "UPDATE usuarios SET saldo = saldo + {$valor_bonus} WHERE id = {$user_id}";
        mysqli_query($mysqli, $sql_saldo);
        
        // Registra no histórico
        $sql_historico = "INSERT INTO bonus_historico 
                         (user_id, bonus_config_id, valor_depositado, porcentagem_aplicada, valor_bonus) 
                         VALUES ({$user_id}, {$bonus_config['id']}, {$valor_depositado}, {$porcentagem}, {$valor_bonus})";
        mysqli_query($mysqli, $sql_historico);
        
        // Atualiza contador de usos (se tiver limite)
        if ($bonus_config['limite_usos'] !== null) {
            $sql_uso = "UPDATE bonus_config SET usos_atuais = usos_atuais + 1 WHERE id = {$bonus_config['id']}";
            mysqli_query($mysqli, $sql_uso);
        }
        
        mysqli_commit($mysqli);
        
        return [
            'sucesso' => true,
            'bonus_id' => $bonus_config['id'],
            'bonus_nome' => $bonus_config['nome'],
            'valor_depositado' => $valor_depositado,
            'porcentagem' => $porcentagem,
            'valor_bonus' => $valor_bonus,
            'mensagem' => "Bônus de {$porcentagem}% aplicado! Você ganhou R$ " . number_format($valor_bonus, 2, ',', '.')
        ];
        
    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao aplicar bônus.'
        ];
    }
}

function pegarLinkJogoigamewin($provider, $game, $email)
{
    global $data_igamewin; 
    

    $dataRequest = array(
        "method"        => "game_launch",
        "agent_code"    => $data_igamewin['agent_code'],
        "agent_token"   => $data_igamewin['agent_token'],
        "user_code"     => $email,
        "provider_code" => $provider,
        "game_code"     => $game,
        "lang"          => "en"
    );
    
    $json_data = json_encode($dataRequest);
    $response = enviarRequest($data_igamewin['url'], $json_data);
    $data = json_decode($response, true);
    
    if (isset($data['launch_url'])) {
        $games = array('gameURL' => $data['launch_url']);
    } else {
        error_log("pegarLinkJogoigamewin - Resposta inválida: " . $response);
        $games = array('gameURL' => null, 'error' => $data);
    }
    
    return $games;
}

function pegarLinkJogoMaxApiGames($provider, $game, $email)
{
    global $data_maxapigames;

    if (!$data_maxapigames || empty($data_maxapigames['agent_code'])) {
        error_log("pegarLinkJogoMaxApiGames - Credenciais nao configuradas");
        return array('gameURL' => null, 'error' => 'MaxApiGames nao configurada');
    }

    $dataRequest = array(
        "method"     => "game_launch",
        "agent_code" => $data_maxapigames['agent_code'],
        "agent_token"=> $data_maxapigames['agent_token'],
        "user_code"  => $email,
        "game_code"  => $game
    );

    $json_data = json_encode($dataRequest);
    $response  = enviarRequest($data_maxapigames['url'], $json_data);
    $data      = json_decode($response, true);

    if (isset($data['launch_url'])) {
        return array('gameURL' => $data['launch_url']);
    } else {
        error_log("pegarLinkJogoMaxApiGames - Resposta invalida: " . $response);
        return array('gameURL' => null, 'error' => $data);
    }
}

function pegarLinkJogoApiPlayFiver($provedor, $game, $email, $saldo)
{
    global $data_apiplayfiver, $ids, $mysqli;
    $keys = $data_apiplayfiver;
    
    // Debug array para registrar todas as etapas
    $debug = array(
        'time' => date('Y-m-d H:i:s'),
        'user' => $email,
        'game' => $game,
        'steps' => array()
    );
    
    $debug['steps'][] = "Credenciais: agent_token=" . $keys['agent_token'] . ", agent_secret=" . $keys['agent_secret'];
    
    // Busca informações do jogo (agora inclui game_original)
    $game_info = buscarInformacoesJogo($game, $mysqli, $debug);
    
    // Determina se o jogo é original ou não
    $is_original = determinarJogoOriginal($game_info, $debug);
    $debug['steps'][] = "🎮 Jogo configurado como: " . ($is_original ? "ORIGINAL" : "DEMO/FALSO");
    
    // Verifica se tem rodadas grátis disponíveis (mas não processa ainda)
    // Nota: Jogos originais não processam rodadas grátis
    $tem_rodadas_gratis = false;
    $free_rounds = 0;
    
    if (!$is_original && $game_info['game_id']) {
        $free_rounds = verificarRodadasGratisUsuario($email, $game_info['game_id'], $mysqli, $debug);
        if ($free_rounds > 0) {
            $tem_rodadas_gratis = true;
            $debug['steps'][] = "✅ Usuário tem $free_rounds rodadas grátis disponíveis (serão processadas após o lançamento)";
        } else {
            $debug['steps'][] = "ℹ️ Usuário não tem rodadas grátis disponíveis ou já as utilizou";
        }
    } else if ($is_original) {
        $debug['steps'][] = "ℹ️ Jogo original detectado - rodadas grátis não aplicáveis";
    }
    
    // PRIMEIRO: Lança o jogo (isso cria o usuário na API automaticamente)
    $debug['steps'][] = "=== LANÇANDO O JOGO PRIMEIRO (para criar usuário) ===";
    $resultado = lancarJogoComOriginal($email, $provedor, $game, $saldo, $keys, $is_original, $debug);
    
    // Verifica se o jogo foi lançado com sucesso
    if (!isset($resultado['gameURL'])) {
        $debug['steps'][] = "❌ Erro ao lançar jogo. Não é possível processar rodadas grátis.";
        // Registra o log e retorna erro
        file_put_contents('debug_apiplayfiver_log.txt', json_encode($debug, JSON_PRETTY_PRINT) . PHP_EOL . PHP_EOL, FILE_APPEND);
        return $resultado;
    }
    
    // SEGUNDO: Agora que o usuário existe na API, processa as rodadas grátis (apenas para jogos demo)
    if ($tem_rodadas_gratis && !$is_original) {
        $debug['steps'][] = "=== PROCESSANDO RODADAS GRÁTIS APÓS LANÇAMENTO ===";
        $debug['steps'][] = "Usuário já existe na API. Processando $free_rounds rodadas grátis...";
        
        // Pequeno delay para garantir que o usuário foi criado
        sleep(1);
        
        $sucesso_rodadas = processarRodadasGratis($email, $provedor, $game, $free_rounds, $keys, $mysqli, $debug);
        
        if ($sucesso_rodadas) {
            // IMPORTANTE: Remove as rodadas grátis IMEDIATAMENTE após sucesso
            removerRodadasGratisUsuario($email, $game_info['game_id'], $mysqli, $debug);
            $resultado['free_rounds_applied'] = true;
            $resultado['free_rounds_count'] = $free_rounds;
            $debug['steps'][] = "🎉 Rodadas grátis processadas com sucesso APÓS lançamento!";
            
            // IMPORTANTE: Adiciona instrução para o usuário recarregar
            $resultado['reload_needed'] = true;
            $resultado['reload_message'] = "Rodadas grátis adicionadas! Recarregue o jogo para vê-las.";
        } else {
            $debug['steps'][] = "⚠️ Falha ao processar rodadas grátis, mas jogo foi lançado normalmente";
            $resultado['free_rounds_failed'] = true;
            
            // CRÍTICO: Mesmo se falhou, marca como usado para evitar loop infinito
            // pois pode ser que a API já tenha o bônus mas retornou erro de "já existe"
            removerRodadasGratisUsuario($email, $game_info['game_id'], $mysqli, $debug);
            $debug['steps'][] = "🔒 Rodadas marcadas como usadas para prevenir loop infinito";
        }
    }
    
    $debug['steps'][] = "✅ Jogo lançado com sucesso!";
    
    // Adiciona informação sobre se o jogo é original
    $resultado['is_original'] = $is_original;
    $resultado['game_type'] = $is_original ? 'original' : 'demo';
    
    // Registra o log completo
    file_put_contents('debug_apiplayfiver_log.txt', json_encode($debug, JSON_PRETTY_PRINT) . PHP_EOL . PHP_EOL, FILE_APPEND);
    
    return $resultado;
}

/**
 * Busca informações do jogo na base de dados (versão completa com rodadas grátis)
 */
function buscarInformacoesJogo($game, $mysqli, &$debug)
{
    $query = "SELECT *, COALESCE(game_original, 0) as game_original FROM games WHERE game_code = '$game' OR game_code = " . intval($game);
    $result = mysqli_query($mysqli, $query);
    
    $debug['query'] = $query;
    $debug['query_error'] = mysqli_error($mysqli);
    
    $game_info = array('game_id' => null, 'game_data' => null, 'game_original' => 0);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $game_data = mysqli_fetch_assoc($result);
        $game_info['game_id'] = $game_data['id'];
        $game_info['game_data'] = $game_data;
        $game_info['game_original'] = (int)$game_data['game_original'];
        $debug['steps'][] = "Jogo encontrado (ID: {$game_data['id']}, Original: " . ($game_info['game_original'] ? 'SIM' : 'NÃO') . ")";
        $debug['game_data'] = $game_data;
    } else {
        // Caso especial para Fortune Tiger
        if ($game == '126' || $game == 126) {
            $direct_query = "SELECT *, COALESCE(game_original, 0) as game_original FROM games WHERE id = 2";
            $direct_result = mysqli_query($mysqli, $direct_query);
            
            if ($direct_result && mysqli_num_rows($direct_result) > 0) {
                $game_data = mysqli_fetch_assoc($direct_result);
                $game_info['game_id'] = $game_data['id'];
                $game_info['game_data'] = $game_data;
                $game_info['game_original'] = (int)$game_data['game_original'];
                $debug['steps'][] = "Fortune Tiger encontrado pela ID (Original: " . ($game_info['game_original'] ? 'SIM' : 'NÃO') . ")";
                $debug['game_data'] = $game_data;
            }
        } else {
            $debug['steps'][] = "Jogo não encontrado na tabela games";
        }
    }
    
    return $game_info;
}

/**
 * Determina se o jogo deve ser lançado como original ou demo
 */
function determinarJogoOriginal($game_info, &$debug)
{
    // Se não encontrou o jogo, assume como demo/falso por segurança
    if (!$game_info['game_data']) {
        $debug['steps'][] = "🔍 Jogo não encontrado - usando modo DEMO por padrão";
        return false;
    }
    
    $is_original = (bool)$game_info['game_original'];
    
    // Log da decisão
    if ($is_original) {
        $debug['steps'][] = "✅ Jogo configurado como ORIGINAL na base de dados";
    } else {
        $debug['steps'][] = "📱 Jogo configurado como DEMO/FALSO na base de dados";
    }
    
    return $is_original;
}

/**
 * Verifica se o usuário tem rodadas grátis disponíveis para este jogo (versão final corrigida)
 */
function verificarRodadasGratisUsuario($email, $game_id, $mysqli, &$debug)
{
    // PRIMEIRA VERIFICAÇÃO: Busca qualquer registro existente para este usuário+jogo
    $query_historico = "SELECT id, rounds, used, created_at, used_at FROM user_free_rounds 
                        WHERE user_email = '$email' AND game_id = $game_id 
                        ORDER BY created_at DESC LIMIT 1";
    
    $result_historico = mysqli_query($mysqli, $query_historico);
    $debug['historico_query'] = $query_historico;
    $debug['historico_error'] = mysqli_error($mysqli);
    
    if ($result_historico && mysqli_num_rows($result_historico) > 0) {
        $historico = mysqli_fetch_assoc($result_historico);
        $debug['historico_data'] = $historico;
        
        // Se o registro já foi usado (used = 1), bloqueia novas rodadas
        if ($historico['used'] == 1) {
            $debug['steps'][] = "❌ Usuário já utilizou suas rodadas grátis para este jogo";
            $debug['steps'][] = "📅 Usado em: " . ($historico['used_at'] ?? 'data não registrada');
            return 0; // NÃO há rodadas disponíveis
        }
        
        // Se o registro existe mas ainda não foi usado, verifica se tem rodadas
        if ($historico['rounds'] > 0) {
            $debug['steps'][] = "✅ Usuário tem {$historico['rounds']} rodadas grátis pendentes";
            return (int)$historico['rounds'];
        } else {
            $debug['steps'][] = "⚠️ Registro existe mas sem rodadas disponíveis";
            return 0;
        }
    }
    
    // Se chegou aqui, é a PRIMEIRA VEZ que o usuário joga este jogo
    // Verifica se o jogo oferece rodadas grátis para novos usuários
    $query_game = "SELECT free_bonus_rounds FROM games WHERE id = $game_id AND free_bonus_rounds > 0";
    $result_game = mysqli_query($mysqli, $query_game);
    $debug['game_bonus_query'] = $query_game;
    
    if ($result_game && mysqli_num_rows($result_game) > 0) {
        $game_row = mysqli_fetch_assoc($result_game);
        $available_rounds = (int)$game_row['free_bonus_rounds'];
        
        // Cria o registro inicial para o usuário (primeira vez apenas)
        if (criarRegistroRodadasGratis($email, $game_id, $available_rounds, $mysqli, $debug)) {
            $debug['steps'][] = "🎁 PRIMEIRA VEZ - criadas $available_rounds rodadas grátis para este usuário";
            return $available_rounds;
        }
    }
    
    $debug['steps'][] = "ℹ️ Nenhuma rodada grátis disponível para este jogo";
    return 0;
}

/**
 * Cria registro inicial de rodadas grátis (apenas primeira vez)
 */
function criarRegistroRodadasGratis($email, $game_id, $rounds, $mysqli, &$debug)
{
    // SEGURANÇA: Verifica mais uma vez se não existe registro
    $check_query = "SELECT id FROM user_free_rounds 
                    WHERE user_email = '$email' AND game_id = $game_id 
                    LIMIT 1";
    
    $check_result = mysqli_query($mysqli, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $debug['steps'][] = "❌ BLOQUEADO: Tentativa de criar registro duplicado";
        return false;
    }
    
    // Cria novo registro
    $expires_at = date('Y-m-d H:i:s', strtotime('+7 days'));
    
    $query = "INSERT INTO user_free_rounds (user_email, game_id, rounds, used, created_at, expires_at) 
              VALUES ('$email', $game_id, $rounds, 0, NOW(), '$expires_at')";
    
    $result = mysqli_query($mysqli, $query);
    $debug['criar_rounds_query'] = $query;
    $debug['criar_rounds_error'] = mysqli_error($mysqli);
    
    if ($result) {
        $new_id = mysqli_insert_id($mysqli);
        $debug['steps'][] = "✅ Registro inicial criado (ID: $new_id) com $rounds rodadas";
        return true;
    } else {
        $debug['steps'][] = "❌ Erro ao criar registro: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Função legacy - mantida para compatibilidade (agora usa a nova função)
 */
function atribuirRodadasGratisUsuario($email, $game_id, $rounds, $mysqli, &$debug)
{
    // Redireciona para a nova função
    return criarRegistroRodadasGratis($email, $game_id, $rounds, $mysqli, $debug);
}

/**
 * Remove rodadas grátis do usuário após uso (compatível com estrutura atual)
 */
function removerRodadasGratisUsuario($email, $game_id, $mysqli, &$debug)
{
    // Verifica se as colunas 'used' e 'used_at' existem
    $check_columns = "SHOW COLUMNS FROM user_free_rounds WHERE Field IN ('used', 'used_at')";
    $column_result = mysqli_query($mysqli, $check_columns);
    $has_used_columns = mysqli_num_rows($column_result) >= 2;
    
    if ($has_used_columns) {
        // Usa a versão completa se as colunas existem
        $query = "UPDATE user_free_rounds 
                  SET rounds = 0, used = 1, used_at = NOW() 
                  WHERE user_email = '$email' AND game_id = $game_id";
    } else {
        // Deleta o registro se as colunas não existem (comportamento antigo)
        $query = "DELETE FROM user_free_rounds 
                  WHERE user_email = '$email' AND game_id = $game_id";
    }
    
    $result = mysqli_query($mysqli, $query);
    $debug['remove_rounds_query'] = $query;
    
    if ($result) {
        if ($has_used_columns) {
            $debug['steps'][] = "Rodadas grátis marcadas como usadas para prevenir reatribuição";
        } else {
            $debug['steps'][] = "Registro de rodadas grátis removido (estrutura antiga)";
        }
        return true;
    } else {
        $debug['steps'][] = "Erro ao processar rodadas: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Processa as rodadas grátis via API (APÓS o usuário ter sido criado)
 */
function processarRodadasGratis($email, $provedor, $game, $free_rounds, $keys, $mysqli, &$debug)
{
    $debug['steps'][] = "🎯 Iniciando processamento de $free_rounds rodadas grátis";
    
    // Consulta credenciais da PlayFiver
    $playfiver_query = "SELECT * FROM playfiver WHERE ativo = 1 LIMIT 1";
    $playfiver_result = mysqli_query($mysqli, $playfiver_query);
    
    if (!$playfiver_result || mysqli_num_rows($playfiver_result) == 0) {
        $debug['steps'][] = "ERRO: Não foi possível obter dados da tabela playfiver";
        return false;
    }
    
    $playfiver_data = mysqli_fetch_assoc($playfiver_result);
    
    // Agora que sabemos que o game_launch funciona, vamos usar o MESMO formato
    // que funciona para o game_launch, mas para free_bonus
    $tentativas = array(
        array(
            'nome' => 'Formato IGUAL ao game_launch (que sabemos que funciona)',
            'dados' => array(
                'method' => 'free_bonus',
                'agentToken' => $playfiver_data['agent_token'],
                'secretKey' => $playfiver_data['agent_secret'],
                'user_code' => $email,
                'provider_code' => $provedor,
                'game_code' => $game,
                'rounds' => $free_rounds
            )
        ),
        array(
            'nome' => 'Formato game_launch SEM method',
            'dados' => array(
                'agentToken' => $playfiver_data['agent_token'],
                'secretKey' => $playfiver_data['agent_secret'],
                'user_code' => $email,
                'provider_code' => $provedor,
                'game_code' => $game,
                'rounds' => $free_rounds
            )
        ),
        array(
            'nome' => 'Formato simples snake_case',
            'dados' => array(
                'agent_token' => $playfiver_data['agent_token'],
                'secret_key' => $playfiver_data['agent_secret'],
                'user_code' => $email,
                'game_code' => $game,
                'rounds' => $free_rounds
            )
        ),
        array(
            'nome' => 'Com agent_code incluído',
            'dados' => array(
                'agent_code' => $playfiver_data['agent_code'],
                'agent_token' => $playfiver_data['agent_token'],
                'secret_key' => $playfiver_data['agent_secret'],
                'user_code' => $email,
                'game_code' => $game,
                'rounds' => $free_rounds
            )
        )
    );
    
    // Tenta cada formato
    foreach ($tentativas as $index => $tentativa) {
        $debug['steps'][] = "🎯 Tentativa " . ($index + 1) . ": " . $tentativa['nome'];
        
        try {
            $json_data = json_encode($tentativa['dados']);
            $debug['tentativa_' . ($index + 1) . '_request'] = $tentativa['dados'];
            
            $response = enviarRequest($keys['url'] . '/api/v2/free_bonus', $json_data);
            $response_data = json_decode($response, true);
            
            $debug['tentativa_' . ($index + 1) . '_response_raw'] = $response;
            $debug['tentativa_' . ($index + 1) . '_response'] = $response_data;
            
            // Verifica sucesso
            $sucesso = false;
            if (isset($response_data['status'])) {
                $status = $response_data['status'];
                $sucesso = ($status === true || $status === 1 || $status === "1" || $status === "success");
            }
            
            if ($sucesso) {
                $debug['steps'][] = "✅ SUCESSO! Rodadas grátis processadas na tentativa " . ($index + 1);
                $debug['steps'][] = "📝 Resposta: " . ($response_data['msg'] ?? 'Rodadas processadas');
                return true;
            } else {
                $erro_msg = $response_data['msg'] ?? $response_data['message'] ?? 'Erro desconhecido';
                $debug['steps'][] = "❌ Falha na tentativa " . ($index + 1) . ": " . $erro_msg;
            }
        } catch (Exception $e) {
            $debug['steps'][] = "💥 Exceção na tentativa " . ($index + 1) . ": " . $e->getMessage();
        }
        
        // Delay menor entre tentativas
        if ($index < count($tentativas) - 1) {
            usleep(300000); // 0.3 segundos
        }
    }
    
    $debug['steps'][] = "❌ Todas as tentativas falharam, mas as rodadas permanecem disponíveis";
    return false;
}

/**
 * Lança o jogo incluindo o parâmetro game_original
 */
function lancarJogoComOriginal($email, $provedor, $game, $saldo, $keys, $is_original, &$debug)
{
    $debug['steps'][] = "🚀 Lançando jogo com parâmetro original: " . ($is_original ? 'true' : 'false');
    
    // Consulta credenciais da PlayFiver
    global $mysqli;
    $playfiver_query = "SELECT * FROM playfiver WHERE ativo = 1 LIMIT 1";
    $playfiver_result = mysqli_query($mysqli, $playfiver_query);
    
    if (!$playfiver_result || mysqli_num_rows($playfiver_result) == 0) {
        $debug['steps'][] = "ERRO: Não foi possível obter dados da tabela playfiver";
        return array('error' => 'Configuração da API não encontrada');
    }
    
    $playfiver_data = mysqli_fetch_assoc($playfiver_result);
    
    // Monta os dados para lançamento do jogo
    $game_data = array(
        'method' => 'game_launch',
        'agentToken' => $playfiver_data['agent_token'],
        'secretKey' => $playfiver_data['agent_secret'],
        'user_code' => $email,
        'provider_code' => $provedor,
        'game_code' => $game,
        'game_original' => $is_original, // Parâmetro adicionado
        'user_balance' => floatval($saldo),
        'lang' => 'pt'
    );
    
    $debug['game_launch_request'] = $game_data;
    
    try {
        $json_data = json_encode($game_data);
        $response = enviarRequest($keys['url'] . '/api/v2/game_launch', $json_data);
        $response_data = json_decode($response, true);
        
        $debug['game_launch_response_raw'] = $response;
        $debug['game_launch_response'] = $response_data;
        
        // Verifica se o lançamento foi bem-sucedido
        $sucesso = false;
        $game_url = null;
        
        if (isset($response_data['status']) && $response_data['status'] == 1) {
            $sucesso = true;
            // Tenta pegar a URL do jogo (pode ser launch_url ou gameURL)
            if (isset($response_data['launch_url'])) {
                $game_url = $response_data['launch_url'];
            } elseif (isset($response_data['gameURL'])) {
                $game_url = $response_data['gameURL'];
            }
        }
        
        if ($sucesso && $game_url) {
            $debug['steps'][] = "✅ Jogo lançado com sucesso (" . ($is_original ? 'ORIGINAL' : 'DEMO') . ")";
            return array(
                'gameURL' => $game_url,
                'launch_url' => $game_url, // Compatibilidade
                'success' => true,
                'is_original' => $is_original,
                'game_type' => $is_original ? 'original' : 'demo',
                'user_balance' => $response_data['user_balance'] ?? $saldo,
                'user_created' => $response_data['user_created'] ?? false,
                'name' => $response_data['name'] ?? 'Jogo'
            );
        } else {
            $error_msg = $response_data['msg'] ?? $response_data['message'] ?? 'Erro desconhecido ao lançar jogo';
            $debug['steps'][] = "❌ Erro no lançamento: " . $error_msg;
            return array(
                'error' => $error_msg,
                'success' => false,
                'status' => $response_data['status'] ?? 0
            );
        }
    } catch (Exception $e) {
        $debug['steps'][] = "💥 Exceção ao lançar jogo: " . $e->getMessage();
        return array(
            'error' => 'Erro interno: ' . $e->getMessage(),
            'success' => false
        );
    }
}

/**
 * Função para gerenciar configurações de jogos originais
 */
function gerenciarJogosOriginais($action, $game_id = null, $is_original = null, $mysqli = null, &$debug = array())
{
    // Se não foi passada uma conexão, usa a global
    if (!$mysqli) {
        global $mysqli;
    }
    
    switch ($action) {
        case 'listar':
            return listarStatusJogosOriginais($mysqli, $debug);
            
        case 'atualizar':
            if ($game_id && $is_original !== null) {
                return atualizarStatusJogoOriginal($game_id, $is_original, $mysqli, $debug);
            }
            return false;
            
        case 'marcar_todos_originais':
            return marcarTodosJogosOriginais(true, $mysqli, $debug);
            
        case 'marcar_todos_demo':
            return marcarTodosJogosOriginais(false, $mysqli, $debug);
            
        default:
            return false;
    }
}

/**
 * Lista o status atual de todos os jogos (original/demo)
 */
function listarStatusJogosOriginais($mysqli, &$debug)
{
    $query = "SELECT id, game_name, game_code, COALESCE(game_original, 0) as game_original 
              FROM games 
              ORDER BY game_name";
    
    $result = mysqli_query($mysqli, $query);
    
    if (!$result) {
        $debug['steps'][] = "Erro ao listar jogos: " . mysqli_error($mysqli);
        return false;
    }
    
    $jogos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $jogos[] = array(
            'id' => $row['id'],
            'name' => $row['game_name'],
            'code' => $row['game_code'],
            'is_original' => (bool)$row['game_original'],
            'type' => $row['game_original'] ? 'Original' : 'Demo'
        );
    }
    
    $debug['steps'][] = "Listados " . count($jogos) . " jogos";
    return $jogos;
}

/**
 * Atualiza o status de um jogo específico
 */
function atualizarStatusJogoOriginal($game_id, $is_original, $mysqli, &$debug)
{
    $is_original = $is_original ? 1 : 0;
    
    $query = "UPDATE games SET game_original = $is_original WHERE id = " . intval($game_id);
    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $affected = mysqli_affected_rows($mysqli);
        $debug['steps'][] = "Status do jogo ID $game_id atualizado para: " . ($is_original ? 'Original' : 'Demo');
        return $affected > 0;
    } else {
        $debug['steps'][] = "Erro ao atualizar jogo: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Marca todos os jogos como originais ou demo
 */
function marcarTodosJogosOriginais($is_original, $mysqli, &$debug)
{
    $value = $is_original ? 1 : 0;
    $type = $is_original ? 'Original' : 'Demo';
    
    $query = "UPDATE games SET game_original = $value";
    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $affected = mysqli_affected_rows($mysqli);
        $debug['steps'][] = "Todos os jogos ($affected) marcados como: $type";
        return true;
    } else {
        $debug['steps'][] = "Erro ao atualizar todos os jogos: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Função auxiliar para enviar request com headers customizados
 */
function enviarRequestComHeaders($url, $data, $headers = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ), $headers));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new Exception("Erro cURL: " . $error);
    }
    
    return $response;
}

/**
 * Função auxiliar para envio de requests (mantida para compatibilidade)
 */
function enviarRequest($url, $data)
{
    return enviarRequestComHeaders($url, $data);
}

/**
 * Cria usuário se necessário (sem falhar se já existir)
 */
function criarUsuarioSeNecessario($email, $playfiver_data, $keys, &$debug)
{
    // Tenta apenas se não foi criado recentemente
    $cache_key = "user_created_" . md5($email);
    if (isset($_SESSION[$cache_key])) {
        $debug['steps'][] = "👤 Usuário já foi verificado recentemente";
        return true;
    }
    
    $criado = criarUsuarioNaAPI($email, $playfiver_data, $keys, $debug);
    if ($criado) {
        $_SESSION[$cache_key] = time();
    }
    
    return $criado;
}

/**
 * Marca rodadas como usadas na tabela local
 */
function marcarRodadasComoUsadas($email, $game, $mysqli, &$debug)
{
    $query = "UPDATE user_free_rounds 
              SET used = 1, rounds = 0 
              WHERE user_email = '$email' AND game_id = (
                  SELECT id FROM games WHERE game_code = '$game' LIMIT 1
              )";
    
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        $debug['steps'][] = "✅ Rodadas marcadas como usadas na tabela local";
    } else {
        $debug['steps'][] = "⚠️ Erro ao marcar rodadas como usadas: " . mysqli_error($mysqli);
    }
}

/**
 * Tenta criar usuário na API antes de processar rodadas grátis
 */
function criarUsuarioNaAPI($email, $playfiver_data, $keys, &$debug)
{
    $debug['steps'][] = "🔧 Tentando criar/verificar usuário na API";
    
    $user_data = array(
        'agentToken' => $playfiver_data['agent_token'],
        'secretKey' => $playfiver_data['agent_secret'],
        'user_code' => $email,
        'user_balance' => 0
    );
    
    try {
        $json_data = json_encode($user_data);
        $response = enviarRequest($keys['url'] . '/api/v2/user_create', $json_data);
        $response_data = json_decode($response, true);
        
        $debug['create_user_request'] = $user_data;
        $debug['create_user_response'] = $response_data;
        
        if (isset($response_data['status']) && ($response_data['status'] === true || $response_data['status'] === 1)) {
            $debug['steps'][] = "✅ Usuário criado/verificado com sucesso";
            return true;
        } else {
            $debug['steps'][] = "⚠️ Resposta da criação de usuário: " . ($response_data['msg'] ?? 'Erro desconhecido');
            // Não retorna false aqui porque o usuário pode já existir
            return true;
        }
    } catch (Exception $e) {
        $debug['steps'][] = "💥 Erro ao criar usuário: " . $e->getMessage();
        return false;
    }
}

/**
 * Lança o jogo via API (função mantida para compatibilidade - usa a nova função)
 */
function lancarJogo($email, $provedor, $game, $saldo, $keys, &$debug)
{
    $debug['steps'][] = "=== LANÇANDO O JOGO (modo compatibilidade) ===";
    $debug['steps'][] = "Iniciando lançamento do jogo para $email";
    
    // Busca informações do jogo para determinar se é original
    global $mysqli;
    $game_info = buscarInformacoesJogo($game, $mysqli, $debug);
    $is_original = determinarJogoOriginal($game_info, $debug);
    
    // Usa a nova função com suporte a jogos originais
    return lancarJogoComOriginal($email, $provedor, $game, $saldo, $keys, $is_original, $debug);
}

/**
 * Função para gerenciar rodadas grátis dos usuários
 */
function gerenciarRodadasGratis($action, $email = null, $game_id = null, $rounds = null, $mysqli = null, &$debug = array())
{
    // Se não foi passada uma conexão, usa a global
    if (!$mysqli) {
        global $mysqli;
    }
    
    switch ($action) {
        case 'listar_usuario':
            if ($email) {
                return listarRodadasGratisUsuario($email, $mysqli, $debug);
            }
            return false;
            
        case 'adicionar':
            if ($email && $game_id && $rounds) {
                return criarRegistroRodadasGratis($email, $game_id, $rounds, $mysqli, $debug);
            }
            return false;
            
        case 'remover':
            if ($email && $game_id) {
                return removerRodadasGratisUsuario($email, $game_id, $mysqli, $debug);
            }
            return false;
            
        case 'resetar_usuario_jogo':
            if ($email && $game_id) {
                return resetarRodadasUsuarioJogo($email, $game_id, $mysqli, $debug);
            }
            return false;
            
        case 'listar_todas':
            return listarTodasRodadasGratis($mysqli, $debug);
            
        case 'limpar_expiradas':
            return limparRodadasExpiradas($mysqli, $debug);
            
        default:
            return false;
    }
}

/**
 * Reseta as rodadas grátis de um usuário para um jogo específico (permite receber novamente)
 */
function resetarRodadasUsuarioJogo($email, $game_id, $mysqli, &$debug)
{
    $query = "DELETE FROM user_free_rounds 
              WHERE user_email = '$email' AND game_id = $game_id";
    
    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $affected = mysqli_affected_rows($mysqli);
        $debug['steps'][] = "Resetadas rodadas grátis para usuário $email no jogo ID $game_id (removidos $affected registros)";
        return true;
    } else {
        $debug['steps'][] = "Erro ao resetar rodadas: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Lista todas as rodadas grátis de um usuário específico
 */
function listarRodadasGratisUsuario($email, $mysqli, &$debug)
{
    $query = "SELECT ufr.*, g.game_name, g.game_code 
              FROM user_free_rounds ufr
              JOIN games g ON ufr.game_id = g.id
              WHERE ufr.user_email = '$email' 
              AND ufr.rounds > 0 
              AND (ufr.expires_at IS NULL OR ufr.expires_at > NOW())
              ORDER BY ufr.created_at DESC";
    
    $result = mysqli_query($mysqli, $query);
    
    if (!$result) {
        $debug['steps'][] = "Erro ao listar rodadas do usuário: " . mysqli_error($mysqli);
        return false;
    }
    
    $rodadas = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rodadas[] = array(
            'id' => $row['id'],
            'game_id' => $row['game_id'],
            'game_name' => $row['game_name'],
            'game_code' => $row['game_code'],
            'rounds' => $row['rounds'],
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at']
        );
    }
    
    $debug['steps'][] = "Encontradas " . count($rodadas) . " rodadas grátis para o usuário $email";
    return $rodadas;
}

/**
 * Lista todas as rodadas grátis ativas no sistema
 */
function listarTodasRodadasGratis($mysqli, &$debug)
{
    $query = "SELECT ufr.*, g.game_name, g.game_code 
              FROM user_free_rounds ufr
              JOIN games g ON ufr.game_id = g.id
              WHERE ufr.rounds > 0 
              AND (ufr.expires_at IS NULL OR ufr.expires_at > NOW())
              ORDER BY ufr.created_at DESC";
    
    $result = mysqli_query($mysqli, $query);
    
    if (!$result) {
        $debug['steps'][] = "Erro ao listar todas as rodadas: " . mysqli_error($mysqli);
        return false;
    }
    
    $rodadas = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rodadas[] = array(
            'id' => $row['id'],
            'user_email' => $row['user_email'],
            'game_id' => $row['game_id'],
            'game_name' => $row['game_name'],
            'game_code' => $row['game_code'],
            'rounds' => $row['rounds'],
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at']
        );
    }
    
    $debug['steps'][] = "Encontradas " . count($rodadas) . " rodadas grátis ativas no sistema";
    return $rodadas;
}

/**
 * Remove rodadas grátis expiradas do sistema
 */
function limparRodadasExpiradas($mysqli, &$debug)
{
    $query = "DELETE FROM user_free_rounds 
              WHERE expires_at IS NOT NULL 
              AND expires_at <= NOW()";
    
    $result = mysqli_query($mysqli, $query);
    
    if ($result) {
        $affected = mysqli_affected_rows($mysqli);
        $debug['steps'][] = "Removidas $affected rodadas grátis expiradas";
        return true;
    } else {
        $debug['steps'][] = "Erro ao limpar rodadas expiradas: " . mysqli_error($mysqli);
        return false;
    }
}

/**
 * Função para verificar e atualizar configurações de jogos
 */
function verificarConfiguracaoJogos($mysqli = null, &$debug = array())
{
    if (!$mysqli) {
        global $mysqli;
    }
    
    // Verifica se a coluna game_original existe
    $query = "SHOW COLUMNS FROM games LIKE 'game_original'";
    $result = mysqli_query($mysqli, $query);
    
    if (mysqli_num_rows($result) == 0) {
        // Adiciona a coluna game_original se não existir
        $alter_query = "ALTER TABLE games ADD COLUMN game_original TINYINT(1) DEFAULT 0 COMMENT 'Define se o jogo é original (1) ou demo (0)'";
        $alter_result = mysqli_query($mysqli, $alter_query);
        
        if ($alter_result) {
            $debug['steps'][] = "✅ Coluna 'game_original' adicionada à tabela 'games'";
        } else {
            $debug['steps'][] = "❌ Erro ao adicionar coluna 'game_original': " . mysqli_error($mysqli);
            return false;
        }
    } else {
        $debug['steps'][] = "✅ Coluna 'game_original' já existe na tabela 'games'";
    }
    
    // Verifica se a tabela user_free_rounds existe
    $query = "SHOW TABLES LIKE 'user_free_rounds'";
    $result = mysqli_query($mysqli, $query);
    
    if (mysqli_num_rows($result) == 0) {
        // Cria a tabela user_free_rounds se não existir
        $create_query = "CREATE TABLE user_free_rounds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_email VARCHAR(255) NOT NULL,
            game_id INT NOT NULL,
            rounds INT NOT NULL DEFAULT 0,
            used TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            used_at TIMESTAMP NULL,
            expires_at TIMESTAMP NULL,
            INDEX idx_user_game (user_email, game_id),
            INDEX idx_expires (expires_at),
            UNIQUE KEY unique_user_game (user_email, game_id),
            FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $create_result = mysqli_query($mysqli, $create_query);
        
        if ($create_result) {
            $debug['steps'][] = "✅ Tabela 'user_free_rounds' criada com sucesso";
        } else {
            $debug['steps'][] = "❌ Erro ao criar tabela 'user_free_rounds': " . mysqli_error($mysqli);
            return false;
        }
    } else {
        $debug['steps'][] = "✅ Tabela 'user_free_rounds' já existe";
        
        // Verifica se a coluna used_at existe, se não, adiciona
        $check_column = "SHOW COLUMNS FROM user_free_rounds LIKE 'used_at'";
        $column_result = mysqli_query($mysqli, $check_column);
        
        if (mysqli_num_rows($column_result) == 0) {
            $add_column = "ALTER TABLE user_free_rounds ADD COLUMN used_at TIMESTAMP NULL AFTER used";
            if (mysqli_query($mysqli, $add_column)) {
                $debug['steps'][] = "✅ Coluna 'used_at' adicionada à tabela 'user_free_rounds'";
            }
        }
        
        // Verifica se existe a constraint única, se não, adiciona
        $check_unique = "SHOW INDEX FROM user_free_rounds WHERE Key_name = 'unique_user_game'";
        $unique_result = mysqli_query($mysqli, $check_unique);
        
        if (mysqli_num_rows($unique_result) == 0) {
            $add_unique = "ALTER TABLE user_free_rounds ADD UNIQUE KEY unique_user_game (user_email, game_id)";
            if (mysqli_query($mysqli, $add_unique)) {
                $debug['steps'][] = "✅ Constraint única 'unique_user_game' adicionada";
            }
        }
    }
    
    // Verifica se a coluna free_bonus_rounds existe na tabela games
    $query = "SHOW COLUMNS FROM games LIKE 'free_bonus_rounds'";
    $result = mysqli_query($mysqli, $query);
    
    if (mysqli_num_rows($result) == 0) {
        // Adiciona a coluna free_bonus_rounds se não existir
        $alter_query = "ALTER TABLE games ADD COLUMN free_bonus_rounds INT DEFAULT 0 COMMENT 'Número de rodadas grátis que este jogo oferece'";
        $alter_result = mysqli_query($mysqli, $alter_query);
        
        if ($alter_result) {
            $debug['steps'][] = "✅ Coluna 'free_bonus_rounds' adicionada à tabela 'games'";
        } else {
            $debug['steps'][] = "❌ Erro ao adicionar coluna 'free_bonus_rounds': " . mysqli_error($mysqli);
            return false;
        }
    } else {
        $debug['steps'][] = "✅ Coluna 'free_bonus_rounds' já existe na tabela 'games'";
    }
    
    return true;
}

/**
 * Função utilitária para debug e logs
 */
function logDebugCompleto($debug, $filename = 'debug_apiplayfiver_log.txt')
{
    $log_entry = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'debug_data' => $debug
    );
    
    file_put_contents($filename, json_encode($log_entry, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL . str_repeat('=', 80) . PHP_EOL, FILE_APPEND);
}

/**
 * Função para estatísticas do sistema
 */
function obterEstatisticasRodadasGratis($mysqli = null, &$debug = array())
{
    if (!$mysqli) {
        global $mysqli;
    }
    
    $stats = array();
    
    // Total de rodadas ativas
    $query = "SELECT COUNT(*) as total_usuarios, SUM(rounds) as total_rodadas 
              FROM user_free_rounds 
              WHERE rounds > 0 
              AND (expires_at IS NULL OR expires_at > NOW())";
    
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['total_usuarios_com_rodadas'] = $row['total_usuarios'];
        $stats['total_rodadas_ativas'] = $row['total_rodadas'];
    }
    
    // Rodadas por jogo
    $query = "SELECT g.game_name, g.game_code, COUNT(*) as usuarios, SUM(ufr.rounds) as total_rodadas
              FROM user_free_rounds ufr
              JOIN games g ON ufr.game_id = g.id
              WHERE ufr.rounds > 0 
              AND (ufr.expires_at IS NULL OR ufr.expires_at > NOW())
              GROUP BY g.id
              ORDER BY total_rodadas DESC";
    
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        $jogos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $jogos[] = $row;
        }
        $stats['rodadas_por_jogo'] = $jogos;
    }
    
    // Rodadas expiradas hoje
    $query = "SELECT COUNT(*) as total_expiradas 
              FROM user_free_rounds 
              WHERE expires_at IS NOT NULL 
              AND DATE(expires_at) = CURDATE()";
    
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $stats['rodadas_expirando_hoje'] = $row['total_expiradas'];
    }
    
    $debug['steps'][] = "Estatísticas de rodadas grátis compiladas";
    return $stats;
}


function distribution($code) {
    global $mysqli;
    
    // Buscar pela coluna distribution baseado no code/game_code
    $game_code = gamecode($code); // Usar a função existente para obter o game_code
    
    $qry = "SELECT distribution FROM games WHERE game_code = '" . mysqli_real_escape_string($mysqli, $game_code) . "' LIMIT 1";
    $result = mysqli_query($mysqli, $qry);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['distribution'];
    }
    
    return 'PlayFiver'; // Padrão se não encontrar
}

function demo_user_email($id)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE mobile='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$data = mysqli_fetch_assoc($res);
		$demo = $data['statusaff'];
	} else {
		$demo = 0;
	}
	return $demo;
}

function get_rtp_config()
{
	global $mysqli;
	$qry = "SELECT * FROM pgclone";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$demo = mysqli_fetch_array($res);
	} else {
		$demo = 0;
	}
	return $demo;
}

function pegarLinkJogoPGClone($provedor, $game, $email, $saldo)
{
    global $data_pgclone, $ids;

//    $is_demo = demo_user_email($email); // Verifica se é demo
//    $config_pg = get_rtp_config();      // Obtém a config de RTP

      $keys = $data_pgclone;

//    Instancia e configura o objeto DemoUser
//    $demo = new DemoUser();
//    $demo->setDemo($email, $is_demo);

//    if (is_array($config_pg)) {
//        $agent_code = $config_pg['agent_code'];
//        $demo->setConfig($agent_code, $config_pg);
//    }

    // Dados para o corpo da requisição em formato JSON
    $data = array(
        'agentToken'    => $keys['agent_token'],
        'secretKey'     => $keys['agent_secret'],
        'user_code'     => $email,
        'provider_code' => 'PGSOFT',
        'game_code'     => $game,
        'user_balance'  => floatval($saldo)
    );

    $json_data = json_encode($data);
    $response = enviarRequest($keys['url'] . '/api/v1/game_launch', $json_data);
    $data = json_decode($response, true);

    $games = array('gameURL' => $data['launch_url']);
    return $games;
}

function consultarSaldoAgente($email)
{
    global $data_fiverscanpanel;
    $keys = $data_fiverscanpanel;
    
    $dataRequest = array(
        "agentToken" => $keys['agent_code'],
        "secretKey" => $keys['agent_token'],
    );

    $json_data = json_encode($dataRequest);

    $url = 'https://api.playfivers.com/api/v2/balance';
    $response = enviarRequest($url, $json_data);

    $data = json_decode($response, true);

    if (isset($data['data']['balance'])) {
        $saldoAgente = $data['data']['balance'];
        return $saldoAgente;
    } else {
        return '0.00';
    }
}

//  CRIAR USER API FIVERSCAN
function criarUsuarioAPI($email)
{
	global $data_fiverscanpanel;
	$keys = $data_fiverscanpanel;
	$postArray = [
	    "method" => "game_launch",
		'agent_code' => $keys['agent_code'],
		'agent_token' => $keys['agent_token'],
		'user_code' => $email,
		"provider_code" => "PGSOFT",
		"game_code" => "fortune-tiger",
		"lang" => "en"
	];
	$jsonData = json_encode($postArray);
	$headerArray = ['Content-Type: application/json'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $keys['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($ch);
	curl_close($ch);
	// Verifique se houve algum erro durante a solicitação
	//$json = '{"status":1,"msg":"SUCCESS","fc_code":"fc104688","user_code":"claudio.web.dev@gmail.com","user_balance":0}';
	$data = json_decode($res, true);
	//var_dump($data);
	// Verifica se a decodificação foi bem-sucedida
	if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
		$SF = 0;
		die('Erro na decodificação JSON: ' . json_last_error_msg());
	}
	if ($data['status'] == 1 and $data['msg'] == "SUCCESS") {
		$SF = 1;
	} else {
		$SF = 0;
	}
	return $SF;
}
#=====================================================#
function afiliado_de_quem($refer)
{
	global $mysqli;
	$qry = "SELECT real_name FROM usuarios WHERE invite_code='" . $refer . "'";
	$res = mysqli_query($mysqli, $qry);
	$dinheiro = 'Sem afiliação'; // Valor padrão

	if ($res) {
		while ($row = mysqli_fetch_assoc($res)) {
			if (!empty($row['real_name'])) {
				$dinheiro = $row['real_name'];
			}
		}
	}

	return $dinheiro;
}
#=====================================================#

# DATA CONFIG BSPAY
function data_fivepay()
{
	global $mysqli;
	$qry = "SELECT * FROM fivepay WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_fivepay = data_fivepay();

# DATA BULLSPAY
function data_bullspay()
{
	global $mysqli;
	$qry = "SELECT * FROM bullspay WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_bullspay = data_bullspay();

# DATA CONFIG SUITPAY
function data_bspay()
{
	global $mysqli;
	$qry = "SELECT * FROM bspay WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_bspay = data_bspay();

#=====================================================#
# DATA CONFIG SUITPAY
function data_suitpay()
{
	global $mysqli;
	$qry = "SELECT * FROM suitpay WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_suitpay = data_suitpay();

#=====================================================#
# DATA CONFIG SUITPAY
function data_versell()
{
	global $mysqli;
	$qry = "SELECT * FROM versell WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_versell = data_versell();

#=====================================================#
# DATA CONFIG
function data_afiliados_cpa_rev()
{
	global $mysqli;
	$qry = "SELECT * FROM afiliados_config WHERE id=1";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}
$data_afiliados_cpa_rev = data_afiliados_cpa_rev();
#=====================================================#
#criar financeiro
function criar_financeiro($id)
{
	global $mysqli;
	$sql1 = $mysqli->prepare("INSERT INTO financeiro (usuario,saldo,bonus) VALUES (?,0,0)");
	$sql1->bind_param("i", $id);
	if ($sql1->execute()) {
		$tr = 1; //certo
	} else {
		$tr = 0; //erro
	}
	return $tr;
}

# count saque
function tabelasaldouser($id)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE id='" . intval($id) . "'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['saldo'] > 0) {
			$dinheiro = $row['saldo'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}
#=====================================================#
#criar financeiro
function criar_tokenrefer($id)
{
	global $mysqli;
	$aftoken = 'af' . $id . token_aff();
	$sql = $mysqli->prepare("UPDATE usuarios SET token_refer=? WHERE id=?");
	$sql->bind_param("si", $aftoken, $id);
	if ($sql->execute()) {
		$tr = 1; //certo
	} else {
		$tr = 0; //erro

	}
	return $tr;
}
#=====================================================#

#=====================================================#
// saldo atual do user
function saldo_user($id)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE id='" . intval($id) . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$data = mysqli_fetch_assoc($res);
		$saldo_arr = array(
			"saldo" => $data['saldo'],
			"saldo_afiliado" => $data['saldo_afiliados']
		);
	} else {
		$saldo_arr = array(
			"saldo" => 0,
			"saldo_afiliado" => 0
		);
	}
	return $saldo_arr;
}
#=====================================================#
// atualiza saldo do user
function att_saldo_user($saldo, $id)
{
	global $mysqli;
	$id_user = intval($id);
	$sql = $mysqli->prepare("UPDATE usuarios SET saldo=? WHERE id=?");
	$sql->bind_param("di", $saldo, $id_user);
	if ($sql->execute()) {
		$rt = 1;
	} else {
		$rt = 0;

	}
	return $rt;
}

function saldo_user_pix($id)
{
	global $mysqli;
	$qry = "SELECT * FROM metodos_pagamentos WHERE pix_id='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$data = mysqli_fetch_assoc($res);
		$saldo = saldo_user($data['user_id'])['saldo'];
		
		$info = array(
		    "saldo" => $saldo,
		    "user_id" => $data['user_id']
		);
		
		return $info;
	}
}

#=====================================================#
// financeiro user atual do user
function financeiro_saldo_user($id)
{
	global $mysqli;
	$qry = "SELECT * FROM financeiro WHERE usuario='" . intval($id) . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$saldo = mysqli_fetch_assoc($res);
	} else {
		$saldo = 0;
	}
	return $saldo;
}
#=====================================================#
//  se exisitr refer 1
function pegar_refer($refer)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE token_refer='" . $refer . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$ex_refer = 1;
	} else {
		$ex_refer = 0;
	}
	return $ex_refer;
}
#=====================================================#
#=====================================================#
//  DELETAR USER
function deletar_user($id)
{
	global $mysqli;
	$sql = $mysqli->prepare("DELETE FROM  usuarios WHERE id=?");
	$sql->bind_param("i", $id);
	$sql->execute();

	$sql99 = $mysqli->prepare("DELETE FROM  financeiro WHERE usuario=?");
	$sql99->bind_param("i", $id);
	$sql99->execute();
}
#=====================================================#
function enviarRequest_PAYMENT($url, $header, $data = null)
{
	$ch = curl_init();
	$data_json = json_encode($data);

	// Configurando as opções do cURL
	curl_setopt($ch, CURLOPT_URL, $url);
	if (!$data == null) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Executando a requisição e obtendo a resposta
	$response = curl_exec($ch);

	// Fechando a conexão cURL
	curl_close($ch);

	return $response;
}
#=====================================================#
function requestToken_PAYMENT($url, $header, $data)
{
	$ch = curl_init();

	// Configurando as opções do cURL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Executando a requisição e obtendo a resposta
	$response = curl_exec($ch);

	// Fechando a conexão cURL
	curl_close($ch);

	return $response;
}
#=====================================================#
#request pix
function request_paymentPIX($transactionId)
{
	global $data_suitpay, $tipoAPI_SUITPAY;
	if ($tipoAPI_SUITPAY == 0) {
		$url = 'https://sandbox.ws.suitpay.app/api/v1/gateway/consult-status-transaction';
		$data = array(
			'typeTransaction' => "PIX",
			'idTransaction' => $transactionId
		);
		$header = array(
			'ci: testesandbox_1687443996536',
			'cs: 5b7d6ed3407bc8c7efd45ac9d4c277004145afb96752e1252c2082d3211fe901177e09493c0d4f57b650d2b2fc1b062d',
			'Content-Type: application/json',
		);
	} else {
		$url = $data_suitpay['url'] . '/api/v1/gateway/consult-status-transaction';
		$data = array(
			'typeTransaction' => "PIX",
			'idTransaction' => $transactionId
		);
		$header = array(
			'ci: ' . $data_suitpay['client_id'],
			'cs: ' . $data_suitpay['client_secret'],
			'Content-Type: application/json'
		);

	}
	$response = enviarRequest_PAYMENT($url, $header, $data);
	$dados = json_decode($response, true);
	return $dados;
}
#=====================================================#
# coun refer direto
function count_refer_direto($refer)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE invitation_code='" . $refer . "'";
	$res = mysqli_query($mysqli, $qry);
	$ex_refer = mysqli_num_rows($res);
	return $ex_refer;
}
#=====================================================#
# count saque
function total_saques_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE id_user='" . $id . "'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}
#=====================================================#
# count depositos
function total_dep_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE usuario='" . $id . "' AND tipo='deposito'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}

function total_dep_pagos_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE usuario='" . $id . "' AND tipo='deposito' AND status='pago'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}

function total_dep_afiliado($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE usuario IN (SELECT id FROM usuarios where invitation_code = '" . $id . "') AND tipo='deposito' AND status='pago'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}
#=====================================================#
# SUM TOTAL ID CPA/REV
function total_CPA_REV_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM pay_valores_cassino WHERE id_user='" . $id . "' AND tipo=0 OR tipo=1";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}

function total_CPA_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM pay_valores_cassino WHERE id_user='" . $id . "' AND tipo=0";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}

function total_REV_id($id)
{
	global $mysqli;
	$qry = "SELECT SUM(valor) as total_soma FROM pay_valores_cassino WHERE id_user='" . $id . "' AND tipo=1";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_soma'] > 0) {
			$dinheiro = $row['total_soma'];
		} else {
			$dinheiro = '0.00';
		}
	}
	return $dinheiro;
}

#=====================================================#
# DATA USER ID
function data_user_id($id)
{
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE id='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}

function gamecode($id)
{
	global $mysqli;
	$qry = "SELECT game_code FROM games WHERE id='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data['game_code'];
}

function gameprovider($id)
{
	global $mysqli;
	$qry = "SELECT provider FROM games WHERE game_code='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data['provider'];
}

function localizarchavepix($id)
{
    global $mysqli;
    $qry = "SELECT pix_id, pix_account FROM metodos_pagamentos WHERE id='" . $id . "'";
    $res = mysqli_query($mysqli, $qry);
    
    if ($res && $data = mysqli_fetch_assoc($res)) {
		$pixId = $data["pix_id"];
		$pixAccount = $data["pix_account"];

        return $pixAccount == null ? $pixId : $pixAccount;
    } else {
        return null; // Return null if no result is found
    }
}

function localizarusuarioporpix($id)
{
    error_log("ID-->>".$id);
	$tamanho = strlen($id);
    $whereCriteria = $tamanho == 11 ? "pix_id" : "pix_account";
	global $mysqli;
	$qry = "SELECT * FROM usuarios WHERE id = (SELECT user_id FROM metodos_pagamentos WHERE " . $whereCriteria . " = '" . $id . "')";
	
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	
	return $data['real_name'];
}

function localizarpix($id)
{
    error_log("ID-->>".$id);
	$tamanho = strlen($id);
    $whereCriteria = $tamanho == 11 ? "pix_id" : "pix_account";
    
	global $mysqli;
	$qry = "SELECT * FROM metodos_pagamentos WHERE ". $whereCriteria . " = '" . $id . "'";
	
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	
	return $data;
}

#=====================================================#
#inserir saldo
function adicionarsaldo($id, $valor)
{
	global $mysqli;
	$qry = "UPDATE financeiro SET saldo= saldo + '" . $valor . "' WHERE usuario='" . $id . "'";
	$res = mysqli_query($mysqli, $qry);
	$data = mysqli_fetch_assoc($res);
	return $data;
}

function requestaddsaldo($email, $valor)
{
	$data = array(
		'user_code' => $email,
		'valor' => $valor
	);
	$json_data = json_encode($data);
	$response = enviarRequest('https://api.zenbet.online/api/v1/adicionarsaldo', $json_data);
	$dados = json_decode($response, true);
	return $dados;
}

#=====================================================#
#inserir saldo
function insert_payment_adm($id, $email, $valor)
{
	global $mysqli;
	$tokentrans = '#pixdinamic-' . rand(99, 99999);
	$data_hora = date('Y-m-d H:i:s');
	$sql1 = $mysqli->prepare("INSERT INTO transacoes (transacao_id,usuario,valor,data_hora,tipo,status,code) VALUES (?,?,?,?,'deposito','pago','dinamico')");
	$sql1->bind_param("ssss", $tokentrans, $id, $valor, $data_hora);
	#ENVIA SALDO VIA API
	$retorna_insert_saldo_suit_pay = enviarSaldo($email, $valor);
	if ($retorna_insert_saldo_suit_pay['status'] == 1 and $retorna_insert_saldo_suit_pay['msg'] == "SUCCESS" and $sql1->execute()) {
		$ert = 1;
	} else {
		$ert = 0;
	}
	return $ert;
}


function numero_total_dep($id)
{
	global $mysqli;
	$qry = "SELECT COUNT(*) as total_count FROM transacoes WHERE usuario IN (SELECT id FROM usuarios WHERE invitation_code = '" . $id . "') AND tipo='deposito' AND status='pago'";
	$result = mysqli_query($mysqli, $qry);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row['total_count'] > 0) {
			$total_count = $row['total_count'];
		} else {
			$total_count = 0;
		}
	}
	return $total_count;
}

#retirar saldo
function retirarsaldo($email, $valor)
{
	$data = array(
		'user_code' => $email,
		'valor' => $valor
	);
	$json_data = json_encode($data);
	$response = enviarRequest('https://api.zenbet.online/api/v1/removersaldo', $json_data);
	$dados = json_decode($response, true);
	return $dados;
}
#=====================================================#
#contar visitas
function visitas_count($tipo)
{
	global $mysqli;
	$data_hoje = date("Y-m-d");
	if ($tipo == 'diario') {
		$qry = "SELECT * FROM visita_site WHERE data_cad='" . $data_hoje . "'";
		$res = mysqli_query($mysqli, $qry);
		$count = mysqli_num_rows($res);
	} elseif ($tipo == 'total') {
		$qry = "SELECT * FROM visita_site";
		$res = mysqli_query($mysqli, $qry);
		$count = mysqli_num_rows($res);
	} else {
		$count = 0;
	}
	return $count;
}
function visitas_count2($tipo) {
    global $mysqli;
    
    if ($tipo == 'diario') {
        $data_hoje = date("Y-m-d");
        $qry = "SELECT cidade, estado, COUNT(*) as total 
                FROM visita_site 
                WHERE data_cad='$data_hoje' 
                GROUP BY cidade, estado 
                ORDER BY total DESC 
                LIMIT 1";
    } elseif ($tipo == 'total') {
        $qry = "SELECT cidade, estado, COUNT(*) as total 
                FROM visita_site 
                GROUP BY cidade, estado 
                ORDER BY total DESC 
                LIMIT 1";
    } else {
        return ['cidade' => null, 'estado' => null, 'total' => 0];
    }

    $res = mysqli_query($mysqli, $qry);
    
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return [
            'cidade' => $row['cidade'],
            'estado' => $row['estado'],
            'total' => $row['total']
        ];
    } else {
        return ['cidade' => null, 'estado' => null, 'total' => 0];
    }
}

#=====================================================#
# busca por token retorn o id
function busca_id_por_refer($token)
{
	global $mysqli;

	$qry = "SELECT * FROM usuarios WHERE token_refer='" . $token . "'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$data = mysqli_fetch_assoc($res);
		$count = $data['id'];
	} else {
		$count = 0;
	}
	return $count;
}
#=====================================================#
function generateQRCode_pix($data)
{
	// Carregue a biblioteca PHP QR Code
	require_once __DIR__ . '/../libraries/phpqrcode/qrlib.php';
	// Caminho onde você deseja salvar o arquivo PNG do QRCode (opcional)
	$file = '../../uploads/qrcode.png';
	// Gere o QRCode
	QRcode::png($data, $file);
	// Carregue o arquivo PNG do QRCode
	$qrCodeImage = file_get_contents($file);
	// Converta a imagem para base64
	$base64QRCode = base64_encode($qrCodeImage);
	return $base64QRCode;
}
#=====================================================#
# busca por ALERT DEP PENDENTES id
function busca_dep_pendentes($id)
{
	global $mysqli;
	$qry = "SELECT * FROM transacoes WHERE usuario='" . $id . "' AND tipo='deposito' AND status='processamento'";
	$res = mysqli_query($mysqli, $qry);
	if (mysqli_num_rows($res) > 0) {
		$data = 1;
	} else {
		$data = 0;
	}
	return $data;
}

// Função para buscar depósitos por dia
function depositos_por_dia()
{
	global $mysqli;
	// Usamos DATE() para extrair apenas a data, ignorando a hora
	$qry = "SELECT DATE(data_hora) as dia, COUNT(*) as total FROM transacoes WHERE status = 'pago' AND tipo = 'deposito' GROUP BY DATE(data_hora) ORDER BY dia DESC LIMIT 7";
	$result = mysqli_query($mysqli, $qry);

	$dados = [];
	if ($result && mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$dados[] = [
				'dia' => $row['dia'],          // Retorna a data no formato YYYY-MM-DD
				'total' => intval($row['total']) // Conta a quantidade de depósitos
			];
		}
	}
	return $dados;
}



// Função para buscar saques por dia
function saques_por_dia()
{
	global $mysqli;
	$qry = "SELECT DATE(data_cad) as dia, COUNT(*) as total FROM solicitacao_saques WHERE status = 1 GROUP BY DATE(data_cad) ORDER BY dia DESC LIMIT 7";
	$result = mysqli_query($mysqli, $qry);

	$dados = [];
	if ($result && mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$dados[] = [
				'dia' => $row['dia'],
				'total' => intval($row['total'])  // Conta a quantidade de saques
			];
		}
	}
	return $dados;
}

function WebhookCadastro($nome_user, $url)
{
    global $mysqli;

    $queryWebhook = "SELECT * FROM webhook WHERE id = 1 AND status = 1";
    $resultWebhook = mysqli_query($mysqli, $queryWebhook);
    
    while ($webhook = mysqli_fetch_assoc($resultWebhook)) {
        $bot_id = $webhook['bot_id'];
        $chat_id = $webhook['chat_id'];
        $message = "✅ Cadastro realizado com sucesso!\n";
        $message .= "🏷️ Nome: $nome_user\n";
        $message .= "🌐 URL do site: $url";
        $urlTelegram = "https://api.telegram.org/bot$bot_id/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
        file_get_contents($urlTelegram);
    }
}

function WebhookPixGerado($nome_user, $url, $valor)
{
    global $mysqli;
    $valor = number_format((float)$valor, 2, '.', ''); 

    $queryWebhook = "SELECT * FROM webhook WHERE id = 1 AND status = 1";
    $resultWebhook = mysqli_query($mysqli, $queryWebhook);
    
    while ($webhook = mysqli_fetch_assoc($resultWebhook)) {
        $bot_id = $webhook['bot_id'];
        $chat_id = $webhook['chat_id'];
        $message = "✅ Pix gerado com sucesso\n";
        $message .= "💰 Valor: R$$valor\n";
        $message .= "⏳ Status: Pendente\n";
        $message .= "🏷️ Nome: $nome_user\n";
        $message .= "🌐 URL do site: $url";
        $urlTelegram = "https://api.telegram.org/bot$bot_id/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
        file_get_contents($urlTelegram);
    }
}

function WebhookPixPagos($nome_user, $url, $valor)
{
    global $mysqli;

    $valorFormatado = is_array($valor) || is_object($valor) ? $valor['valor'] : $valor;

    $queryWebhook = "SELECT * FROM webhook WHERE id = 1 AND status = 1";
    $resultWebhook = mysqli_query($mysqli, $queryWebhook);
    
    while ($webhook = mysqli_fetch_assoc($resultWebhook)) {
        $bot_id = $webhook['bot_id'];
        $chat_id = $webhook['chat_id'];
        $message = "✅ Pix pago com sucesso\n";
        $message .= "💰 Valor: R$$valorFormatado\n";
        $message .= "⏳ Status: Pago\n";
        $message .= "🏷️ Nome: $nome_user\n";
        $message .= "🌐 URL do site: $url";
        $urlTelegram = "https://api.telegram.org/bot$bot_id/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
        file_get_contents($urlTelegram);
    }
}

function WebhookSaquePendente($nome_user, $url, $valor)
{
    global $mysqli;
    $valor = number_format((float)$valor, 2, '.', '');
    
    // Buscar especificamente o webhook de "Saques"
    $queryWebhook = "SELECT * FROM webhook WHERE id = 2 AND status = 1";
    $resultWebhook = mysqli_query($mysqli, $queryWebhook);
    
    while ($webhook = mysqli_fetch_assoc($resultWebhook)) {
        $bot_id = $webhook['bot_id'];
        $chat_id = $webhook['chat_id'];
        $message = "📤 Solicitação de saque recebida\n";
        $message .= "💸 Valor: R$$valor\n";
        $message .= "⏳ Status: Pendente\n";
        $message .= "🏷️ Nome: $nome_user\n";
        $message .= "🌐 URL do site: https://$url";
        $urlTelegram = "https://api.telegram.org/bot$bot_id/sendMessage?chat_id=$chat_id&text=" . urlencode($message);
        file_get_contents($urlTelegram);
    }
}

function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . $host;
}

?>