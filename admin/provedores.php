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

# Função para buscar todos os provedores da tabela provedores com paginação
function get_providers($limit, $offset)
{
    global $mysqli;
    $qry = "SELECT * FROM provedores LIMIT $limit OFFSET $offset";
    $result = mysqli_query($mysqli, $qry);
    $providers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $providers[] = $row;
    }
    return $providers;
}

# Função para contar o total de provedores
function count_providers()
{
    global $mysqli;
    $qry = "SELECT COUNT(*) as total FROM provedores";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total'];
}

# Função para atualizar os dados do provedor
function update_provider($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE provedores SET 
        code = ?, 
        name = ?, 
        type = ?, 
        status = ? 
        WHERE id = ?");

    $qry->bind_param(
        "sssii",
        $data['code'],
        $data['name'],
        $data['type'],
        $data['status'],
        $data['id']
    );
    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados do provedor
$toastType = null; 
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id' => intval($_POST['id']),
        'code' => $_POST['code'],
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'status' => intval($_POST['status']),
    ];

    if (update_provider($data)) {
        $toastType = 'success';
        $toastMessage = 'Provedor atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o provedor. Tente novamente.';
    }
}

# Configurações de paginação
$limit = 10;  // Número de provedores por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$total_providers = count_providers();
$total_pages = ceil($total_providers / $limit);

# Buscar todos os provedores com paginação
$providers = get_providers($limit, $offset);
?>

<head>
    <?php $title = "Gerenciamento de Provedores";
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
                                <h4 class="card-title">Gerenciamento de Provedores</h4>
                            </div>

                            <div class="card-body">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>Tipo</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($providers as $provider): ?>
                                            <tr>
                                                <td><?= $provider['id'] ?></td>
                                                <td><?= $provider['code'] ?></td>
                                                <td><?= $provider['name'] ?></td>
                                                <td><?= $provider['type'] ?></td>
                                                <td><?= $provider['status'] == 1 ? 'Ativo' : 'Inativo' ?></td>
                                                <td>
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProviderModal<?= $provider['id'] ?>">Editar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal de Edição -->
                                            <div class="modal fade" id="editProviderModal<?= $provider['id'] ?>" tabindex="-1" aria-labelledby="editProviderModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editProviderModalLabel">Editar Provedor</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST" action="">
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="code" class="form-label">Código</label>
                                                                    <input type="text" name="code" class="form-control" value="<?= $provider['code'] ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Nome</label>
                                                                    <input type="text" name="name" class="form-control" value="<?= $provider['name'] ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="type" class="form-label">Tipo</label>
                                                                    <input type="text" name="type" class="form-control" value="<?= $provider['type'] ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select name="status" class="form-select" required>
                                                                        <option value="1" <?= $provider['status'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                                                        <option value="0" <?= $provider['status'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="id" value="<?= $provider['id'] ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Paginação -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>

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

            setTimeout(function () {
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
