<?php
session_start();

// Limpar dados temporários de 2FA
if (isset($_SESSION['temp_2fa_login'])) {
    unset($_SESSION['temp_2fa_login']);
}

echo json_encode(['status' => 'success']);
?>