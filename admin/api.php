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
$csrf = new CSRF_Protect();

checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_afiliados_config() {
    global $mysqli;
    $qry = "SELECT * FROM fiverscan WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function get_afiliados_config2() {
    global $mysqli;
    $qry = "SELECT * FROM playfiver WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function get_pgclone_config() {
    global $mysqli;
    $qry = "SELECT * FROM pgclone WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function get_igamewin_config() {
    global $mysqli;
    $qry = "SELECT * FROM igamewin WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function get_maxapigames_config() {
    global $mysqli;
    try {
        $result = mysqli_query($mysqli, "SELECT * FROM maxapigames WHERE id = 1");
        if (!$result) return null;
        return mysqli_fetch_assoc($result);
    } catch (Exception $e) {
        return null;
    }
}

function update_maxapigames_config($data) {
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE maxapigames SET
        url = ?,
        agent_code = ?,
        agent_token = ?,
        agent_secret = ?,
        ativo = ?
        WHERE id = 1");

    $qry->bind_param(
        "ssssi",
        $data['url'],
        $data['agent_code'],
        $data['agent_token'],
        $data['agent_secret'],
        $data['ativo']
    );
    return $qry->execute();
}

function update_config($data) {
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE fiverscan SET 
        url = ?, 
        agent_code = ?, 
        agent_token = ?,
        agent_secret = ?
        WHERE id = 1");
    $qry->bind_param("ssss", $data['url'], $data['agent_code'], $data['agent_token'], $data['agent_secret']);
    return $qry->execute();
}

function get_infinitysoft_config()
{
    global $mysqli;
    $qry = "SELECT * FROM infinitysoft WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function update_infinitysoft_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE infinitysoft SET 
        url = ?, 
        agent_code = ?, 
        agent_token = ?, 
        agent_secret = ?, 
        ativo = ?
        WHERE id = 1");

    $qry->bind_param(
        "ssssi",
        $data['url'],
        $data['agent_code'],
        $data['agent_token'],
        $data['agent_secret'],
        $data['ativo']
    );
    return $qry->execute();
}

function update_pgclone_config($data) {
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE pgclone SET 
        url = ?, 
        agent_code = ?,
        agent_token = ?, 
        agent_secret = ?, 
        ativo = ?
        WHERE id = 1");

    $qry->bind_param(
        "ssssi",
        $data['url'],
        $data['agent_code'],
        $data['agent_token'],
        $data['agent_secret'],
        $data['ativo']
    );
    return $qry->execute();
}

function update_igamewin_config($data) {
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE igamewin SET 
        url = ?, 
        agent_code = ?,
        agent_token = ?, 
        ativo = ?,
        rtp = ?
        WHERE id = 1");

    $qry->bind_param(
        "sssii",
        $data['url'],
        $data['agent_code'],
        $data['agent_token'],
        $data['ativo'],
        $data['rtp']
    );
    return $qry->execute();
}

function update_config_playfiver($data) {
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE playfiver SET 
            url = ?, 
            agent_code = ?, 
            agent_token = ?,
            agent_secret = ?
            WHERE id = 1");
    $qry->bind_param("ssss", $data['url'], $data['agent_code'], $data['agent_token'], $data['agent_secret']);
    return $qry->execute();
}

function update_gateway_status($gateway, $status) {
    global $mysqli;
    if ($gateway === 'fiverscan') {
        $qry = "UPDATE fiverscan SET ativo = ? WHERE id = 1";
        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param("i", $status);
        $stmt->execute();
        $qry = "UPDATE playfiver SET ativo = ? WHERE id = 1";
        $status = $status ? 0 : 1; // Inverte o estado para o outro gateway
    } else {
        $qry = "UPDATE playfiver SET ativo = ? WHERE id = 1";
        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param("i", $status);
        $stmt->execute();
        $qry = "UPDATE fiverscan SET ativo = ? WHERE id = 1";
        $status = $status ? 0 : 1; // Inverte o estado para o outro gateway
    }
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param("i", $status);
    return $stmt->execute();
}

