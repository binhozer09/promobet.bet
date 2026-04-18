<?php
session_start();

error_log("=== Iniciando verificação de autenticação e afiliação ===");

if (!isset($_SESSION['user_id'])) {
    error_log("Sessão sem user_id. Redirecionando para login.");
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
error_log("User ID da sessão: " . $user_id);

include '../admin/services/database.php';
include '../admin/services/crud.php';
include '../admin/services/afiliadoController.php';

// Usar prepared statement para maior segurança
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    error_log("Erro na consulta ao banco: " . $mysqli->error);
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($result->num_rows === 0) {
    error_log("Nenhum registro encontrado para o user_id: " . $user_id);
    session_destroy();
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();
error_log("Dados do usuário recuperados: " . print_r($user, true));

// Verificação mais robusta do statusaff
$statusaff = isset($user['statusaff']) ? (int)$user['statusaff'] : 0;
error_log("Valor de statusaff convertido para inteiro: " . $statusaff);

// Se não possui afiliação ativa, exibe página de erro personalizada
if ($statusaff !== 1) {
    error_log("Usuário não possui afiliação ativa. Valor de statusaff: " . $statusaff);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Negado - Painel de Afiliados</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            :root {
                --primary-color: #667eea;
                --primary-dark: #5a67d8;
                --secondary-color: #764ba2;
                --danger-color: #f56565;
                --warning-color: #ed8936;
                --bg-primary: #0f0f0f;
                --bg-secondary: #1a1a1a;
                --bg-tertiary: #262626;
                --text-primary: #ffffff;
                --text-secondary: #a0a0a0;
                --text-tertiary: #666666;
                --border-color: #333333;
                --shadow-color: rgba(0, 0, 0, 0.5);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: var(--bg-primary);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                position: relative;
                overflow-x: hidden;
            }

            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: 
                    radial-gradient(circle at 20% 50%, rgba(245, 101, 101, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(237, 137, 54, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(102, 126, 234, 0.05) 0%, transparent 50%);
                animation: backgroundShift 20s ease-in-out infinite;
                z-index: -1;
            }

            @keyframes backgroundShift {
                0%, 100% { transform: scale(1) rotate(0deg); }
                50% { transform: scale(1.1) rotate(5deg); }
            }

            .error-container {
                width: 100%;
                max-width: 500px;
                margin: 0 auto;
                position: relative;
                z-index: 1;
            }

            .error-card {
                background: var(--bg-secondary);
                border: 1px solid var(--border-color);
                border-radius: 24px;
                box-shadow: 
                    0 20px 40px var(--shadow-color),
                    0 0 0 1px rgba(255, 255, 255, 0.05);
                padding: 3rem 2.5rem;
                width: 100%;
                position: relative;
                backdrop-filter: blur(20px);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                animation: cardEntrance 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                text-align: center;
            }

            @keyframes cardEntrance {
                from {
                    opacity: 0;
                    transform: translateY(40px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .error-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--danger-color), var(--warning-color));
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 2rem;
                animation: iconFloat 3s ease-in-out infinite;
            }

            .error-icon i {
                font-size: 2rem;
                color: white;
            }

            @keyframes iconFloat {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-10px) rotate(5deg); }
            }

            .error-title {
                color: var(--text-primary);
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 1rem;
                line-height: 1.2;
            }

            .error-message {
                color: var(--text-secondary);
                font-size: 1.125rem;
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .error-details {
                background: rgba(245, 101, 101, 0.1);
                border: 1px solid rgba(245, 101, 101, 0.2);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                color: var(--danger-color);
                font-size: 0.9rem;
            }

            .error-details strong {
                display: block;
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }

            .action-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn {
                padding: 1rem 2rem;
                border: none;
                border-radius: 16px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                position: relative;
                overflow: hidden;
                min-width: 150px;
            }

            .btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.6s ease;
            }

            .btn:hover::before {
                left: 100%;
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 
                    0 12px 32px rgba(102, 126, 234, 0.4),
                    0 0 30px rgba(102, 126, 234, 0.3);
            }

            .btn-secondary {
                background: var(--bg-tertiary);
                color: var(--text-secondary);
                border: 2px solid var(--border-color);
            }

            .btn-secondary:hover {
                background: var(--bg-secondary);
                color: var(--text-primary);
                border-color: var(--primary-color);
                transform: translateY(-2px);
            }

            .status-info {
                margin-top: 2rem;
                padding: 1rem;
                background: var(--bg-tertiary);
                border-radius: 12px;
                border-left: 4px solid var(--warning-color);
            }

            .status-info h4 {
                color: var(--warning-color);
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }

            .status-info p {
                color: var(--text-secondary);
                font-size: 0.9rem;
                margin: 0;
            }

            @media (max-width: 640px) {
                .error-card {
                    padding: 2rem 1.5rem;
                }

                .error-title {
                    font-size: 1.75rem;
                }

                .action-buttons {
                    flex-direction: column;
                }

                .btn {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-card">
                <div class="error-icon">
                    <i class="fas fa-ban"></i>
                </div>
                
                <h1 class="error-title">Acesso Negado</h1>
                
                <p class="error-message">
                    Você não possui uma afiliação ativa para acessar o painel de afiliados.
                </p>

                <div class="error-details">
                    <strong>Status da Afiliação:</strong>
                    <?php
                    switch($statusaff) {
                        case 0:
                            echo "Afiliação Inativa - Entre em contato para ativar sua conta";
                            break;
                        case 2:
                            echo "Afiliação Suspensa - Verifique com o suporte";
                            break;
                        case 3:
                            echo "Afiliação Cancelada - Renovação necessária";
                            break;
                        default:
                            echo "Status Desconhecido (Código: $statusaff)";
                    }
                    ?>
                </div>

                <div class="action-buttons">
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar ao Login
                    </a>
                    <a href="mailto:suporte@seusite.com" class="btn btn-primary">
                        <i class="fas fa-envelope"></i>
                        Contatar Suporte
                    </a>
                </div>

                <div class="status-info">
                    <h4><i class="fas fa-info-circle"></i> Informações Adicionais</h4>
                    <p>
                        Para ativar sua afiliação ou resolver problemas de acesso, 
                        entre em contato com nossa equipe de suporte através do botão acima 
                        ou pelo WhatsApp <strong>(XX) XXXXX-XXXX</strong>.
                    </p>
                </div>
            </div>
        </div>

        <script>
            // Animação de entrada dos elementos
            document.addEventListener('DOMContentLoaded', function() {
                const elements = document.querySelectorAll('.error-details, .action-buttons, .status-info');
                elements.forEach((element, index) => {
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }, (index + 1) * 200);
                });

                // Efeito de hover nos botões
                const buttons = document.querySelectorAll('.btn');
                buttons.forEach(button => {
                    button.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-3px) scale(1.02)';
                    });
                    
                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0) scale(1)';
                    });
                });
            });
        </script>
    </body>
    </html>
    <?php
    exit();
}

