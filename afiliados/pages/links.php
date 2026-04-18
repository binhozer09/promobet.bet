<?php
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

$invite_code = getUserInviteCode($user_id);

$visitorCount = getVisitorCount($user_id);

$stats = getUserStats($user_id);
$cadastros = $stats['total_indications'] ?? 0;
$depositantes = $stats['total_depositantes'] ?? 0;

$conversaoPercent = 0;
if ($cadastros > 0) {
    $conversaoPercent = ($depositantes / $cadastros) * 100;
}
?>

<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">
                <i class="fas fa-link" style="color: var(--primary-color); margin-right: 12px;"></i>
                Meus Links
            </h1>
        </div>
        <div class="navbar-right">
            <button class="btn btn-outline" onclick="generateQRCode()">
                <i class="fas fa-qrcode"></i>
                Gerar QR Code
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
                <div class="value"><?php echo $visitorCount; ?></div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +5.2% hoje
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
                <h4>Cadastros Gerados</h4>
                <div class="value"><?php echo $cadastros; ?></div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +3 novos
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Taxa de Conversão</h4>
                <div class="value"><?php echo number_format($conversaoPercent, 1, ',', '.'); ?>%</div>
                <div class="change">
                    <i class="fas fa-chart-line"></i> Performance
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(139, 92, 246, 0.1)); color: var(--accent-purple);">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Depositantes</h4>
                <div class="value"><?php echo $depositantes; ?></div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> Ativos
                </div>
            </div>
        </div>
    </div>

    <!-- Link Principal -->
    <div class="card" data-aos="fade-up" data-aos-delay="500">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-share-alt" style="color: var(--primary-color); margin-right: 12px;"></i>
                Seu Link de Afiliado
            </h3>
            <div style="display: flex; gap: 12px;">
                <button class="btn btn-outline" onclick="shareLink()">
                    <i class="fas fa-share"></i>
                    Compartilhar
                </button>
                <button class="btn btn-outline" onclick="copyToClipboard(document.getElementById('currentLink').textContent, this)">
                    <i class="fas fa-copy"></i>
                    Copiar
                </button>
            </div>
        </div>
        
        <div style="padding: 28px;">
            <!-- Link Display -->
            <div style="background: var(--tertiary-bg); border: 2px solid var(--border-color); border-radius: 16px; padding: 20px; margin-bottom: 24px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--gradient-primary);"></div>
                
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-link" style="font-size: 20px;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; color: var(--text-color); font-size: 1.1rem; font-weight: 700;">Link Principal</h4>
                        <p style="margin: 4px 0 0 0; color: var(--text-light); font-size: 0.9rem;">Compartilhe este link para ganhar comissões</p>
                    </div>
                </div>

                <div style="background: var(--card-bg); border: 1px solid var(--border-light); border-radius: 12px; padding: 16px; font-family: 'Courier New', monospace; font-size: 0.9rem; color: var(--primary-color); word-break: break-all; position: relative;">
                    <span id="currentLink"></span>
                    <button onclick="copyToClipboard(document.getElementById('currentLink').textContent, this)" 
                            style="position: absolute; top: 12px; right: 12px; background: var(--primary-color); border: none; color: white; padding: 8px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-copy" style="font-size: 0.8rem;"></i>
                    </button>
                </div>
            </div>

            <!-- QR Code Container -->
            <div id="qrCodeContainer" style="display: none; text-align: center; background: var(--tertiary-bg); border-radius: 16px; padding: 32px; margin-top: 24px;">
                <h4 style="margin-bottom: 20px; color: var(--text-color);">
                    <i class="fas fa-qrcode" style="margin-right: 8px; color: var(--primary-color);"></i>
                    QR Code do seu Link
                </h4>
                <div style="display: inline-block; padding: 20px; background: white; border-radius: 16px; margin-bottom: 20px;">
                    <canvas id="qrcode"></canvas>
                </div>
                <div>
                    <button onclick="downloadQRCode()" class="btn btn-primary">
                        <i class="fas fa-download"></i>
                        Baixar QR Code
                    </button>
                    <button onclick="document.getElementById('qrCodeContainer').style.display='none'" class="btn btn-outline">
                        <i class="fas fa-times"></i>
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Links -->
    <div class="card" data-aos="fade-up" data-aos-delay="600">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar" style="color: var(--success-color); margin-right: 12px;"></i>
                Performance do Link
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
                        <th><i class="fas fa-link" style="margin-right: 8px; opacity: 0.7;"></i>Link</th>
                        <th><i class="fas fa-mouse-pointer" style="margin-right: 8px; opacity: 0.7;"></i>Cliques</th>
                        <th><i class="fas fa-user-plus" style="margin-right: 8px; opacity: 0.7;"></i>Cadastros</th>
                        <th><i class="fas fa-wallet" style="margin-right: 8px; opacity: 0.7;"></i>Depositantes</th>
                        <th><i class="fas fa-chart-line" style="margin-right: 8px; opacity: 0.7;"></i>Conversões</th>
                        <th><i class="fas fa-cog" style="margin-right: 8px; opacity: 0.7;"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="animation: fadeInUp 0.5s ease forwards;">
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem;">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div>
                                    <span id="currentLinkTable" style="font-family: 'Courier New', monospace; color: var(--primary-color); font-size: 0.85rem; max-width: 300px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                                    <div style="font-size: 0.75rem; color: var(--text-light); margin-top: 2px;">Link Principal</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--primary-color); animation: pulse 2s infinite;"></div>
                                <span style="font-weight: 600; font-size: 1rem;"><?php echo $visitorCount; ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--success-color);"></div>
                                <span style="font-weight: 600; font-size: 1rem;"><?php echo $cadastros; ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--warning-color);"></div>
                                <span style="font-weight: 600; font-size: 1rem;"><?php echo $stats['qtd_depositos']; ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 24px; height: 24px; border-radius: 6px; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-arrow-up" style="font-size: 0.7rem; color: var(--success-color);"></i>
                                </div>
                                <span style="font-weight: 700; color: var(--success-color); font-size: 1rem;">
                                    <?php echo number_format($stats['desempenho'], 1, ',', '.'); ?>%
                                </span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button onclick="copyToClipboard(document.getElementById('currentLink').textContent, this)" class="btn btn-outline" style="padding: 8px 12px;">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button onclick="shareLink()" class="btn btn-outline" style="padding: 8px 12px;">
                                    <i class="fas fa-share"></i>
                                </button>
                                <button onclick="generateQRCode()" class="btn btn-outline" style="padding: 8px 12px;">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = button.querySelector('i');
        const originalClass = icon.className;
        
        icon.className = 'fas fa-check';
        button.style.background = 'var(--success-color)';
        button.style.color = 'white';
        button.style.borderColor = 'var(--success-color)';
        
        setTimeout(() => {
            icon.className = originalClass;
            button.style.background = '';
            button.style.color = '';
            button.style.borderColor = '';
        }, 2000);
        
        // Show success toast
        showToast('Link copiado com sucesso!', 'success');
    }).catch(err => {
        console.error('Erro ao copiar:', err);
        showToast('Erro ao copiar link', 'error');
    });
}

