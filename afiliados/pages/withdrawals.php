<?php
session_start();
global $mysqli;

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<p>Usuário não logado.</p>";
    exit;
}

$saldoDisponivel = 0.0;
$saqueMinimo = 10.00; 

// Usando prepared statement para buscar o saldo
$querySaldo = "SELECT saldo_afiliados FROM usuarios WHERE id = ? LIMIT 1";
$stmt = $mysqli->prepare($querySaldo);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultSaldo = $stmt->get_result();
    
    if ($resultSaldo && $resultSaldo->num_rows > 0) {
        $row = $resultSaldo->fetch_assoc();
        $saldoDisponivel = (float)$row['saldo_afiliados'];
    }
    $stmt->close();
}

$msg = "";
$msgType = "info";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valorSaque = floatval(str_replace(',', '.', $_POST['valor_saque'] ?? '0'));
    $chavePix   = trim($_POST['chave_pix'] ?? '');
    
    if ($valorSaque <= 0) {
        $msg = "Valor de saque inválido.";
        $msgType = "error";
    } elseif ($valorSaque < $saqueMinimo) {
        $msg = "O valor do saque deve ser no mínimo R$ " . number_format($saqueMinimo, 2, ',', '.') . ".";
        $msgType = "error";
    } elseif ($valorSaque > $saldoDisponivel) {
        $msg = "Você não possui saldo suficiente para esse saque.";
        $msgType = "error";
    } else {
        $transacao_id = uniqid('SW');
        $tipo = 'pix';
        $tipo_saque = 1;
        $status = 0;
        $data_cad  = date('Y-m-d');
        $data_hora = date('H:i:s');

        $sqlInsert = "
            INSERT INTO solicitacao_saques 
            (id_user, transacao_id, valor, tipo, pix, data_cad, data_hora, status, tipo_saque)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $mysqli->prepare($sqlInsert);
        $stmt->bind_param(
            "issssssii", 
            $user_id, $transacao_id, $valorSaque, $tipo, $chavePix, $data_cad, $data_hora, $status, $tipo_saque
        );
        
        if ($stmt->execute()) {
            $novoSaldo = $saldoDisponivel - $valorSaque;
            $updSaldo = "UPDATE usuarios SET saldo_afiliados = ? WHERE id = ?";
            $stmtUpd = $mysqli->prepare($updSaldo);
            $stmtUpd->bind_param("di", $novoSaldo, $user_id);
            $stmtUpd->execute();
            $stmtUpd->close();

            $msg = "Saque solicitado com sucesso!";
            $msgType = "success";
            $saldoDisponivel = $novoSaldo;
        } else {
            $msg = "Erro ao solicitar saque: " . $stmt->error;
            $msgType = "error";
        }
        $stmt->close();
    }
}

$historico = [];
$qHist = "SELECT id, transacao_id, valor, tipo, pix, data_cad, data_hora, status
          FROM solicitacao_saques
          WHERE id_user = ? AND tipo_saque = 1
          ORDER BY id DESC";
$stmtHist = $mysqli->prepare($qHist);
$stmtHist->bind_param("i", $user_id);
$stmtHist->execute();
$rHist = $stmtHist->get_result();
if ($rHist && $rHist->num_rows > 0) {
    while ($rowH = $rHist->fetch_assoc()) {
        $historico[] = $rowH;
    }
}
$stmtHist->close();
?>

