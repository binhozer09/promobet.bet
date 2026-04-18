<div class="startbar d-print-none">
    <div class="brand">
        <a href="dashboard" class="logo">
            <span>
                <img src="../uploads/<?= $dataconfig['logo'] ?>" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
            </span>
        </a>
    </div>
    
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    
                    <!-- INÍCIO -->
                    <li class="menu-label pt-0 mt-0">
                        <span style="color: white;">INÍCIO</span>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="dashboard">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>

                    <!-- CONFIGURAÇÕES GERAIS -->
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">CONFIGURAÇÕES GERAIS</span>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarMaps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMaps">
                            <i class="iconoir-html5 menu-icon"></i>
                            <span>Plataforma</span>
                        </a>
                        <div class="collapse" id="sidebarMaps">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="configuracoes">Gerenciar Valores</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="gerenciamento-nomes">Gerenciar Nomes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="pixel">Gerenciar Pixels</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="identidade-visual">Gerenciar Imagens</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="bonuscadastros">Bônus de Depósitos</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                            <i class="iconoir-settings menu-icon"></i>
                            <span>Afiliados</span>
                        </a>
                        <div class="collapse" id="sidebarCharts">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="gerenciamento-afiliados">Gerenciar Valores</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#baus" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="baus">
                            <i class="iconoir-html5 menu-icon"></i>
                            <span>Baús</span>
                        </a>
                        <div class="collapse" id="baus">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="baus">Gerenciar Baús</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#gateway" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="gateway">
                            <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                            <span>Gateway</span>
                        </a>
                        <div class="collapse" id="gateway">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="suitpay">Credenciais GGPix</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#temas" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="temas">
                            <i class="iconoir-design-pencil menu-icon"></i>
                            <span>Personalização</span>
                        </a>
                        <div class="collapse" id="temas">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="banners">Gerenciar Banners</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="popups">Gerenciar Popups</a>
                                </li>
                                <!--<li class="nav-item">
                                    <a class="nav-link" href="baixarpop">Gerenciar App</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link" href="promocao">Gerenciar Promoções</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="mensagens">Gerenciar Anuncios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="temas">
                                        Temas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="iconesfloat">
                                        Icones Float
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="iconesNav">
                                        Icones Navbar
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="jackpot">
                                        Jackpot
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="numeros-jackpot">
                                        Números Jackpot
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>

                    <!-- FINANCEIRO -->
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">FINANCEIRO</span>
                    </li>
                    
                    <?php
                    $query_depositos_processamento = "SELECT COUNT(*) as total_processamento FROM transacoes WHERE status = 'processamento'";
                    $result_depositos_processamento = mysqli_query($mysqli, $query_depositos_processamento);
                    $row_depositos_processamento = mysqli_fetch_assoc($result_depositos_processamento);
                    $total_depositos_processamento = $row_depositos_processamento['total_processamento'];
                    
                    $query_depositos_aprovados = "SELECT COUNT(*) as total_aprovados FROM transacoes WHERE status = 'pago'";
                    $result_depositos_aprovados = mysqli_query($mysqli, $query_depositos_aprovados);
                    $row_depositos_aprovados = mysqli_fetch_assoc($result_depositos_aprovados);
                    $total_depositos_aprovados = $row_depositos_aprovados['total_aprovados'];
                    
                    $query_depositos_recusados = "SELECT COUNT(*) as total_recusados FROM transacoes WHERE status = 'expirado'";
                    $result_depositos_recusados = mysqli_query($mysqli, $query_depositos_recusados);
                    $row_depositos_recusados = mysqli_fetch_assoc($result_depositos_recusados);
                    $total_depositos_recusados = $row_depositos_recusados['total_recusados'];
                    
                    $total_depositos = $total_depositos_processamento + $total_depositos_aprovados + $total_depositos_recusados;
                    ?>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarElements" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarElements">
                            <i class="iconoir-receive-dollars menu-icon"></i>
                            <span>Depósitos</span>
                            <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_depositos; ?></span>
                        </a>
                        <div class="collapse" id="sidebarElements">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_pagos">
                                        Aprovados 
                                        <span class="badge rounded text-success bg-success-subtle ms-1"><?= $total_depositos_aprovados; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_pendentes">
                                        Pendentes 
                                        <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_depositos_processamento; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_expirados">
                                        Expirados 
                                        <span class="badge rounded text-danger bg-danger-subtle ms-1"><?= $total_depositos_recusados; ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <?php
                    $query_saques_pendentes = "SELECT COUNT(*) as total_pendentes FROM solicitacao_saques WHERE status = '0' AND tipo_saque = '0'";
                    $result_saques_pendentes = mysqli_query($mysqli, $query_saques_pendentes);
                    $row_saques_pendentes = mysqli_fetch_assoc($result_saques_pendentes);
                    $total_saques_pendentes = $row_saques_pendentes['total_pendentes'];
                    
                    $query_saques_aprovados = "SELECT COUNT(*) as total_aprovados FROM solicitacao_saques WHERE status = '1' AND tipo_saque = '0'";
                    $result_saques_aprovados = mysqli_query($mysqli, $query_saques_aprovados);
                    $row_saques_aprovados = mysqli_fetch_assoc($result_saques_aprovados);
                    $total_saques_aprovados = $row_saques_aprovados['total_aprovados'];
                    
                    $query_saques_recusados = "SELECT COUNT(*) as total_recusados FROM solicitacao_saques WHERE status = '2' AND tipo_saque = '0'";
                    $result_saques_recusados = mysqli_query($mysqli, $query_saques_recusados);
                    $row_saques_recusados = mysqli_fetch_assoc($result_saques_recusados);
                    $total_saques_recusados = $row_saques_recusados['total_recusados'];
                    
                    $total_saques = $total_saques_pendentes + $total_saques_aprovados + $total_saques_recusados;
                    
                    $query_saques_afiliados_pendentes = "SELECT COUNT(*) as total_pendentes FROM solicitacao_saques WHERE status = '0' AND tipo_saque = '1'";
                    $result_saques_afiliados_pendentes = mysqli_query($mysqli, $query_saques_afiliados_pendentes);
                    $row_saques_afiliados_pendentes = mysqli_fetch_assoc($result_saques_afiliados_pendentes);
                    $total_saques_afiliados_pendentes = $row_saques_afiliados_pendentes['total_pendentes'];
                    
                    $query_saques_afiliados_aprovados = "SELECT COUNT(*) as total_aprovados FROM solicitacao_saques WHERE status = '1' AND tipo_saque = '1'";
                    $result_saques_afiliados_aprovados = mysqli_query($mysqli, $query_saques_afiliados_aprovados);
                    $row_saques_afiliados_aprovados = mysqli_fetch_assoc($result_saques_afiliados_aprovados);
                    $total_saques_afiliados_aprovados = $row_saques_afiliados_aprovados['total_aprovados'];
                    
                    $query_saques_afiliados_recusados = "SELECT COUNT(*) as total_recusados FROM solicitacao_saques WHERE status = '2' AND tipo_saque = '1'";
                    $result_saques_afiliados_recusados = mysqli_query($mysqli, $query_saques_afiliados_recusados);
                    $row_saques_afiliados_recusados = mysqli_fetch_assoc($result_saques_afiliados_recusados);
                    $total_saques_afiliados_recusados = $row_saques_afiliados_recusados['total_recusados'];
                    
                    $total_saques_afiliados = $total_saques_afiliados_pendentes + $total_saques_afiliados_aprovados + $total_saques_afiliados_recusados;
                    ?>

                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarAdvancedUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvancedUI">
                            <i class="iconoir-send-dollars menu-icon"></i>
                            <span>Saques</span>
                            <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques_pendentes; ?></span>
                        </a>
                        <div class="collapse" id="sidebarAdvancedUI">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_aprovados">Aprovados</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_pendentes">
                                        Pendentes
                                        <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques_pendentes; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_recusados">Recusados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                            <i class="iconoir-send-dollars menu-icon"></i>
                            <span>Saques Afiliados</span>
                            <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques_afiliados_pendentes; ?></span>
                        </a>
                        <div class="collapse" id="sidebarIcons">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_afiliados_aprovados">Aprovados</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_afiliados_pendentes">
                                        Pendentes
                                        <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques_afiliados_pendentes; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_afiliados_recusados">Recusados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>

                    <!-- USUARIOS -->
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">USUARIOS</span>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarForms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarForms">
                            <i class="iconoir-community menu-icon"></i>
                            <span>Usuários</span>
                        </a>
                        <div class="collapse" id="sidebarForms">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="usuarios">Todos Usuários</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contasdemos">Criar contas demos</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                            <i class="iconoir-user-star menu-icon"></i>
                            <span>Afiliados</span>
                        </a>
                        <div class="collapse" id="sidebarTables">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="afiliados">Todos Afiliados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>

                    <!-- OUTROS -->
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">OUTROS</span>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#webhooks" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="webhooks">
                            <i class="iconoir-bell menu-icon"></i>
                            <span>Notificações</span>
                        </a>
                        <div class="collapse" id="webhooks">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="webhooks">Webhooks</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#historico" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="historico">
                            <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                            <span>Históricos</span>
                        </a>
                        <div class="collapse" id="historico">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="logsadmin">Atividades Administrativas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="historico-partidas">Todas Partidas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="logsafiliados">Registros de Contas</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>

                    <!-- API -->
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">API</span>
                    </li>

                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#jogosapi" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="jogosapi">
                            <i class="iconoir-spades menu-icon"></i>
                            <span>Jogos</span>
                        </a>
                        <div class="collapse" id="jogosapi">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="maxapigamesjogos">MaxApiGames</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="gamesplayfiver">PlayFiver</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                   <!-- <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#provedoresapi" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="provedoresapi">
                            <i class="iconoir-server menu-icon"></i>
                            <span>Gerenciar Provedores</span>
                        </a>
                        <div class="collapse" id="provedoresapi">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="provedores">Listar E Gerenciar Provedores</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#chavesapi" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="chavesapi">
                            <i class="iconoir-key-plus menu-icon"></i>
                            <span>Chaves</span>
                        </a>
                        <div class="collapse" id="chavesapi">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="api">Gerenciar Credenciais</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <div class="border-dashed-bottom pb-2"></div>
                </ul>

                
            </div>
        </div>
    </div>
</div>
<div class="startbar-overlay d-print-none"></div>