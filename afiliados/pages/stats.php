<?php
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

// Verificar se a conexão com o banco existe
if (!isset($mysqli) || !$mysqli) {
    echo "<div style='padding: 40px; text-align: center; color: #ef4444;'>";
    echo "<h3>Erro de Conexão</h3>";
    echo "<p>Não foi possível conectar com o banco de dados.</p>";
    echo "</div>";
    return;
}

// Inicializar arrays vazios
$cliques = [];
$conversoes = [];
$datas = [];

// Buscar dados de cliques usando id_user (não inviter)
try {
    $queryC = "SELECT DATE(data_cad) as dia, COUNT(*) as total 
               FROM visita_site 
               WHERE id_user = ?
               GROUP BY dia 
               ORDER BY dia DESC
               LIMIT 30";
    $stmtC = $mysqli->prepare($queryC);
    
    if ($stmtC === false) {
        throw new Exception("Erro na preparação da query de cliques: " . $mysqli->error);
    }
    
    $stmtC->bind_param("i", $user_id);
    $stmtC->execute();
    $resC = $stmtC->get_result();
    
    if ($resC) {
        while ($row = $resC->fetch_assoc()) {
            $dia = $row['dia'];
            $datas[$dia] = date('d/m', strtotime($dia));
            $cliques[$dia] = (int)$row['total'];
        }
    }
    $stmtC->close();
} catch (Exception $e) {
    error_log("Erro ao buscar dados de cliques: " . $e->getMessage());
}

// Se não há dados, criar dados de exemplo para demonstração
if (empty($datas)) {
    for ($i = 6; $i >= 0; $i--) {
        $dia = date('Y-m-d', strtotime("-$i days"));
        $datas[$dia] = date('d/m', strtotime($dia));
        $cliques[$dia] = 0; // Dados vazios
        $conversoes[$dia] = 0;
    }
}

// Organizar dados cronologicamente
ksort($datas);
$labels = [];
$cliquesArr = [];
$conversoesArr = [];

foreach ($datas as $dia => $label) {
    $labels[] = $label;
    $cliquesArr[] = $cliques[$dia] ?? 0;
    $conversoesArr[] = $conversoes[$dia] ?? 0;
}

// Dados de dispositivos usando id_user
$deviceCounts = ['Desktop' => 0, 'Mobile' => 0, 'Outros' => 0];
try {
    $queryDevices = "SELECT nav_os, mac_os FROM visita_site WHERE id_user = ?";
    $stmtDevices = $mysqli->prepare($queryDevices);
    
    if ($stmtDevices === false) {
        throw new Exception("Erro na preparação da query de dispositivos: " . $mysqli->error);
    }
    
    $stmtDevices->bind_param("i", $user_id);
    $stmtDevices->execute();
    $resDevices = $stmtDevices->get_result();
    
    if ($resDevices && $resDevices->num_rows > 0) {
        while ($rowD = $resDevices->fetch_assoc()) {
            // Classificação inline de dispositivos
            $nav_os = $rowD['nav_os'] ?? '';
            $mac_os = $rowD['mac_os'] ?? '';
            
            $userAgent = strtolower($nav_os . ' ' . $mac_os);
            
            if (strpos($userAgent, 'iphone') !== false || 
                strpos($userAgent, 'android') !== false ||
                strpos($userAgent, 'handheld') !== false ||
                strpos($mac_os, 'iPhone') !== false) {
                $cat = 'Mobile';
            } elseif (strpos($userAgent, 'windows') !== false || 
                      strpos($userAgent, 'mac') !== false || 
                      strpos($userAgent, 'linux') !== false ||
                      strpos($userAgent, 'chrome') !== false ||
                      strpos($userAgent, 'firefox') !== false ||
                      strpos($userAgent, 'safari') !== false) {
                $cat = 'Desktop';
            } else {
                $cat = 'Outros';
            }
            
            if (isset($deviceCounts[$cat])) {
                $deviceCounts[$cat]++;
            } else {
                $deviceCounts['Outros']++;
            }
        }
    }
    $stmtDevices->close();
} catch (Exception $e) {
    error_log("Erro ao buscar dados de dispositivos: " . $e->getMessage());
}

