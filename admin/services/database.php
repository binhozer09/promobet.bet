<?php

date_default_timezone_set("America/Sao_Paulo");
define('SITE_URL', 'https://'.$_SERVER['HTTP_HOST']);

$bd = array(
    'local' => 'localhost',
    'usuario' => '123',
    'senha' => '123',
    'banco' => '123'
);

$mysqli = new mysqli($bd['local'], $bd['usuario'], $bd['senha'], $bd['banco']);
if ($mysqli->connect_errno) {
    echo "Erro ao Conectar o BD: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}
$mysqli->set_charset("utf8");
?>