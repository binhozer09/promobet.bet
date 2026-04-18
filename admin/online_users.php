<?php
$dataFile = __DIR__ . '/online_users.json';
$users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
echo json_encode(['count' => count($users)]);
