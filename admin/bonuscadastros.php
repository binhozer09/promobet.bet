<?php include 'partials/html.php' ?>

<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();
include_once "services/database.php";
include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
include_once "validar_2fa.php";
$csrf = new CSRF_Protect();

checa_login_adm();

// =============================================
// FUNÇÕES PARA SISTEMA DE CUPONS (ANTIGO)
// =============================================

function get_coupons($limit, $offset)
{
    global $mysqli;
    $qry = "SELECT * FROM cupom LIMIT $limit OFFSET $offset";
    $result = mysqli_query($mysqli, $qry);
    $coupons = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $coupons[] = $row;
    }
    return $coupons;
}

function add_coupon($data) {
    global $mysqli;

    $sql = "SELECT COUNT(*) as total FROM cupom";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    
    $next_x = $row['total'] + 1;
    $nome_bonificacao = "Bônus de Depósito {$next_x}x";

    if (!isset($data['qtd_insert'])) {
        echo "Erro: O campo 'qtd_insert' não foi enviado.";
        return false;
    }

    $sql = "INSERT INTO cupom (nome, tipo, valor, qtd, qtd_insert, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        echo "Erro na preparação da consulta: " . $mysqli->error;
        return false;
    }

    $qtd_total = $data['qtd'] + $data['qtd_insert'];
    $stmt->bind_param("siiiii", $nome_bonificacao, $data['tipo'], $data['valor'], $qtd_total, $data['qtd_insert'], $data['status']);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Erro ao adicionar cupom: " . $mysqli->error;
        return false;
    }
}

function count_coupons()
{
    global $mysqli;
    $qry = "SELECT COUNT(*) as total FROM cupom";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total'];
}

function update_coupon($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE cupom SET 
        nome = ?, 
        tipo = ?, 
        valor = ?, 
        qtd = ?, 
        qtd_insert = ?, 
        status = ? 
        WHERE id = ?");

    $qry->bind_param(
        "siiisii",
        $data['nome'],
        $data['tipo'],
        $data['valor'],
        $data['qtd'],
        $data['qtd_insert'],
        $data['status'],
        $data['id']
    );
    return $qry->execute();
}

function delete_coupon($id)
{
    global $mysqli;
    $qry = $mysqli->prepare("DELETE FROM cupom WHERE id = ?");
    $qry->bind_param("i", $id);
    return $qry->execute();
}

// =============================================
// FUNÇÕES PARA SISTEMA DE BÔNUS POR PORCENTAGEM (NOVO)
// =============================================

function get_bonus_configs($limit, $offset)
{
    global $mysqli;
    $qry = "SELECT bc.*, 
            COUNT(bh.id) as total_usos,
            COALESCE(SUM(bh.valor_bonus), 0) as total_valor_distribuido
            FROM bonus_config bc
            LEFT JOIN bonus_historico bh ON bc.id = bh.bonus_config_id
            GROUP BY bc.id
            ORDER BY bc.id DESC
            LIMIT $limit OFFSET $offset";
    $result = mysqli_query($mysqli, $qry);
    $bonus_configs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bonus_configs[] = $row;
    }
    return $bonus_configs;
}

function count_bonus_configs()
{
    global $mysqli;
    $qry = "SELECT COUNT(*) as total FROM bonus_config";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total'];
}

function add_bonus_config($data) {
    global $mysqli;

    $sql = "INSERT INTO bonus_config (nome, porcentagem, limite_usos, ativo) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        echo "Erro na preparação da consulta: " . $mysqli->error;
        return false;
    }

    $limite_usos = empty($data['limite_usos']) ? NULL : intval($data['limite_usos']);
    $stmt->bind_param("sdii", $data['nome'], $data['porcentagem'], $limite_usos, $data['ativo']);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Erro ao adicionar bônus: " . $mysqli->error;
        return false;
    }
}

function update_bonus_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE bonus_config SET 
        nome = ?, 
        porcentagem = ?, 
        limite_usos = ?, 
        ativo = ?,
        data_expiracao = ?
        WHERE id = ?");

    $limite_usos = empty($data['limite_usos']) ? NULL : intval($data['limite_usos']);
    $data_expiracao = empty($data['data_expiracao']) ? NULL : $data['data_expiracao'];

    $qry->bind_param(
        "sdissi",
        $data['nome'],
        $data['porcentagem'],
        $limite_usos,
        $data['ativo'],
        $data_expiracao,
        $data['id']
    );
    return $qry->execute();
}