<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">
                <i class="fas fa-money-bill-wave" style="color: var(--success-color); margin-right: 12px;"></i>
                Saques
            </h1>
        </div>
        <div class="navbar-right">
            <button class="btn btn-outline" onclick="showWithdrawalInfo()">
                <i class="fas fa-info-circle"></i>
                Informações
            </button>
        </div>
    </nav>

    <!-- Stats Overview -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-bottom: 32px;">
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Saldo Disponível</h4>
                <div class="value" style="color: var(--success-color);">
                    R$ <?php echo number_format($saldoDisponivel, 2, ',', '.'); ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> Pronto para saque
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Saques Pendentes</h4>
                <div class="value">
                    <?php 
                    $pendentes = 0;
                    foreach($historico as $h) {
                        if ($h['status'] == 0) $pendentes++;
                    }
                    echo $pendentes;
                    ?>
                </div>
                <div class="change">
                    <i class="fas fa-hourglass-half"></i> Em análise
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: var(--primary-color);">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Total Sacado</h4>
                <div class="value">
                    R$ <?php 
                    $totalSacado = 0;
                    foreach($historico as $h) {
                        if ($h['status'] == 1) $totalSacado += $h['valor'];
                    }
                    echo number_format($totalSacado, 2, ',', '.');
                    ?>
                </div>
                <div class="change">
                    <i class="fas fa-check"></i> Histórico
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(139, 92, 246, 0.1)); color: var(--accent-purple);">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Valor Mínimo</h4>
                <div class="value">R$ <?php echo number_format($saqueMinimo, 2, ',', '.'); ?></div>
                <div class="change">
                    <i class="fas fa-info"></i> Limite mínimo
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Solicitar Saque -->
    <div class="card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 32px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle" style="color: var(--success-color); margin-right: 12px;"></i>
                Solicitar Novo Saque
            </h3>
            <div style="display: flex; align-items: center; gap: 8px; color: var(--text-light);">
                <i class="fas fa-shield-alt" style="color: var(--success-color);"></i>
                <span style="font-size: 0.9rem;">Transação segura</span>
            </div>
        </div>
        
        <div style="padding: 28px;">
            <!-- Saldo Display -->
            <div style="background: var(--tertiary-bg); border: 2px solid var(--border-color); border-radius: 16px; padding: 24px; margin-bottom: 32px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--gradient-success);"></div>
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--gradient-success); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-wallet" style="font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: var(--text-color); font-size: 1.1rem; font-weight: 700;">Saldo Disponível</h4>
                            <p style="margin: 4px 0 0 0; color: var(--text-light); font-size: 0.9rem;">Valor disponível para saque</p>
                        </div>
                    </div>
                    
                    <div style="text-align: right;">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--success-color); line-height: 1;">
                            R$ <?php echo number_format($saldoDisponivel, 2, ',', '.'); ?>
                        </div>
                        <p style="margin: 4px 0 0 0; color: var(--text-light); font-size: 0.85rem;">
                            Mín: R$ <?php echo number_format($saqueMinimo, 2, ',', '.'); ?>
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div style="background: var(--card-bg); border-radius: 10px; height: 8px; overflow: hidden; margin-top: 16px;">
                    <div style="background: var(--gradient-success); height: 100%; width: <?php echo min(100, ($saldoDisponivel / 100) * 100); ?>%; transition: width 0.3s ease;"></div>
                </div>
            </div>

            <!-- Formulário -->
            <form method="POST" action="" id="withdrawalForm">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
                    <!-- Valor do Saque -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-dollar-sign" style="margin-right: 8px; color: var(--primary-color);"></i>
                            Valor do Saque
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="text" 
                                name="valor_saque"
                                id="valor_saque"
                                placeholder="Ex: 100,00"
                                style="width: 100%; padding: 16px 16px 16px 48px; border: 2px solid var(--border-color); border-radius: 12px; background: var(--input-bg); color: var(--text-color); font-size: 1rem; font-weight: 600;"
                                required
                            />
                            <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-weight: 600;">R$</div>
                        </div>
                        <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--text-light);">
                            <i class="fas fa-info-circle" style="margin-right: 4px;"></i>
                            Valor entre R$ <?php echo number_format($saqueMinimo, 2, ',', '.'); ?> e R$ <?php echo number_format($saldoDisponivel, 2, ',', '.'); ?>
                        </p>
                    </div>

                    <!-- Chave PIX -->
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-credit-card" style="margin-right: 8px; color: var(--warning-color);"></i>
                            Chave PIX (CPF)
                        </label>
                        <div style="position: relative;">
                            <input 
                                type="text" 
                                name="chave_pix"
                                id="chave_pix"
                                placeholder="000.000.000-00"
                                style="width: 100%; padding: 16px 16px 16px 48px; border: 2px solid var(--border-color); border-radius: 12px; background: var(--input-bg); color: var(--text-color); font-size: 1rem;"
                                required
                            />
                            <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-light);">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <p style="margin: 8px 0 0 0; font-size: 0.85rem; color: var(--text-light);">
                            <i class="fas fa-shield-alt" style="margin-right: 4px; color: var(--success-color);"></i>
                            Apenas CPF é aceito como chave PIX
                        </p>
                    </div>
                </div>

                <!-- Método de Pagamento -->
                <div style="background: var(--tertiary-bg); border-radius: 12px; padding: 20px; margin-bottom: 32px;">
                    <h4 style="margin: 0 0 16px 0; color: var(--text-color); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-mobile-alt" style="color: var(--primary-color);"></i>
                        Método de Pagamento
                    </h4>
                    <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--card-bg); border: 2px solid var(--primary-color); border-radius: 12px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-qrcode" style="font-size: 20px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h5 style="margin: 0; color: var(--text-color); font-weight: 700;">PIX</h5>
                            <p style="margin: 4px 0 0 0; color: var(--text-light); font-size: 0.9rem;">Transferência instantânea e segura</p>
                        </div>
                        <div style="color: var(--primary-color);">
                            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>

                <!-- Botão Solicitar -->
                <div style="text-align: center;">
                    <button 
                        type="submit" 
                        class="btn btn-primary"
                        style="padding: 16px 48px; font-size: 1.1rem; font-weight: 700; border-radius: 12px; position: relative; overflow: hidden;"
                    >
                        <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>
                        Solicitar Saque
                    </button>
                    <p style="margin: 16px 0 0 0; color: var(--text-light); font-size: 0.9rem;">
                        <i class="fas fa-clock" style="margin-right: 4px;"></i>
                        Processamento em até 24 horas úteis
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Histórico de Saques -->
    <div class="card" data-aos="fade-up" data-aos-delay="600">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history" style="color: var(--primary-color); margin-right: 12px;"></i>
                Histórico de Saques
            </h3>
            <div style="display: flex; gap: 12px;">
                <button class="btn btn-outline">
                    <i class="fas fa-filter"></i>
                    Filtrar
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-download"></i>
                    Exportar
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-calendar" style="margin-right: 8px; opacity: 0.7;"></i>Data</th>
                        <th><i class="fas fa-clock" style="margin-right: 8px; opacity: 0.7;"></i>Hora</th>
                        <th><i class="fas fa-dollar-sign" style="margin-right: 8px; opacity: 0.7;"></i>Valor</th>
                        <th><i class="fas fa-credit-card" style="margin-right: 8px; opacity: 0.7;"></i>Método</th>
                        <th><i class="fas fa-check-circle" style="margin-right: 8px; opacity: 0.7;"></i>Status</th>
                        <th><i class="fas fa-file-download" style="margin-right: 8px; opacity: 0.7;"></i>Comprovante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($historico)): ?>
                        <?php foreach ($historico as $index => $hrow): ?>
                            <?php
                            $statusLabel = 'Pendente';
                            $statusClass = 'status-pending';
                            $statusIcon = 'fa-clock';
                            
                            if ($hrow['status'] == 1) {
                                $statusLabel = 'Pago';
                                $statusClass = 'status-approved';
                                $statusIcon = 'fa-check';
                            } elseif ($hrow['status'] == 2) {
                                $statusLabel = 'Recusado';
                                $statusClass = 'status-rejected';
                                $statusIcon = 'fa-times';
                            }
                            ?>
                            <tr style="animation: fadeInUp 0.5s ease forwards; animation-delay: <?php echo $index * 0.1; ?>s; opacity: 0;">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 8px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.7rem;">
                                            <?php echo date('d', strtotime($hrow['data_cad'])); ?>
                                        </div>
                                        <?php echo date('d/m/Y', strtotime($hrow['data_cad'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--text-light);">
                                        <?php echo date('H:i', strtotime($hrow['data_hora'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; font-size: 1rem; color: var(--success-color);">
                                        R$ <?php echo number_format($hrow['valor'], 2, ',', '.'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 24px; height: 24px; border-radius: 6px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas fa-qrcode" style="font-size: 0.7rem;"></i>
                                        </div>
                                        <span style="font-weight: 600; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($hrow['tipo']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <i class="fas <?php echo $statusIcon; ?>"></i>
                                        <?php echo $statusLabel; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 6px 12px;" <?php echo $hrow['status'] != 1 ? 'disabled' : ''; ?>>
                                        <i class="fas fa-download"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-light);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                    <i class="fas fa-money-bill-wave" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <div>
                                        <strong style="display: block; margin-bottom: 8px; font-size: 1.1rem;">Nenhuma solicitação de saque</strong>
                                        <p style="margin: 0; opacity: 0.7;">Suas solicitações de saque aparecerão aqui.</p>
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

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 10000;"></div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
function showToast(message, type) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    
    const bgColor = type === 'success' ? 'var(--success-color)' : 
                   type === 'error' ? 'var(--danger-color)' : 'var(--info-color)';
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    toast.style.cssText = `
        background: ${bgColor};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        margin-bottom: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 300px;
        box-shadow: var(--shadow-lg);
        animation: slideInRight 0.3s ease, fadeOut 0.3s ease 4.7s forwards;
    `;
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

function showWithdrawalInfo() {
    showToast('Saques são processados em até 24h úteis via PIX', 'info');
}

function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace(".", ",");
    value = value.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
    value = value.replace(/(\d)(\d{3}),/g, "$1.$2,");
    input.value = value;
}

function formatCPF(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})/, '$1-$2');
    input.value = value;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Format inputs
    const valorInput = document.getElementById('valor_saque');
    const cpfInput = document.getElementById('chave_pix');
    
    if (valorInput) {
        valorInput.addEventListener('input', function() {
            formatCurrency(this);
        });
    }
    
    if (cpfInput) {
        cpfInput.addEventListener('input', function() {
            formatCPF(this);
        });
    }

    // Add click effects to interactive cards
    document.querySelectorAll('.interactive-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Form validation
    const form = document.getElementById('withdrawalForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const valor = valorInput.value.replace(/\D/g, '') / 100;
            const cpf = cpfInput.value.replace(/\D/g, '');
            
            if (valor < <?php echo $saqueMinimo; ?>) {
                e.preventDefault();
                showToast('Valor mínimo é R$ <?php echo number_format($saqueMinimo, 2, ",", "."); ?>', 'error');
                return;
            }
            
            if (valor > <?php echo $saldoDisponivel; ?>) {
                e.preventDefault();
                showToast('Saldo insuficiente', 'error');
                return;
            }
            
            if (cpf.length !== 11) {
                e.preventDefault();
                showToast('CPF deve ter 11 dígitos', 'error');
                return;
            }
        });
    }

    // Show toast if there's a message from PHP
    <?php if (!empty($msg)): ?>
        showToast("<?php echo addslashes($msg); ?>", "<?php echo $msgType; ?>");
    <?php endif; ?>

    // Add hover effects to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Table row animations
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.background = 'var(--hover-bg)';
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.background = '';
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>

<style>
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

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

/* Enhanced form styling */
input:focus {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md), 0 0 0 4px var(--input-focus-ring);
}