function get_provider_stats() {
    global $mysqli;
    $stats = [
        'total' => 0,
        'active' => 0,
        'configurations' => 0
    ];
    
    // Count total providers
    $providers = ['fiverscan', 'playfiver', 'pgclone', 'igamewin', 'infinitysoft', 'maxapigames'];
    $stats['total'] = count($providers);
    
    // Count active providers
    $active_count = 0;
    foreach ($providers as $provider) {
        $qry = "SELECT ativo FROM $provider WHERE id = 1";
        $result = mysqli_query($mysqli, $qry);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            if ($row['ativo'] == 1) {
                $active_count++;
            }
        }
    }
    $stats['active'] = $active_count;
    
    // Count total configurations (fields)
    $stats['configurations'] = 12; // Fixed number based on form fields
    
    return $stats;
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['client_code'])) {
        $data = [];
        $data['url'] = $_POST['url'];
        $data['agent_code'] = $_POST['client_code'];
        $data['agent_token'] = $_POST['client_token'];
        $data['agent_secret'] = $_POST['client_secret'];
        
        if (update_config_playfiver($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais PlayFiver atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as Credenciais PlayFiver. Tente novamente.';
        }
    } elseif (isset($_POST['update_infinitysoft'])) {
        $data = [
            'url' => $_POST['url_infinitysoft'],
            'agent_code' => $_POST['agent_code_infinitysoft'],
            'agent_token' => $_POST['agent_token_infinitysoft'],
            'agent_secret' => $_POST['agent_secret_infinitysoft'],
            'ativo' => intval($_POST['ativo_infinitysoft']),
        ];

        if (update_infinitysoft_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais InfinitySoft atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as credenciais do InfinitySoft. Tente novamente.';
        }
    } elseif (isset($_POST['update_pgclone'])) {
        $data = [
            'url' => $_POST['url_pgclone'],
            'agent_code' => $_POST['agent_code_pgclone'],
            'agent_token' => $_POST['agent_token_pgclone'],
            'agent_secret' => $_POST['agent_secret_pgclone'],
            'ativo' => intval($_POST['ativo_pgclone']),
        ];

        if (update_pgclone_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais PGClone atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as credenciais do PGClone. Tente novamente.';
        }
    } elseif (isset($_POST['update_igamewin'])) {
        $data = [
            'url' => $_POST['url_igamewin'],
            'agent_code' => $_POST['agent_code_igamewin'],
            'agent_token' => $_POST['agent_token_igamewin'],
            'ativo' => intval($_POST['ativo_igamewin']),
            'rtp' => intval($_POST['rtp_igamewin']),
        ];

        if (update_igamewin_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais iGameWin atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as credenciais do iGameWin. Tente novamente.';
        }
    } elseif (isset($_POST['update_maxapigames'])) {
        $data = [
            'url' => $_POST['url_maxapigames'],
            'agent_code' => $_POST['agent_code_maxapigames'],
            'agent_token' => $_POST['agent_token_maxapigames'],
            'agent_secret' => $_POST['agent_secret_maxapigames'],
            'ativo' => intval($_POST['ativo_maxapigames']),
        ];

        if (update_maxapigames_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais MaxApiGames atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as credenciais do MaxApiGames. Tente novamente.';
        }
    } else {
        $data = [];
        $data['url'] = $_POST['url'];
        $data['agent_code'] = $_POST['agent_code'];
        $data['agent_token'] = $_POST['agent_token'];
        $data['agent_secret'] = $_POST['agent_secret'];
        
        if (update_config($data)) {
            $toastType = 'success';
            $toastMessage = 'Credenciais FiverScan atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as Credenciais FiverScan. Tente novamente.';
        }
    }
}

$config = get_afiliados_config();
$config2 = get_afiliados_config2();
$infinitysoft_config = get_infinitysoft_config();
$pgclone_config = get_pgclone_config();
$igamewin_config = get_igamewin_config();
$maxapigames_config = get_maxapigames_config();
$provider_stats = get_provider_stats();
?>