function delete_bonus_config($id)
{
    global $mysqli;
    $qry = $mysqli->prepare("DELETE FROM bonus_config WHERE id = ?");
    $qry->bind_param("i", $id);
    return $qry->execute();
}

function get_bonus_historico($bonus_id, $limit = 50)
{
    global $mysqli;
    $qry = "SELECT bh.*, u.mobile 
            FROM bonus_historico bh
            LEFT JOIN usuarios u ON bh.user_id = u.id
            WHERE bh.bonus_config_id = ?
            ORDER BY bh.data_aplicacao DESC
            LIMIT $limit";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param("i", $bonus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $historico = [];
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
    return $historico;
}

// =============================================
// PROCESSAMENTO DOS FORMULÁRIOS
// =============================================

$toastType = null; 
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sistema de Cupons (Antigo)
    if (isset($_POST['add_coupon'])) {
        $data = [
            'tipo' => 2,
            'valor' => intval($_POST['add_valor']),
            'qtd' => intval($_POST['add_qtd']),
            'qtd_insert' => intval($_POST['add_valor_bonus']),
            'status' => intval($_POST['add_status']),
        ];

        if (add_coupon($data)) {
            $toastType = 'success';
            $toastMessage = 'Cupom adicionado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao adicionar o cupom. Tente novamente.';
        }
    } elseif (isset($_POST['update_coupon']) && isset($_POST['coupon_id'])) {
        $data = [
            'id' => intval($_POST['coupon_id']),
            'nome' => $_POST['nome'],
            'tipo' => 2,
            'valor' => intval($_POST['valor']),
            'qtd' => intval($_POST['qtd']),
            'qtd_insert' => intval($_POST['qtd_insert']),
            'status' => intval($_POST['status']),
        ];

        if (update_coupon($data)) {
            $toastType = 'success';
            $toastMessage = 'Cupom editado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao editar o cupom. Tente novamente.';
        }
    } elseif (isset($_POST['delete_coupon'])) {
        $coupon_id = intval($_POST['coupon_id']);
        
        if (delete_coupon($coupon_id)) {
            $toastType = 'success';
            $toastMessage = 'Cupom excluído com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao excluir o cupom.';
        }
    }
    
    // Sistema de Bônus por Porcentagem (Novo)
    elseif (isset($_POST['add_bonus_config'])) {
        $data = [
            'nome' => $_POST['add_bonus_nome'],
            'porcentagem' => floatval($_POST['add_porcentagem']),
            'limite_usos' => $_POST['add_limite_usos'],
            'ativo' => intval($_POST['add_ativo']),
        ];

        if (add_bonus_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Bônus por porcentagem adicionado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao adicionar o bônus. Tente novamente.';
        }
    } elseif (isset($_POST['update_bonus_config']) && isset($_POST['bonus_id'])) {
        $data = [
            'id' => intval($_POST['bonus_id']),
            'nome' => $_POST['bonus_nome'],
            'porcentagem' => floatval($_POST['porcentagem']),
            'limite_usos' => $_POST['limite_usos'],
            'ativo' => intval($_POST['ativo']),
            'data_expiracao' => $_POST['data_expiracao'],
        ];

        if (update_bonus_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Bônus editado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao editar o bônus. Tente novamente.';
        }
    } elseif (isset($_POST['delete_bonus_config'])) {
        $bonus_id = intval($_POST['bonus_id']);
        
        if (delete_bonus_config($bonus_id)) {
            $toastType = 'success';
            $toastMessage = 'Bônus excluído com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao excluir o bônus.';
        }
    }
}

// Paginação
$limit = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Dados dos cupons
$total_coupons = count_coupons();
$total_pages_coupons = ceil($total_coupons / $limit);
$coupons = get_coupons($limit, $offset);

// Dados dos bônus por porcentagem
$total_bonus_configs = count_bonus_configs();
$total_pages_bonus = ceil($total_bonus_configs / $limit);
$bonus_configs = get_bonus_configs($limit, $offset);
?>

