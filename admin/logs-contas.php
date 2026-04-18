<?php
include 'partials/html.php';
?>

<?php
#======================================#
ini_set('display_errors', 0);
error_reporting(E_ALL);
#======================================#
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
#======================================#
# Expulsa usuário caso não esteja ativo
checa_login_adm();

#======================================#
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

// Inicializar variáveis para toast
$toastType = '';
$toastMessage = '';

// Processar remoção de indicação
if ($_POST && isset($_POST['remover_indicacao'])) {
    $user_id = (int)$_POST['user_id'];
    
    // Buscar dados do usuário
    $query_user = "SELECT mobile, invitation_code FROM usuarios WHERE id = $user_id";
    $result_user = mysqli_query($mysqli, $query_user);
    
    if (mysqli_num_rows($result_user) > 0) {
        $user_data = mysqli_fetch_assoc($result_user);
        $old_invitation_code = $user_data['invitation_code'];
        
        // Remover a indicação
        $query_remove = "UPDATE usuarios SET invitation_code = NULL WHERE id = $user_id";
        
        if (mysqli_query($mysqli, $query_remove)) {
            // Decrementar contador do afiliado se havia indicação
            if ($old_invitation_code) {
                $query_afiliado = "UPDATE usuarios SET pessoas_convidadas = pessoas_convidadas - 1 WHERE invite_code = '$old_invitation_code' AND pessoas_convidadas > 0";
                mysqli_query($mysqli, $query_afiliado);
            }
            
            $toastType = 'success';
            $toastMessage = 'Indicação removida com sucesso! Usuário: ' . $user_data['mobile'];
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao remover indicação!';
        }
    } else {
        $toastType = 'error';
        $toastMessage = 'Usuário não encontrado!';
    }
}

// Pegando a pesquisa do formulário, caso exista
$search = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
?>