/* Button enhancements */
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

.btn-primary:hover {
    box-shadow: var(--shadow-lg), var(--glow-primary);
    transform: translateY(-2px) scale(1.02);
}

/* Interactive card effects */
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

/* Table styling */
tbody tr {
    transition: all 0.3s var(--ease-swift);
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

tbody tr:hover {
    background: var(--hover-bg) !important;
    transform: translateX(4px) !important;
}

/* Status badge animations */
.status-badge {
    transition: all 0.3s ease;
}

.status-badge:hover {
    transform: scale(1.05);
}

/* Progress bar animation */
.stat-card:hover .progress-bar {
    animation: progressFill 1s ease-in-out;
}

@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--progress-width, 100%);
    }
}

/* Form container enhancements */
.card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(99, 102, 241, 0.3);
}

/* Input focus effects */
input[type="text"]:focus {
    border-color: var(--primary-color);
    background: var(--card-bg);
}

/* Withdrawal method card */
.card div div div:hover {
    background: var(--hover-bg) !important;
    transform: translateY(-2px);
    transition: all 0.3s var(--ease-swift);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .stat-card .value {
        font-size: 1.5rem;
    }
    
    .card {
        padding: 20px;
    }
    
    .table-responsive {
        font-size: 0.85rem;
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
    
    form > div {
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }
}

@media (max-width: 480px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 16px;
    }
    
    .stat-info .value {
        font-size: 1.25rem;
    }
    
    tbody tr > td:nth-child(n+4) {
        display: none;
    }
    
    thead tr > th:nth-child(n+4) {
        display: none;
    }
    
    .btn {
        padding: 12px 24px;
        font-size: 0.9rem;
    }
}

/* Loading states */
.loading {
    background: linear-gradient(90deg, var(--card-bg) 25%, var(--tertiary-bg) 50%, var(--card-bg) 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 8px;
    color: transparent !important;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Tooltip styles */
.tooltip {
    position: relative;
    cursor: pointer;
}

.tooltip:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: var(--card-bg);
    color: var(--text-color);
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    white-space: nowrap;
    z-index: 1000;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-md);
}

/* Success states */
.success-state {
    animation: successPulse 0.6s ease;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Error states */
.error-state {
    animation: errorShake 0.6s ease;
    border-color: var(--danger-color) !important;
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Custom scrollbar for mobile */
@media (max-width: 768px) {
    .table-responsive {
        scrollbar-width: thin;
        scrollbar-color: var(--border-color) var(--card-bg);
    }
    
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: var(--card-bg);
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--border-color);
        border-radius: 3px;
    }
}
</style>