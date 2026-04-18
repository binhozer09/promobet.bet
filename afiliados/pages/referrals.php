<?php
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

$statsRefs = getReferralsStats($user_id);

$referralsList = getReferrals($user_id);

$currentMonth = date('F Y');
?>

<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">
                <i class="fas fa-users" style="color: var(--success-color); margin-right: 12px;"></i>
                Indicações
            </h1>
        </div>
        <div class="navbar-right">
            <button class="btn btn-outline">
                <i class="fas fa-filter"></i>
                Filtrar
            </button>
            <button class="btn btn-outline">
                <i class="fas fa-download"></i>
                Exportar
            </button>
        </div>
    </nav>

    <!-- Estatísticas de Referidos -->
    <div class="grid" style="margin-bottom: 32px;">
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Total de Referidos</h4>
                <div class="value" data-target="<?php echo $statsRefs['total']; ?>"><?php echo $statsRefs['total']; ?></div>
                <div class="change">
                    <i class="fas fa-user-plus"></i> Todos os tempos
                </div>
            </div>
        </div>
        
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Depositantes</h4>
                <div class="value" data-target="<?php echo $statsRefs['ativos']; ?>"><?php echo $statsRefs['ativos']; ?></div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> Gerando receita
                </div>
            </div>
        </div>
        
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Sem Depósitos</h4>
                <div class="value" data-target="<?php echo $statsRefs['pendentes']; ?>"><?php echo $statsRefs['pendentes']; ?></div>
                <div class="change">
                    <i class="fas fa-hourglass-half"></i> Potencial
                </div>
            </div>
        </div>
        
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1)); color: var(--danger-color);">
                    <i class="fas fa-user-slash"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Inativos</h4>
                <div class="value" data-target="<?php echo $statsRefs['inativos']; ?>"><?php echo $statsRefs['inativos']; ?></div>
                <div class="change">
                    <i class="fas fa-arrow-down"></i> Sem atividade
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 32px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-pie" style="color: var(--primary-color); margin-right: 12px;"></i>
                Resumo de Performance
            </h3>
        </div>
        
        <div style="padding: 28px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                <!-- Taxa de Conversão -->
                <div style="text-align: center; padding: 24px; background: var(--tertiary-bg); border-radius: 16px; border: 1px solid var(--border-color);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--gradient-success); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0; color: var(--text-color);">Taxa de Conversão</h4>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--success-color);">
                        <?php 
                        $conversao = $statsRefs['total'] > 0 ? ($statsRefs['ativos'] / $statsRefs['total']) * 100 : 0;
                        echo number_format($conversao, 1, ',', '.'); 
                        ?>%
                    </div>
                    <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 0.85rem;">Depositantes / Total</p>
                </div>

                <!-- Valor Médio por Referido -->
                <div style="text-align: center; padding: 24px; background: var(--tertiary-bg); border-radius: 16px; border: 1px solid var(--border-color);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--gradient-warning); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0; color: var(--text-color);">Valor Médio</h4>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--warning-color);">
                        R$ <?php 
                        // Calcular valor médio baseado nos dados disponíveis
                        $totalDepositos = 0;
                        $countDepositos = 0;
                        foreach($referralsList as $ref) {
                            if ($ref['soma_depositos'] > 0) {
                                $totalDepositos += $ref['soma_depositos'];
                                $countDepositos++;
                            }
                        }
                        $valorMedio = $countDepositos > 0 ? $totalDepositos / $countDepositos : 0;
                        echo number_format($valorMedio, 2, ',', '.'); 
                        ?>
                    </div>
                    <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 0.85rem;">Por depositante</p>
                </div>

                <!-- Melhor Referido -->
                <div style="text-align: center; padding: 24px; background: var(--tertiary-bg); border-radius: 16px; border: 1px solid var(--border-color);">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--gradient-primary); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h4 style="margin: 0 0 8px 0; color: var(--text-color);">Melhor Referido</h4>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-color);">
                        R$ <?php 
                        $melhorDeposito = 0;
                        foreach($referralsList as $ref) {
                            if ($ref['soma_depositos'] > $melhorDeposito) {
                                $melhorDeposito = $ref['soma_depositos'];
                            }
                        }
                        echo number_format($melhorDeposito, 2, ',', '.'); 
                        ?>
                    </div>
                    <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 0.85rem;">Maior depósito</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Referidos -->
    <div class="card" data-aos="fade-up" data-aos-delay="600">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list" style="color: var(--success-color); margin-right: 12px;"></i>
                Lista de Referidos
            </h3>
            <div style="display: flex; gap: 12px;">
                <select style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                    <option>Todos os Status</option>
                    <option>Ativos</option>
                    <option>Pendentes</option>
                    <option>Inativos</option>
                </select>
                <button class="btn btn-outline">
                    <i class="fas fa-sync"></i>
                    Atualizar
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag" style="margin-right: 8px; opacity: 0.7;"></i>ID</th>
                        <th><i class="fas fa-user" style="margin-right: 8px; opacity: 0.7;"></i>Nome</th>
                        <th><i class="fas fa-calendar" style="margin-right: 8px; opacity: 0.7;"></i>Data Cadastro</th>
                        <th><i class="fas fa-check-circle" style="margin-right: 8px; opacity: 0.7;"></i>Status</th>
                        <th><i class="fas fa-money-bill" style="margin-right: 8px; opacity: 0.7;"></i>Depósitos</th>
                        <th><i class="fas fa-cog" style="margin-right: 8px; opacity: 0.7;"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($referralsList)): ?>
                        <?php foreach ($referralsList as $index => $ref): ?>
                            <tr style="animation: fadeInUp 0.5s ease forwards; animation-delay: <?php echo $index * 0.1; ?>s; opacity: 0;">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 8px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.7rem;">
                                            <?php echo substr($ref['id'], -2); ?>
                                        </div>
                                        <span style="font-weight: 600;">#<?php echo $ref['id']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 12px; background: var(--gradient-success); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.9rem;">
                                            <?php echo strtoupper(substr($ref['mobile'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text-color);"><?php echo htmlspecialchars($ref['mobile']); ?></div>
                                            <div style="font-size: 0.8rem; color: var(--text-light);">
                                                <?php echo $ref['status_calculado'] === 'Ativo' ? 'Usuário ativo' : ($ref['status_calculado'] === 'Pendente' ? 'Aguardando primeiro depósito' : 'Sem atividade recente'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-calendar-alt" style="color: var(--primary-color); opacity: 0.7;"></i>
                                        <div>
                                            <div style="font-weight: 600;"><?php echo date('d/m/Y', strtotime($ref['data_cad'])); ?></div>
                                            <div style="font-size: 0.8rem; color: var(--text-light);"><?php echo date('H:i', strtotime($ref['data_cad'])); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($ref['status_calculado'] === 'Ativo'): ?>
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check"></i> Ativo
                                        </span>
                                    <?php elseif ($ref['status_calculado'] === 'Pendente'): ?>
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i> Pendente
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times"></i> Inativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo $ref['soma_depositos'] > 0 ? 'var(--success-color)' : 'var(--text-light)'; ?>;"></div>
                                        <span style="font-weight: 700; font-size: 1rem; color: <?php echo $ref['soma_depositos'] > 0 ? 'var(--success-color)' : 'var(--text-light)'; ?>;">
                                            R$ <?php echo number_format($ref['soma_depositos'], 2, ',', '.'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <button class="btn btn-outline" style="padding: 6px 12px;" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline" style="padding: 6px 12px;" title="Enviar mensagem">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <button class="btn btn-outline" style="padding: 6px 12px;" title="Histórico">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-light);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                    <i class="fas fa-user-friends" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <div>
                                        <strong style="display: block; margin-bottom: 8px; font-size: 1.1rem;">Nenhum referido encontrado</strong>
                                        <p style="margin: 0; opacity: 0.7;">Compartilhe seu link de afiliado para começar a ganhar comissões!</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Animate counter values
    const valueElements = document.querySelectorAll('.value[data-target]');
    valueElements.forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        if (target > 0) {
            animateValue(element, 0, target, 1500);
        }
    });

    // Add click effects to interactive cards
    document.querySelectorAll('.interactive-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add hover effects to table rows
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.background = 'var(--hover-bg)';
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.background = '';
            this.style.transform = 'translateX(0)';
        });
    });

    // Add hover effects to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= end) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current);
    }, 16);
}
</script>

