<?php include 'partials/html.php' ?>



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



$csrf = new CSRF_Protect();
#======================================#
#expulsa user
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
#======================================#


// Pegar os dados para os gráficos de PHP
$depositos_dias = depositos_por_dia();
$saques_dias = saques_por_dia();

// Formatar os dados para uso em JavaScript
$labels_depositos = json_encode(array_column($depositos_dias, 'dia'));
$dados_depositos = json_encode(array_column($depositos_dias, 'total'));

$labels_saques = json_encode(array_column($saques_dias, 'dia'));
$dados_saques = json_encode(array_column($saques_dias, 'total'));
/// final do script --#
?>

<head>
    <?php $title = "Dashboard";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">

    <?php include 'partials/head-css.php' ?>
    
    
    
    
    <style>




/* Fundo do painel */
.page-wrapper {
    background-color: #121212;
    color: #fff;
}

/* Cartões */
.card {
    background-color: #1e1e1e;
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    margin-bottom: 20px;
}

/* Cabeçalhos dos cartões */
.card-body .fw-semibold {
    color: #fff;
}

/* Títulos principais */
h5 {
    color: #4CAF50;
    font-weight: bold;
    font-size: 1.2rem;
}

/* Ícones */
.iconoir-user-plus,
.iconoir-group,
.iconoir-graph-up,
.iconoir-graph-down,
.iconoir-piggy-bank,
.iconoir-hand-cash,
.iconoir-server-connection {
    color: #4CAF50;
}

/* Cartões de informações principais */
.card-body h3 {
    color: #00E676;
    font-weight: bold;
}

/* Para texto de status */
.text-success {
    color: #00E676 !important;
}

.text-danger {
    color: #FF3D00 !important;
}

.text-warning {
    color: #FFEB3B !important;
}

.text-muted {
    color: #B0BEC5 !important;
}

/* Linhas de divisão */
.border-dashed-bottom {
    border-bottom: 2px dashed #4CAF50;
}

/* Card de ações com fundo mais escuro */
.card-header {
    background-color: #333;
    color: #fff;
}

/* Estilo para as tabelas */
.table {
    background-color: #212121;
    color: #B0BEC5;
}

.table th {
    background-color: #424242;
    color: #fff;
}

.table tbody tr {
    background-color: #1e1e1e;
}

.table tbody tr:hover {
    background-color: #333;
}

/* Botões com foco */
.btn {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 5px;
}

.btn:hover {
    background-color: #45a049;
}

/* Ajustes nos ícones circulares */
.thumb-xl {
    background-color: #333;
    border: 3px solid #4CAF50;
}

/* Estilo para gráficos */
.card-body #chart-saques, .card-body #chart-depositos {
    height: 300px;
    background-color: #333;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Seção de seções com visibilidade de categorias */
.d-flex.align-items-center .iconoir-user-plus, 
.d-flex.align-items-center .iconoir-database-monitor {
    color: #4CAF50;
}

</style>

    
</head>

<body>

    <!-- Top Bar Start -->
    <?php include 'partials/topbar.php' ?>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <?php include 'partials/startbar.php' ?>
    <!-- end leftbar-tab-menu-->


    <div class="modal fade" id="modal2FA" tabindex="-1" aria-labelledby="modal2FALabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal2FALabel">Autenticação de 2 Fatores</h5>
                </div>
                <div class="modal-body">
                    <p>Por favor, insira o código de autenticação 2FA para continuar.</p>
                    <input type="text" id="token2fa" class="form-control" placeholder="Token 2FA" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-submit-2fa">Validar Token</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('btn-submit-2fa').addEventListener('click', function() {
        var token2fa = document.getElementById('token2fa').value.trim();

        if (token2fa === '') {
            alert('Por favor, insira o token 2FA!');
            return;
        }

        fetch('validar_2fa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'token=' + encodeURIComponent(token2fa)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    alert('Token inválido. Tente novamente!');
                }
            });
    });
    </script>

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div style="padding-left: 20px;padding-top: 10px;padding-bottom: 20px;">
                        <h1>Hora Atual</h1>
                        <p>Hora atual: <span id="hora">Carregando...</span></p>
                    </div>
                    
<?php
$dataFile = __DIR__ . '/online_users.json';
$users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
$total_online = count($users);
?>

<div id="online-box" style="
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color: #00ff99;
    padding: 10px 16px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 0 10px rgba(0, 255, 140, 0.5);
    border: 1px solid #00ff99;
    max-width: 240px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
">
    <span style="font-size: 18px;">🌍</span>
    <span style="color: #ffffff;">Usuários online agora: 
        <strong id="online-count" style="color: #00ff99;"><?php echo $total_online; ?></strong>
    </span>
</div>

<script>
function atualizarOnline() {
    fetch('online_users.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('online-count').innerText = data.count;
        });
}

// Atualiza imediatamente ao carregar a página
atualizarOnline();

