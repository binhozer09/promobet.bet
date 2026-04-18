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
//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

# Função para buscar os dados atuais da tabela config
function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

# Função para atualizar os dados da tabela config
function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        facebookads = ?, 
        googleAnalytics = ?
        WHERE id = 1");

    $qry->bind_param(
        "ss",
        $data['facebookads'],
        $data['googleads']
    );
    return $qry->execute();
}

# Inicializar variáveis para toast
$toastType = '';
$toastMessage = '';

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'googleads' => trim($_POST['googleads']),
        'facebookads' => trim($_POST['facebookads']),
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações de pixel atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

# Buscar os dados atuais
$config = get_afiliados_config();
$modo_plataforma = $modo_plataforma ?? 0; // Definir default se não existir
?>

<head>
    <?php $title = "Configurações de Pixels de Rastreamento";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .pixel-card {
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .pixel-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, #4285f4 0%, #ea4335 25%, #fbbc05 50%, #34a853 75%, #4285f4 100%);
        }
        
        .pixel-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.2);
        }
        
        .platform-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        
        .platform-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: inherit;
            border-radius: inherit;
            filter: blur(20px);
            opacity: 0.3;
            transform: scale(1.2);
        }
        
        .google-icon {
            background: linear-gradient(135deg, #4285f4 0%, #34a853 50%, #ea4335 100%);
            box-shadow: 0 15px 40px rgba(66, 133, 244, 0.4);
        }
        
        .facebook-icon {
            background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);
            box-shadow: 0 15px 40px rgba(24, 119, 242, 0.4);
        }
        
        .pixel-input {
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 20px 24px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
            color: white;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            letter-spacing: 0.5px;
        }
        
        .pixel-input::placeholder {
            color: rgba(255,255,255,0.5);
            font-family: inherit;
        }
        
        .pixel-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            background: rgba(255,255,255,0.08);
            transform: scale(1.02);
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .section-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
        }
        
        .save-button {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 20px;
            padding: 20px 64px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            transition: all 0.4s ease;
            box-shadow: 0 10px 35px rgba(86, 171, 47, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .save-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        
        .save-button:hover::before {
            left: 100%;
        }
        
        .save-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 50px rgba(86, 171, 47, 0.5);
        }
        
        .save-button:disabled {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
        }
        
        .demo-warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            border: none;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            color: #2d3436;
        }
        
        .pixel-info {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 20px;
            margin-top: 16px;
        }
        
        .pixel-status {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 12px;
        }
        
        .status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .status-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .pixel-preview {
            background: rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
            overflow-x: auto;
        }
        
        .analytics-overview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 32px;
            color: white;
            margin-bottom: 40px;
        }
        
        .stat-item {
            text-align: center;
            padding: 16px;
        }
        
        .stat-item i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: 0.9;
        }
        
        .tips-container {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 6px solid #2196f3;
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            color: #1565c0;
        }
        
        .tips-container h6 {
            color: #0d47a1;
            margin-bottom: 16px;
        }
        
        .tips-list {
            margin: 0;
            padding-left: 24px;
        }
        
        .tips-list li {
            margin-bottom: 12px;
            line-height: 1.6;
        }
        
        .code-snippet {
            background: #1e1e1e;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 16px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85rem;
            color: #d4d4d4;
            overflow-x: auto;
            margin-top: 12px;
        }
        
        .validation-message {
            padding: 12px 16px;
            border-radius: 12px;
            margin-top: 12px;
            font-size: 0.9rem;
            display: none;
        }
        
        .validation-success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .validation-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pixel-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .pixel-card:nth-child(1) { animation-delay: 0.2s; }
        .pixel-card:nth-child(2) { animation-delay: 0.4s; }
        
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    
                    <!-- Header Principal -->
                    <div class="col-12">
                        <div class="section-header">
                            <div class="floating-particles">
                                <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
                                <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
                                <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
                                <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
                                <div class="particle" style="left: 60%; animation-delay: 4s;"></div>
                                <div class="particle" style="left: 70%; animation-delay: 5s;"></div>
                                <div class="particle" style="left: 80%; animation-delay: 2s;"></div>
                                <div class="particle" style="left: 90%; animation-delay: 1s;"></div>
                            </div>
                            <h3 class="mb-3">
                                <i class="iconoir-analytics me-3"></i>Analytics & Tracking
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Configure pixels de rastreamento para otimizar campanhas e medir conversões
                            </p>
                        </div>
                    </div>

                    <!-- Overview Analytics -->
                    <div class="col-12">
                        <div class="analytics-overview">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-google"></i>
                                        <h6 class="mb-1">Google Ads</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php if (!empty($config['googleAnalytics'])): ?>
                                                <span class="status-active">✓ Configurado</span>
                                            <?php else: ?>
                                                <span class="status-inactive">✗ Não configurado</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-facebook"></i>
                                        <h6 class="mb-1">Facebook Ads</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php if (!empty($config['facebookads'])): ?>
                                                <span class="status-active">✓ Configurado</span>
                                            <?php else: ?>
                                                <span class="status-inactive">✗ Não configurado</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-eye"></i>
                                        <h6 class="mb-1">Tracking Status</h6>
                                        <p class="mb-0 opacity-75">
                                            <?php 
                                            $total_configured = (!empty($config['googleAnalytics']) ? 1 : 0) + (!empty($config['facebookads']) ? 1 : 0);
                                            echo $total_configured . "/2 Plataformas";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item">
                                        <i class="iconoir-shield-check"></i>
                                        <h6 class="mb-1">Compliance</h6>
                                        <p class="mb-0 opacity-75">LGPD Ready</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dicas Importantes -->
                    <div class="col-12">
                        <div class="tips-container">
                            <h6><i class="iconoir-light-bulb me-2"></i>Dicas Importantes para Pixels</h6>
                            <ul class="tips-list">
                                <li><strong>Google Ads:</strong> Use o ID de conversão (formato: AW-XXXXXXXXX/YYYYYY)</li>
                                <li><strong>Facebook Pixel:</strong> Use apenas o ID numérico (ex: 1234567890123456)</li>
                                <li><strong>Teste:</strong> Use ferramentas como Facebook Pixel Helper para validar</li>
                                <li><strong>LGPD:</strong> Certifique-se de ter consentimento do usuário antes de ativar</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Verificar se está em modo demo -->
                    <?php if ($modo_plataforma == 0): ?>
                    <div class="col-12">
                        <div class="demo-warning">
                            <div class="d-flex align-items-center">
                                <i class="iconoir-warning-triangle h3 me-3 mb-0" style="color: #e17055;"></i>
                                <div>
                                    <h6 class="mb-1" style="color: #2d3436;">Modo Demonstração Ativo</h6>
                                    <p class="mb-0 opacity-75">Os pixels não podem ser alterados no modo demo por segurança.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" id="pixelForm">
                            <div class="row">
                                
                                <!-- Google Ads -->
                                <div class="col-lg-6 mb-5">
                                    <div class="pixel-card p-5 h-100">
                                        <div class="platform-icon google-icon mx-auto">
                                            <i class="iconoir-google" style="position: relative; z-index: 1;"></i>
                                        </div>
                                        
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold mb-2">Google Ads</h4>
                                            <p class="text-light opacity-75 mb-0">
                                                Configure o pixel do Google Ads para rastrear conversões e otimizar campanhas
                                            </p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-medium mb-3">
                                                <i class="iconoir-code me-2"></i>ID de Conversão do Google Ads
                                            </label>
                                            <input type="text" name="googleads" class="form-control pixel-input" 
                                                   value="<?= htmlspecialchars($config['googleAnalytics']); ?>" 
                                                   placeholder="AW-XXXXXXXXX/YYYYYY"
                                                   data-platform="google">
                                            
                                            <div class="validation-message validation-error" id="google-error">
                                                <i class="iconoir-warning-triangle me-2"></i>
                                                Formato inválido. Use o formato: AW-XXXXXXXXX/YYYYYY
                                            </div>
                                            
                                            <div class="validation-message validation-success" id="google-success">
                                                <i class="iconoir-check-circle me-2"></i>
                                                ID do Google Ads válido!
                                            </div>
                                        </div>

                                        <div class="pixel-info">
                                            <h6 class="mb-3">
                                                <i class="iconoir-info-circle me-2"></i>Como encontrar seu ID
                                            </h6>
                                            <ol class="mb-3 small opacity-75">
                                                <li>Acesse o Google Ads</li>
                                                <li>Vá em Ferramentas → Conversões</li>
                                                <li>Copie o ID da ação de conversão</li>
                                            </ol>
                                            
                                            <div class="pixel-status <?= !empty($config['googleAnalytics']) ? 'status-active' : 'status-inactive'; ?>">
                                                <i class="iconoir-<?= !empty($config['googleAnalytics']) ? 'check-circle' : 'cancel'; ?> me-2"></i>
                                                <?= !empty($config['googleAnalytics']) ? 'Ativo' : 'Inativo'; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($config['googleAnalytics'])): ?>
                                        <div class="pixel-preview">
                                            <strong>Preview do código:</strong>
                                            <div class="code-snippet">
