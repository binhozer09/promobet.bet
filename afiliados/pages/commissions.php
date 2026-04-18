<?php
session_start();
global $mysqli;

$user_id = $_SESSION['user_id'] ?? 0;

$limit = 20;
$page = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($page - 1) * $limit;

$totalCommissions = getTotalCommissionCount($user_id);
$totalPages = ceil($totalCommissions / $limit);

$comHistory = getCommissionHistory($user_id, $limit, $offset);

$currentMonth = date('F Y');
?>

<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">
                <i class="fas fa-coins" style="color: var(--warning-color); margin-right: 12px;"></i>
                Comissões
            </h1>
        </div>
        <div class="navbar-right">
            <a href="index.php?page=withdrawals" style="text-decoration: none;">
                <button class="btn btn-primary">
                    <i class="fas fa-money-bill-wave"></i>
                    Solicitar Saque
                </button>
            </a>
        </div>
    </nav>

    <!-- Stats Overview -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); margin-bottom: 32px;">
        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1)); color: var(--success-color);">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Total de Comissões</h4>
                <div class="value"><?php echo $totalCommissions; ?></div>
                <div class="change">
                    <i class="fas fa-chart-line"></i> Este mês
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1)); color: var(--primary-color);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Valor Total</h4>
                <div class="value">
                    R$ <?php 
                    $totalValue = 0;
                    foreach($comHistory as $com) {
                        $totalValue += $com['comissao'] ?? 0;
                    }
                    echo number_format($totalValue, 2, ',', '.'); 
                    ?>
                </div>
                <div class="change">
                    <i class="fas fa-arrow-up"></i> +12.5%
                </div>
            </div>
        </div>

        <div class="stat-card interactive-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-header">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.1)); color: var(--warning-color);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-info">
                <h4>Período</h4>
                <div class="value" style="font-size: 1.25rem;"><?php echo $currentMonth; ?></div>
                <div class="change">
                    <i class="fas fa-clock"></i> Atual
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Comissões -->
    <div class="card" data-aos="fade-up" data-aos-delay="400">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history" style="color: var(--primary-color); margin-right: 12px;"></i>
                Histórico de Depósitos
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
                        <th><i class="fas fa-user" style="margin-right: 8px; opacity: 0.7;"></i>Referido</th>
                        <th><i class="fas fa-tag" style="margin-right: 8px; opacity: 0.7;"></i>Tipo</th>
                        <th><i class="fas fa-check-circle" style="margin-right: 8px; opacity: 0.7;"></i>Status</th>
                        <th><i class="fas fa-coins" style="margin-right: 8px; opacity: 0.7;"></i>Comissão</th>
                        <th><i class="fas fa-money-bill" style="margin-right: 8px; opacity: 0.7;"></i>Depositado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($comHistory)): ?>
                        <?php foreach ($comHistory as $comRow): ?>
                            <tr style="animation: fadeInUp 0.5s ease forwards;">
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.7rem;">
                                            <?php echo date('d', strtotime($comRow['data_hora'])); ?>
                                        </div>
                                        <?php echo date('d/m/Y', strtotime($comRow['data_hora'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 28px; height: 28px; border-radius: 8px; background: var(--gradient-success); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.7rem;">
                                            <?php echo strtoupper(substr($comRow['referido'], 0, 2)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($comRow['referido']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color); padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; border: 1px solid rgba(99, 102, 241, 0.2);">
                                        <i class="fas fa-credit-card" style="margin-right: 4px;"></i>
                                        <?php echo htmlspecialchars($comRow['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (strtolower($comRow['status']) === 'pago'): ?>
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check"></i> Pago
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i> Pendente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight: 700; color: var(--success-color); font-size: 1rem;">
                                    <i class="fas fa-coins" style="margin-right: 6px; opacity: 0.7;"></i>
                                    R$ <?php echo number_format($comRow['comissao'] ?? 0, 2, ',', '.'); ?>
                                </td>
                                <td style="font-weight: 600; font-size: 1rem;">
                                    <i class="fas fa-money-bill" style="margin-right: 6px; opacity: 0.7; color: var(--primary-color);"></i>
                                    R$ <?php echo number_format($comRow['valor'] ?? 0, 2, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-light);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                                    <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <div>
                                        <strong style="display: block; margin-bottom: 8px; font-size: 1.1rem;">Nenhuma comissão encontrada</strong>
                                        <p style="margin: 0; opacity: 0.7;">Suas comissões aparecerão aqui quando houver depósitos dos seus referidos.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <?php if ($totalPages > 1): ?>
            <div style="margin-top: 32px; display: flex; gap: 8px; justify-content: center; align-items: center; padding: 20px;">
                <?php if ($page > 1): ?>
                    <a href="?pagina=<?= $page - 1 ?>" class="btn btn-outline" style="text-decoration: none;">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                <?php endif; ?>

                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                if ($start > 1): ?>
                    <a href="?pagina=1" class="btn btn-outline" style="text-decoration: none;">1</a>
                    <?php if ($start > 2): ?>
                        <span style="color: var(--text-light); padding: 0 8px;">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?pagina=<?= $i ?>" class="btn <?= $i == $page ? 'btn-primary' : 'btn-outline' ?>" style="text-decoration: none; min-width: 40px;">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <span style="color: var(--text-light); padding: 0 8px;">...</span>
                    <?php endif; ?>
                    <a href="?pagina=<?= $totalPages ?>" class="btn btn-outline" style="text-decoration: none;"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?pagina=<?= $page + 1 ?>" class="btn btn-outline" style="text-decoration: none;">
                        Próxima <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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

    // Add click effects to interactive cards
    document.querySelectorAll('.interactive-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Animate table rows on scroll
    const tableRows = document.querySelectorAll('tbody tr');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = `${index * 100}ms`;
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.1 });

    tableRows.forEach(row => observer.observe(row));

    // Add hover effects to pagination buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
.animate-in {
    animation: slideInFromLeft 0.5s ease forwards;
}

@keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Enhanced table styling */
tbody tr {
    transition: all 0.3s var(--ease-swift);
}

tbody tr:hover {
    background: var(--hover-bg);
    transform: translateX(4px);
}

/* Pagination styling */
.btn {
    transition: all 0.3s var(--ease-swift);
}

.btn:hover {
    box-shadow: var(--shadow-md);
}

.btn-primary {
    box-shadow: var(--glow-primary);
}
</style>