<style>
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

/* Enhanced table styling */
tbody tr {
    transition: all 0.3s var(--ease-swift);
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

tbody tr:hover {
    background: var(--hover-bg) !important;
    transform: translateX(4px) !important;
}

/* Status badge enhancements */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

/* Button hover effects */
.btn {
    transition: all 0.3s var(--ease-swift);
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
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    box-shadow: var(--shadow-md);
}

/* Interactive card pulse effect */
.interactive-card:hover {
    animation: pulseGlow 2s infinite;
}

@keyframes pulseGlow {
    0%, 100% { 
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl), var(--glow-primary);
    }
    50% { 
        transform: translateY(-8px) scale(1.025);
        box-shadow: var(--shadow-xl), 0 0 30px rgba(99, 102, 241, 0.5);
    }
}

/* Performance cards hover */
.card > div > div > div {
    transition: all 0.3s var(--ease-swift);
}

.card > div > div > div:hover {
    transform: translateY(-4px);
    background: var(--hover-bg) !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .stat-card .value {
        font-size: 1.5rem;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .card-header > div {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    tbody tr > td:nth-child(n+4) {
        display: none;
    }
    
    thead tr > th:nth-child(n+4) {
        display: none;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-info .value {
        font-size: 1.25rem;
    }
}
</style>