// Continua atualizando a cada 10 segundos
setInterval(atualizarOnline, 10000);
</script>






                    <!-- Seção de Cadastros -->
                    <div class="row">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                            Cadastros
                        </h5>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Total cadastros</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios(); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-group h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Total de
                                            cadastros da operação.</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Cadastros hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios_diarios(); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Novos
                                            cadastros realizados hoje.</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Cadastros 90D</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= qtd_usuarios_ultimos_90_dias(); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Novos
                                            cadastros nos últimos 90 dias.</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção Financeiro -->
                    <div class="row mt-4">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                            Financeiro
                        </h5>

                        <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                    <div class="col-9">
                                        <p class="text-dark mb-0 fw-semibold fs-14">Total de Jogadas</p>
                                        <h3 class="mt-2 mb-0 fw-bold"><?= total_jogadas(); ?></h3>
                                    </div>
                                    <!--end col-->
                                    <div class="col-3 align-self-center">
                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                            <i class="iconoir-piggy-bank h1 align-self-center mb-0 text-secondary"></i>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                                <?php
                                    $jogo_popular = jogo_mais_jogado();
                                    $jogo_color_class = ($jogo_popular === 'Nenhum jogo encontrado') ? 'text-danger' : 'text-success';
                                ?>
                                <p class="mb-0 text-truncate text-muted mt-3">
                                    Jogo popular: <span class="<?= $jogo_color_class ?>"><?= $jogo_popular; ?></span>
                                </p>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Lucro cassino</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saldo_cassino()); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i
                                                    class="iconoir-check-circle h1 align-self-center mb-0 text-danger"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Lucro da
                                            operação (Dep - Saques).</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Depósitos pagos hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= Reais2(depositos_diarios_pagos()); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <!-- Ícone de sucesso (check-circle) -->
                                                <i
                                                    class="iconoir-check-circle h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Total de
                                            depósitos pagos hoje</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Depósitos processando hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(depositos_diarios()); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-hourglass h1 align-self-center mb-0 text-warning"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Total de
                                            depósitos processando hoje</span></p>
                                </div>
                            </div>
                        </div>

                        <!--end col-->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Saques total</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_total()); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i
                                                    class="iconoir-check-circle h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Total de
                                            saques da operação.</span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Saques pagos hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_diarios_pagos()); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i
                                                    class="iconoir-check-circle h1 align-self-center mb-0 text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Total de
                                            saques pagos hoje.</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Saques pendentes hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(saques_diarios_pagos()); ?></h3>
                                        </div>
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-hourglass h1 align-self-center mb-0 text-warning"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-warning">Valor de
                                            saques pendentes hoje.</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                     <div class="row">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                            Estatística dos Usuários
                        </h5>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Total de Saldos dos Usuários</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(total_saldos_usuarios()); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">Saldo de todos os Usuários> </span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        
                          <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Total Sacado</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(total_saques_usuarios()); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">Saques pendentes (hoje)> </span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        
                          <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Saldo Médio</p>
                                            <h3 class="mt-2 mb-0 fw-bold">R$<?= Reais2(media_saldo_usuarios()); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">Saldo Médio> </span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                    
                    <div class="row">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                              visitas
                        </h5>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Acessos total</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= visitas_count('total'); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">Acessos
                                            total:
                                            <?= visitas_count('total'); ?> </span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Acessos Hoje</p>
                                            <h3 class="mt-2 mb-0 fw-bold"><?= visitas_count('diario'); ?></h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3"><span class="text-danger">Acessos
                                            hoje:
                                            <?= visitas_count('diario'); ?> </span></p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center border-dashed-bottom pb-3">
                                        <div class="col-9">
                                            <p class="text-dark mb-0 fw-semibold fs-14">Lugar Mais Acessado</p>
                                            <?php
                                            // Chamada da função
                                            $lugar_mais_acessado = visitas_count2('total');
                                            ?>
                                            <h3 class="mt-2 mb-0 fw-bold">
                                                <?php
                                                // Verifica se há dados
                                                if ($lugar_mais_acessado['cidade'] && $lugar_mais_acessado['estado']) {
                                                    echo $lugar_mais_acessado['cidade'] . ', ' . $lugar_mais_acessado['estado'];
                                                } else {
                                                    echo "Não há dados";
                                                }
                                                ?>
                                            </h3>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                <i class="iconoir-user h1 align-self-center mb-0 text-secondary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                    <p class="mb-0 text-truncate text-muted mt-3">
                                        <span class="text-danger">Acessos do lugar mais acessado:
                                            <?= $lugar_mais_acessado['total']; ?></span>
                                    </p>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                    </div>
                    <!--end col-->
                    <div class="row mt-4">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                            Gráficos
                        </h5>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Gráfico de Depósitos por Dia</h5>
                                    <div id="chart-depositos"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Gráfico de Saques por Dia</h5>
                                    <div id="chart-saques"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end row-->
                    <div class="row">
                        <h5 class="col-12 mb-3 d-flex align-items-center">
                            <div class="tag"></div>
                            Histórico financeiro
                        </h5>
                        <div class="col-lg-6">
                            <div class="card card-h-100">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h4 class="card-title">Saques Aprovados</h4>
                                            <p class="fs-11 fst-bold text-muted">Últimos 5 Saques Aprovados.<a href="#!"
                                                    class="link-danger ms-1"><i
                                                        class="align-middle iconoir-refresh"></i></a></p>
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
                                                session_start();
                                                include_once '../services/database.php';
                                                include_once '../services/funcao.php';
                                                include_once '../services/crud.php';
                                                include_once '../services/crud-adm.php';
                                                include_once '../services/checa_login_adm.php';
                                                checa_login_adm();

                                                global $mysqli;
                                                $pagina = 1; // Página atual
                                                $qnt_result_pg = 5; // Quantidade de resultados por página
                                                $inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

                                                // Consultar saques aprovados no banco de dados
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
                            <div class="card card-h-100">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h4 class="card-title">Depósitos Pagos</h4>
                                            <p class="fs-11 fst-bold text-muted">Últimos 5 Depósitos Pagos.<a href="#!"
                                                    class="link-danger ms-1"><i
                                                        class="align-middle iconoir-refresh"></i></a></p>
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
                                                // Consultar total de visitas por canal no banco de dados
                                                $result_visitas = "SELECT * FROM transacoes ORDER BY id DESC LIMIT $inicio, $qnt_result_pg";
                                                $resultado_visitas = mysqli_query($mysqli, $result_visitas);

                                                if ($resultado_visitas && mysqli_num_rows($resultado_visitas) > 0) {
                                                    while ($visit = mysqli_fetch_assoc($resultado_visitas)) {
                                                        $change_icon = ($visit['valor'] >= 20) ? 'fa-caret-up text-success' : 'fa-caret-down text-warning';
                                                        ?>
                                                        <tr>
                                                            <td><?= $visit['id']; ?></td>
                                                            <td><?= $visit['usuario']; ?></td>
                                                            <td><?= $visit['data_hora']; ?></td>
                                                            <td><?= $visit['valor']; ?> <i
                                                                    class="fas <?= $change_icon; ?> font-16"></i>
                                                            </td>
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
                    </div>
                </div>

                <!--end row-->
            </div><!-- container -->

            <!--Start Rightbar-->
            <?php include 'partials/endbar.php' ?>
            <!--end Rightbar-->
            <!--Start Footer-->
            <?php include 'partials/footer.php' ?>
            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->
    <?php include 'partials/vendorjs.php' ?>

    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/data/stock-prices.js"></script>
    <script src="assets/libs/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/libs/jsvectormap/maps/world.js"></script>
    <script src="assets/js/pages/index.init.js"></script>
    <script src="assets/js/app.js"></script>


    <script>
        // Usar as variáveis PHP para passar os dados diretamente para o JavaScript
        var labelsDepositos = <?= $labels_depositos; ?>;
        var depositosData = <?= $dados_depositos; ?>;

        var labelsSaques = <?= $labels_saques; ?>;
        var saquesData = <?= $dados_saques; ?>;

        // Gráfico de Depósitos
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
            title: {
                text: 'Depósitos Diários',
                align: 'left'
            }
        };

        var chartDepositos = new ApexCharts(document.querySelector("#chart-depositos"), optionsDepositos);
        chartDepositos.render();

        // Gráfico de Saques
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
            title: {
                text: 'Saques Diários',
                align: 'left'
            }
        };

        var chartSaques = new ApexCharts(document.querySelector("#chart-saques"), optionsSaques);
        chartSaques.render();
    </script>

    <script type="text/javascript">
        // Função para fazer a requisição AJAX e atualizar a hora
        function atualizarHora() {
            var xhr = new XMLHttpRequest(); // Cria um objeto XMLHttpRequest
            xhr.open('GET', 'hora.php', true); // Configura o método GET para o arquivo hora.php

            // Define o que acontece quando a resposta chegar
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Atualiza o conteúdo da página com a hora recebida do PHP
                    document.getElementById('hora').innerText = xhr.responseText;
                } else {
                    console.error('Erro ao carregar a hora.');
                }
            };

            // Envia a requisição para o servidor
            xhr.send();
        }

        // Chama a função para atualizar a hora a cada 15 segundos (15000 ms)
        setInterval(atualizarHora, 1000);

        // Atualiza a hora imediatamente quando a página for carregada
        window.onload = function () {
            atualizarHora(); // Atualiza a hora assim que a página for carregada
        };
    </script>

<script>
function atualizarUsuariosOnline() {
    fetch('usuarios_online.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('contador-online').innerText = data;
        })
        .catch(err => console.error('Erro ao buscar usuários online:', err));
}

// Atualiza a cada 2 segundos
setInterval(atualizarUsuariosOnline, 2000);

// Carrega imediatamente ao abrir
atualizarUsuariosOnline();
</script>


</body>
<!--end body-->

</html>

<?php
// Funções de totalização e média para o resumo financeiro
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