&lt;script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($config['googleAnalytics']); ?>"&gt;&lt;/script&gt;
&lt;script&gt;
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('config', '<?= htmlspecialchars($config['googleAnalytics']); ?>');
&lt;/script&gt;
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Facebook Ads -->
                                <div class="col-lg-6 mb-5">
                                    <div class="pixel-card p-5 h-100">
                                        <div class="platform-icon facebook-icon mx-auto">
                                            <i class="iconoir-facebook" style="position: relative; z-index: 1;"></i>
                                        </div>
                                        
                                        <div class="text-center mb-4">
                                            <h4 class="fw-bold mb-2">Facebook Ads</h4>
                                            <p class="text-light opacity-75 mb-0">
                                                Configure o Facebook Pixel para rastrear eventos e criar públicos personalizados
                                            </p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-medium mb-3">
                                                <i class="iconoir-code me-2"></i>ID do Facebook Pixel
                                            </label>
                                            <input type="text" name="facebookads" class="form-control pixel-input" 
                                                   value="<?= htmlspecialchars($config['facebookads']); ?>" 
                                                   placeholder="1234567890123456"
                                                   data-platform="facebook">
                                            
                                            <div class="validation-message validation-error" id="facebook-error">
                                                <i class="iconoir-warning-triangle me-2"></i>
                                                Formato inválido. Use apenas números (15-16 dígitos)
                                            </div>
                                            
                                            <div class="validation-message validation-success" id="facebook-success">
                                                <i class="iconoir-check-circle me-2"></i>
                                                ID do Facebook Pixel válido!
                                            </div>
                                        </div>

                                        <div class="pixel-info">
                                            <h6 class="mb-3">
                                                <i class="iconoir-info-circle me-2"></i>Como encontrar seu ID
                                            </h6>
                                            <ol class="mb-3 small opacity-75">
                                                <li>Acesse o Facebook Business Manager</li>
                                                <li>Vá em Eventos → Pixels</li>
                                                <li>Copie o ID do pixel (apenas números)</li>
                                            </ol>
                                            
                                            <div class="pixel-status <?= !empty($config['facebookads']) ? 'status-active' : 'status-inactive'; ?>">
                                                <i class="iconoir-<?= !empty($config['facebookads']) ? 'check-circle' : 'cancel'; ?> me-2"></i>
                                                <?= !empty($config['facebookads']) ? 'Ativo' : 'Inativo'; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($config['facebookads'])): ?>
                                        <div class="pixel-preview">
                                            <strong>Preview do código:</strong>
                                            <div class="code-snippet">
