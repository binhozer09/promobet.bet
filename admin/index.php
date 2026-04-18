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

if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
    echo "<script>setTimeout(function() { 
        var modal = new bootstrap.Modal(document.getElementById('modal2FA'));
        modal.show();
    }, 500);</script>";
}

$depositos_dias = depositos_por_dia();
$saques_dias = saques_por_dia();
$labels_depositos = json_encode(array_column($depositos_dias, 'dia'));
$dados_depositos = json_encode(array_column($depositos_dias, 'total'));
$labels_saques = json_encode(array_column($saques_dias, 'dia'));
$dados_saques = json_encode(array_column($saques_dias, 'total'));
?>

<head>
    <?php $title = "Dashboard";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
</head>

<body>

    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="modal fade" id="modal2FA" tabindex="-1" aria-labelledby="modal2FALabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal2FALabel">Autenticação de 2 Fatores</h5>
                </div>
                <div class="modal-body">
                    <div id="alert-2fa" class="alert alert-danger d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="alert-message">Token inválido. Tente novamente!</span>
                    </div>
                    
                    <p>Por favor, insira o código de autenticação 2FA para continuar.</p>
                    <input type="text" id="token2fa" class="form-control" placeholder="Token 2FA" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-submit-2fa">
                        <span id="btn-text">Validar Token</span>
                        <span id="btn-spinner" class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showAlert(message, type = 'danger') {
        const alert = document.getElementById('alert-2fa');
        const alertMessage = document.getElementById('alert-message');
        
        alert.className = `alert alert-${type}`;
        alertMessage.textContent = message;
        alert.classList.remove('d-none');
        
        setTimeout(() => {
            alert.classList.add('d-none');
        }, 5000);
    }
    
    function hideAlert() {
        document.getElementById('alert-2fa').classList.add('d-none');
    }
    
    function setLoading(loading) {
        const btn = document.getElementById('btn-submit-2fa');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        
        if (loading) {
            btn.disabled = true;
            btnText.textContent = 'Validando...';
            btnSpinner.classList.remove('d-none');
        } else {
            btn.disabled = false;
            btnText.textContent = 'Validar Token';
            btnSpinner.classList.add('d-none');
        }
    }
    
    document.getElementById('btn-submit-2fa').addEventListener('click', function() {
        var token2fa = document.getElementById('token2fa').value.trim();
        
        hideAlert();
        
        if (token2fa === '') {
            showAlert('Por favor, insira o token 2FA!');
            document.getElementById('token2fa').focus();
            return;
        }
        
        setLoading(true);
        
        fetch('validar_2fa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'token=' + encodeURIComponent(token2fa)
            })
            .then(response => response.json())
            .then(data => {
                setLoading(false);
                
                if (data.success) {
                    showAlert('Token validado com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 1500);
                } else {
                    showAlert(data.message || 'Token inválido. Tente novamente!');
                    document.getElementById('token2fa').value = '';
                    document.getElementById('token2fa').focus();
                }
            })
            .catch(error => {
                setLoading(false);
                showAlert('Erro de conexão. Tente novamente!');
            });
    });
    
    document.getElementById('token2fa').addEventListener('input', function() {
        hideAlert();
    });
    
    document.getElementById('token2fa').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('btn-submit-2fa').click();
        }
    });
    </script>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    
                    <?php
                    $dataFile = __DIR__ . '/online_users.json';
                    $users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
                    $total_online = count($users);
                    ?>

                    <!-- SEÇÃO CADASTROS -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle">
                            <i class="iconoir-user-plus h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                        <h5 class="mb-0 ms-1">Cadastros</h5>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Usuários Online</p>
                                        <h3 class="mt-2 mb-0 fw-bold" id="online-count"><?php echo $total_online; ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-success">🌍 Usuários conectados agora</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total cadastros</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios(); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-group h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Total de cadastros da operação.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Cadastros hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios_diarios(); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Novos cadastros realizados hoje.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Cadastros 90D</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios_ultimos_90_dias(); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Novos cadastros nos últimos 90 dias.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO FINANCEIRO -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle">
                            <i class="iconoir-database-monitor h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                        <h5 class="mb-0 ms-1">Financeiro</h5>
                    </div>

                    <!-- Gráficos primeiro -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title">Gráfico de Depósitos por Dia</h5>
                                <div id="chart-depositos"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title">Gráfico de Saques por Dia</h5>
                                <div id="chart-saques"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards financeiros do arquivo original -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total de Jogadas</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= total_jogadas(); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-piggy-bank h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $jogo_popular = jogo_mais_jogado();
                                    $jogo_color_class = ($jogo_popular === 'Nenhum jogo encontrado') ? 'text-danger' : 'text-success';
                                ?>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    Jogo popular: <span class="<?= $jogo_color_class ?>"><?= $jogo_popular; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Lucro cassino</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saldo_cassino()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-check-circle h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Lucro da operação (Dep - Saques).</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Depósitos pagos hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(depositos_diarios_pagos()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-check-circle h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Total de depósitos pagos hoje</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Depósitos processando hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(depositos_diarios()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-hourglass h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Total de depósitos processando hoje</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Saques total</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_total()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-check-circle h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Total de saques da operação.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Saques pagos hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_diarios_pagos()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-check-circle h1 align-self-center mb-0 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Total de saques pagos hoje.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Saques pendentes hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_diarios_pagos()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-hourglass h1 align-self-center mb-0 "></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-warning">Valor de saques pendentes hoje.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO ESTATÍSTICA DOS USUÁRIOS -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle">
                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                        <h5 class="mb-0 ms-1">Estatística dos Usuários</h5>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total de Saldos dos Usuários</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(total_saldos_usuarios()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Saldo de todos os Usuários</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total de Saldos dos Afiliados</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(total_saldos_usuarios_afiliados()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Saldo de todos os Afiliados</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total Sacado</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(total_saques_usuarios()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Total de saques aprovados</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Saldo Médio</p>
                                        <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(media_saldo_usuarios()); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Saldo médio por usuário</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO VISITAS -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle">
                            <i class="iconoir-server-connection h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                        <h5 class="mb-0 ms-1">Visitas</h5>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Acessos total</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= visitas_count('total'); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Total de acessos: <?= visitas_count('total'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Acessos Hoje</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= visitas_count('diario'); ?></h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Acessos hoje: <?= visitas_count('diario'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Lugar Mais Acessado</p>
                                        <?php
                                        $lugar_mais_acessado = visitas_count2('total');
                                        ?>
                                        <h3 class="mt-2 mb-0 fw-bold">
                                            <?php
                                            if ($lugar_mais_acessado['cidade'] && $lugar_mais_acessado['estado']) {
                                                echo $lugar_mais_acessado['cidade'] . ', ' . $lugar_mais_acessado['estado'];
                                            } else {
                                                echo "Não há dados";
                                            }
                                            ?>
                                        </h3>
                                    </div>
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    <span class="text-danger">Acessos: <?= $lugar_mais_acessado['total']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO HISTÓRICO FINANCEIRO -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle">
                            <i class="iconoir-database-monitor h1 align-self-center mb-0 text-secondary"></i>
                        </div>
                        <h5 class="mb-0 ms-1">Histórico financeiro</h5>
                    </div>

                    <div class="col-lg-6">
                        <div class="card card-h-100 rounded-4">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Saques Aprovados</h4>
                                        <p class="fs-11 fst-bold text-muted">Últimos 5 Saques Aprovados.<a href="#!" class="link-danger ms-1"><i class="align-middle iconoir-refresh"></i></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive browser_users">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Id</th>
                                                <th class="border-top-0">Usuário</th>
                                                <th class="border-top-0">Data/Hora</th>
                                                <th class="border-top-0">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            global $mysqli;
                                            $pagina = 1;
                                            $qnt_result_pg = 5;
                                            $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

                                            $result_usuario = "SELECT * FROM solicitacao_saques WHERE status=1 ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
                                            $resultado_usuario = mysqli_query($mysqli, $result_usuario);

                                            if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
                                                while ($data = mysqli_fetch_assoc($resultado_usuario)) {
                                                    $data_return = data_user_id($data['id_user']);
                                                    ?>
                                                    <tr>
                                                        <td><?= $data['id']; ?></td>
                                                        <td><?= $data_return['mobile']; ?></td>
                                                        <td><?= ver_data($data['data_hora']); ?></td>
                                                        <td>R$ <?= Reais2($data['valor']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>Sem dados disponíveis!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card card-h-100 rounded-4">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Depósitos Pagos</h4>
                                        <p class="fs-11 fst-bold text-muted">Últimos 5 Depósitos Pagos.<a href="#!" class="link-danger ms-1"><i class="align-middle iconoir-refresh"></i></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Id</th>
                                                <th class="border-top-0">Usuário</th>
                                                <th class="border-top-0">Data/Hora</th>
                                                <th class="border-top-0">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result_visitas = "SELECT * FROM transacoes ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
                                            $resultado_visitas = mysqli_query($mysqli, $result_visitas);

                                            if ($resultado_visitas && mysqli_num_rows($resultado_visitas) > 0) {
                                                while ($visit = mysqli_fetch_assoc($resultado_visitas)) {
                                                    $change_icon = ($visit['valor'] >= 20) ? 'fa-caret-up ' : 'fa-caret-down text-warning';
                                                    ?>
                                                    <tr>
                                                        <td><?= $visit['id']; ?></td>
                                                        <td><?= $visit['usuario']; ?></td>
                                                        <td><?= $visit['data_hora']; ?></td>
                                                        <td><?= $visit['valor']; ?> <i class="fas <?= $change_icon; ?> font-16"></i></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>Sem dados disponíveis!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABELA DE VISITAS -->
                    <div class="col-lg-12">
                        <div class="card card-h-100 rounded-4">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Visitas</h4>
                                        <p class="fs-11 fst-bold text-muted">Últimas Visitas.<a href="#!" class="link-danger ms-1"><i class="align-middle iconoir-refresh"></i></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0">Cidade</th>
                                                <th class="border-top-0">Estado</th>
                                                <th class="border-top-0">IP</th>
                                                <th class="border-top-0">Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result_visitas = "SELECT * FROM visita_site ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
                                            $resultado_visitas = mysqli_query($mysqli, $result_visitas);

                                            if ($resultado_visitas && mysqli_num_rows($resultado_visitas) > 0) {
                                                while ($visit = mysqli_fetch_assoc($resultado_visitas)) {
                                            ?>
                                                    <tr>
                                                        <td><?= $visit['cidade']; ?></td>
                                                        <td><?= $visit['estado']; ?></td>
                                                        <td><?= $visit['ip_visita']; ?></td>
                                                        <td><?= $visit['data_cad'] . " " . $visit['hora_cad']; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center empty-message'>Sem dados disponíveis!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include 'partials/endbar.php' ?>
        </div>
    </div>

    <?php include 'partials/vendorjs.php' ?>

    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/data/stock-prices.js"></script>
    <script src="assets/libs/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/libs/jsvectormap/maps/world.js"></script>
    <script src="assets/js/pages/index.init.js"></script>
    <script src="assets/js/app.js"></script>

    <script>
    function atualizarOnline() {
        fetch('online_users.php')
            .then(res => res.json())
            .then(data => {
                // Atualiza tanto o card quanto o box original
                document.getElementById('online-count').innerText = data.count;
                const originalCounter = document.getElementById('online-count-original');
                if (originalCounter) {
                    originalCounter.innerText = data.count;
                }
            });
    }

    atualizarOnline();
    setInterval(atualizarOnline, 10000);
    </script>

    <script>
    var labelsDepositos = <?= $labels_depositos; ?>;
    var depositosData = <?= $dados_depositos; ?>;

    var labelsSaques = <?= $labels_saques; ?>;
    var saquesData = <?= $dados_saques; ?>;

    var optionsDepositos = {
        series: [{
            name: 'Depósitos',
            data: depositosData
        }],
        chart: {
            type: 'line',
            height: 350
        },
        xaxis: {
            categories: labelsDepositos
        },
        stroke: {
            curve: 'smooth'
        },
        colors: ['#28a745'],
        title: {
            text: 'Depósitos Diários',
            align: 'left'
        }
    };

    var chartDepositos = new ApexCharts(document.querySelector("#chart-depositos"), optionsDepositos);
    chartDepositos.render();

    var optionsSaques = {
        series: [{
            name: 'Saques',
            data: saquesData
        }],
        chart: {
            type: 'line',
            height: 350
        },
        xaxis: {
            categories: labelsSaques
        },
        stroke: {
            curve: 'smooth'
        },
        colors: ['#dc3545'],
        title: {
            text: 'Saques Diários',
            align: 'left'
        }
    };

    var chartSaques = new ApexCharts(document.querySelector("#chart-saques"), optionsSaques);
    chartSaques.render();
    </script>

    <script>
function atualizarUsuariosOnline() {
    fetch('usuarios_online.php')
        .then(response => response.text())
        .then(data => {
            const contador = document.getElementById('contador-online');
            if (contador) {
                contador.innerText = data;
            } else {
                console.warn('Elemento #contador-online não encontrado.');
            }
        })
        .catch(err => console.error('Erro ao buscar usuários online:', err));
}


    </script>

    <style>
        .empty-message {
            text-align: center;
            display: table-cell;
        }
        
        @media (max-width: 768px) {
            .empty-message {
                colspan: 4;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1200px) {
            .empty-message {
                colspan: 4;
            }
        }
    </style>
    
    <script>
        window.addEventListener('resize', function() {
            let emptyMessageCell = document.querySelector('.empty-message');
            if (emptyMessageCell) {
                if (window.innerWidth < 768) {
                    emptyMessageCell.setAttribute('colspan', '4');
                } else {
                    emptyMessageCell.setAttribute('colspan', '4');
                }
            }
        });
    </script>

</body>

</html>

<?php
function total_dep_pagos_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE status = 'pago' AND tipo = 'deposito'";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function total_saldos_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(saldo) as total_saldo FROM usuarios";
    $result = mysqli_query($mysqli, $qry);
    
    return mysqli_fetch_assoc($result)['total_saldo'] ?? 0;
}

function total_saldos_usuarios_afiliados()
{
    global $mysqli;
    $qry = "SELECT SUM(saldo_afiliados) as total_saldo FROM usuarios WHERE statusAff = '1'";
    $result = mysqli_query($mysqli, $qry);
    
    return mysqli_fetch_assoc($result)['total_saldo'] ?? 0;
}

function total_saques_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function media_saldo_usuarios()
{
    global $mysqli;
    $qry = "SELECT AVG(saldo) as media_saldo FROM usuarios";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['media_saldo'] ?? 0;
}
?>