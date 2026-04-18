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
include_once "validar_2fa.php";
$csrf = new CSRF_Protect();
#======================================#
#expulsa user
checa_login_adm();
#======================================#
//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

# Função para buscar os dados atuais da tabela afiliados_config
function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

# Função para atualizar os dados da tabela afiliados_config
function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        versao_app_android = ?, 
        versao_app_ios = ?, 
        mensagem_app = ?, 
        link_app_android = ?,
        link_app_ios = ?
        WHERE id = 1");

    $qry->bind_param(
        "sssss",
        $data['versao_app_android'],
        $data['versao_app_ios'],
        $data['mensagem_app'],
        $data['link_app_android'],
        $data['link_app_ios']
    );
    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados
$toastType = null; // Variável para definir o tipo de Toast
$toastMessage = ''; // Variável para definir a mensagem do Toast

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'versao_app_android' => $_POST['versao_app_android'],  // Texto, não usar floatval
        'versao_app_ios' => $_POST['versao_app_ios'],  // Texto, não usar floatval
        'mensagem_app' => $_POST['mensagem_app'],  // Texto, não usar floatval
        'link_app_android' => $_POST['link_app_android'],  // Texto, não usar floatval
        'link_app_ios' => $_POST['link_app_ios'],  // Texto, não usar floatval
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações de apps atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}


# Buscar os dados atuais
$config = get_afiliados_config();
?>

<head>
    <?php $title = "Configurações de App";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
</head>

<body>

    <!-- Top Bar Start -->
    <?php include 'partials/topbar.php' ?>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <?php include 'partials/startbar.php' ?>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Gerenciamento de popup de download</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row">
                                        <!-- Versão Do Aplicativo Android -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-user"></i> Versão Do Aplicativo Android</h5>
                                                    <input type="text" name="versao_app_android" class="form-control"
                                                        value="<?= $config['versao_app_android'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Versão Do Aplicativo IOS -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-group"></i> Versão Do Aplicativo IOS
                                                    </h5>
                                                    <input type="text" name="versao_app_ios" class="form-control"
                                                        value="<?= $config['versao_app_ios'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mensagem Popup Android -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-community"></i> Mensagem No Popup(Android/IOS)
                                                    </h5>
                                                    <input type="text" name="mensagem_app" class="form-control"
                                                        value="<?= $config['mensagem_app'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Link Do App Android-->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-percentage-circle"></i>
                                                        Link Do App Android</h5>
                                                    <input type="text" name="link_app_android" class="form-control"
                                                        value="<?= $config['link_app_android'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Link Do App Android -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-percentage-circle"></i>
                                                        Link Do App IOS</h5>
                                                    <input type="text" name="link_app_ios" class="form-control"
                                                        value="<?= $config['link_app_ios'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($modo_plataforma == 0) { ?>

                                        <div class="text-center" style="margin-bottom: 15px;">
                                            <button type="submit" class="btn btn-success" disabled>Salvar Configurações (MODO DEMO ATIVADO)</button>
                                        </div>

                                    <?php } else { ?>

                                        <div class="text-center" style="margin-bottom: 15px;">
                                            <button type="submit" class="btn btn-success">Salvar Configurações</button>
                                        </div>

                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
        </div><!-- page content -->
    </div><!-- page-wrapper -->

    <!-- Toast container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Javascript -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Função de Toast -->
    <script>
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            toast.className = `toast align-items-center bg-light border-0 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header">
                    <img src="assets/images/logo-sm.png" alt="" height="20" class="me-1">
                    <h5 class="me-auto my-0">Dashboard</h5>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            toastPlacement.appendChild(toast);

            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function() {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    <!-- Exibir o Toast baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

</body>

</html>