<head>
    <?php $title = "Configurações de Credenciais";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        :root {
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --info-accent: #3b82f6;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-info: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            color: var(--text-primary) !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        .page-wrapper {
            background: var(--primary-bg) !important;
            min-height: 100vh;
        }

        .page-content {
            background: var(--primary-bg) !important;
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .credentials-header {
            background: var(--gradient-info);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
        }

        .credentials-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 15s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .credentials-header h4 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            z-index: 2;
        }

        .credentials-header p {
            margin: 16px 0 0 0;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        /* Stats Overview */
        .credentials-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--secondary-bg) !important;
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4);
            border-color: var(--info-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            border-radius: 20px 20px 0 0;
        }

        .stat-card.active::before {
            background: var(--gradient-success);
        }

        .stat-card.providers::before {
            background: var(--gradient-info);
        }

        .stat-card.configuration::before {
            background: var(--gradient-warning);
        }

        .stat-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
            position: relative;
        }

        .stat-icon.active {
            background: var(--gradient-success);
        }

        .stat-icon.providers {
            background: var(--gradient-info);
        }

        .stat-icon.configuration {
            background: var(--gradient-warning);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: var(--text-primary) !important;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        /* Provider Cards */
        .provider-card {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 20px !important;
            margin-bottom: 32px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .provider-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(59, 130, 246, 0.3);
            border-color: var(--info-color);
        }

        .provider-header {
            background: var(--gradient-info);
            padding: 24px 32px;
            position: relative;
            overflow: hidden;
        }

        .provider-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="30" fill="rgba(255,255,255,0.1)"/></svg>');
            transform: translate(30%, -30%);
        }

        .provider-title {
            color: white !important;
            font-size: 24px !important;
            font-weight: 800 !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .provider-body {
            background: var(--secondary-bg) !important;
            padding: 32px !important;
        }

        /* Form Controls */
        .credential-group {
            background: var(--primary-bg);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .credential-group:hover {
            border-color: var(--info-color);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
        }

        .credential-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-info);
            border-radius: 2px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .credential-group:hover::before {
            opacity: 1;
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control, .form-select {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 12px !important;
            padding: 16px 20px !important;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--secondary-bg) !important;
            border-color: var(--info-color) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-secondary) !important;
            font-style: italic;
        }

        .form-text {
            color: var(--text-secondary) !important;
            font-size: 14px;
            margin-top: 8px;
            font-style: italic;
        }

        /* Buttons */
        .btn {
            border-radius: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            padding: 16px 32px !important;
            font-size: 16px !important;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-success {
            background: var(--gradient-success) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px) scale(1.05) !important;
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.6) !important;
        }

        /* Input Groups */
        .input-group {
            position: relative;
        }

        .credential-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 18px;
            z-index: 10;
        }

        .form-control.with-icon {
            padding-left: 50px !important;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        .fade-in-delay-1 {
            animation: fadeInUp 0.6s ease 0.1s forwards;
            opacity: 0;
        }

        .fade-in-delay-2 {
            animation: fadeInUp 0.6s ease 0.2s forwards;
            opacity: 0;
        }

        .fade-in-delay-3 {
            animation: fadeInUp 0.6s ease 0.3s forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Animation */
        .success-animation {
            animation: successPulse 0.6s ease;
        }

        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Provider Specific Colors */
        .provider-card.playfiver .provider-header {
            background: linear-gradient(135deg, #fd79a8, #e84393);
        }

        .provider-card.maxapigames .provider-header {
            background: linear-gradient(135deg, #00b894, #00cec9);
        }

        /* Toast Styling */
        .toast {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 16px !important;
            color: var(--text-primary) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .toast-header {
            background: var(--secondary-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 16px 16px 0 0 !important;
        }

        .toast-body {
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }
            
            .credentials-header {
                padding: 24px;
            }
            
            .credentials-header h4 {
                font-size: 24px;
            }
            
            .credentials-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .provider-body {
                padding: 20px !important;
            }

            .credential-group {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .credentials-stats {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                
                <!-- Header -->
                <div class="credentials-header fade-in">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4>
                                <i class="bi bi-shield-lock"></i>
                                Configurações de Credenciais
                            </h4>
                            <p>Gerencie todas as credenciais dos provedores de jogos • Mantenha suas configurações sempre atualizadas</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="credentials-stats">
                    <div class="stat-card active fade-in-delay-1">
                        <div class="stat-icon active">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-value"><?= $provider_stats['active']; ?></div>
                        <div class="stat-label">Provedores Ativos</div>
                    </div>
                    <div class="stat-card providers fade-in-delay-2">
                        <div class="stat-icon providers">
                            <i class="bi bi-hdd-network"></i>
                        </div>
                        <div class="stat-value"><?= $provider_stats['total']; ?></div>
                        <div class="stat-label">Total de Provedores</div>
                    </div>
                    <div class="stat-card configuration fade-in-delay-3">
                        <div class="stat-icon configuration">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div class="stat-value"><?= $provider_stats['configurations']; ?></div>
                        <div class="stat-label">Configurações</div>
                    </div>
                </div>
                
                
                <!-- MaxApiGames Provider -->
                <div class="provider-card maxapigames fade-in-delay-1">
                    <div class="provider-header">
                        <h4 class="provider-title">
                            <i class="bi bi-joystick"></i>
                            MaxApiGames
                        </h4>
                    </div>
                    <div class="provider-body">
                        <form method="POST" action="">
                            <input type="hidden" name="update_maxapigames">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-link-45deg"></i>
                                            URL do Provedor
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="url_maxapigames" class="form-control with-icon"
                                                   value="<?= htmlspecialchars($maxapigames_config['url'] ?? '') ?>"
                                                   placeholder="https://maxapigames.com/api/v2" required>
                                            <i class="credential-icon bi bi-link-45deg"></i>
                                        </div>
                                        <div class="form-text">Endpoint principal da API MaxApiGames</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-person-badge"></i>
                                            Agent Code
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="agent_code_maxapigames" class="form-control with-icon"
                                                   value="<?= htmlspecialchars($maxapigames_config['agent_code'] ?? '') ?>"
                                                   placeholder="MAX_AGENT_CODE" required>
                                            <i class="credential-icon bi bi-person-badge"></i>
                                        </div>
                                        <div class="form-text">Codigo unico do agente</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-key"></i>
                                            Agent Token
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="agent_token_maxapigames" class="form-control with-icon"
                                                   value="<?= htmlspecialchars($maxapigames_config['agent_token'] ?? '') ?>"
                                                   placeholder="••••••••••••••••" required>
                                            <i class="credential-icon bi bi-key"></i>
                                        </div>
                                        <div class="form-text">Token para chamadas de API (game_launch, game_list, etc.)</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-shield-lock"></i>
                                            Agent Secret
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="agent_secret_maxapigames" class="form-control with-icon"
                                                   value="<?= htmlspecialchars($maxapigames_config['agent_secret'] ?? '') ?>"
                                                   placeholder="••••••••••••••••" required>
                                            <i class="credential-icon bi bi-shield-lock"></i>
                                        </div>
                                        <div class="form-text">Chave secreta para validar webhooks (diferente do token)</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-toggle-on"></i>
                                            Status do Provedor
                                        </label>
                                        <select name="ativo_maxapigames" class="form-select" required>
                                            <option value="1" <?= ($maxapigames_config['ativo'] ?? 0) == 1 ? 'selected' : '' ?>>&#10004; Ativo</option>
                                            <option value="0" <?= ($maxapigames_config['ativo'] ?? 0) == 0 ? 'selected' : '' ?>>&#10008; Inativo</option>
                                        </select>
                                        <div class="form-text">Define se o provedor esta ativo no sistema</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save me-2"></i>
                                    Salvar Configuracoes MaxApiGames
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PlayFiver Provider -->
                <div class="provider-card playfiver fade-in-delay-3">
                    <div class="provider-header">
                        <h4 class="provider-title">
                            <i class="bi bi-play-circle"></i>
                            PlayFiver
                        </h4>
                    </div>
                    <div class="provider-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-person-badge"></i>
                                            Agent Code
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="client_code" class="form-control with-icon" 
                                                   value="<?= htmlspecialchars($config2['agent_code']) ?>"
                                                   placeholder="PLF_AGENT_CODE" required>
                                            <i class="credential-icon bi bi-person-badge"></i>
                                        </div>
                                        <div class="form-text">Código único do agente PlayFiver</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-key"></i>
                                            Agent Token
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="client_token" class="form-control with-icon" 
                                                   value="<?= htmlspecialchars($config2['agent_token']) ?>"
                                                   placeholder="••••••••••••••••" required>
                                            <i class="credential-icon bi bi-key"></i>
                                        </div>
                                        <div class="form-text">Token de autenticação do agente</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-shield-lock"></i>
                                            Agent Secret
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="client_secret" class="form-control with-icon" 
                                                   value="<?= htmlspecialchars($config2['agent_secret']) ?>"
                                                   placeholder="••••••••••••••••" required>
                                            <i class="credential-icon bi bi-shield-lock"></i>
                                        </div>
                                        <div class="form-text">Chave secreta para validação</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="credential-group">
                                        <label class="form-label">
                                            <i class="bi bi-link-45deg"></i>
                                            Endpoint URL
                                        </label>
                                        <div class="input-group">
                                            <input type="text" name="url" class="form-control with-icon" 
                                                   value="<?= htmlspecialchars($config2['url']) ?>"
                                                   placeholder="https://api.playfiver.com" required>
                                            <i class="credential-icon bi bi-link-45deg"></i>
                                        </div>
                                        <div class="form-text">URL do endpoint da API PlayFiver</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save me-2"></i>
                                    Salvar Configurações PlayFiver
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- container -->
        </div><!-- page content -->
    </div><!-- page-wrapper -->


    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    
    <script>
        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats counters
            animateCounters();
            
            // Add loading animation to buttons on form submit
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        setLoadingState(submitBtn, true);
                    }
                });
            });

            // Add focus effects to credential groups
            const credentialGroups = document.querySelectorAll('.credential-group');
            credentialGroups.forEach(group => {
                const input = group.querySelector('input, select');
                if (input) {
                    input.addEventListener('focus', () => {
                        group.style.transform = 'translateY(-2px)';
                        group.style.boxShadow = '0 12px 30px rgba(59, 130, 246, 0.3)';
                    });
                    
                    input.addEventListener('blur', () => {
                        group.style.transform = 'translateY(0)';
                        group.style.boxShadow = '';
                    });
                }
            });

            // Add hover effects to provider cards
            const providerCards = document.querySelectorAll('.provider-card');
            providerCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-6px) scale(1.01)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Password visibility toggle
            addPasswordToggle();
            
            // Add ripple effect to buttons
            addRippleEffect();
        });

        // Animate stat counters
        function animateCounters() {
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const value = parseInt(stat.textContent);
                if (!isNaN(value) && value > 0) {
                    animateCounter(stat, value);
                }
            });
        }

        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 50);
        }

        // Enhanced loading states
        function setLoadingState(button, isLoading) {
            if (isLoading) {
                button.dataset.originalText = button.innerHTML;
                button.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Processando...';
                button.disabled = true;
                button.classList.add('loading');
            } else {
                button.innerHTML = button.dataset.originalText;
                button.disabled = false;
                button.classList.remove('loading');
            }
        }

        // Password visibility toggle
        function addPasswordToggle() {
            const passwordInputs = document.querySelectorAll('input[type="password"]');
            passwordInputs.forEach(input => {
                const toggleBtn = document.createElement('button');
                toggleBtn.type = 'button';
                toggleBtn.className = 'btn btn-outline-secondary position-absolute';
                toggleBtn.style.cssText = 'right: 10px; top: 50%; transform: translateY(-50%); z-index: 10; border: none; background: none; color: var(--text-secondary);';
                toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
                
                input.parentNode.style.position = 'relative';
                input.parentNode.appendChild(toggleBtn);
                
                toggleBtn.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                    } else {
                        input.type = 'password';
                        this.innerHTML = '<i class="bi bi-eye"></i>';
                    }
                });
            });
        }

        // Add ripple effect to buttons
        function addRippleEffect() {
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        }
        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
            
            .spinner-border-sm {
                width: 1rem;
                height: 1rem;
                border-width: 0.125em;
            }
            
            .form-loading {
                position: relative;
                pointer-events: none;
                opacity: 0.7;
            }
            
            .form-success {
                border-color: var(--success-color) !important;
                box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
            }
            
            .form-error {
                border-color: var(--danger-color) !important;
                box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25) !important;
            }
        `;
        document.head.appendChild(style);
    </script>

    <script>
    function showToast(type, message) {
        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;padding:16px 24px;border-radius:12px;color:#fff;font-weight:600;font-size:14px;box-shadow:0 8px 25px rgba(0,0,0,0.3);transition:opacity 0.5s;max-width:400px;';
        toast.style.background = type === 'success' ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#ef4444,#dc2626)';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; }, 3000);
        setTimeout(function() { toast.remove(); }, 3500);
    }
    </script>

    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                }, 500);
            });
        </script>
    <?php endif; ?>

</body>
</html>