<head>
    <?php $title = "Gerenciar IPs Duplicados";
    include 'partials/title-meta.php'; ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php'; ?>
    
    <!-- Estilos customizados para animações e efeitos modernos -->
    <style>
        .stats-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .table-row-hover:hover {
            background-color: rgba(0,123,255,0.05);
            transition: all 0.2s ease;
        }
        
        .user-card {
            transition: all 0.2s ease;
        }
        
        .user-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card {
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: none;
        }
        
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        
        .ip-badge-container .badge {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85em;
        }
        
        .empty-state i {
            opacity: 0.3;
        }
        
        .modal-content {
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        
        .detail-card, .action-card {
            transition: all 0.2s ease;
        }
        
        .detail-card:hover, .action-card:hover {
            transform: translateX(3px);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stats-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stats-card:nth-child(1) { animation-delay: 0.1s; }
        .stats-card:nth-child(2) { animation-delay: 0.2s; }
        .stats-card:nth-child(3) { animation-delay: 0.3s; }
        .stats-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/startbar.php'; ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">

                    <!-- Card Principal -->
                    <div class="col-12">
                        <div class="card rounded-4 shadow-sm">
                            <div class="card-header bg-transparent border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title mb-0 text-primary">
                                            <i class="iconoir-shield-alert me-2"></i>Contas com IPs Suspeitos
                                        </h5>
                                        <p class="text-muted mb-0 small">Identifique e gerencie contas potencialmente duplicadas</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                            <i class="iconoir-warning-triangle me-1"></i>Sistema Anti-Fraude
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Legenda de Cores -->
                                <div class="alert alert-info rounded-3 mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <h6 class="mb-2 text-info">
                                                <i class="iconoir-info-circle me-1"></i>Legenda de Tipos:
                                            </h6>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success me-2 px-3 py-2">
                                                        <i class="iconoir-user-crown me-1"></i>Influenciador
                                                    </span>
                                                    <small class="text-muted">Conta principal sem indicação</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning text-dark me-2 px-3 py-2">
                                                        <i class="iconoir-user-plus me-1"></i>Indicado
                                                    </span>
                                                    <small class="text-muted">Conta indicada legitimamente</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-danger me-2 px-3 py-2">
                                                        <i class="iconoir-user-xmark me-1"></i>Conta Suspeita
                                                    </span>
                                                    <small class="text-muted">Mesmo IP do indicador</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulário de Pesquisa -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="search-box rounded-3 p-3">
                                            <form method="get" action="" class="d-flex gap-3">
                                                <div class="flex-grow-1">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-transparent border-end-0 pe-3">
                                                            <i class="iconoir-search text-muted"></i>
                                                        </span>
                                                        <input type="text" name="search" class="form-control border-start-0 ps-3" 
                                                               placeholder="Pesquisar por IP, usuário ou código de convite..." 
                                                               value="<?= htmlspecialchars($search); ?>">
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary px-4" type="submit">
                                                    Pesquisar
                                                </button>
                                                <?php if($search): ?>
                                                <a href="?" class="btn btn-outline-secondary">
                                                    Limpar
                                                </a>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estatísticas Rápidas -->
                                <?php
                                global $mysqli;
                                
                                // Estatísticas básicas
                                $stats_query = "SELECT 
                                    COUNT(DISTINCT ip_cadastro) as ips_unicos,
                                    COUNT(*) as total_contas,
                                    SUM(CASE WHEN invitation_code IS NOT NULL THEN 1 ELSE 0 END) as contas_indicadas,
                                    COUNT(DISTINCT ip_cadastro) - COUNT(DISTINCT CASE WHEN ip_cadastro IN (
                                        SELECT ip_cadastro FROM usuarios GROUP BY ip_cadastro HAVING COUNT(*) > 1
                                    ) THEN ip_cadastro END) as ips_limpos
                                FROM usuarios WHERE ip_cadastro IS NOT NULL AND ip_cadastro != ''";
                                $stats_result = mysqli_query($mysqli, $stats_query);
                                $stats = mysqli_fetch_assoc($stats_result);
                                
                                // Contar IPs com duplicatas
                                $ips_duplicados_query = "SELECT COUNT(DISTINCT ip_cadastro) as ips_duplicados FROM usuarios 
                                    WHERE ip_cadastro IS NOT NULL AND ip_cadastro != '' AND ip_cadastro IN (
                                        SELECT ip_cadastro FROM usuarios GROUP BY ip_cadastro HAVING COUNT(*) > 1
                                    )";
                                $ips_duplicados_result = mysqli_query($mysqli, $ips_duplicados_query);
                                $ips_duplicados = mysqli_fetch_assoc($ips_duplicados_result)['ips_duplicados'];
                                ?>
                                
                                <div class="row mb-4">
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-primary-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-network h3 text-primary mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['ips_unicos']); ?></h5>
                                            <p class="text-muted mb-0 small">IPs Únicos Total</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-danger-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-warning-triangle h3 text-danger mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($ips_duplicados); ?></h5>
                                            <p class="text-muted mb-0 small">IPs Duplicados</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="stats-card bg-warning-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-user-plus h3 text-warning mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['contas_indicadas']); ?></h5>
                                            <p class="text-muted mb-0 small">Contas Indicadas</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="stats-card bg-success-subtle rounded-3 p-3 text-center">
                                            <div class="stats-icon mb-2">
                                                <i class="iconoir-check-circle h3 text-success mb-0"></i>
                                            </div>
                                            <h5 class="mb-1"><?= number_format($stats['ips_limpos']); ?></h5>
                                            <p class="text-muted mb-0 small">IPs Limpos</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabela Responsiva -->
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">
                                                    <i class="iconoir-network me-1 text-muted"></i>Endereço IP
                                                </th>
                                                <th class="border-0 text-center">
                                                    <i class="iconoir-group me-1 text-muted"></i>Qtd. Contas
                                                </th>
                                                <th class="border-0">
                                                    <i class="iconoir-user me-1 text-muted"></i>Usuários Associados
                                                </th>
                                                <th class="border-0">
                                                    <i class="iconoir-info-circle me-1 text-muted"></i>Detalhes das Contas
                                                </th>
                                                <th class="border-0 text-center">
                                                    <i class="iconoir-settings me-1 text-muted"></i>Ações
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Definir a página atual
                                            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                            $qnt_result_pg = 15; // Reduzido para melhor UX
                                            $inicio = ($pagina - 1) * $qnt_result_pg;

                                            // Buscar IPs com múltiplas contas
                                            $sql_condition = "WHERE ip_cadastro IS NOT NULL AND ip_cadastro != ''";
                                            if ($search) {
                                                $sql_condition .= " AND (ip_cadastro LIKE '%$search%' OR mobile LIKE '%$search%')";
                                            }

                                            // Query para encontrar IPs duplicados
                                            $query_ips = "
                                                SELECT ip_cadastro, COUNT(*) as total_contas
                                                FROM usuarios 
                                                $sql_condition
                                                GROUP BY ip_cadastro 
                                                HAVING COUNT(*) > 1 
                                                ORDER BY total_contas DESC 
                                                LIMIT $inicio, $qnt_result_pg
                                            ";

                                            $resultado_ips = mysqli_query($mysqli, $query_ips);

                                            if ($resultado_ips && mysqli_num_rows($resultado_ips) > 0) {
                                                while ($ip_data = mysqli_fetch_assoc($resultado_ips)) {
                                                    $ip_especifico = $ip_data['ip_cadastro'];
                                                    
                                                    // Buscar todas as contas deste IP
                                                    $query_contas = "SELECT id, mobile, invitation_code, data_cad FROM usuarios WHERE ip_cadastro = '$ip_especifico' ORDER BY data_cad ASC";
                                                    $resultado_contas = mysqli_query($mysqli, $query_contas);
                                                    $contas = [];
                                                    while ($conta = mysqli_fetch_assoc($resultado_contas)) {
                                                        $contas[] = $conta;
                                                    }
                                            ?>
                                                <tr class="table-row-hover">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ip-badge-container me-2">
                                                                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                                                    <i class="iconoir-network me-1"></i><?= $ip_data['ip_cadastro']; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 fs-6">
                                                            <strong><?= $ip_data['total_contas']; ?></strong> contas
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="users-list">
                                                            <?php foreach ($contas as $index => $conta): 
                                                                // Determinar o tipo da conta e cor
                                                                $tipo_conta = '';
                                                                $cor_classe = '';
                                                                $cor_badge = '';
                                                                $icon_type = '';
                                                                
                                                                if (!$conta['invitation_code']) {
                                                                    $tipo_conta = 'Influenciador';
                                                                    $cor_classe = 'text-success fw-bold';
                                                                    $cor_badge = 'bg-success';
                                                                    $cor_borda = '#198754';
                                                                    $cor_fundo = '25, 135, 84';
                                                                    $icon_type = 'iconoir-user-crown';
                                                                } else {
                                                                    // Verificar se é conta repetida
                                                                    $query_verificar = "SELECT COUNT(*) as contas_mesmo_ip FROM usuarios WHERE ip_cadastro = '{$ip_especifico}' AND invitation_code = '{$conta['invitation_code']}'";
                                                                    $result_verificar = mysqli_query($mysqli, $query_verificar);
                                                                    $verificar_data = mysqli_fetch_assoc($result_verificar);
                                                                    
                                                                    if ($verificar_data['contas_mesmo_ip'] > 1) {
                                                                        $tipo_conta = 'Conta Suspeita';
                                                                        $cor_classe = 'text-danger fw-bold';
                                                                        $cor_badge = 'bg-danger';
                                                                        $cor_borda = '#dc3545';
                                                                        $cor_fundo = '220, 53, 69';
                                                                        $icon_type = 'iconoir-user-xmark';
                                                                    } else {
                                                                        $tipo_conta = 'Indicado';
                                                                        $cor_classe = 'text-warning fw-bold';
                                                                        $cor_badge = 'bg-warning';
                                                                        $cor_borda = '#ffc107';
                                                                        $cor_fundo = '255, 193, 7';
                                                                        $icon_type = 'iconoir-user-plus';
                                                                    }
                                                                }
                                                            ?>
                                                                <div class="user-card mb-2 p-3 rounded-3" style="border-left: 4px solid <?= $cor_borda; ?>; background-color: rgba(<?= $cor_fundo; ?>, 0.1);">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-sm bg-<?= str_replace('bg-', '', $cor_badge); ?>-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                                <i class="<?= $icon_type; ?> text-<?= str_replace('bg-', '', $cor_badge); ?>"></i>
                                                                            </div>
                                                                            <strong class="<?= $cor_classe; ?>"><?= $conta['mobile']; ?></strong>
                                                                        </div>
                                                                        <span class="badge <?= $cor_badge; ?> <?= $cor_badge == 'bg-warning' ? 'text-dark' : 'text-white'; ?>">
                                                                            <i class="<?= $icon_type; ?> me-1"></i><?= $tipo_conta; ?>
                                                                        </span>
                                                                    </div>
                                                                    
                                                                    <?php if ($conta['invitation_code']): ?>
                                                                        <div class="invitation-info">
                                                                            <small class="text-info">
                                                                                <i class="iconoir-link me-1"></i>Indicado por: 
                                                                                <span class="fw-medium"><?= $conta['invitation_code']; ?></span>
                                                                            </small>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="invitation-info">
                                                                            <small class="text-muted">
                                                                                <i class="iconoir-user me-1"></i>Conta principal - Sem indicação
                                                                            </small>
                                                                            
                                                                            <!-- Botão para ver indicados -->
                                                                            <?php
                                                                            $query_invite_code = "SELECT invite_code, pessoas_convidadas, celular FROM usuarios WHERE id = '{$conta['id']}'";
                                                                            $result_invite = mysqli_query($mysqli, $query_invite_code);
                                                                            $invite_data = mysqli_fetch_assoc($result_invite);
                                                                            
                                                                            if ($invite_data && $invite_data['pessoas_convidadas'] > 0): ?>
                                                                                <div class="mt-2">
                                                                                    <button class="btn btn-sm btn-info rounded-pill" onclick="abrirModal('<?= $conta['mobile']; ?>', '<?= $conta['id']; ?>', '<?= $invite_data['invite_code']; ?>', '<?= $invite_data['celular']; ?>')">
                                                                                        <i class="iconoir-group me-1"></i>Ver <?= $invite_data['pessoas_convidadas']; ?> indicados
                                                                                    </button>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="details-list">
                                                            <?php foreach ($contas as $conta): ?>
                                                                <div class="detail-card mb-2 p-2 bg-light rounded-2">
                                                                    <div class="row g-1">
                                                                        <div class="col-6">
                                                                            <small class="text-muted">ID:</small>
                                                                            <div class="fw-bold text-primary">#<?= $conta['id']; ?></div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <small class="text-muted">Cadastro:</small>
                                                                            <div class="fw-medium small"><?= date('d/m/Y', strtotime($conta['data_cad'])); ?></div>
                                                                            <div class="text-muted small"><?= date('H:i:s', strtotime($conta['data_cad'])); ?></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="actions-list">
                                                            <?php foreach ($contas as $conta): 
                                                                // Determinar o tipo da conta para consistência
                                                                if (!$conta['invitation_code']) {
                                                                    $cor_badge = 'bg-success';
                                                                    $icon_type = 'iconoir-user-crown';
                                                                } else {
                                                                    $query_verificar = "SELECT COUNT(*) as contas_mesmo_ip FROM usuarios WHERE ip_cadastro = '{$ip_especifico}' AND invitation_code = '{$conta['invitation_code']}'";
                                                                    $result_verificar = mysqli_query($mysqli, $query_verificar);
                                                                    $verificar_data = mysqli_fetch_assoc($result_verificar);
                                                                    
                                                                    if ($verificar_data['contas_mesmo_ip'] > 1) {
                                                                        $cor_badge = 'bg-danger';
                                                                        $icon_type = 'iconoir-user-xmark';
                                                                    } else {
                                                                        $cor_badge = 'bg-warning';
                                                                        $icon_type = 'iconoir-user-plus';
                                                                    }
                                                                }
                                                            ?>
                                                                <div class="action-card mb-2 p-2 border rounded-2">
                                                                    <?php if ($conta['invitation_code']): ?>
                                                                        <div class="d-flex flex-column align-items-center">
                                                                            <span class="badge <?= $cor_badge; ?> <?= $cor_badge == 'bg-warning' ? 'text-dark' : 'text-white'; ?> mb-2">
                                                                                <i class="<?= $icon_type; ?> me-1"></i>Remover Indicação
                                                                            </span>
                                                                            <form method="post" class="w-100">
                                                                                <input type="hidden" name="user_id" value="<?= $conta['id']; ?>">
                                                                                <button type="submit" name="remover_indicacao" class="btn btn-sm btn-outline-danger w-100" 
                                                                                        onclick="return confirm('Tem certeza que deseja remover a indicação do usuário <?= $conta['mobile']; ?>?')">
                                                                                    <i class="iconoir-user-xmark me-1"></i>Remover
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="d-flex flex-column align-items-center">
                                                                            <span class="badge <?= $cor_badge; ?> text-white mb-2">
                                                                                <i class="<?= $icon_type; ?> me-1"></i>Influenciador
                                                                            </span>
                                                                            <span class="text-muted small text-center">Sem indicação para remover</span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center py-5'>";
                                                echo "<div class='empty-state'>";
                                                echo "<i class='iconoir-network text-muted' style='font-size: 3rem;'></i>";
                                                echo "<h5 class='text-muted mt-3'>Nenhum IP duplicado encontrado</h5>";
                                                echo "<p class='text-muted'>Todas as contas parecem estar com IPs únicos.</p>";
                                                echo "</div>";
                                                echo "</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginação -->
                                <?php
                                // Query para contar total de IPs duplicados (para paginação)
                                $count_query = "
                                    SELECT COUNT(DISTINCT ip_cadastro) as total
                                    FROM usuarios 
                                    $sql_condition
                                    GROUP BY ip_cadastro 
                                    HAVING COUNT(*) > 1
                                ";
                                $count_result = mysqli_query($mysqli, $count_query);
                                $total_registros = mysqli_num_rows($count_result);
                                $total_paginas = ceil($total_registros / $qnt_result_pg);

                                if ($total_paginas > 1):
                                ?>
                                <div class="d-flex justify-content-center mt-4">
                                    <nav aria-label="Navegação de páginas">
                                        <ul class="pagination">
                                            <?php if ($pagina > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=<?= $pagina - 1; ?><?= $search ? '&search=' . urlencode($search) : ''; ?>">
                                                        <i class="iconoir-nav-arrow-left me-1"></i>Anterior
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                                <li class="page-item <?= $i == $pagina ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?pagina=<?= $i; ?><?= $search ? '&search=' . urlencode($search) : ''; ?>"><?= $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($pagina < $total_paginas): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=<?= $pagina + 1; ?><?= $search ? '&search=' . urlencode($search) : ''; ?>">
                                                        Próxima<i class="iconoir-nav-arrow-right ms-1"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Modal para ver detalhes da conta -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-primary-subtle border-0">
                    <h5 class="modal-title text-primary" id="modalDetalhesLabel">
                        <i class="iconoir-user-crown me-2"></i>Detalhes da Conta e Indicações
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-primary rounded-3">
                                <div class="card-header bg-primary-subtle border-0">
                                    <h6 class="text-primary mb-0">
                                        <i class="iconoir-user me-1"></i>Informações da Conta Principal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <span class="text-muted small">Usuário:</span><br>
                                                <span id="modalUsuario" class="fw-bold text-primary fs-5"></span>
                                            </p>
                                            <p class="mb-2">
                                                <span class="text-muted small">ID da Conta:</span><br>
                                                <span id="modalId" class="badge bg-primary-subtle text-primary px-2 py-1"></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <span class="text-muted small">Código de Convite:</span><br>
                                                <span id="modalInviteCode" class="badge bg-success-subtle text-success px-2 py-1 font-monospace"></span>
                                            </p>
                                            <p class="mb-2">
                                                <span class="text-muted small">Telefone:</span><br>
                                                <span id="modalTelefone" class="fw-medium text-dark"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center p-3 bg-info-subtle rounded-3">
                                                <i class="iconoir-group h4 text-info me-3 mb-0"></i>
                                                <div>
                                                    <span class="text-muted small">Total de Pessoas Indicadas:</span><br>
                                                    <span id="modalTotalIndicados" class="fw-bold text-info fs-4"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-warning rounded-3">
                                <div class="card-header bg-warning-subtle border-0">
                                    <h6 class="text-warning mb-0">
                                        <i class="iconoir-users me-1"></i>Contas Indicadas por esta Conta
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0 px-3">
                                                        <i class="iconoir-hashtag me-1 text-muted"></i>ID
                                                    </th>
                                                    <th class="border-0">
                                                        <i class="iconoir-user me-1 text-muted"></i>Usuário
                                                    </th>
                                                    <th class="border-0">
                                                        <i class="iconoir-phone me-1 text-muted"></i>Telefone
                                                    </th>
                                                    <th class="border-0">
                                                        <i class="iconoir-calendar me-1 text-muted"></i>Data Cadastro
                                                    </th>
                                                    <th class="border-0">
                                                        <i class="iconoir-network me-1 text-muted"></i>IP Cadastro
                                                    </th>
                                                    <th class="border-0 text-center">
                                                        <i class="iconoir-settings me-1 text-muted"></i>Ação
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabelaIndicados">
                                                <!-- Será preenchido via JavaScript -->
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Carregando...</span>
                                                        </div>
                                                        <p class="text-muted mt-2 mb-0">Carregando indicações...</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="iconoir-cancel me-1"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php'; ?>
    <script src="assets/js/app.js"></script>

    <!-- Scripts customizados -->
    <script>
        // Função de Toast melhorada
        function showToast(type, message) {
            const toastPlacement = document.getElementById('toastPlacement');
            const toastId = 'toast_' + Date.now();
            
            let bgClass = 'bg-light';
            let iconClass = 'iconoir-info-circle';
            let borderClass = 'border-primary';
            
            switch(type) {
                case 'success':
                    bgClass = 'bg-success-subtle';
                    iconClass = 'iconoir-check-circle';
                    borderClass = 'border-success';
                    break;
                case 'error':
                    bgClass = 'bg-danger-subtle';
                    iconClass = 'iconoir-warning-triangle';
                    borderClass = 'border-danger';
                    break;
                case 'warning':
                    bgClass = 'bg-warning-subtle';
                    iconClass = 'iconoir-warning-triangle';
                    borderClass = 'border-warning';
                    break;
            }
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast align-items-center ${bgClass} ${borderClass} border-2 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header ${bgClass} border-0">
                    <i class="${iconClass} text-${type === 'error' ? 'danger' : type} me-2"></i>
                    <strong class="me-auto">Sistema Anti-Fraude</strong>
                    <small class="text-muted">agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body fw-medium">
                    ${message}
                </div>
            `;
            
            toastPlacement.appendChild(toast);

            const bootstrapToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 5000
            });
            bootstrapToast.show();

            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }

        // Função para abrir modal com detalhes melhorada
        function abrirModal(usuario, id, inviteCode, telefone) {
            // Preencher informações básicas
            document.getElementById('modalUsuario').textContent = usuario;
            document.getElementById('modalId').textContent = '#' + id;
            document.getElementById('modalInviteCode').textContent = inviteCode;
            document.getElementById('modalTelefone').textContent = telefone || 'N/A';
            
            // Reset da tabela
            const tbody = document.getElementById('tabelaIndicados');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Carregando indicações...</p>
                    </td>
                </tr>
            `;
            
            // Fazer requisição AJAX para buscar indicados
            fetch('buscar_indicados.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'invite_code=' + encodeURIComponent(inviteCode)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('modalTotalIndicados').textContent = data.total;
                    
                    tbody.innerHTML = '';
                    
                    if (data.indicados.length > 0) {
                        data.indicados.forEach(function(indicado, index) {
                            const isEvenRow = index % 2 === 0;
                            const rowClass = isEvenRow ? '' : 'table-active';
                            
                            const row = document.createElement('tr');
                            row.className = `${rowClass} table-row-hover`;
                            row.innerHTML = `
                                <td class="px-3">
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1">#${indicado.id}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info-subtle rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="iconoir-user text-info"></i>
                                        </div>
                                        <strong class="text-dark">${indicado.mobile}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">${indicado.celular || 'N/A'}</span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium small">${indicado.data_cad}</div>
                                        <small class="text-muted">${indicado.hora_cad || ''}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 font-monospace">
                                        ${indicado.ip_cadastro || 'N/A'}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" 
                                            onclick="removerIndicacaoModal(${indicado.id}, '${indicado.mobile}')"
                                            title="Remover indicação">
                                        <i class="iconoir-user-xmark me-1"></i>Remover
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="iconoir-users text-muted" style="font-size: 2.5rem;"></i>
                                        <h6 class="text-muted mt-3">Nenhuma indicação encontrada</h6>
                                        <p class="text-muted small mb-0">Esta conta ainda não indicou ninguém.</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                } else {
                    document.getElementById('modalTotalIndicados').textContent = '0';
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="alert alert-danger border-0 rounded-3 mb-0">
                                    <i class="iconoir-warning-triangle me-2"></i>
                                    <strong>Erro:</strong> ${data.message || 'Erro ao carregar dados'}
                                </div>
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById('modalTotalIndicados').textContent = '0';
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="alert alert-warning border-0 rounded-3 mb-0">
                                <i class="iconoir-wifi-off me-2"></i>
                                <strong>Erro de conexão:</strong> Não foi possível carregar os dados
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
            modal.show();
        }

        // Função para remover indicação via modal
        function removerIndicacaoModal(userId, username) {
            if (confirm(`Tem certeza que deseja remover a indicação do usuário ${username}?\n\nEsta ação não pode ser desfeita.`)) {
                // Mostrar loading
                showToast('info', `Removendo indicação de ${username}...`);
                
                // Criar form temporário e submeter
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="user_id" value="${userId}">
                    <input type="hidden" name="remover_indicacao" value="1">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Adicionar efeitos visuais aos cards de estatísticas
        document.addEventListener('DOMContentLoaded', function() {
            const statsCards = document.querySelectorAll('.stats-card');
            
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Adicionar animação de hover nas linhas da tabela
            const tableRows = document.querySelectorAll('.table-row-hover');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(13, 110, 253, 0.08)';
                    this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.boxShadow = '';
                });
            });

            // Adicionar contador animado nas estatísticas
            const counters = document.querySelectorAll('.stats-card h5');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                if (!isNaN(target) && target > 0) {
                    animateCounter(counter, target);
                }
            });
        });

        // Função para animar contadores
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current).toLocaleString();
            }, 30);
        }

        // Adicionar loading state nos botões
        document.querySelectorAll('form[method="post"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="iconoir-hourglass me-1"></i>Processando...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Melhorar responsividade da tabela
        function adjustTableForMobile() {
            if (window.innerWidth < 768) {
                document.querySelectorAll('.table-responsive').forEach(table => {
                    table.style.fontSize = '0.85rem';
                });
            }
        }

        window.addEventListener('resize', adjustTableForMobile);
        adjustTableForMobile();
    </script>

    <!-- Exibir Toast baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            });
        </script>
    <?php endif; ?>

</body>
</html>