// Dados de estados usando id_user
$statesCount = [];
try {
    $queryStates = "SELECT estado, COUNT(*) as cnt
                    FROM visita_site
                    WHERE id_user = ? AND estado IS NOT NULL AND estado != ''
                    GROUP BY estado
                    ORDER BY cnt DESC
                    LIMIT 10";
    $stmtStates = $mysqli->prepare($queryStates);
    
    if ($stmtStates === false) {
        throw new Exception("Erro na preparação da query de estados: " . $mysqli->error);
    }
    
    $stmtStates->bind_param("i", $user_id);
    $stmtStates->execute();
    $resStates = $stmtStates->get_result();
    
    if ($resStates && $resStates->num_rows > 0) {
        while ($rowS = $resStates->fetch_assoc()) {
            $st = $rowS['estado'] ?: 'Não Identificado';
            $statesCount[$st] = (int)$rowS['cnt'];
        }
    }
    $stmtStates->close();
} catch (Exception $e) {
    error_log("Erro ao buscar dados de estados: " . $e->getMessage());
}

// Se não há dados de estados, adicionar placeholder
if (empty($statesCount)) {
    $statesCount = ['Sem dados' => 0];
}

// Preparar dados para JavaScript com validação
$jsCliques = json_encode(array_values($cliquesArr));
$jsConversoes = json_encode(array_values($conversoesArr));
$jsDatas = json_encode(array_values($labels));

$jsDeviceLabels = json_encode(array_keys($deviceCounts));
$jsDeviceValues = json_encode(array_values($deviceCounts));

$jsStateLabels = json_encode(array_keys($statesCount));
$jsStateValues = json_encode(array_values($statesCount));

$currentMonth = date('F Y');

// Calcular métricas com validação
$totalCliques = array_sum($cliquesArr);
$totalConversoes = array_sum($conversoesArr);
$totalDevices = array_sum($deviceCounts);
$totalStates = count($statesCount);

// Validação segura para max()
$maxCliques = !empty($cliquesArr) && max($cliquesArr) > 0 ? max($cliquesArr) : 0;
$bestDayIndex = !empty($cliquesArr) && $maxCliques > 0 ? array_search($maxCliques, $cliquesArr) : false;
$mobilePercent = $totalDevices > 0 ? ($deviceCounts['Mobile'] / $totalDevices) * 100 : 0;
$taxaConversao = $totalCliques > 0 ? ($totalConversoes / $totalCliques) * 100 : 0;

// Debug: mostrar dados encontrados (remover em produção)
// echo "<!-- Debug: Total cliques: $totalCliques, Total devices: $totalDevices, Total states: $totalStates -->";
?>

