<?php
    date_default_timezone_set('America/Sao_Paulo');
	include_once('database.php');
	include_once('funcao.php');
	#-------------------------------------#
	#=====================================================#
	# DATA CONFIG
	function data_api_fiverscan(){
		global $mysqli;
		$qry = "SELECT * FROM fiverscan WHERE id=1";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_fetch_assoc($res );
		return $data;
	}
	$data_fiverscan = data_api_fiverscan();
	#=====================================================#
	# saldo api fiverscan
	function balance_api(){
		global $data_fiverscan;
	 $balance =0;
	 $postArray = [
		 "method" => "money_info",
		 'agent_code' => $data_fiverscan['agent_code'], 
		 'agent_token' => $data_fiverscan['agent_token']
	 ];
	 $jsonData = json_encode($postArray);
	 $headerArray = ['Content-Type: application/json'];
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, 'https://api.payigaming.com.br/');
	 curl_setopt($ch, CURLOPT_POST, 1);
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	 curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
	 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 $res = curl_exec($ch);
	 
	 //var_dump($res);
	 // Verifique se houve algum erro durante a solicitação
	 if (curl_errno($ch)) {
		 $status = 'erro';
		 $balance = 0;
	 } else {
		 // Decodifique o JSON retornado
		 $responseData = json_decode($res, true);
		 // Verifique se a decodificação foi bem-sucedida
		 if ($responseData === null) {
			 $status = 'erro';
			 $balance = 0;
		 } else {
			 // Agora você pode acessar os dados como um array associativo
			 if($responseData['msg'] == "SUCCESS"){
				 $balance = $responseData['agent']['balance'];
			 }else{
				 $balance = 0;
			 }
		 }
	 }
 
	 curl_close($ch);
	 return $balance;
 }
	#=====================================================#
	# DATA AVATAR
	function data_avatar(){
		global $mysqli;
		$qry = "SELECT * FROM admin_users WHERE id=1";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_fetch_assoc($res );
		return $data;
	}
	$data_avatar = data_avatar();
	#=====================================================#
	
	#=====================================================#
	# DATA CONFIG
	function qtd_provedor_games($provedor){
		global $mysqli;
		$qry = "SELECT * FROM games WHERE provider='".$provedor."'";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_num_rows($res);
		return $data;
	}
	#=====================================================#
	# DATA RPOVEDOR count
	function qtd_provedor_ativos(){
		global $mysqli;
		$qry = "SELECT * FROM provedores WHERE status=1";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_num_rows($res);
		return $data;
	}
	#=====================================================#
	# DATA games count
	function qtd_games_ativos(){
		global $mysqli;
		$qry = "SELECT * FROM games WHERE status=1";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_num_rows($res);
		return $data;
	}
	#=====================================================#
	# DATA user count
	function qtd_usuarios(){
		global $mysqli;
		$qry = "SELECT * FROM usuarios";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_num_rows($res);
		return $data;
	}
	#=====================================================#
	# DATA SALDO CASSINO
	function saldo_cassino(){
		global $mysqli;
		$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE tipo='deposito' AND status='pago'";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		#-
		$qry_saques = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status=1";
		$result_saques = mysqli_query($mysqli, $qry_saques);
		while($row_saques = mysqli_fetch_assoc($result_saques)){
			if($row_saques['total_soma'] >0){
				$saques = $row_saques['total_soma'];
			}else{
			   $saques = '0.00';
			}
		}
		$total = $deposito-$saques;
		return $total;
	}
	#=====================================================#
	# DATA deposito pendentes
	function depositos_pendentes(){
		global $mysqli;
		$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE tipo='deposito' AND status='processamento'";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		return $deposito;
	}
	#=====================================================#
	# DATA deposito diario
	function depositos_diarios(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE tipo='deposito' AND DATE(data_hora) = ?";
		$stmt = $mysqli->prepare($qry);
		$stmt->bind_param("s", $data);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$deposito = '0.00'; // Valor padrão
	
		if($row = $result->fetch_assoc()){
			if($row['total_soma'] > 0){
				$deposito = $row['total_soma'];
			}
		}
		
		return $deposito;
	}

	# DATA deposito diario
	function depositos_diarios_pagos(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE tipo='deposito' AND status='pago' AND DATE(data_hora) = ?";
		$stmt = $mysqli->prepare($qry);
		$stmt->bind_param("s", $data);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$deposito = '0.00'; // Valor padrão
	
		if($row = $result->fetch_assoc()){
			if($row['total_soma'] > 0){
				$deposito = $row['total_soma'];
			}
		}
		
		return $deposito;
	}
	#=====================================================#
	# DATA deposito diario
	function depositos_total(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE tipo='deposito' AND status='pago'";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		return $deposito;
	}
	#=====================================================#
	# DATA saque pendentes 
	function saques_pendentes(){
		global $mysqli;
		$qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status=0";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		return $deposito;
	}
	#=====================================================#
	# DATA saque diarios pagos 
	function saques_diarios_pagos(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE data_cad='".$data."' AND status=1";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		return $deposito;
	}
	#=====================================================#
	# DATA saque diarios pagos 
	function saques_total(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status= '1'";
		$result = mysqli_query($mysqli, $qry);
		while($row = mysqli_fetch_assoc($result)){
			if($row['total_soma'] >0){
				$deposito = $row['total_soma'];
			}else{
			   $deposito = '0.00';
			}
		}
		return $deposito;
	}
	#=====================================================#
	#count saques pendentes
	function count_saques_pendentes(){
		global $mysqli;
		$qry = "SELECT * FROM solicitacao_saques WHERE status=0";
		$res = mysqli_query($mysqli, $qry);
		$count = mysqli_num_rows($res);
		return $count;
	}
	#=====================================================#
	#jogo_mais_jogado
	function total_jogadas() {
        global $mysqli;
        $qry = "SELECT COUNT(*) as total FROM historico_play";
        $result = mysqli_query($mysqli, $qry);
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    
    function formatar_nome_jogo($nome_game) {
    return ucwords(str_replace('-', ' ', $nome_game));
    }

    function jogo_mais_jogado() {
        global $mysqli;
        $qry = "SELECT nome_game, COUNT(*) as total FROM historico_play GROUP BY nome_game ORDER BY total DESC LIMIT 1";
        $result = mysqli_query($mysqli, $qry);
        $row = mysqli_fetch_assoc($result);
    
        return $row ? formatar_nome_jogo($row['nome_game']) : 'Nenhum jogo encontrado';
    }

	#=====================================================#
	# DATA user count
	function qtd_usuarios_diarios(){
		global $mysqli;
		$data = date('Y-m-d');
		$qry = "SELECT * FROM usuarios WHERE data_cad='".$data."'";
		$res = mysqli_query($mysqli,$qry);
		$data = mysqli_num_rows($res);
		return $data;
	}
	#=====================================================#
	function qtd_usuarios_ultimos_90_dias() {
		global $mysqli;
		
		// Obtendo a data atual e a data 90 dias atrás
		$data_atual = date('Y-m-d');
		$data_90_dias_atras = date('Y-m-d', strtotime('-90 days'));
		
		// Ajustando a consulta para buscar cadastros entre as duas datas
		$qry = "SELECT * FROM usuarios WHERE data_cad BETWEEN '$data_90_dias_atras' AND '$data_atual'";
		$res = mysqli_query($mysqli, $qry);
		
		// Contando o número de registros retornados
		$data = mysqli_num_rows($res);
		
		return $data;
	}

?>