function generateQRCode() {
    const currentLink = document.getElementById('currentLink').textContent;
    const container = document.getElementById('qrCodeContainer');
    const qrcodeElement = document.getElementById('qrcode');

    qrcodeElement.innerHTML = '';
    
    QRCode.toCanvas(qrcodeElement, currentLink, {
        width: 256,
        margin: 2,
        color: {
            dark: '#2e2e31',
            light: '#FFFFFF'
        },
        errorCorrectionLevel: 'M'
    }, function (error) {
        if (error) {
            console.error(error);
            showToast('Erro ao gerar QR Code', 'error');
        } else {
            container.style.display = 'block';
            container.scrollIntoView({ behavior: 'smooth' });
            showToast('QR Code gerado com sucesso!', 'success');
        }
    });
}

function downloadQRCode() {
    const canvas = document.querySelector('#qrcode canvas');
    if (canvas) {
        const link = document.createElement('a');
        link.download = 'qrcode-afiliado.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        showToast('QR Code baixado!', 'success');
    }
}

function shareLink() {
    const currentLink = document.getElementById('currentLink').textContent;
    
    if (navigator.share) {
        navigator.share({
            title: 'Meu Link de Afiliado',
            text: 'Cadastre-se usando meu link e ganhe bônus!',
            url: currentLink
        }).then(() => {
            showToast('Link compartilhado!', 'success');
        }).catch(err => {
            console.error('Erro ao compartilhar:', err);
            copyToClipboard(currentLink, document.createElement('button'));
        });
    } else {
        copyToClipboard(currentLink, document.createElement('button'));
    }
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        font-weight: 600;
        z-index: 10000;
        animation: slideInRight 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 8px;
    `;
    
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100
    });

    // Generate the affiliate link
    const domainAtual = window.location.origin;
    const inviteCodeFromPhp = '<?php echo $invite_code; ?>';
    const finalURL = `${domainAtual}/?id=${inviteCodeFromPhp}&currency=BRL&type=2`;

    // Set the link in all places
    const currentLinkEl = document.getElementById('currentLink');
    const currentLinkTableEl = document.getElementById('currentLinkTable');
    
    if (currentLinkEl) currentLinkEl.textContent = finalURL;
    if (currentLinkTableEl) currentLinkTableEl.textContent = finalURL;

    // Add click effects to interactive cards
    document.querySelectorAll('.interactive-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
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

    // Animate counter values
    const valueElements = document.querySelectorAll('.value');
    valueElements.forEach(element => {
        const finalValue = parseInt(element.textContent) || 0;
        if (finalValue > 0) {
            animateValue(element, 0, finalValue, 1500);
        }
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

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(1.1);
    }
}

/* Enhanced button hover effects */
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

.btn-primary:hover {
    box-shadow: var(--shadow-lg), var(--glow-primary);
}

/* Table row animations */
tbody tr {
    transition: all 0.3s var(--ease-swift);
}

tbody tr:hover {
    background: var(--hover-bg);
    transform: translateX(4px);
}

/* QR Code container animation */
#qrCodeContainer {
    animation: fadeInUp 0.5s ease;
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

/* Link display enhancements */
#currentLink {
    position: relative;
}

#currentLink::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary-color);
    transition: width 0.3s ease;
}

#currentLink:hover::after {
    width: 100%;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .card-header > div {
        width: 100%;
        justify-content: center;
    }
    
    #currentLink {
        font-size: 0.8rem;
        word-break: break-all;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .stat-card .value {
        font-size: 1.5rem;
    }
}
</style>