&lt;script&gt;
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?= htmlspecialchars($config['facebookads']); ?>');
fbq('track', 'PageView');
&lt;/script&gt;
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Salvar -->
                            <div class="text-center mt-5">
                                <?php if ($modo_plataforma == 0): ?>
                                    <button type="button" class="save-button" disabled>
                                        <i class="iconoir-lock me-3"></i>Salvar Configurações (MODO DEMO ATIVADO)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="save-button">
                                        <i class="iconoir-check me-3"></i>Salvar Configurações
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Scripts customizados -->
    <script>
        // Função de Toast
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

            setTimeout(function() {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Validação em tempo real dos pixels
        function setupPixelValidation() {
            const googleInput = document.querySelector('input[name="googleads"]');
            const facebookInput = document.querySelector('input[name="facebookads"]');

            // Regex patterns
            const googlePattern = /^AW-\d{8,10}\/[A-Za-z0-9_-]+$/;
            const facebookPattern = /^\d{15,16}$/;

            function validateInput(input, pattern, successId, errorId) {
                const value = input.value.trim();
                const successEl = document.getElementById(successId);
                const errorEl = document.getElementById(errorId);

                if (value === '') {
                    successEl.style.display = 'none';
                    errorEl.style.display = 'none';
                    input.style.borderColor = 'rgba(255,255,255,0.1)';
                    return true; // Vazio é válido
                } else if (pattern.test(value)) {
                    successEl.style.display = 'block';
                    errorEl.style.display = 'none';
                    input.style.borderColor = '#22c55e';
                    input.style.background = 'rgba(34, 197, 94, 0.1)';
                    return true;
                } else {
                    successEl.style.display = 'none';
                    errorEl.style.display = 'block';
                    input.style.borderColor = '#ef4444';
                    input.style.background = 'rgba(239, 68, 68, 0.1)';
                    return false;
                }
            }

            googleInput.addEventListener('input', function() {
                validateInput(this, googlePattern, 'google-success', 'google-error');
                updatePreview('google', this.value);
            });

            facebookInput.addEventListener('input', function() {
                validateInput(this, facebookPattern, 'facebook-success', 'facebook-error');
                updatePreview('facebook', this.value);
            });

            // Validação inicial
            validateInput(googleInput, googlePattern, 'google-success', 'google-error');
            validateInput(facebookInput, facebookPattern, 'facebook-success', 'facebook-error');
        }

        // Atualizar preview do código em tempo real
        function updatePreview(platform, value) {
            const card = document.querySelector(`input[data-platform="${platform}"]`).closest('.pixel-card');
            let previewEl = card.querySelector('.pixel-preview');

            if (value.trim() === '') {
                if (previewEl) {
                    previewEl.remove();
                }
                return;
            }

            if (!previewEl) {
                previewEl = document.createElement('div');
                previewEl.className = 'pixel-preview';
                card.appendChild(previewEl);
            }

            let codeContent = '';
            if (platform === 'google') {
                codeContent = `
&lt;script async src="https://www.googletagmanager.com/gtag/js?id=${value}"&gt;&lt;/script&gt;
&lt;script&gt;
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('config', '${value}');
&lt;/script&gt;`;
            } else if (platform === 'facebook') {
                codeContent = `
&lt;script&gt;
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '${value}');
fbq('track', 'PageView');
&lt;/script&gt;`;
            }

            previewEl.innerHTML = `
                <strong>Preview do código:</strong>
                <div class="code-snippet">${codeContent}</div>
            `;
        }

        // Atualizar status em tempo real
        function updateStatus() {
            const googleInput = document.querySelector('input[name="googleads"]');
            const facebookInput = document.querySelector('input[name="facebookads"]');
            
            // Atualizar status visual nos cards
            updateCardStatus('google', googleInput.value.trim());
            updateCardStatus('facebook', facebookInput.value.trim());
            
            // Atualizar overview
            updateOverview();
        }

        function updateCardStatus(platform, value) {
            const card = document.querySelector(`input[data-platform="${platform}"]`).closest('.pixel-card');
            const statusEl = card.querySelector('.pixel-status');
            
            if (value !== '') {
                statusEl.className = 'pixel-status status-active';
                statusEl.innerHTML = '<i class="iconoir-check-circle me-2"></i>Ativo';
            } else {
                statusEl.className = 'pixel-status status-inactive';
                statusEl.innerHTML = '<i class="iconoir-cancel me-2"></i>Inativo';
            }
        }

        function updateOverview() {
            const googleInput = document.querySelector('input[name="googleads"]');
            const facebookInput = document.querySelector('input[name="facebookads"]');
            
            const overviewStats = document.querySelectorAll('.analytics-overview .stat-item');
            
            // Google status
            const googleStatus = overviewStats[0].querySelector('p span');
            if (googleInput.value.trim()) {
                googleStatus.className = 'status-active';
                googleStatus.textContent = '✓ Configurado';
            } else {
                googleStatus.className = 'status-inactive';
                googleStatus.textContent = '✗ Não configurado';
            }
            
            // Facebook status
            const facebookStatus = overviewStats[1].querySelector('p span');
            if (facebookInput.value.trim()) {
                facebookStatus.className = 'status-active';
                facebookStatus.textContent = '✓ Configurado';
            } else {
                facebookStatus.className = 'status-inactive';
                facebookStatus.textContent = '✗ Não configurado';
            }
            
            // Total count
            const totalConfigured = (googleInput.value.trim() ? 1 : 0) + (facebookInput.value.trim() ? 1 : 0);
            overviewStats[2].querySelector('p').textContent = `${totalConfigured}/2 Plataformas`;
        }

        // Validação do formulário
        function setupFormValidation() {
            const form = document.getElementById('pixelForm');
            
            form.addEventListener('submit', function(e) {
                const googleInput = document.querySelector('input[name="googleads"]');
                const facebookInput = document.querySelector('input[name="facebookads"]');
                
                const googlePattern = /^AW-\d{8,10}\/[A-Za-z0-9_-]+$/;
                const facebookPattern = /^\d{15,16}$/;
                
                let hasError = false;
                
                // Validar Google Ads (se preenchido)
                if (googleInput.value.trim() && !googlePattern.test(googleInput.value.trim())) {
                    showToast('error', 'Formato do Google Ads inválido! Use: AW-XXXXXXXXX/YYYYYY');
                    hasError = true;
                }
                
                // Validar Facebook Pixel (se preenchido)
                if (facebookInput.value.trim() && !facebookPattern.test(facebookInput.value.trim())) {
                    showToast('error', 'Formato do Facebook Pixel inválido! Use apenas 15-16 dígitos');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault();
                    return;
                }
                
                // Loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="iconoir-hourglass me-3"></i>Salvando Pixels...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.pixel-card');
            
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-16px) scale(1.03)';
                    this.style.boxShadow = '0 25px 80px rgba(0,0,0,0.4)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = 'none';
                });
            });
        }

        // Dicas contextuais
        function setupTooltips() {
            const inputs = document.querySelectorAll('.pixel-input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    const card = this.closest('.pixel-card');
                    card.style.borderColor = 'rgba(102, 126, 234, 0.5)';
                    card.style.boxShadow = '0 0 0 4px rgba(102, 126, 234, 0.2)';
                });
                
                input.addEventListener('blur', function() {
                    const card = this.closest('.pixel-card');
                    card.style.borderColor = 'rgba(255,255,255,0.1)';
                    card.style.boxShadow = 'none';
                });
            });
        }

        // Auto-formatação
        function setupAutoFormatting() {
            const googleInput = document.querySelector('input[name="googleads"]');
            const facebookInput = document.querySelector('input[name="facebookads"]');
            
            // Remover caracteres inválidos do Facebook Pixel
            facebookInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d]/g, '');
            });
            
            // Formatação do Google Ads
            googleInput.addEventListener('blur', function() {
                let value = this.value.trim().toUpperCase();
                if (value && !value.startsWith('AW-')) {
                    // Tentar adicionar o prefixo AW- se parece ser um ID válido
                    if (/^\d{8,10}\/[A-Za-z0-9_-]+$/.test(value)) {
                        this.value = 'AW-' + value;
                    }
                }
            });
        }

        // Copiar código para clipboard
        function setupCopyCode() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('code-snippet')) {
                    const code = e.target.textContent;
                    navigator.clipboard.writeText(code).then(() => {
                        showToast('success', 'Código copiado para a área de transferência!');
                    });
                }
            });
        }

        // Indicadores de mudança
        function setupChangeIndicators() {
            const inputs = document.querySelectorAll('.pixel-input');
            const originalValues = {};
            
            // Armazenar valores originais
            inputs.forEach(input => {
                originalValues[input.name] = input.value;
            });
            
            function checkChanges() {
                let hasChanges = false;
                inputs.forEach(input => {
                    if (input.value !== originalValues[input.name]) {
                        hasChanges = true;
                    }
                });
                
                const saveButton = document.querySelector('.save-button');
                if (hasChanges && saveButton && !saveButton.disabled) {
                    saveButton.innerHTML = '<i class="iconoir-warning-triangle me-3"></i>Salvar Alterações';
                    saveButton.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%)';
                } else if (saveButton && !saveButton.disabled) {
                    saveButton.innerHTML = '<i class="iconoir-check me-3"></i>Salvar Configurações';
                    saveButton.style.background = 'linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%)';
                }
            }
            
            inputs.forEach(input => {
                input.addEventListener('input', checkChanges);
            });
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            setupPixelValidation();
            setupFormValidation();
            setupAnimations();
            setupTooltips();
            setupAutoFormatting();
            setupCopyCode();
            setupChangeIndicators();
            
            // Event listeners para atualização em tempo real
            const inputs = document.querySelectorAll('.pixel-input');
            inputs.forEach(input => {
                input.addEventListener('input', updateStatus);
            });
            
            // Atualização inicial
            updateStatus();
            
            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    const form = document.getElementById('pixelForm');
                    if (form) form.submit();
                }
            });
            
            // Dica de clique para copiar código
            setTimeout(() => {
                const codeSnippets = document.querySelectorAll('.code-snippet');
                codeSnippets.forEach(snippet => {
                    snippet.style.cursor = 'pointer';
                    snippet.title = 'Clique para copiar o código';
                });
            }, 1000);
        });
    </script>

    <!-- Exibir Toast baseado nas ações -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            });
        </script>
    <?php endif; ?>

</body>
</html>