<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">
                <i class="fas fa-chart-line" style="color: var(--primary-color); margin-right: 12px;"></i>
                Estatísticas
            </h1>
        </div>
        <div class="navbar-right">
            <select style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); margin-right: 12px;">
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
                <option>Últimos 90 dias</option>
            </select>
            <button class="btn btn-outline">
                <i class="fas fa-sync"></i>
                Atualizar
            </button>
        </div>
    </nav>

    <!-- Stats Overview -->
    <div class="grid" style="margin-bottom: 32px;">
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: var(--primary-color);">
                    <i class="fas fa-mouse-pointer"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Total de Cliques</h4>
                <div class="value" data-target="<?php echo $totalCliques; ?>"><?php echo $totalCliques; ?></div>
                <div class="change">
                    <i class="fas fa-chart-line"></i> Últimos 7 dias
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Conversões</h4>
                <div class="value" data-target="<?php echo $totalConversoes; ?>"><?php echo $totalConversoes; ?></div>
                <div class="change">
                    <i class="fas fa-percentage"></i> <?php echo number_format($taxaConversao, 1, ',', '.'); ?>% de taxa
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-mobile-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Tráfego Mobile</h4>
                <div class="value" data-target="<?php echo $deviceCounts['Mobile']; ?>"><?php echo $deviceCounts['Mobile']; ?></div>
                <div class="change">
                    <i class="fas fa-percentage"></i> <?php echo number_format($mobilePercent, 1, ',', '.'); ?>% do total
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(139, 92, 246, 0.1)); color: var(--accent-purple);">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Estados Únicos</h4>
                <div class="value" data-target="<?php echo $totalStates; ?>"><?php echo $totalStates; ?></div>
                <div class="change">
                    <i class="fas fa-globe"></i> Alcance geográfico
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico Principal -->
    <div class="card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 32px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-area" style="color: var(--primary-color); margin-right: 12px;"></i>
                Visão Geral de Performance
            </h3>
            <div style="display: flex; gap: 12px;">
                <span style="display: flex; align-items: center; gap: 6px; font-size: 0.875rem; color: var(--text-light);">
                    <div style="width: 12px; height: 12px; border-radius: 2px; background: #6366f1;"></div>
                    Cliques
                </span>
                <span style="display: flex; align-items: center; gap: 6px; font-size: 0.875rem; color: var(--text-light);">
                    <div style="width: 12px; height: 12px; border-radius: 2px; background: #10b981;"></div>
                    Conversões
                </span>
            </div>
        </div>
        <div class="chart-container" style="height: 400px; padding: 20px;">
            <div id="mainChart" style="height: 100%; width: 100%;"></div>
        </div>
    </div>

    <!-- Estatísticas Detalhadas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 28px; margin-bottom: 32px;">
        <!-- Donut: Dispositivo -->
        <div class="card" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-desktop" style="color: var(--success-color); margin-right: 12px;"></i>
                    Origem do Tráfego
                </h3>
                <div style="display: flex; gap: 8px;">
                    <?php foreach($deviceCounts as $device => $count): ?>
                        <span style="font-size: 0.8rem; color: var(--text-light); background: var(--tertiary-bg); padding: 4px 8px; border-radius: 6px;">
                            <?php echo $device; ?>: <?php echo $count; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div style="height: 300px; padding: 20px;">
                <div id="trafficChart" style="height: 100%; width: 100%;"></div>
            </div>
        </div>

        <!-- Barras: Estado -->
        <div class="card" data-aos="fade-up" data-aos-delay="700">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map" style="color: var(--warning-color); margin-right: 12px;"></i>
                    Visitas por Estado
                </h3>
                <div style="display: flex; gap: 8px;">
                    <span style="font-size: 0.8rem; color: var(--text-light); background: var(--tertiary-bg); padding: 4px 8px; border-radius: 6px;">
                        <?php echo count($statesCount); ?> estados
                    </span>
                </div>
            </div>
            <div style="height: 300px; padding: 20px;">
                <div id="conversionsChart" style="height: 100%; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Performance Insights -->
    <div class="card" data-aos="fade-up" data-aos-delay="800">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-lightbulb" style="color: var(--warning-color); margin-right: 12px;"></i>
                Insights de Performance
            </h3>
        </div>
        
        <div style="padding: 28px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
                <!-- Melhor Dia -->
                <div style="padding: 20px; background: var(--tertiary-bg); border-radius: 12px; border-left: 4px solid var(--success-color);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="fas fa-trophy" style="color: var(--success-color); font-size: 1.2rem;"></i>
                        <h4 style="margin: 0; color: var(--text-color);">Melhor Dia</h4>
                    </div>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        <?php 
                        if ($bestDayIndex !== false && $maxCliques > 0) {
                            echo $labels[$bestDayIndex] . ' com ' . $maxCliques . ' cliques';
                        } else {
                            echo 'Sem dados suficientes';
                        }
                        ?>
                    </p>
                </div>

                <!-- Taxa de Conversão -->
                <div style="padding: 20px; background: var(--tertiary-bg); border-radius: 12px; border-left: 4px solid var(--primary-color);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="fas fa-percentage" style="color: var(--primary-color); font-size: 1.2rem;"></i>
                        <h4 style="margin: 0; color: var(--text-color);">Taxa de Conversão</h4>
                    </div>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        <?php echo number_format($taxaConversao, 2, ',', '.'); ?>% dos visitantes convertem
                    </p>
                </div>

                <!-- Dispositivo Preferido -->
                <div style="padding: 20px; background: var(--tertiary-bg); border-radius: 12px; border-left: 4px solid var(--warning-color);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="fas fa-mobile-alt" style="color: var(--warning-color); font-size: 1.2rem;"></i>
                        <h4 style="margin: 0; color: var(--text-color);">Dispositivo Top</h4>
                    </div>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        <?php 
                        $maxDeviceCount = max($deviceCounts);
                        if ($maxDeviceCount > 0) {
                            $topDevice = array_search($maxDeviceCount, $deviceCounts);
                            echo $topDevice . ' representa a maioria dos acessos';
                        } else {
                            echo 'Dados insuficientes';
                        }
                        ?>
                    </p>
                </div>

                <!-- Estado Top -->
                <div style="padding: 20px; background: var(--tertiary-bg); border-radius: 12px; border-left: 4px solid var(--danger-color);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="fas fa-map-pin" style="color: var(--danger-color); font-size: 1.2rem;"></i>
                        <h4 style="margin: 0; color: var(--text-color);">Estado Top</h4>
                    </div>
                    <p style="margin: 0; color: var(--text-light); font-size: 0.9rem;">
                        <?php 
                        if (!empty($statesCount) && max($statesCount) > 0) {
                            $maxStateCount = max($statesCount);
                            $topState = array_search($maxStateCount, $statesCount);
                            echo $topState . ' com ' . $maxStateCount . ' visitas';
                        } else {
                            echo 'Dados insuficientes';
                        }
                        ?>
                    </p>
                </div>
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
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Data from PHP
    const cliques = <?php echo $jsCliques; ?>;
    const conversoes = <?php echo $jsConversoes; ?>;
    const datas = <?php echo $jsDatas; ?>;
    const deviceLabels = <?php echo $jsDeviceLabels; ?>;
    const deviceValues = <?php echo $jsDeviceValues; ?>;
    const stateLabels = <?php echo $jsStateLabels; ?>;
    const stateValues = <?php echo $jsStateValues; ?>;

    console.log('Dados carregados:', {
        cliques,
        conversoes,
        datas,
        deviceLabels,
        deviceValues,
        stateLabels,
        stateValues
    });

    // Main Chart Configuration
    const mainChartOptions = {
        series: [{
            name: 'Cliques',
            data: cliques,
            type: 'area'
        }, {
            name: 'Conversões',
            data: conversoes,
            type: 'line'
        }],
        chart: {
            height: '100%',
            type: 'line',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            background: 'transparent',
            foreColor: '#a1a1aa',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#6366f1', '#10b981'],
        fill: {
            type: ['gradient', 'solid'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: [0, 3]
        },
        grid: {
            borderColor: '#404040',
            strokeDashArray: 4,
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        },
        xaxis: {
            categories: datas,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px'
                }
            }
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(value) {
                    return value + ' unidades';
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        noData: {
            text: 'Sem dados para exibir',
            align: 'center',
            verticalAlign: 'middle',
            offsetX: 0,
            offsetY: 0,
            style: {
                color: '#a1a1aa',
                fontSize: '14px'
            }
        }
    };

    // Traffic Chart (Donut) Configuration
    const trafficChartOptions = {
        series: deviceValues,
        chart: {
            type: 'donut',
            height: '100%',
            fontFamily: 'Inter, sans-serif',
            foreColor: '#a1a1aa',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: deviceLabels,
        colors: ['#6366f1', '#10b981', '#f59e0b'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            color: '#ffffff',
                            fontSize: '16px',
                            fontWeight: 600,
                            formatter: function(w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return total;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '12px'
            },
            formatter: function(val, opts) {
                return opts.w.config.series[opts.seriesIndex];
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: {
                colors: '#a1a1aa'
            },
            markers: {
                width: 8,
                height: 8
            }
        },
        tooltip: {
            theme: 'dark'
        },
        noData: {
            text: 'Sem dados para exibir',
            align: 'center',
            verticalAlign: 'middle',
            style: {
                color: '#a1a1aa',
                fontSize: '14px'
            }
        }
    };

    // States Chart (Bar) Configuration
    const conversionsChartOptions = {
        series: [{
            name: 'Visitas',
            data: stateValues
        }],
        chart: {
            type: 'bar',
            height: '100%',
            fontFamily: 'Inter, sans-serif',
            background: 'transparent',
            foreColor: '#a1a1aa',
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#f59e0b'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                barHeight: '60%'
            }
        },
        dataLabels: { 
            enabled: false 
        },
        xaxis: {
            categories: stateLabels,
            labels: {
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#a1a1aa',
                    fontSize: '12px'
                }
            }
        },
        grid: {
            borderColor: '#404040',
            strokeDashArray: 4,
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(value) {
                    return value + ' visitas';
                }
            }
        },
        noData: {
            text: 'Sem dados para exibir',
            align: 'center',
            verticalAlign: 'middle',
            style: {
                color: '#a1a1aa',
                fontSize: '14px'
            }
        }
    };

    // Render Charts
    try {
        console.log('Renderizando gráfico principal...');
        const mainChart = new ApexCharts(document.querySelector("#mainChart"), mainChartOptions);
        mainChart.render();

        console.log('Renderizando gráfico de tráfego...');
        const trafficChart = new ApexCharts(document.querySelector("#trafficChart"), trafficChartOptions);
        trafficChart.render();

        console.log('Renderizando gráfico de estados...');
        const conversionsChart = new ApexCharts(document.querySelector("#conversionsChart"), conversionsChartOptions);
        conversionsChart.render();

        console.log('Todos os gráficos renderizados com sucesso!');
    } catch (error) {
        console.error('Erro ao renderizar gráficos:', error);
    }

    // Animate counter values
    const valueElements = document.querySelectorAll('.value[data-target]');
    valueElements.forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        if (target >= 0) {
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
});

