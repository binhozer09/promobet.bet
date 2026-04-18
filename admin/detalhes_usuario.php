<?php include 'partials/html.php' ?>

<head>
    <?php $title = "Detalhes do Usuário"; ?>
    <?php include 'partials/title-meta.php' ?>
    <?php include 'partials/head-css.php' ?>
</head>

<body>
    <!-- Top Bar Start -->
    <?php include 'partials/topbar.php' ?>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <?php include 'partials/startbar.php' ?>
    <!-- end leftbar-tab-menu-->
    <!-- decodificar slug usuario -->
    <?php
    // Definir o badge com base no status do usuário
    function getStatusBadge($status)
    {
        switch ($status) {
            case 'pago':
            case '1':
                return "<span class='badge bg-success'>Pago</span>";
            case 'processamento':
                return "<span class='badge bg-warning'>Pendente</span>";
            case 'expirado':
                return "<span class='badge bg-danger'>Expirado</span>";
            default:
                return "<span class='badge bg-secondary'>Indefinido</span>";
        }
    }
    
    if (isset($_REQUEST['slug'])) {
        $id_user = decodeAll($_REQUEST['slug']);
        $qry = "SELECT * FROM usuarios WHERE id='" . intval($id_user) . "'";
        $res = mysqli_query($mysqli, $qry);
        $data = mysqli_fetch_assoc($res);
        $saldo_user = saldo_user($data['id']);
    }

    // Atualizar os dados do usuário - VERSÃO COM SALDO DE AFILIADOS E PROBABILIDADE DE INDICAÇÃO
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
        $real_name = mysqli_real_escape_string($mysqli, $_POST['real_name']);
        $token = mysqli_real_escape_string($mysqli, $_POST['token']);
        $password = $_POST['password'];
        $statusaff = intval($_POST['statusaff']);
        $tipo_pagamento = intval($_POST['tipo_pagamento']);
        $invite = mysqli_real_escape_string($mysqli, $_POST['invite']);
        $senha_saque = mysqli_real_escape_string($mysqli, $_POST['senhaparasacar']);
        
        // CAMPOS EXISTENTES
        $tipo_conta = mysqli_real_escape_string($mysqli, $_POST['tipo_conta']);
        $comissao_deposito = floatval($_POST['comissao_deposito']);
        
        // NOVO CAMPO: Saldo de afiliados
        $saldo_afiliados = floatval($_POST['saldo_afiliados']);
        
        // NOVO CAMPO: Probabilidade de Indicação (ChanceCpa Individual)
        $chanceCpa_individual = !empty($_POST['chanceCpa_individual']) ? intval($_POST['chanceCpa_individual']) : null;
        
        // Validações
        $errors = [];
        
        // Validar tipo de conta
        if (!in_array($tipo_conta, ['usuario', 'afiliado'])) {
            $errors[] = "Tipo de conta inválido";
        }
        
        // Validar comissão (apenas para afiliados)
        if ($tipo_conta === 'afiliado') {
            if ($comissao_deposito < 0 || $comissao_deposito > 100) {
                $errors[] = "Comissão deve estar entre 0% e 100%";
            }
        } else {
            // Se não é afiliado, zerar a comissão
            $comissao_deposito = 0.00;
        }
        
        // Validar saldo de afiliados
        if ($saldo_afiliados < 0) {
            $errors[] = "Saldo de comissões não pode ser negativo";
        }
        
        // Validar probabilidade de indicação
        if ($chanceCpa_individual !== null && $chanceCpa_individual < 1) {
            $errors[] = "Probabilidade de Indicação deve ser pelo menos 1 (ou deixe vazio para usar configuração global)";
        }
        
        // Validar código de convite único
        $check_invite = "SELECT id FROM usuarios WHERE invite_code='$invite' AND id != " . intval($id_user);
        $check_result = mysqli_query($mysqli, $check_invite);
        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = "Código de convite já está em uso por outro usuário";
        }
        
        // Processar senha
        $pass = $password;
        if (strlen($password) < 20) {
            $pass = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
        }
        
        // Se não há erros, fazer a atualização
        if (empty($errors)) {
            // Query de atualização com os novos campos
            $update_query = "UPDATE usuarios SET 
                mobile='$real_name', 
                token='$token', 
                password='$pass', 
                statusaff='$statusaff', 
                invite_code='$invite', 
                senhaparasacar='$senha_saque', 
                tipo_pagamento='$tipo_pagamento',
                tipo_conta='$tipo_conta',
                comissao_deposito='$comissao_deposito',
                saldo_afiliados='$saldo_afiliados',
                chanceCpa_individual=" . ($chanceCpa_individual ? "'$chanceCpa_individual'" : "NULL") . "
                WHERE id='" . intval($id_user) . "'";
            
            $update_res = mysqli_query($mysqli, $update_query);

            if ($update_res) {
                // Log da atualização com saldo de afiliados
                error_log("[USER_UPDATE] Usuário ID $id_user atualizado - Tipo: $tipo_conta, Comissão: $comissao_deposito%, Saldo Afiliados: R$ $saldo_afiliados");
                
                // Verificar se houve mudança para afiliado
                if ($tipo_conta === 'afiliado' && $data['tipo_conta'] !== 'afiliado') {
                    error_log("[USER_UPDATE] Usuário ID $id_user promovido para AFILIADO com $comissao_deposito% de comissão");
                }
                
                // Log específico para mudanças no saldo de afiliados
                $saldo_anterior = $data['saldo_afiliados'] ?? 0;
                if ($saldo_afiliados != $saldo_anterior) {
                    $diferenca = $saldo_afiliados - $saldo_anterior;
                    $acao = $diferenca > 0 ? "ADICIONADO" : "REMOVIDO";
                    error_log("[SALDO_AFILIADOS] Usuário ID $id_user - $acao R$ " . abs($diferenca) . " (Anterior: R$ $saldo_anterior, Novo: R$ $saldo_afiliados)");
                }
                
                // Log específico para mudanças na probabilidade de indicação
                $chanceCpa_anterior = $data['chanceCpa_individual'] ?? null;
                if ($chanceCpa_individual != $chanceCpa_anterior) {
                    $config_anterior = $chanceCpa_anterior ? "Individual: $chanceCpa_anterior" : "Global";
                    $config_nova = $chanceCpa_individual ? "Individual: $chanceCpa_individual" : "Global";
                    error_log("[PROBABILIDADE_INDICACAO] Usuário ID $id_user - Alterado de ($config_anterior) para ($config_nova)");
                }
                
                // Recarregar os dados do usuário após atualização
                $qry = "SELECT * FROM usuarios WHERE id='" . intval($id_user) . "'";
                $res = mysqli_query($mysqli, $qry);
                $data = mysqli_fetch_assoc($res);
                $saldo_user = saldo_user($data['id']);
                
                // Atualizar o status do afiliado
                if ($data['statusaff'] == 2 || $data['statusaff'] == 1) {
                    if (($data['tipo_conta'] ?? '') === 'afiliado') {
                        $view_status = '<span class="label label-success">Afiliado Ativo</span>';
                    } else {
                        $view_status = '<span class="label label-warning">Status Afiliado (Legacy)</span>';
                    }
                } else {
                    $view_status = '<span class="label label-info">Usuário</span>';
                }
                
                $probabilidade_msg = $chanceCpa_individual ? "Probabilidade: $chanceCpa_individual" : "Probabilidade: Global";
                
                echo "<script>window.addEventListener('DOMContentLoaded', function() { 
                    showToast('success', 'Dados do usuário atualizados com sucesso!<br>Tipo: $tipo_conta | Comissão: $comissao_deposito% | Saldo Afiliados: R$ " . number_format($saldo_afiliados, 2, ',', '.') . "<br>$probabilidade_msg'); 
                });</script>";
                
                // Alternativa: Redirect imediato após 2 segundos (caso o JavaScript falhe)
                echo "<script>
                    setTimeout(function() {
                        window.location.href = window.location.href;
                    }, 2000);
                </script>";
            } else {
                error_log("[USER_UPDATE] Erro ao atualizar usuário ID $id_user: " . mysqli_error($mysqli));
                echo "<script>window.addEventListener('DOMContentLoaded', function() { 
                    showToast('danger', 'Erro ao atualizar os dados do usuário: " . mysqli_error($mysqli) . "'); 
                });</script>";
            }
        } else {
            // Mostrar erros de validação
            $error_message = implode('<br>', $errors);
            echo "<script>window.addEventListener('DOMContentLoaded', function() { 
                showToast('danger', 'Erros de validação:<br>$error_message'); 
            });</script>";
        }
    }

    // Função para exibir o status do afiliado de forma mais clara
    if ($data['statusaff'] == 2 || $data['statusaff'] == 1) {
        if (($data['tipo_conta'] ?? '') === 'afiliado') {
            $view_status = '<span class="label label-success">Afiliado Ativo</span>';
        } else {
            $view_status = '<span class="label label-warning">Status Afiliado (Legacy)</span>';
        }
    } else {
        $view_status = '<span class="label label-info">Usuário</span>';
    }
    ?>

    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content">
            <div class="container-xxl">

                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                        <div class="d-flex align-items-center flex-row flex-wrap">
                                            <div class="">
                                            <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0 text-center">
                                                <img src="assets/images/users/avatar.webp" alt="" height="120" class="rounded-circle">
                                                <h5 class="fw-semibold fs-22 mb-1"><?= $data['real_name']; ?></h5>
                                                <h5 class="fw-semibold fs-20 mb-1"><span class="badge bg-success"><?= $view_status; ?></span></h5>
                                                <!-- Novo badge para tipo de conta -->
                                                <?php if (($data['tipo_conta'] ?? '') === 'afiliado'): ?>
                                                <div class="mt-2">
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-percentage me-1"></i>
                                                        <?= number_format($data['comissao_deposito'] ?? 0, 1); ?>% Comissão
                                                    </span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <style>
                                        @media screen and (max-width: 600px) {
                                            .fivegaming {
                                                flex-wrap: wrap;
                                            }
                                        }
                                    </style>

                                    <div class="col-lg-5 ms-auto align-self-center">
                                        <div class="d-flex fivegaming justify-content-center">
                                            <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                                <h5 class="fw-semibold fs-22 mb-1">
                                                    R$<?= Reais2(total_dep_pagos_id($data['id'])); ?>
                                                </h5>
                                                <p class="text-muted mb-0 fw-medium">Dep. total</p>
                                            </div>
                                            <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                                <h5 class="fw-semibold fs-22 mb-1">
                                                    R$<?= Reais2(total_saques_id($data['id'])); ?>
                                                </h5>
                                                <p class="text-muted mb-0 fw-medium">Saques total</p>
                                            </div>
                                            <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                                <h5 class="fw-semibold fs-22 mb-1">
                                                    R$<?= Reais2($saldo_user['saldo']); ?>
                                                </h5>
                                                <p class="text-muted mb-0 fw-medium">Saldo Atual</p>
                                            </div>
                                            <div class="border-dashed rounded border-theme-color p-2 me-2 flex-grow-1 flex-basis-0">
                                                <h5 class="fw-semibold fs-22 mb-1">
                                                    <?= afiliado_de_quem($data['invitation_code']); ?>
                                                </h5>
                                                <p class="text-muted mb-0 fw-medium">Afiliação</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->

                                    <div class="col-lg-3 col-md-6 col-sm-12 align-self-center mt-3 mt-lg-0">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end justify-content-sm-center">
                                            <?php if ($data['banido'] == 1): ?>
                                            <!-- Botão de Desbanir -->
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#desbanirUsuarioModal">
                                                Desbanir Usuário
                                            </button>
                                            <?php else: ?>
                                            <!-- Botão de Banir -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#banirUsuarioModal">
                                                Banir Usuário
                                            </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editarSaldoModal">Editar Saldo</button>
                                        </div>
                                    </div>

                                    <!-- Modal de Confirmação -->
                                    <div class="modal fade" id="banirUsuarioModal" tabindex="-1"
                                        aria-labelledby="banirUsuarioLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="banirUsuarioLabel">Confirmar Banimento</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Você tem certeza que deseja banir este usuário?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-danger"
                                                        id="confirmarBanimento">Sim, tenho certeza</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Confirmação de Desbanir -->
                                    <div class="modal fade" id="desbanirUsuarioModal" tabindex="-1"
                                        aria-labelledby="desbanirUsuarioLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="desbanirUsuarioLabel">Confirmar Desbanimento</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Você tem certeza que deseja desbanir este usuário?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-success"
                                                        id="confirmarDesbanimento">Sim, tenho certeza</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Editar Saldo -->
                                    <div class="modal fade" id="editarSaldoModal" tabindex="-1"
                                        aria-labelledby="editarSaldoLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarSaldoLabel">Editar Saldo Principal</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="saldoAtual" class="form-label">Saldo Principal Atual</label>
                                                        <input type="text" class="form-control" id="saldoAtual"
                                                            value="R$<?= Reais2($saldo_user['saldo']); ?>" disabled>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="adicionarSaldo" class="form-label">Adicionar Saldo</label>
                                                        <input type="number" class="form-control" id="adicionarSaldo"
                                                            placeholder="Insira o valor para adicionar" step="0.01">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="removerSaldo" class="form-label">Remover Saldo</label>
                                                        <input type="number" class="form-control" id="removerSaldo"
                                                            placeholder="Insira o valor para remover" step="0.01">
                                                    </div>

                                                    <div class="mt-3">
                                                        <h6>Saldo Final Estimado: <span
                                                                id="saldoFinal">R$<?= Reais2($saldo_user['saldo']); ?></span>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="confirmarEdicaoSaldo">Salvar Alterações</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#afiliado" role="tab"
                                    aria-selected="true">Informações de afiliado</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium" data-bs-toggle="tab" href="#usersafiliados" role="tab"
                                    aria-selected="false">Indicados</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium" data-bs-toggle="tab" href="#depositos" role="tab"
                                    aria-selected="false">Registros de depósitos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium" data-bs-toggle="tab" href="#saques" role="tab"
                                    aria-selected="false">Registros de retiradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium" data-bs-toggle="tab" href="#editar" role="tab"
                                    aria-selected="false">Editar usuario</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Aba de edição do usuário - VERSÃO COM PROBABILIDADE DE INDICAÇÃO -->
                            <div class="tab-pane p-3" id="editar" role="tabpanel">
                                <form method="POST" action="">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="real_name" class="form-label">Nome de usuário</label>
                                            <input type="text" class="form-control" name="real_name" id="real_name"
                                                value="<?= $data['mobile']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="token" class="form-label">Token</label>
                                            <input type="text" class="form-control" name="token" id="token"
                                                value="<?= $data['token']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Senha</label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                value="<?= $data['password']; ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="invite" class="form-label">Código De Convite</label>
                                            <input type="text" class="form-control" name="invite" id="invite"
                                                value="<?= $data['invite_code']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="senhaparasacar" class="form-label">Senha De Saque</label>
                                            <input type="text" class="form-control" name="senhaparasacar"
                                                id="senhaparasacar" placeholder="Senha Para Sacar"
                                                value="<?= $data['senhaparasacar']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="statusaff" class="form-label">Status de Afiliado</label>
                                            <select class="form-select" name="statusaff" id="statusaff">
                                                <option value="1" <?= $data['statusaff'] == 1 ? 'selected' : ''; ?>>
                                                    Ativo
                                                </option>
                                                <option value="0" <?= $data['statusaff'] == 0 ? 'selected' : ''; ?>>
                                                    Inativo
                                                </option>
                                                <option value="2" <?= $data['statusaff'] == 2 ? 'selected' : ''; ?>>
                                                    Bloqueado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- LINHA: Tipo de Conta e Comissão -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="tipo_conta" class="form-label">Tipo de Conta</label>
                                            <select class="form-select" name="tipo_conta" id="tipo_conta" onchange="toggleComissaoField()">
                                                <option value="usuario" <?= ($data['tipo_conta'] ?? 'usuario') == 'usuario' ? 'selected' : ''; ?>>
                                                    Usuário
                                                </option>
                                                <option value="afiliado" <?= ($data['tipo_conta'] ?? '') == 'afiliado' ? 'selected' : ''; ?>>
                                                    Afiliado
                                                </option>
                                            </select>
                                            <small class="form-text text-muted">Define se o usuário é um afiliado ou usuário comum</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="comissao_deposito" class="form-label">Comissão de Depósito (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="comissao_deposito" id="comissao_deposito"
                                                    value="<?= $data['comissao_deposito'] ?? '0.00'; ?>" 
                                                    min="0" max="100" step="0.01" placeholder="0.00">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <small class="form-text text-muted">Porcentagem de comissão sobre depósitos dos indicados (0 a 100%)</small>
                                        </div>
                                    </div>

                                    <!-- NOVA LINHA: Saldos (Principal e Afiliados) -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="saldo_principal" class="form-label">Saldo Principal (Apenas Visualização)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" id="saldo_principal_display"
                                                    value="<?= number_format($saldo_user['saldo'], 2, ',', '.'); ?>" 
                                                    disabled readonly>
                                            </div>
                                            <small class="form-text text-muted">Saldo principal da conta (edite via botão "Editar Saldo")</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="saldo_afiliados_edit" class="form-label">Saldo de Comissões (Editável)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" name="saldo_afiliados" id="saldo_afiliados_edit"
                                                    value="<?= $data['saldo_afiliados'] ?? '0.00'; ?>" 
                                                    min="0" step="0.01" placeholder="0.00">
                                            </div>
                                            <small class="form-text text-muted">Saldo de comissões disponível para saque</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="tipo_pagamento" class="form-label">Tipo De Pagamento Do Afiliado</label>
                                            <select class="form-select" name="tipo_pagamento" id="tipo_pagamento">
                                                <option value="1" <?= $data['tipo_pagamento'] == 1 ? 'selected' : ''; ?>>
                                                    CPA (Cost Per Acquisition)
                                                </option>
                                                <option value="0" <?= $data['tipo_pagamento'] == 0 ? 'selected' : ''; ?>>
                                                    CPA + Revenue Share
                                                </option>
                                                <option value="2" <?= $data['tipo_pagamento'] == 2 ? 'selected' : ''; ?>>
                                                    Revenue Share
                                                </option>
                                            </select>
                                            <small class="form-text text-muted">Modelo de pagamento para afiliados</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="chanceCpa_individual" class="form-label">Probabilidade de Indicação</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="chanceCpa_individual" id="chanceCpa_individual"
                                                    value="<?= $data['chanceCpa_individual'] ?? ''; ?>" 
                                                    min="1" step="1" placeholder="Deixe vazio para usar configuração global">
                                                <span class="input-group-text">
                                                    <i class="fas fa-dice" title="Probabilidade de Indicação"></i>
                                                </span>
                                            </div>
                                            <small class="form-text text-muted">
                                                <strong>1</strong> = sempre credita | <strong>2+</strong> = credita a cada X convites | 
                                                <strong>Vazio</strong> = usa configuração global
                                            </small>
                                        </div>
                                    </div>

                                    <!-- NOVA LINHA: Status da Probabilidade de Indicação -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Status da Probabilidade de Indicação</label>
                                            <div class="p-2 border rounded bg-light">
                                                <?php 
                                                $chanceCpa_status = '';
                                                $chanceCpa_badge = '';
                                                if (!empty($data['chanceCpa_individual'])) {
                                                    $chanceCpa_status = "Individual: " . $data['chanceCpa_individual'];
                                                    $chanceCpa_badge = "success";
                                                } else {
                                                    // Buscar configuração global
                                                    $global_chanceCpa = 1; // Valor padrão
                                                    $query_global = "SELECT chanceCpa FROM afiliados_config LIMIT 1";
                                                    $result_global = mysqli_query($mysqli, $query_global);
                                                    if ($result_global && mysqli_num_rows($result_global) > 0) {
                                                        $global_data = mysqli_fetch_assoc($result_global);
                                                        $global_chanceCpa = $global_data['chanceCpa'];
                                                    }
                                                    $chanceCpa_status = "Global: " . $global_chanceCpa;
                                                    $chanceCpa_badge = "info";
                                                }
                                                ?>
                                                <span class="badge bg-<?= $chanceCpa_badge ?>" id="probabilidade_status_badge">
                                                    <i class="fas fa-cog me-1"></i><?= $chanceCpa_status ?>
                                                </span>
                                                <small class="d-block mt-1 text-muted">Configuração atual em uso</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Campo vazio para manter layout -->
                                        </div>
                                    </div>

                                    <!-- Seção de informações sobre os saldos -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alert alert-light border">
                                                <h6 class="alert-heading">💰 Informações sobre os Saldos:</h6>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Saldo Principal:</strong> Usado para jogos e apostas</p>
                                                        <p class="mb-1"><strong>Edição:</strong> Via botão "Editar Saldo" (adicionar/remover)</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Saldo de Comissões:</strong> Ganhos de afiliação</p>
                                                        <p class="mb-0"><strong>Edição:</strong> Valor direto no formulário</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BLOCO INFORMATIVO: Probabilidade de Indicação -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alert alert-info border-info">
                                                <h6 class="alert-heading">🎲 Como funciona a Probabilidade de Indicação:</h6>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Probabilidade = 1:</strong> Sempre credita comissão</p>
                                                        <p class="mb-1"><strong>Probabilidade = 2:</strong> Credita a cada 2 convites</p>
                                                        <p class="mb-0"><strong>Probabilidade = 3:</strong> Credita a cada 3 convites</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Individual:</strong> Só este usuário</p>
                                                        <p class="mb-1"><strong>Global:</strong> Todos os usuários</p>
                                                        <p class="mb-0"><strong>Prioridade:</strong> Individual > Global</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Exemplo com Probabilidade = 3:</strong></p>
                                                        <p class="mb-1">• 1º convite: ❌ sem comissão</p>
                                                        <p class="mb-1">• 2º convite: ❌ sem comissão</p>
                                                        <p class="mb-0">• 3º convite: ✅ com comissão</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informações adicionais para afiliados -->
                                    <div class="row mb-3" id="affiliate_info_section" style="display: <?= ($data['tipo_conta'] ?? '') == 'afiliado' ? 'flex' : 'none'; ?>;">
                                        <div class="col-12">
                                            <div class="alert alert-success">
                                                <h6 class="alert-heading">🎯 Informações do Sistema de Afiliados:</h6>
                                                <hr>
                                                <p class="mb-1"><strong>Tipo de Conta:</strong> Define se o usuário pode ganhar comissões</p>
                                                <p class="mb-1"><strong>Comissão de Depósito:</strong> Porcentagem que o afiliado ganha sobre cada depósito dos indicados</p>
                                                <p class="mb-1"><strong>Exemplo:</strong> Com 10% de comissão, um depósito de R$ 100 gera R$ 10 de comissão</p>
                                                <p class="mb-1"><strong>Saldo de Afiliados:</strong> Total acumulado de comissões disponível para saque</p>
                                                <p class="mb-0"><strong>Probabilidade de Indicação:</strong> Controla quantos convites são necessários para gerar comissão</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" name="update_user" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i> Atualizar Usuário
                                            </button>
                                            <button type="button" class="btn btn-secondary ms-2" onclick="resetForm()">
                                                <i class="fas fa-undo me-1"></i> Resetar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- end tab-content -->

                        <div class="tab-content">
                            <!-- Aba de informações de afiliado - VERSÃO COM PROBABILIDADE DE INDICAÇÃO -->
                            <div class="tab-pane active" id="afiliado" role="tabpanel">
                                <div class="row">
                                    <!-- Cartão: Tipo de Conta -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Tipo de Conta</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <?php 
                                                            $tipo_conta_display = ($data['tipo_conta'] ?? 'usuario') === 'afiliado' ? 'Afiliado' : 'Usuário';
                                                            $badge_class = ($data['tipo_conta'] ?? 'usuario') === 'afiliado' ? 'success' : 'info';
                                                            echo "<span class='badge bg-$badge_class'>$tipo_conta_display</span>";
                                                            ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-user-crown fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Comissão Configurada -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Comissão Configurada</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <?= number_format($data['comissao_deposito'] ?? 0, 2, ',', '.'); ?>%
                                                        </h3>
                                                        <small class="text-muted">Por depósito dos indicados</small>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-percentage-circle fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Configuração Probabilidade de Indicação -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Probabilidade de Indicação</p>
                                                        <?php 
                                                        // Determinar qual configuração está sendo usada
                                                        $chanceCpa_efetivo = null;
                                                        $tipo_config = '';
                                                        $badge_color = '';
                                                        
                                                        if (!empty($data['chanceCpa_individual'])) {
                                                            $chanceCpa_efetivo = $data['chanceCpa_individual'];
                                                            $tipo_config = 'Individual';
                                                            $badge_color = 'success';
                                                        } else {
                                                            // Buscar configuração global
                                                            $query_global_chanceCpa = "SELECT chanceCpa FROM afiliados_config LIMIT 1";
                                                            $result_global_chanceCpa = mysqli_query($mysqli, $query_global_chanceCpa);
                                                            if ($result_global_chanceCpa && mysqli_num_rows($result_global_chanceCpa) > 0) {
                                                                $global_chanceCpa_data = mysqli_fetch_assoc($result_global_chanceCpa);
                                                                $chanceCpa_efetivo = $global_chanceCpa_data['chanceCpa'];
                                                            } else {
                                                                $chanceCpa_efetivo = 1; // Padrão
                                                            }
                                                            $tipo_config = 'Global';
                                                            $badge_color = 'info';
                                                        }
                                                        ?>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <span class="badge bg-<?= $badge_color ?>"><?= $chanceCpa_efetivo ?></span>
                                                        </h3>
                                                        <small class="text-muted">
                                                            Tipo: <?= $tipo_config ?> | 
                                                            <?= $chanceCpa_efetivo == 1 ? 'Sempre credita' : "Credita a cada $chanceCpa_efetivo convites" ?>
                                                        </small>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-dice-six fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Referências diretas -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Referências diretas</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <?= count_refer_direto($data['invite_code']); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-eye fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Numero De Depositantes -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Numero De Depositantes</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <?= numero_total_dep($data['invite_code']); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-piggy-bank fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Total ganho CPA -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Total ganho: CPA</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            R$<?= Reais2(total_CPA_id($data['id'])); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-receive-dollars fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Total ganho REV -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Total ganho: Rev</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            R$<?= Reais2(total_REV_id($data['id'])); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-receive-dollars fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Depósitos dos indicados -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Depósitos dos indicados</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            R$<?= Reais2(total_dep_afiliado($data['invite_code'])); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-send-dollars fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Saldo de afiliado (ATUALIZADO) -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Saldo de Comissões</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            R$<?= Reais2($data['saldo_afiliados'] ?? 0); ?>
                                                        </h3>
                                                        <small class="text-muted">Disponível para saque</small>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-dollar-circle fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Afiliação -->
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row d-flex justify-content-center">
                                                    <div class="col">
                                                        <p class="text-dark mb-1 fw-semibold">Afiliação:</p>
                                                        <h3 class="my-2 fs-24 fw-bold">
                                                            <?= afiliado_de_quem($data['invitation_code']); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <div class="d-flex justify-content-center align-items-center thumb-xl bg-light rounded-circle mx-auto">
                                                            <i class="iconoir-group fs-30 align-self-center text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cartão: Simulação Probabilidade de Indicação (se não for 1) -->
                                    <?php if ($chanceCpa_efetivo > 1): ?>
                                    <div class="col-lg-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h5 class="card-title mb-0">
                                                    <i class="iconoir-dice-six me-2"></i>Simulação Probabilidade de Indicação: <?= $chanceCpa_efetivo ?>
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Como funciona:</h6>
                                                        <p class="mb-2">Com Probabilidade = <?= $chanceCpa_efetivo ?>, apenas <strong>1 em cada <?= $chanceCpa_efetivo ?> convites</strong> gera comissão.</p>
                                                        
                                                        <h6>Exemplo prático:</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <?php for ($i = 1; $i <= ($chanceCpa_efetivo * 2); $i++): ?>
                                                                <?php 
                                                                $gera_comissao = ($i % $chanceCpa_efetivo == 0);
                                                                $badge_class = $gera_comissao ? 'success' : 'secondary';
                                                                $icon = $gera_comissao ? '✅' : '❌';
                                                                ?>
                                                                <span class="badge bg-<?= $badge_class ?>" title="<?= $gera_comissao ? 'Gera comissão' : 'Não gera comissão' ?>">
                                                                    <?= $i ?>º <?= $icon ?>
                                                                </span>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php 
                                                        $pessoas_convidadas_real = $data['pessoas_convidadas_real'] ?? 0;
                                                        $proximo_credito = $chanceCpa_efetivo - ($pessoas_convidadas_real % $chanceCpa_efetivo);
                                                        if ($proximo_credito == $chanceCpa_efetivo) $proximo_credito = 0;
                                                        ?>
                                                        <h6>Status atual:</h6>
                                                        <p class="mb-1">
                                                            <strong>Total de convites:</strong> <?= $pessoas_convidadas_real ?>
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Próxima comissão em:</strong> 
                                                            <?php if ($proximo_credito == 0): ?>
                                                                <span class="badge bg-success">Próximo convite!</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning"><?= $proximo_credito ?> convite(s)</span>
                                                            <?php endif; ?>
                                                        </p>
                                                        
                                                        <div class="progress mt-2" style="height: 20px;">
                                                            <?php 
                                                            $progresso = (($pessoas_convidadas_real % $chanceCpa_efetivo) / $chanceCpa_efetivo) * 100;
                                                            if ($progresso == 0 && $pessoas_convidadas_real > 0) $progresso = 100;
                                                            ?>
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $progresso ?>%">
                                                                <?= round($progresso) ?>%
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Progresso para próxima comissão</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Cartão: Histórico de Saldo de Afiliados -->
                                    <div class="col-lg-12">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h5 class="card-title mb-0">
                                                    <i class="iconoir-clock me-2"></i>Resumo Financeiro de Afiliação
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 text-center border-end">
                                                        <h6 class="text-muted">Saldo Atual</h6>
                                                        <h4 class="text-info">R$ <?= number_format($data['saldo_afiliados'] ?? 0, 2, ',', '.'); ?></h4>
                                                        <small>Disponível para saque</small>
                                                    </div>
                                                    <div class="col-md-3 text-center border-end">
                                                        <h6 class="text-muted">Total Ganho (CPA)</h6>
                                                        <h4 class="text-success">R$ <?= Reais2(total_CPA_id($data['id'])); ?></h4>
                                                        <small>Comissões por aquisição</small>
                                                    </div>
                                                    <div class="col-md-3 text-center border-end">
                                                        <h6 class="text-muted">Total Ganho (Rev)</h6>
                                                        <h4 class="text-success">R$ <?= Reais2(total_REV_id($data['id'])); ?></h4>
                                                        <small>Revenue Share</small>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <h6 class="text-muted">Total Geral</h6>
                                                        <h4 class="text-primary">R$ <?= number_format((total_CPA_id($data['id']) + total_REV_id($data['id'])), 2, ',', '.'); ?></h4>
                                                        <small>Todos os ganhos</small>
                                                    </div>
                                                </div>
                                                
                                                <?php if (($data['tipo_conta'] ?? '') === 'afiliado'): ?>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6 class="mb-3">🎯 Potencial de Ganhos:</h6>
                                                        <p class="mb-1">
                                                            <strong>Com <?= count_refer_direto($data['invite_code']); ?> indicados</strong> 
                                                            e <strong><?= number_format($data['comissao_deposito'] ?? 0, 1); ?>% de comissão</strong>:
                                                        </p>
                                                        <?php 
                                                        $depositos_indicados = total_dep_afiliado($data['invite_code']);
                                                        $comissao_teorica = ($depositos_indicados * ($data['comissao_deposito'] ?? 0)) / 100;
                                                        ?>
                                                        <p class="text-muted">
                                                            Depósitos dos indicados: <strong>R$ <?= number_format($depositos_indicados, 2, ',', '.'); ?></strong><br>
                                                            Comissão teórica: <strong>R$ <?= number_format($comissao_teorica, 2, ',', '.'); ?></strong>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NOVO CARTÃO: Simulador de Comissão -->
                                    <?php if (($data['tipo_conta'] ?? '') === 'afiliado' && ($data['comissao_deposito'] ?? 0) > 0): ?>
                                    <div class="col-lg-12">
                                        <div class="card border-success">
                                            <div class="card-header bg-success text-white">
                                                <h5 class="card-title mb-0">
                                                    <i class="iconoir-calculator me-2"></i>Simulador de Comissão
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <h6>Comissão Configurada:</h6>
                                                        <h4 class="text-success"><?= number_format($data['comissao_deposito'], 2, ',', '.'); ?>%</h4>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h6>Exemplos de Ganhos:</h6>
                                                        <div class="row">
                                                            <?php 
                                                            $exemplos = [50, 100, 500, 1000];
                                                            foreach ($exemplos as $valor) {
                                                                $comissao_exemplo = ($valor * $data['comissao_deposito']) / 100;
                                                                echo "<div class='col-3 text-center'>";
                                                                echo "<small class='text-muted'>Depósito R$ $valor</small><br>";
                                                                echo "<strong class='text-success'>R$ " . number_format($comissao_exemplo, 2, ',', '.') . "</strong>";
                                                                echo "</div>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!--end row-->
                            </div>

                            <div class="tab-content">
                            <div class="tab-pane p-3" id="usersafiliados" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Depositou</th>
                                                <th>Data de Cadastro</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                                // Supondo que o ID do usuário atual está disponível em $id_user
                                                $id_user = decodeAll($_REQUEST['slug']); // Captura o ID do usuário da requisição

                                                // Primeiro, pegue o código de convite do usuário atual
                                                $query_codigo_afiliado = "SELECT invite_code FROM usuarios WHERE id = '$id_user'";
                                                $result_codigo_afiliado = mysqli_query($mysqli, $query_codigo_afiliado);

                                                if ($result_codigo_afiliado && mysqli_num_rows($result_codigo_afiliado) > 0) {
                                                    $usuario_atual = mysqli_fetch_assoc($result_codigo_afiliado);
                                                    $affiliate_invite_code = $usuario_atual['invite_code'];

                                                    // Agora, use o código de convite para buscar os indicados
                                                    $query_usuarios_afiliado = "
                                                        SELECT u.*, 
                                                            (SELECT t.valor 
                                                            FROM transacoes t 
                                                            WHERE t.usuario = u.id AND t.status IN ('1', 'pago') 
                                                            ORDER BY t.id ASC 
                                                            LIMIT 1) AS primeiro_deposito 
                                                        FROM usuarios u 
                                                        WHERE u.invitation_code = '$affiliate_invite_code' 
                                                        ORDER BY u.id DESC";

                                                    $result_usuarios_afiliado = mysqli_query($mysqli, $query_usuarios_afiliado);

                                                    if (!$result_usuarios_afiliado) {
                                                        echo "<tr><td colspan='5' class='text-center'>Erro na consulta: " . mysqli_error($mysqli) . "</td></tr>";
                                                    } else {
                                                        if (mysqli_num_rows($result_usuarios_afiliado) > 0) {
                                                            while ($usuario_afiliado = mysqli_fetch_assoc($result_usuarios_afiliado)) {
                                                                ?>
                                                                <tr>
                                                                    <td><?= $usuario_afiliado['id']; ?></td>
                                                                    <td><?= $usuario_afiliado['real_name']; ?></td>
                                                                    <td><?= $usuario_afiliado['primeiro_deposito'] ? 'R$ ' . number_format($usuario_afiliado['primeiro_deposito'], 2, ',', '.') : 'Nenhum depósito'; ?></td>
                                                                    <td><?= $usuario_afiliado['data_cad']; ?></td>
                                                                    <td>
                                                                        <button class="btn btn-danger btn-sm" onclick="excluirIndicado(<?= $usuario_afiliado['id']; ?>)">Excluir</button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='5' class='text-center'>Nenhum usuário afiliado encontrado!</td></tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>Erro: Não foi possível encontrar o código de convite do usuário.</td></tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Registros de Depósitos -->
                                <div class="tab-pane p-3" id="depositos" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table  mb-0 table-centered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Transação ID</th>
                                                    <th>Valor</th>
                                                    <th>Data/Hora</th>
                                                    <th>Status</th>
                                                    <th>Comissão</th>
                                                    <th>Afiliado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_depositos = "SELECT * FROM transacoes WHERE usuario = '" . intval($id_user) . "' ORDER BY id DESC";
                                                $result_depositos = mysqli_query($mysqli, $query_depositos);

                                                if ($result_depositos && mysqli_num_rows($result_depositos) > 0) {
                                                    while ($deposito = mysqli_fetch_assoc($result_depositos)) {
                                                        ?>
                                                <tr>
                                                    <td><?= $deposito['id']; ?></td>
                                                    <td><?= $deposito['transacao_id']; ?></td>
                                                    <td>R$ <?= number_format($deposito['valor'], 2, ',', '.'); ?></td>
                                                    <td><?= $deposito['data_hora']; ?></td>
                                                    <td><?= getStatusBadge($deposito['status']); ?></td>
                                                    <td>
                                                        <?php if (!empty($deposito['comissao']) && $deposito['comissao'] > 0): ?>
                                                            <span class="badge bg-success">R$ <?= number_format($deposito['comissao'], 2, ',', '.'); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($deposito['afiliado_id'])): ?>
                                                            <span class="badge bg-info">ID: <?= $deposito['afiliado_id']; ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' class='text-center'>Sem depósitos disponíveis!</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <!--end /table-->
                                    </div>
                                    <!--end /tableresponsive-->
                                </div>

                                <!-- Registros de Saques -->
                                <div class="tab-pane p-3" id="saques" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table  mb-0 table-centered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Valor</th>
                                                    <th>Data/Hora</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_saques = "SELECT * FROM solicitacao_saques WHERE id_user = '" . intval($id_user) . "' ORDER BY id DESC";
                                                $result_saques = mysqli_query($mysqli, $query_saques);

                                                if ($result_saques && mysqli_num_rows($result_saques) > 0) {
                                                    while ($saque = mysqli_fetch_assoc($result_saques)) {
                                                        $status_saque = $saque['status'] == 1 ? "Aprovado" : "Em Análise";
                                                        ?>
                                                <tr>
                                                    <td><?= $saque['id']; ?></td>
                                                    <td>R$ <?= number_format($saque['valor'], 2, ',', '.'); ?></td>
                                                    <td><?= $saque['data_hora']; ?></td>
                                                    <td><span
                                                            class="badge bg-<?= $saque['status'] == 1 ? 'success' : 'warning' ?>"><?= $status_saque; ?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4' class='text-center'>Sem registros de saques disponíveis!</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <!--end /table-->
                                    </div>
                                    <!--end /tableresponsive-->
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

                </div><!-- container -->
                <!--Start Rightbar-->
                <?php include 'partials/endbar.php' ?>
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- Toast Container -->
        <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

        <!-- Javascript  -->
        <?php include 'partials/vendorjs.php' ?>
        <script src="assets/js/app.js"></script>

        <script>
        // Função para formatar valor como moeda brasileira
        function formatarMoeda(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        }

        // Validação do campo saldo de afiliados
        document.getElementById('saldo_afiliados_edit').addEventListener('input', function() {
            let value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            
            // Limitar a 2 casas decimais
            if (this.value.includes('.')) {
                let parts = this.value.split('.');
                if (parts[1].length > 2) {
                    this.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
        });

        // Validação do campo probabilidade de indicação
        document.getElementById('chanceCpa_individual').addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1 && this.value !== '') {
                this.value = 1;
                showToast('warning', 'Probabilidade de Indicação deve ser pelo menos 1');
            }
            
            // Atualizar preview do status
            updateProbabilidadeStatus();
        });

        // Função para atualizar o status da Probabilidade de Indicação em tempo real
        function updateProbabilidadeStatus() {
            const probabilidadeInput = document.getElementById('chanceCpa_individual');
            const statusBadge = document.getElementById('probabilidade_status_badge');
            
            if (probabilidadeInput && statusBadge) {
                const valor = probabilidadeInput.value;
                if (valor && valor !== '') {
                    statusBadge.className = 'badge bg-success';
                    statusBadge.innerHTML = `<i class="fas fa-cog me-1"></i>Individual: ${valor}`;
                } else {
                    statusBadge.className = 'badge bg-info';
                    statusBadge.innerHTML = '<i class="fas fa-cog me-1"></i>Global: (configuração do sistema)';
                }
            }
        }

        // Adicionar validação específica para Probabilidade de Indicação no formulário
        function validarProbabilidadeIndicacao() {
            const probabilidadeValue = document.getElementById('chanceCpa_individual').value;
            
            if (probabilidadeValue !== '' && (isNaN(probabilidadeValue) || parseInt(probabilidadeValue) < 1)) {
                return 'Probabilidade de Indicação deve ser um número maior ou igual a 1 (ou deixe vazio para usar configuração global)';
            }
            
            return null;
        }

        // Função para validar o formulário antes do envio
        function validarFormulario() {
            const tipoConta = document.getElementById('tipo_conta').value;
            const comissao = parseFloat(document.getElementById('comissao_deposito').value);
            const saldoAfiliados = parseFloat(document.getElementById('saldo_afiliados_edit').value);
            
            let errors = [];
            
            // Validar comissão para afiliados
            if (tipoConta === 'afiliado' && (comissao < 0 || comissao > 100)) {
                errors.push('Comissão deve estar entre 0% e 100% para afiliados');
            }
            
            // Validar saldo de afiliados
            if (saldoAfiliados < 0) {
                errors.push('Saldo de comissões não pode ser negativo');
            }
            
            // Validar Probabilidade de Indicação
            const erroProbabilidade = validarProbabilidadeIndicacao();
            if (erroProbabilidade) {
                errors.push(erroProbabilidade);
            }
            
            if (errors.length > 0) {
                alert('Erros encontrados:\n' + errors.join('\n'));
                return false;
            }
            
            return true;
        }

        // Adicionar validação ao formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validarFormulario()) {
                e.preventDefault();
            }
        });

        // Função para mostrar preview das alterações
        function mostrarPreviewAlteracoes() {
            const saldoAfiliados = parseFloat(document.getElementById('saldo_afiliados_edit').value) || 0;
            const saldoAtual = <?= $data['saldo_afiliados'] ?? 0 ?>;
            
            if (saldoAfiliados !== saldoAtual) {
                const diferenca = saldoAfiliados - saldoAtual;
                const acao = diferenca > 0 ? 'ADIÇÃO' : 'REDUÇÃO';
                const cor = diferenca > 0 ? 'text-success' : 'text-danger';
                
                console.log(`${acao} no saldo de afiliados: ${formatarMoeda(Math.abs(diferenca))}`);
            }
        }

        // Listener para mostrar preview em tempo real
        document.getElementById('saldo_afiliados_edit').addEventListener('input', mostrarPreviewAlteracoes);

        // Função para mostrar/ocultar campos de afiliado
        function toggleComissaoField() {
            const tipoConta = document.getElementById('tipo_conta').value;
            const affiliateSection = document.getElementById('affiliate_info_section');
            const comissaoField = document.getElementById('comissao_deposito');
            const saldoAfiliadosField = document.getElementById('saldo_afiliados_edit');
            const probabilidadeField = document.getElementById('chanceCpa_individual');
            
            if (tipoConta === 'afiliado') {
                affiliateSection.style.display = 'flex';
                comissaoField.disabled = false;
                saldoAfiliadosField.disabled = false;
                probabilidadeField.disabled = false;
            } else {
                affiliateSection.style.display = 'none';
                comissaoField.disabled = true;
                comissaoField.value = '0.00';
                // Não zerar o saldo de afiliados automaticamente, mas desabilitar edição
                saldoAfiliadosField.disabled = true;
                // Não desabilitar probabilidade de indicação pois pode ser útil para usuários também
                probabilidadeField.disabled = false;
            }
        }

        // Função para resetar o formulário
        function resetForm() {
            if (confirm('Tem certeza que deseja resetar todos os campos?')) {
                document.querySelector('form').reset();
                toggleComissaoField();
                updateProbabilidadeStatus();
            }
        }

        // Validação do campo de comissão
        document.getElementById('comissao_deposito').addEventListener('input', function() {
            let value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });

        // Função para mostrar tooltip explicativo
        function mostrarTooltipProbabilidade() {
            const probabilidadeField = document.getElementById('chanceCpa_individual');
            if (probabilidadeField) {
                probabilidadeField.setAttribute('title', 
                    'Probabilidade de Indicação:\n' +
                    '• 1 = Sempre credita comissão\n' +
                    '• 2 = Credita a cada 2 convites\n' +
                    '• 3 = Credita a cada 3 convites\n' +
                    '• Vazio = Usa configuração global do sistema'
                );
            }
        }

        // Executar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            toggleComissaoField();
            updateProbabilidadeStatus();
            mostrarTooltipProbabilidade();
        });

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
            <h5 class="me-auto my-0">Atualização</h5>
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

            // Reload da página após 3 segundos para mostrar os dados atualizados
            setTimeout(function() {
                // Mantém o mesmo slug na URL
                const currentUrl = window.location.href;
                const urlParams = new URLSearchParams(window.location.search);
                const slug = urlParams.get('slug');
                
                if (slug) {
                    window.location.href = window.location.pathname + "?slug=" + encodeURIComponent(slug);
                } else {
                    window.location.reload();
                }
            }, 3000);
        }

        function excluirIndicado(usuarioId) {
            fetch('fetch/excluir_indicado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ usuario_id: usuarioId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Usuário excluído com sucesso.');
                    // Opcional: Atualizar a tabela ou recarregar a página
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    showToast('danger', 'Erro ao excluir usuário: ' + data.message);
                }
            })
            .catch((error) => {
                showToast('danger', 'Erro ao excluir usuário: ' + error);
            });
        }

        document.getElementById('confirmarBanimento').addEventListener('click', function() {
            modificarUsuario(<?= $id_user; ?>, 'banir');
        });

        document.getElementById('confirmarDesbanimento').addEventListener('click', function() {
            modificarUsuario(<?= $id_user; ?>, 'desbanir');
        });

        function modificarUsuario(userId, action) {
            fetch('fetch/banir_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        showToast('danger', 'Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    showToast('danger', 'Erro ao processar a solicitação.');
                });
        }

        document.getElementById('adicionarSaldo').addEventListener('input', atualizarSaldoFinal);
        document.getElementById('removerSaldo').addEventListener('input', atualizarSaldoFinal);

        function atualizarSaldoFinal() {
            var saldoAtual = parseFloat(<?= $saldo_user['saldo'] ?>);
            var adicionarSaldo = parseFloat(document.getElementById('adicionarSaldo').value) || 0;
            var removerSaldo = parseFloat(document.getElementById('removerSaldo').value) || 0;
        
            var saldoFinal = saldoAtual + adicionarSaldo - removerSaldo;
        
            document.getElementById('saldoFinal').textContent = 'R$ ' + saldoFinal.toFixed(2).replace('.', ',');
        }

        document.getElementById('confirmarEdicaoSaldo').addEventListener('click', function() {
            var adicionarSaldo = parseFloat(document.getElementById('adicionarSaldo').value) || 0;
            var removerSaldo = parseFloat(document.getElementById('removerSaldo').value) || 0;

            editarSaldoUsuario(<?= $id_user; ?>, adicionarSaldo, removerSaldo);
        });

        function editarSaldoUsuario(userId, adicionar, remover) {
            fetch('fetch/editar_saldo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        adicionar: adicionar,
                        remover: remover
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Saldo atualizado com sucesso!');
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    } else {
                        showToast('danger', 'Erro: ' + data.message);
                    }
                })
                .catch(error => {
                    showToast('danger', 'Erro ao atualizar o saldo.');
                });
        }
        </script>
        
</body>

</html>