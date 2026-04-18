<?php
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
$stats = getUserStats($user_id);
$visitorCount = getVisitorCount($user_id);
$lastCommissions = getDepositosIndiretos($user_id, 'pago');
$performanceData = getPerformanceData($user_id, 7);
?>

<div class="dashboard">
    <!-- Navbar com título da página -->
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">Dashboard</h1>
        </div>
        <div class="navbar-right">
            <button class="btn btn-outline">
                <i class="fas fa-download"></i>
                Exportar
            </button>
        </div>
    </nav>

    <!-- Primeira Linha de Estatísticas -->
    <div class="grid">
        <!-- Box Comissão Disponível -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: var(--primary-color);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Comissão Disponível</h4>
                <div class="value" data-value="<?php echo getAvailableCommission($user_id); ?>">
                    R$ <?php echo number_format(getAvailableCommission($user_id), 2, ',', '.'); ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +12.5% este mês
                </div>
            </div>
        </div>
        
        <!-- Box Total Sacado -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1)); color: var(--danger-color);">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Total Sacado</h4>
                <div class="value" data-value="<?php echo $stats['total_sacado']; ?>">
                    R$ <?php echo number_format($stats['total_sacado'], 2, ',', '.'); ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +8.2% este mês
                </div>
            </div>
        </div>
        
        <!-- Box Indicações Diretas -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Indicações Diretas</h4>
                <div class="value" data-value="<?php echo $stats['total_indications']; ?>">
                    <?php echo $stats['total_indications']; ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +3 novos
                </div>
            </div>
        </div>
        
        <!-- Box Quantidade de Depósitos -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(75, 85, 99, 0.2), rgba(75, 85, 99, 0.1)); color: #6B7280;">
                    <i class="fas fa-list-ol"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Depósitos</h4>
                <div class="value" data-value="<?php echo $stats['qtd_depositos']; ?>">
                    <?php echo $stats['qtd_depositos']; ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +15 hoje
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Linha de Estatísticas -->
    <div class="grid">
        <!-- Box Valor Depositado -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="500">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-money-check-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Valor Depositado</h4>
                <div class="value" data-value="<?php echo $stats['valor_depositado']; ?>">
                    R$ <?php echo number_format($stats['valor_depositado'], 2, ',', '.'); ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +18.7% este mês
                </div>
            </div>
        </div>
        
        <!-- Box Desempenho -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="600">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(107, 114, 128, 0.2), rgba(107, 114, 128, 0.1)); color: #6B7280;">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Desempenho</h4>
                <div class="value" data-value="<?php echo $stats['desempenho']; ?>">
                    <?php echo number_format($stats['desempenho'], 1, ',', '.'); ?>%
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +2.1% melhoria
                </div>
            </div>
        </div>
        
        <!-- Box Visitas Únicas -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="700">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(139, 92, 246, 0.1)); color: var(--accent-purple);">
                    <i class="fas fa-network-wired"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Visitas Únicas</h4>
                <div class="value" data-value="<?php echo $visitorCount; ?>">
                    <?php echo $visitorCount; ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +7.3% esta semana
                </div>
            </div>
        </div>
        
        <!-- Box Primeiros Depósitos -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="800">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Primeiros Depósitos</h4>
                <div class="value" data-value="<?php echo $stats['total_depositantes']; ?>">
                    <?php echo $stats['total_depositantes']; ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +5 novos usuários
                </div>
            </div>
        </div>
    </div>

    <!-- Terceira Linha -->
    <div class="grid">
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="900">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(134, 239, 172, 0.2), rgba(134, 239, 172, 0.1)); color: #22c55e;">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Porcentagem Cooperação</h4>
                <div class="value" data-value="<?php echo $stats['comissao_percentual']; ?>">
                    <?php echo number_format($stats['comissao_percentual'], 0, ',', '.'); ?>%
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> Taxa fixa
                </div>
            </div>
        </div>
        
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="1000">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-money-check-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Próximo Salário</h4>
                <div class="value" data-value="<?php echo $stats['prox_salario_valor'] ?? 0; ?>">
                    R$ <?php echo number_format($stats['prox_salario_valor'] ?? 0, 2, ',', '.'); ?>
                </div>
                <div class="change">
                    <i class="fas fa-clock"></i> Em breve
                </div>
            </div>
        </div>

        <!-- Box Data Próximo Salário -->
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="1100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(96, 165, 250, 0.2), rgba(96, 165, 250, 0.1)); color: #60a5fa;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Data Próximo Salário</h4>
                <div class="value">
                    <?php 
                        $nextDate = $stats['prox_salario'] ?? '';
                        echo ($nextDate != '') ? date('d/m/Y', strtotime($nextDate)) : 'DD/MM/AAAA';
                    ?>
                </div>
                <div class="change">
                    <i class="fas fa-calendar-check"></i> Próxima data
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas Comissões -->
    <div class="grid">
        <div class="card" data-aos="fade-up" data-aos-delay="1200">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-coins" style="color: var(--warning-color); margin-right: 12px;"></i>
                    Últimas Comissões
                </h3>
                <button class="btn btn-outline">
                    <i class="fas fa-eye"></i>
                    Ver todas
                </button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Referido</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Comissão</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($lastCommissions)): ?>
                            <?php foreach ($lastCommissions as $com): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($com['data_hora'])); ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem;">
                                                <?php echo strtoupper(substr($com['nome_usuario'], 0, 2)); ?>
                                            </div>
                                            <?php echo htmlspecialchars($com['nome_usuario']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                            <?php echo htmlspecialchars($com['tipo']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($com['status'] === 'pago'): ?>
                                            <span class="status-badge status-approved">
                                                <i class="fas fa-check"></i> Aprovado
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending">
                                                <i class="fas fa-clock"></i> <?php echo $com['status']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-weight: 700; color: var(--success-color);">
                                        R$ <?php echo number_format($com['comissao'] ?? 0, 2, ',', '.'); ?>
                                    </td>
                                    <td style="font-weight: 600;">
                                        R$ <?php echo number_format($com['valor'], 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">
                                    <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
                                    <strong>Nenhuma comissão encontrada.</strong><br>
                                    <small>Suas comissões aparecerão aqui quando disponíveis.</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gráfico de Performance -->
    <div class="grid">
        <div class="card" data-aos="fade-up" data-aos-delay="1300">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-area" style="color: var(--primary-color); margin-right: 12px;"></i>
                    Performance dos Últimos 7 Dias
                </h3>
                <div style="display: flex; gap: 12px;">
                    <span style="display: flex; align-items: center; gap: 6px; font-size: 0.875rem; color: var(--text-light);">
                        <div style="width: 12px; height: 12px; border-radius: 2px; background: #6366f1;"></div>
                        Cadastros
                    </span>
                    <span style="display: flex; align-items: center; gap: 6px; font-size: 0.875rem; color: var(--text-light);">
                        <div style="width: 12px; height: 12px; border-radius: 2px; background: #0ea5e9;"></div>
                        Depósitos
                    </span>
                </div>
            </div>
            <div class="chart-container">
                <div id="performanceChart"></div>
            </div>
        </div>
    </div>
</div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Animate counter values
    function animateValue(element, start = 0, end, duration = 2000) {
        const isPercentage = element.textContent.includes('%');
        const isCurrency = element.textContent.includes('R$');
        
        if (isPercentage) {
            end = parseFloat(element.textContent.replace('%', '').replace(',', '.'));
        } else if (isCurrency) {
            end = parseFloat(element.textContent.replace('R$', '').replace(/\./g, '').replace(',', '.'));
        } else {
            end = parseFloat(element.textContent.replace(/\./g, '')) || parseInt(element.textContent) || 0;
        }
        
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        element.classList.add('loading');
        
        setTimeout(() => {
            element.classList.remove('loading');
            
            const timer = setInterval(() => {
                current += increment;
                
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                
                if (isPercentage) {
                    element.textContent = current.toLocaleString('pt-BR', {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    }) + '%';
                } else if (isCurrency) {
                    element.textContent = 'R$ ' + current.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    element.textContent = Math.floor(current).toLocaleString('pt-BR');
                }
            }, 16);
        }, 500);
    }

    // Intersection Observer for value animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const valueElement = entry.target.querySelector('.value');
                if (valueElement && !valueElement.classList.contains('animated')) {
                    valueElement.classList.add('animated');
                    animateValue(valueElement);
                }
            }
        });
    }, { threshold: 0.5 });

    // Observe all stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        observer.observe(card);
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

    // Performance Chart
    const performanceData = <?php echo json_encode($performanceData); ?>;
    
    const performanceOptions = {
        series: [{
            name: 'Cadastros',
            data: performanceData.cadastros || [12, 19, 3, 5, 2, 3, 8]
        }, {
            name: 'Depósitos Efetivados',
            data: performanceData.depositos || [8, 12, 2, 3, 1, 2, 5]
        }],
        chart: {
            height: 300,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            background: 'transparent',
            foreColor: '#a1a1aa'
        },
        colors: ['#6366f1', '#10b981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        grid: {
            borderColor: '#404040',
            strokeDashArray: 4,
            xaxis: {
                lines: { show: true }
            },
            yaxis: {
                lines: { show: true }
            }
        },
        xaxis: {
            categories: performanceData.dates || ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            axisBorder: { 
                show: false 
            },
            axisTicks: { 
                show: false 
            },
            labels: {
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                },
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark',
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif'
            },
            y: {
                formatter: function(value) {
                    return value + ' unidades';
                }
            }
        },
        markers: {
            size: 0,
            hover: {
                size: 6
            }
        }
    };

    const performanceChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
    performanceChart.render();

    // Add hover sound effect (optional)
    document.querySelectorAll('.stat-card, .btn').forEach(element => {
        element.addEventListener('mouseenter', function() {
            // You can add a subtle sound effect here
            // new Audio('hover.mp3').play().catch(() => {});
        });
    });

    // Auto refresh data every 5 minutes
    setInterval(() => {
        // You can add AJAX calls here to refresh data
        console.log('Refreshing dashboard data...');
    }, 300000);
});
</script>

<style>
/* Additional custom styles for enhanced dashboard */
.stat-card .change {
    margin-top: 16px;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 20px;
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(16, 185, 129, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    animation: fadeInUp 0.6s ease 0.8s both;
}

.stat-card .change.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border-color: rgba(239, 68, 68, 0.2);
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

/* Chart container dark theme */
#performanceChart {
    height: 100%;
}

.apexcharts-canvas {
    background: transparent !important;
}

.apexcharts-gridline {
    stroke: #404040 !important;
}

.apexcharts-text {
    fill: #a1a1aa !important;
}
</style>