function animateValue(element, start, end, duration) {
    if (end === 0) {
        element.textContent = '0';
        return;
    }
    
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
/* Chart containers styling */
.chart-container {
    background: transparent;
    border-radius: 12px;
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

.apexcharts-legend-text {
    color: #a1a1aa !important;
}

/* Enhanced card hover effects */
.card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(99, 102, 241, 0.3);
}

/* Interactive elements */
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

/* Insights cards hover */
.card div div div:hover {
    transform: translateY(-2px);
    transition: all 0.3s var(--ease-swift);
}

/* Loading state for charts */
#mainChart, #trafficChart, #conversionsChart {
    position: relative;
}

#mainChart::before,
#trafficChart::before,
#conversionsChart::before {
    content: 'Carregando...';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: var(--text-light);
    font-size: 0.9rem;
    z-index: -1;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .chart-container {
        height: 300px !important;
    }
    
    .grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .navbar-right {
        flex-direction: column;
        gap: 8px;
    }
    
    .card-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .dashboard > div:nth-child(4) {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card .value {
        font-size: 1.5rem;
    }
    
    .dashboard > div:nth-child(4) {
        grid-template-columns: 1fr;
    }
    
    .card {
        margin-bottom: 20px;
    }
}

/* Empty state styling */
.empty-state {
    text-align: center;
    padding: 40px;
    color: var(--text-light);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.3;
    margin-bottom: 16px;
    display: block;
}

