<?php
session_start();
$dataFile = __DIR__ . '/online_users.json';

$users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$now = time();

// Registra ou atualiza o timestamp da sessão atual
$users[session_id()] = $now;

// Remove sessões inativas (mais de 30 segundos sem ping)
$users = array_filter($users, function ($lastPing) use ($now) {
    return ($now - $lastPing) < 30;
});

file_put_contents($dataFile, json_encode($users));