<head>
    <?php $title = "Gerenciamento de Bônus";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --primary-bg: #1a1a1a;
            --secondary-bg: #303231;
            --accent-color: #10b981;
            --accent-secondary: #8b5cf6;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }

        body {
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .bonus-layout {
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .bonus-header {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: var(--text-primary);
            position: relative;
            overflow: hidden;
        }

        .bonus-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color), #059669);
        }

        .bonus-header h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bonus-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Tabs Navigation */
        .bonus-tabs {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 8px;
            margin-bottom: 24px;
            display: flex;
            gap: 8px;
        }

        .tab-button {
            flex: 1;
            background: transparent;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-button.active {
            background: var(--accent-color);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .tab-button:hover:not(.active) {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 28px;
            color: var(--text-primary);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.3);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #059669);
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, var(--accent-secondary), #7c3aed);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--accent-color);
            font-size: 28px;
        }

        .stat-icon.purple {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-secondary);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            margin: 0 0 8px 0;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 16px;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 600;
        }

        /* Content Layout */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
        }

        /* Main Content Section */
        .main-content {
            background: var(--secondary-bg);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .content-header {
            background: var(--secondary-bg);
            padding: 28px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-header h5 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Search Section */
        .search-section {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border-color);
            background: var(--secondary-bg);
        }

        .search-input {
            width: 100%;
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        /* Bonus Cards Grid */
        .bonus-grid {
            padding: 28px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .bonus-card {
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .bonus-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
        }

        .bonus-card.percentage-card {
            border-color: var(--accent-secondary);
        }

        .bonus-card.percentage-card:hover {
            border-color: var(--accent-secondary);
        }

        .bonus-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #059669);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .bonus-card.percentage-card::before {
            background: linear-gradient(90deg, var(--accent-secondary), #7c3aed);
        }

        .bonus-card:hover::before {
            opacity: 1;
        }

        .bonus-header-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .bonus-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .bonus-id {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* Status Badge */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Bonus Details Grid */
        .bonus-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .bonus-details.three-col {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .bonus-detail {
            text-align: center;
            padding: 16px 12px;
            background: var(--secondary-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .bonus-detail-label {
            font-size: 12px;
            color: var(--text-secondary);
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bonus-detail-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .bonus-detail-value.highlight {
            color: var(--accent-color);
        }

        .bonus-detail-value.highlight-purple {
            color: var(--accent-secondary);
        }

        /* Type Badge */
        .type-badge {
            background: var(--border-color);
            color: var(--text-primary);
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            grid-column: 1 / -1;
            text-align: center;
        }

        .type-badge.percentage {
            background: rgba(139, 92, 246, 0.2);
            color: var(--accent-secondary);
        }

        /* Action Buttons */
        .bonus-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .action-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-edit {
            background: var(--accent-color);
            color: white;
        }

        .btn-edit:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-edit.purple {
            background: var(--accent-secondary);
        }

        .btn-edit.purple:hover {
            background: #7c3aed;
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
        }

        .btn-history {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .btn-history:hover {
            background: rgba(245, 158, 11, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.2);
        }

        /* Sidebar Form */
        .sidebar-form {
            background: var(--secondary-bg);
            border-radius: 16px;
            height: fit-content;
            border: 1px solid var(--border-color);
        }

        .form-header {
            background: var(--secondary-bg);
            padding: 28px;
            border-radius: 16px 16px 0 0;
            border-bottom: 1px solid var(--border-color);
        }

        .form-header h5 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-body {
            padding: 28px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .form-input, .form-select {
            width: 100%;
            background: var(--primary-bg);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .form-select option {
            background: var(--primary-bg);
            color: var(--text-primary);
        }

        .form-help {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
            font-weight: 500;
        }

        .add-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-color), #059669);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
        }

        .add-btn.purple {
            background: linear-gradient(135deg, var(--accent-secondary), #7c3aed);
        }

        .add-btn.purple:hover {
            box-shadow: 0 12px 30px rgba(139, 92, 246, 0.3);
        }

        /* Modal Styles */
        .modal-content {
            background: var(--secondary-bg);
            border: none;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .modal-header {
            background: var(--secondary-bg);
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0;
            padding: 24px 28px;
        }

        .modal-title {
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 700;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.7;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            background: var(--secondary-bg);
            color: var(--text-primary);
            padding: 28px;
        }

        .modal-footer {
            background: var(--secondary-bg);
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 16px 16px;
            padding: 24px 28px;
        }

        .btn-secondary {
            background: var(--border-color);
            border: none;
            color: var(--text-primary);
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-secondary:hover {
            background: #6b7280;
            color: var(--text-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #059669);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #059669, var(--accent-color));
            color: white;
        }

        /* History Table */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .history-table th,
        .history-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .history-table th {
            background: var(--primary-bg);
            color: var(--text-primary);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .history-table td {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .history-table tr:hover {
            background: var(--hover-bg);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 28px;
            border-top: 1px solid var(--border-color);
            background: var(--secondary-bg);
        }

        .pagination {
            margin: 0;
            justify-content: center;
        }

        .page-link {
            background: var(--primary-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            margin: 0 4px;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 600;
        }

        .page-link:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .page-item.active .page-link {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-layout {
                grid-template-columns: 1fr;
            }
            
            .bonus-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .bonus-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .bonus-layout {
                padding: 16px;
            }
            
            .bonus-header {
                padding: 24px;
            }
            
            .content-header, .form-header {
                padding: 20px;
            }

            .bonus-tabs {
                flex-direction: column;
            }

            .tab-button {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content bonus-layout">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="bonus-header">
                    <h4>🎁 Gerenciamento de Bônus</h4>
                    <p>Controle e gerencie todos os sistemas de bônus: cupons fixos e bônus por porcentagem</p>
                </div>

                <!-- Tabs Navigation -->
                <div class="bonus-tabs">
                    <button class="tab-button active" data-tab="cupons">
                        <i class="bi bi-gift"></i>
                        Sistema de Cupons
                    </button>
                    <button class="tab-button" data-tab="bonus-porcentagem">
                        <i class="bi bi-percent"></i>
                        Bônus por Porcentagem
                    </button>
                </div>

                <!-- Tab Content: Sistema de Cupons (Antigo) -->
                <div class="tab-content active" id="cupons-content">
                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h3 class="stat-number"><?= $total_coupons ?></h3>
                            <p class="stat-label">Total de Cupons</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="stat-number"><?= count(array_filter($coupons, function($c) { return $c['status'] == 1; })) ?></h3>
                            <p class="stat-label">Cupons Ativos</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h3 class="stat-number">R$ <?= number_format(array_sum(array_column($coupons, 'valor')), 2, ',', '.') ?></h3>
                            <p class="stat-label">Valor Total</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-coin"></i>
                            </div>
                            <h3 class="stat-number">R$ <?= number_format(array_sum(array_column($coupons, 'qtd_insert')), 2, ',', '.') ?></h3>
                            <p class="stat-label">Total em Bônus</p>
                        </div>
                    </div>

                    <!-- Content Layout -->
                    <div class="content-layout">
                        
                        <!-- Main Content Section -->
                        <div class="main-content">
                            <div class="content-header">
                                <h5>📋 Lista de Cupons Fixos</h5>
                            </div>
                            
                            <!-- Search Section -->
                            <div class="search-section">
                                <input type="text" id="searchCupons" class="search-input" 
                                       placeholder="Pesquisar cupons por nome, valor ou status...">
                            </div>
                            
                            <!-- Bonus Cards Grid -->
                            <div class="bonus-grid" id="cuponsList">
                                <?php foreach ($coupons as $coupon): ?>
                                    <div class="bonus-card bonus-item" 
                                         data-name="<?= strtolower($coupon['nome']); ?>" 
                                         data-value="<?= $coupon['valor']; ?>" 
                                         data-status="<?= $coupon['status'] == 1 ? 'ativo' : 'inativo'; ?>">
                                        
                                        <div class="bonus-header-card">
                                            <div>
                                                <h6 class="bonus-title"><?= htmlspecialchars($coupon['nome']) ?></h6>
                                                <span class="bonus-id">ID: #<?= $coupon['id'] ?></span>
                                            </div>
                                            <span class="status-badge <?= $coupon['status'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                                <?= $coupon['status'] == 1 ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </div>

                                        <div class="bonus-details">
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Valor Mínimo</span>
                                                <div class="bonus-detail-value">R$ <?= number_format($coupon['valor'], 2, ',', '.') ?></div>
                                            </div>
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Valor Bônus</span>
                                                <div class="bonus-detail-value highlight">R$ <?= number_format($coupon['qtd_insert'], 2, ',', '.') ?></div>
                                            </div>
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Usos Disponíveis</span>
                                                <div class="bonus-detail-value"><?= $coupon['qtd'] ?></div>
                                            </div>
                                            <div class="type-badge">
                                                <?= $coupon['tipo'] == 1 ? 'Recarga' : 'Depósito' ?>
                                            </div>
                                        </div>

                                        <div class="bonus-actions">
                                            <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editCouponModal<?= $coupon['id'] ?>">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="action-btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteCouponModal<?= $coupon['id'] ?>">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Modal de edição de cupom -->
                                    <div class="modal fade" id="editCouponModal<?= $coupon['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil me-2"></i>Editar Cupom
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Nome do Cupom</label>
                                                                    <input type="text" name="nome" class="form-input" value="<?= $coupon['nome'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Valor Mínimo (R$)</label>
                                                                    <input type="number" name="valor" class="form-input" value="<?= $coupon['valor'] ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Quantidade de Usos</label>
                                                                    <input type="number" name="qtd" class="form-input" value="<?= $coupon['qtd'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Valor do Bônus (R$)</label>
                                                                    <input type="number" name="qtd_insert" class="form-input" value="<?= $coupon['qtd_insert'] ?>" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="1" <?= $coupon['status'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                                <option value="0" <?= $coupon['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="coupon_id" value="<?= $coupon['id'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" name="update_coupon" class="btn btn-primary">Salvar Alterações</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de exclusão de cupom -->
                                    <div class="modal fade" id="deleteCouponModal<?= $coupon['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <p>Tem certeza que deseja excluir o cupom <strong><?= htmlspecialchars($coupon['nome']); ?></strong>?</p>
                                                        <div class="alert alert-danger">
                                                            <strong>⚠️ Esta ação não pode ser desfeita!</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" class="w-100">
                                                        <input type="hidden" name="coupon_id" value="<?= $coupon['id'] ?>">
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" name="delete_coupon" class="btn btn-danger">Excluir Cupom</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Empty State -->
                            <div id="emptyStateCupons" class="empty-state" style="display: none;">
                                <i class="bi bi-gift"></i>
                                <h5>Nenhum cupom encontrado</h5>
                                <p>Tente ajustar os filtros de pesquisa</p>
                            </div>
                        </div>

                        <!-- Sidebar Form para Cupons -->
                        <div class="sidebar-form">
                            <div class="form-header">
                                <h5><i class="bi bi-plus-circle me-2"></i>Adicionar Novo Cupom</h5>
                            </div>
                            <div class="form-body">
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label class="form-label">Valor Mínimo para Depósito</label>
                                        <input type="number" name="add_valor" class="form-input" placeholder="100" required min="1" step="0.01">
                                        <div class="form-help">Valor mínimo que o usuário deve depositar</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Quantidade de Usos</label>
                                        <input type="number" name="add_qtd" class="form-input" placeholder="10" required min="1">
                                        <div class="form-help">Número de vezes que este cupom pode ser utilizado</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Valor do Bônus</label>
                                        <input type="number" name="add_valor_bonus" class="form-input" placeholder="50" required min="1" step="0.01">
                                        <div class="form-help">Valor fixo que será creditado como bônus</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Status Inicial</label>
                                        <select name="add_status" class="form-select" required>
                                            <option value="1" selected>Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" name="add_coupon" class="add-btn">
                                        <i class="bi bi-plus-lg me-2"></i>Adicionar Cupom
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Bônus por Porcentagem (Novo) -->
                <div class="tab-content" id="bonus-porcentagem-content">
                    <!-- Stats Cards -->
                    <div class="stats-grid">
                        <div class="stat-card purple">
                            <div class="stat-icon purple">
                                <i class="bi bi-percent"></i>
                            </div>
                            <h3 class="stat-number"><?= $total_bonus_configs ?></h3>
                            <p class="stat-label">Total de Bônus</p>
                        </div>
                        <div class="stat-card purple">
                            <div class="stat-icon purple">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h3 class="stat-number"><?= count(array_filter($bonus_configs, function($c) { return $c['ativo'] == 1; })) ?></h3>
                            <p class="stat-label">Bônus Ativos</p>
                        </div>
                        <div class="stat-card purple">
                            <div class="stat-icon purple">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="stat-number"><?= array_sum(array_column($bonus_configs, 'total_usos')) ?></h3>
                            <p class="stat-label">Total de Usos</p>
                        </div>
                        <div class="stat-card purple">
                            <div class="stat-icon purple">
                                <i class="bi bi-coin"></i>
                            </div>
                            <h3 class="stat-number">R$ <?= number_format(array_sum(array_column($bonus_configs, 'total_valor_distribuido')), 2, ',', '.') ?></h3>
                            <p class="stat-label">Total Distribuído</p>
                        </div>
                    </div>

                    <!-- Content Layout -->
                    <div class="content-layout">
                        
                        <!-- Main Content Section -->
                        <div class="main-content">
                            <div class="content-header">
                                <h5>📊 Lista de Bônus por Porcentagem</h5>
                            </div>
                            
                            <!-- Search Section -->
                            <div class="search-section">
                                <input type="text" id="searchBonus" class="search-input" 
                                       placeholder="Pesquisar bônus por nome, porcentagem ou status...">
                            </div>
                            
                            <!-- Bonus Cards Grid -->
                            <div class="bonus-grid" id="bonusList">
                                <?php foreach ($bonus_configs as $bonus): ?>
                                    <div class="bonus-card percentage-card bonus-item" 
                                         data-name="<?= strtolower($bonus['nome']); ?>" 
                                         data-value="<?= $bonus['porcentagem']; ?>" 
                                         data-status="<?= $bonus['ativo'] == 1 ? 'ativo' : 'inativo'; ?>">
                                        
                                        <div class="bonus-header-card">
                                            <div>
                                                <h6 class="bonus-title"><?= htmlspecialchars($bonus['nome']) ?></h6>
                                                <span class="bonus-id">ID: #<?= $bonus['id'] ?></span>
                                            </div>
                                            <span class="status-badge <?= $bonus['ativo'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                                <?= $bonus['ativo'] == 1 ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </div>

                                        <div class="bonus-details three-col">
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Porcentagem</span>
                                                <div class="bonus-detail-value highlight-purple"><?= number_format($bonus['porcentagem'], 1) ?>%</div>
                                            </div>
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Total Usos</span>
                                                <div class="bonus-detail-value"><?= $bonus['total_usos'] ?></div>
                                            </div>
                                            <div class="bonus-detail">
                                                <span class="bonus-detail-label">Limite</span>
                                                <div class="bonus-detail-value"><?= $bonus['limite_usos'] ? number_format($bonus['limite_usos']) : 'Ilimitado' ?></div>
                                            </div>
                                            <div class="bonus-detail" style="grid-column: 1 / -1;">
                                                <span class="bonus-detail-label">Total Distribuído</span>
                                                <div class="bonus-detail-value highlight-purple">R$ <?= number_format($bonus['total_valor_distribuido'], 2, ',', '.') ?></div>
                                            </div>
                                            <div class="type-badge percentage">
                                                Bônus por Porcentagem
                                            </div>
                                        </div>

                                        <div class="bonus-actions">
                                            <button class="action-btn btn-edit purple" data-bs-toggle="modal" data-bs-target="#editBonusModal<?= $bonus['id'] ?>">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="action-btn btn-history" data-bs-toggle="modal" data-bs-target="#historyBonusModal<?= $bonus['id'] ?>">
                                                <i class="bi bi-clock-history"></i> Histórico
                                            </button>
                                            <button class="action-btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteBonusModal<?= $bonus['id'] ?>">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Modal de edição de bônus -->
                                    <div class="modal fade" id="editBonusModal<?= $bonus['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil me-2"></i>Editar Bônus por Porcentagem
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Nome do Bônus</label>
                                                                    <input type="text" name="bonus_nome" class="form-input" value="<?= $bonus['nome'] ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Porcentagem (%)</label>
                                                                    <input type="number" name="porcentagem" class="form-input" value="<?= $bonus['porcentagem'] ?>" required step="0.01" min="0" max="1000">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Limite de Usos</label>
                                                                    <input type="number" name="limite_usos" class="form-input" value="<?= $bonus['limite_usos'] ?>" placeholder="Deixe vazio para ilimitado">
                                                                    <div class="form-help">Deixe vazio para usos ilimitados</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Data de Expiração</label>
                                                                    <input type="datetime-local" name="data_expiracao" class="form-input" value="<?= $bonus['data_expiracao'] ? date('Y-m-d\TH:i', strtotime($bonus['data_expiracao'])) : '' ?>">
                                                                    <div class="form-help">Deixe vazio para não expirar</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select name="ativo" class="form-select" required>
                                                                <option value="1" <?= $bonus['ativo'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                                <option value="0" <?= $bonus['ativo'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="bonus_id" value="<?= $bonus['id'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" name="update_bonus_config" class="btn btn-primary">Salvar Alterações</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de histórico -->
                                    <div class="modal fade" id="historyBonusModal<?= $bonus['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-clock-history me-2"></i>Histórico de Uso - <?= htmlspecialchars($bonus['nome']) ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php $historico = get_bonus_historico($bonus['id']); ?>
                                                    <?php if (count($historico) > 0): ?>
                                                        <div class="table-responsive">
                                                            <table class="history-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Usuário</th>
                                                                        <th>Valor Depositado</th>
                                                                        <th>Porcentagem</th>
                                                                        <th>Valor Bônus</th>
                                                                        <th>Data</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($historico as $item): ?>
                                                                        <tr>
                                                                            <td><?= htmlspecialchars($item['mobile'] ?? 'N/A') ?></td>
                                                                            <td>R$ <?= number_format($item['valor_depositado'], 2, ',', '.') ?></td>
                                                                            <td><?= number_format($item['porcentagem_aplicada'], 1) ?>%</td>
                                                                            <td>R$ <?= number_format($item['valor_bonus'], 2, ',', '.') ?></td>
                                                                            <td><?= date('d/m/Y H:i', strtotime($item['data_aplicacao'])) ?></td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="empty-state">
                                                            <i class="bi bi-clock-history"></i>
                                                            <h5>Nenhum uso registrado</h5>
                                                            <p>Este bônus ainda não foi utilizado por nenhum usuário</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de exclusão de bônus -->
                                    <div class="modal fade" id="deleteBonusModal<?= $bonus['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <p>Tem certeza que deseja excluir o bônus <strong><?= htmlspecialchars($bonus['nome']); ?></strong>?</p>
                                                        <div class="alert alert-danger">
                                                            <strong>⚠️ Esta ação não pode ser desfeita!</strong><br>
                                                            O histórico de usos também será perdido.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST" class="w-100">
                                                        <input type="hidden" name="bonus_id" value="<?= $bonus['id'] ?>">
                                                        <div class="d-flex gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" name="delete_bonus_config" class="btn btn-danger">Excluir Bônus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Empty State -->
                            <div id="emptyStateBonus" class="empty-state" style="display: none;">
                                <i class="bi bi-percent"></i>
                                <h5>Nenhum bônus encontrado</h5>
                                <p>Tente ajustar os filtros de pesquisa</p>
                            </div>
                        </div>

                        <!-- Sidebar Form para Bônus por Porcentagem -->
                        <div class="sidebar-form">
                            <div class="form-header">
                                <h5><i class="bi bi-plus-circle me-2"></i>Adicionar Novo Bônus</h5>
                            </div>
                            <div class="form-body">
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label class="form-label">Nome do Bônus</label>
                                        <input type="text" name="add_bonus_nome" class="form-input" placeholder="Bônus Primeiro Depósito 100%" required>
                                        <div class="form-help">Nome identificador para este bônus</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Porcentagem do Bônus</label>
                                        <input type="number" name="add_porcentagem" class="form-input" placeholder="100" required min="0" max="1000" step="0.01">
                                        <div class="form-help">Porcentagem que será aplicada sobre o valor depositado</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Limite de Usos</label>
                                        <input type="number" name="add_limite_usos" class="form-input" placeholder="Deixe vazio para ilimitado" min="1">
                                        <div class="form-help">Número máximo de vezes que pode ser usado (deixe vazio para ilimitado)</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Status Inicial</label>
                                        <select name="add_ativo" class="form-select" required>
                                            <option value="1" selected>Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                        <div class="form-help">Define se o bônus estará disponível imediatamente</div>
                                    </div>
                                    
                                    <button type="submit" name="add_bonus_config" class="add-btn purple">
                                        <i class="bi bi-plus-lg me-2"></i>Adicionar Bônus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'partials/endbar.php' ?>
    </div>

    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    
    <script>
        // Função de Toast moderna
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            
            var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            var icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';
            
            toast.className = `toast align-items-center ${bgClass} text-white border-0 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header ${bgClass} text-white border-0">
                    <i class="bi ${icon} me-2"></i>
                    <strong class="me-auto">Sistema de Bônus</strong>
                    <small>Agora</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            toastPlacement.appendChild(toast);

            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function () {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Sistema de Abas
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const targetContent = document.getElementById(targetTab + '-content');
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
        }

        // Função de pesquisa aprimorada
        function setupSearch() {
            // Pesquisa para cupons
            const searchCupons = document.getElementById('searchCupons');
            const cuponsItems = document.querySelectorAll('#cuponsList .bonus-item');
            const emptyStateCupons = document.getElementById('emptyStateCupons');

            if (searchCupons) {
                searchCupons.addEventListener('input', function() {
                    performSearch(this.value, cuponsItems, emptyStateCupons);
                });
            }

            // Pesquisa para bônus
            const searchBonus = document.getElementById('searchBonus');
            const bonusItems = document.querySelectorAll('#bonusList .bonus-item');
            const emptyStateBonus = document.getElementById('emptyStateBonus');

            if (searchBonus) {
                searchBonus.addEventListener('input', function() {
                    performSearch(this.value, bonusItems, emptyStateBonus);
                });
            }

            function performSearch(searchTerm, items, emptyState) {
                const term = searchTerm.toLowerCase().trim();
                let visibleItems = 0;

                items.forEach((item) => {
                    const name = item.getAttribute('data-name') || '';
                    const value = item.getAttribute('data-value') || '';
                    const status = item.getAttribute('data-status') || '';
                    
                    const isVisible = term === '' || 
                                    name.includes(term) || 
                                    value.includes(term) || 
                                    status.includes(term);
                    
                    item.style.display = isVisible ? 'block' : 'none';
                    if (isVisible) visibleItems++;
                });

                if (emptyState) {
                    emptyState.style.display = visibleItems === 0 && term !== '' ? 'block' : 'none';
                }
            }
        }

        // Animações e efeitos visuais
        function setupAnimations() {
            // Hover effect nos cards
            const bonusCards = document.querySelectorAll('.bonus-card');
            bonusCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px)';
                });
        
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        
            // Animação dos contadores nas estatísticas
            // Animação dos contadores nas estatísticas
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const text = counter.textContent.trim();
                const isCurrency = text.includes('R$');
                const isPercent = text.includes('%');
                let target;
            
                if (isCurrency) {
                    target = parseFloat(text.replace(/[^\d,]/g, '').replace(',', '.'));
                } else if (isPercent) {
                    target = parseFloat(text.replace('%', ''));
                } else {
                    target = parseInt(text.replace(/[^\d]/g, ''));
                }
            
                if (!isNaN(target) && target > 0) {
                    const suffix = isPercent ? '%' : '';
                    animateCounter(counter, target, isCurrency, suffix);
                }
            });

        }

        // Função para animar contadores
        function animateCounter(element, target, isCurrency = false) {
            let current = 0;
            const increment = target / 40;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (isCurrency) {
                    element.textContent = `R$ ${Math.floor(current).toLocaleString('pt-BR')}`;
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 40);
        }

        // Validação de formulários
        function setupFormValidation() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = '#ef4444';
                            field.addEventListener('input', function() {
                                this.style.borderColor = '#4b5563';
                            }, { once: true });
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        showToast('error', 'Por favor, preencha todos os campos obrigatórios.');
                    }
                });
            });
        }

        // Confirmação aprimorada para exclusão
        function setupDeleteConfirmation() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Adiciona efeito visual no botão
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        }

        // Inicialização do sistema
        document.addEventListener('DOMContentLoaded', function() {
            setupTabs();
            setupSearch();
            setupAnimations();
            setupFormValidation();
            setupDeleteConfirmation();

            // Limpar formulário quando modal for fechado
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    const form = this.querySelector('form');
                    if (form && !form.querySelector('input[name*="id"]')) {
                        form.reset();
                        // Remove estilos de erro
                        const inputs = form.querySelectorAll('input, select');
                        inputs.forEach(input => {
                            input.style.borderColor = '#4b5563';
                        });
                    }
                });
            });

            // Feedback visual para botões
            const allButtons = document.querySelectorAll('.action-btn, .add-btn, .btn-primary, .btn-secondary, .tab-button');
            allButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            });
        });

        // CSS para animação de loading
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>

    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            });
        </script>
    <?php endif; ?>

</body>
</html>