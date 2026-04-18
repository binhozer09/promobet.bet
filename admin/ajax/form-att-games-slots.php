<?php
session_start();
include_once('../services/database.php');
include_once('../services/funcao.php');
include_once('../services/crud-adm.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
#include_once('../services/checa_login_adm.php');
#expulsa user
#checa_login_adm();
#------------------------------------------------------------#
function att_game_slots_providers($gameCode, $gameName, $banner, $gameStatus, $provedor)
{
	global $mysqli;
	//$provedorxx = preg_replace("/[^a-zA-Z]/", "", $provedor);
	$stmt = $mysqli->prepare("SELECT * FROM games WHERE game_code = ? AND game_name = ? AND provider = ?");
	$stmt->bind_param("sss", $gameCode, $gameName, $provedor);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$id = $row['id'];
		$sql = $mysqli->prepare("UPDATE games SET banner=?,status=? WHERE id=?");
		$sql->bind_param("ssi", $banner, $gameStatus, $id);
		if ($sql->execute()) {
			$r_data = 1;
		} else {
			$r_data = 0;
		}
	} else {
		$sql1 = $mysqli->prepare("INSERT INTO games (game_code,game_name,banner,status,provider) VALUES (?,?,?,?,?)");
		$sql1->bind_param("sssss", $gameCode, $gameName, $banner, $gameStatus, $provedor);
		if ($sql1->execute()) {
			$r_data = 1;
		} else {
			$r_data = 0;
		}
	}

	return $r_data;
}


#capta dados do form
$provedor = 'PROVEDOR';
#------------------------------------------------------------#
if (!empty($data_fiverscan['agent_code']) and !empty($data_fiverscan['agent_token'])) {
	// Definir parâmetros para a requisição GET (se necessário)
	$queryParams = [
		  // Parametro de exemplo (adicione outros conforme necessário)
	];

	// Criar a URL com os parâmetros de consulta (query string)
	$url = 'https://list.playfivers.com/games' . http_build_query($queryParams);

	// Configuração do cabeçalho
	$headerArray = ['Content-Type: application/json'];

	// Inicializar cURL
	$ch = curl_init();

	// Definir a URL com os parâmetros de consulta
	curl_setopt($ch, CURLOPT_URL, $url);

	// Configurar a requisição como GET (padrão, mas explicitar)
	curl_setopt($ch, CURLOPT_HTTPGET, true);  // Pode omitir, já que GET é o método padrão, mas deixamos explícito

	// Definir cabeçalhos (se necessário)
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

	// Definir timeout de conexão
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

	// Definir para retornar a resposta em vez de exibi-la
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Executar a requisição
	$res = curl_exec($ch);

	// Fechar a sessão cURL
	curl_close($ch);

	// Verificar se houve erro na execução da requisição
	if (curl_errno($ch)) {
		echo 'Erro cURL: ' . curl_error($ch);
	}

	// Decodificar a resposta JSON
	$data = json_decode($res, true);

	// Exibir a resposta (caso necessário)
	var_dump($data);

	if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
		die('Erro na decodificação JSON: ' . json_last_error_msg());
	}
	if ($data['status'] == 1) {
		// Acessa itens específicos
		$status = $data['status'];
		$msg = $data['msg'];
		$games = $data['games'];
		$count = 0;
		$success_count = 0;
		// Itera sobre os provedores
		foreach ($games as $game) {
			$id = $game['id'];
			$gameCode = $game['game_code'];
			$gameName = $game['game_name'];
			$banner = $game['img_url'];
			$gameStatus = $game['status'];
			$count++;
			$success_count  += att_game_slots_providers($gameCode, $gameName, $banner, $gameStatus, $provedor);
		}

		if ($count == $success_count) {
			echo "<div class='alert alert-success' role='alert'><i class='fa fa-check-circle'></i> Dados atualizados com sucesso.</div><script>  setTimeout('window.location.href=\"" . $painel_adm_provedores_games . "\";', 3000); </script>";
		} else {
			echo "<div class='alert alert-warning' role='alert'><i class='fa fa-exclamation-circle'></i> Revise seus dados de Api..</div><script>  setTimeout('window.location.href=\"" . $painel_adm_provedores_games . "\";', 3000); </script>";
		}
	} else {
		echo "<div class='alert alert-warning' role='alert'><i class='fa fa-exclamation-circle'></i> Revise seus dados de Api..
        </div><script>  setTimeout('window.location.href=\"" . $painel_adm_provedores_games . "\";', 3000); </script>";
	}
}