/* No data message for charts */
.no-data-message {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: var(--text-light);
    z-index: 10;
}

.no-data-message i {
    font-size: 2rem;
    opacity: 0.3;
    margin-bottom: 12px;
    display: block;
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
    transform: translateY(-2px);
}

/* Enhanced table styling if needed */
.stats-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.stats-table th,
.stats-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.stats-table th {
    background: var(--tertiary-bg);
    color: var(--text-light);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-table td {
    color: var(--text-color);
    font-weight: 500;
}

.stats-table tr:hover {
    background: var(--hover-bg);
}

/* Metric badges */
.metric-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--tertiary-bg);
    color: var(--text-light);
    border: 1px solid var(--border-color);
}

.metric-badge.positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    border-color: rgba(16, 185, 129, 0.2);
}

.metric-badge.negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    border-color: rgba(239, 68, 68, 0.2);
}

.metric-badge.neutral {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
    border-color: rgba(107, 114, 128, 0.2);
}

/* Progress indicators */
.progress-indicator {
    width: 100%;
    height: 6px;
    background: var(--tertiary-bg);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 8px;
}

.progress-indicator .progress-bar {
    height: 100%;
    background: var(--gradient-primary);
    border-radius: 3px;
    transition: width 0.8s ease;
}

/* Animation for fade-in elements */
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

.fade-in {
    animation: fadeInUp 0.6s ease forwards;
}

/* Custom scrollbar */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: var(--border-color) var(--card-bg);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: var(--card-bg);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--primary-color);
}

/* Print styles */
@media print {
    .navbar-right,
    .btn {
        display: none !important;
    }
    
    .card,
    .stat-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ccc;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .chart-container {
        height: 200px !important;
    }
}
</style>