// Se chegou até aqui, usuário tem afiliação ativa
$stats = getUserStats($user_id);
$performanceData = getPerformanceData($user_id, 7);

// Captura o nome do mobile para exibir no painel
$mobile_name = isset($user['mobile']) ? htmlspecialchars($user['mobile']) : 'Afiliado';

error_log("Usuário autenticado e com afiliação ativa. Acesso permitido.");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Afiliados</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="user-info">
                    <img src="https://p2.trrsf.com/image/fget/cf/1200/1600/middle/images.terra.com/2024/09/10/nft_6633-qhq3yanf8ab8.PNG" alt="Perfil" class="profile-img">
                    <div>
                        <span class="user-role"><?php echo $mobile_name; ?></span>
                    </div>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="index.php?page=dashboard">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="index.php?page=links">
                        <i class="fas fa-link"></i>
                        Meus Links
                    </a>
                </li>
                <li>
                    <a href="index.php?page=referrals">
                        <i class="fas fa-users"></i>
                        Indicações
                    </a>
                </li>
            <!--    <li>
                    <a href="index.php?page=stats">
                        <i class="fas fa-chart-bar"></i>
                        Estatísticas
                    </a>
                </li> -->
                <li>
                    <a href="index.php?page=commissions">
                        <i class="fas fa-dollar-sign"></i>
                        Comissões
                    </a>
                </li>
                <li>
                    <a href="index.php?page=withdrawals">
                        <i class="fas fa-money-bill-wave"></i>
                        Saques
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <div class="page-content">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                $allowed_pages = ['dashboard', 'links', 'referrals', 'stats', 'commissions', 'withdrawals', 'materials', 'support', 'profile'];
                
                if (in_array($page, $allowed_pages)) {
                    include "pages/{$page}.php";
                } else {
                    include "pages/dashboard.php";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = '<?php echo $page; ?>';
        document.querySelectorAll('.list-unstyled li').forEach(item => {
            const link = item.querySelector('a');
            const href = link.getAttribute('href');
            if (href.includes(currentPage)) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    });
    </script>
</body>
</html>