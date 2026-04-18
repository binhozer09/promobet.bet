<?php include 'partials/html.php' ?>

<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
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
checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        nome = ?, 
        nome_site = ?, 
        descricao = ?, 
        grupoplataforma = ?, 
        keyword = ?,
        marquee = ?
        WHERE id = 1");

    $qry->bind_param(
        "ssssss",
        $data['nome'],
        $data['nome_site'],
        $data['descricao'],
        $data['grupoplataforma'],
        $data['keyword'],
        $data['marquee']
    );
    return $qry->execute();
}

$toastType = '';
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nome' => trim(htmlspecialchars($_POST['nome'])),
        'nome_site' => trim(htmlspecialchars($_POST['nome_site'])),
        'descricao' => trim(htmlspecialchars($_POST['descricao'])),
        'grupoplataforma' => trim(htmlspecialchars($_POST['grupoplataforma'])),
        'keyword' => trim(htmlspecialchars($_POST['keyword'])),
        'marquee' => trim(htmlspecialchars($_POST['marquee']))
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações de nomes atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

$config = get_afiliados_config();
?>

<head>
    <?php $title = "Configurações de Nomes da Plataforma";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .config-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
        }
        
        .config-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .config-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .config-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .icon-brand {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }
        
        .icon-seo {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.3);
        }
        
        .icon-social {
            background: linear-gradient(135deg, #45b7d1 0%, #3742fa 100%);
            box-shadow: 0 8px 25px rgba(69, 183, 209, 0.3);
        }
        
        .icon-marquee {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
            box-shadow: 0 8px 25px rgba(255, 167, 38, 0.3);
        }
        
        .icon-description {
            background: linear-gradient(135deg, #ab47bc 0%, #8e24aa 100%);
            box-shadow: 0 8px 25px rgba(171, 71, 188, 0.3);
        }
        
        .config-input {
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .config-input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .config-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            background: rgba(255,255,255,0.1);
            transform: scale(1.02);
        }
        
        .config-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 40px;
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
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }
        
        .save-button {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            border-radius: 20px;
            padding: 18px 60px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px rgba(86, 171, 47, 0.3);
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
            transition: left 0.5s;
        }
        
        .save-button:hover::before {
            left: 100%;
        }
        
        .save-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(86, 171, 47, 0.4);
        }
        
        .card-subtitle {
            line-height: 1.6;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.8);
        }
        
        .preview-box {
            background: rgba(255,255,255,0.1);
            border: 2px dashed rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
            font-style: italic;
            color: rgba(255,255,255,0.8);
        }
        
        .character-counter {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            text-align: right;
            margin-top: 8px;
        }
        
        .character-counter.warning {
            color: #ffc107;
        }
        
        .character-counter.danger {
            color: #ff6b6b;
        }
        
        .floating-label {
            position: relative;
        }
        
        .floating-label label {
            position: absolute;
            top: 16px;
            left: 20px;
            transition: all 0.3s ease;
            pointer-events: none;
            color: #6c757d;
        }
        
        .floating-label input:focus + label,
        .floating-label input:not(:placeholder-shown) + label {
            top: -8px;
            left: 16px;
            font-size: 0.8rem;
            color: #667eea;
            background: white;
            padding: 0 8px;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .config-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .config-card:nth-child(1) { animation-delay: 0.1s; }
        .config-card:nth-child(2) { animation-delay: 0.2s; }
        .config-card:nth-child(3) { animation-delay: 0.3s; }
        .config-card:nth-child(4) { animation-delay: 0.4s; }
        .config-card:nth-child(5) { animation-delay: 0.5s; }
        
        .tips-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .tips-box h6 {
            color: #1976d2;
            margin-bottom: 12px;
        }
        
        .tips-list {
            margin: 0;
            padding-left: 20px;
        }
        
        .tips-list li {
            margin-bottom: 8px;
            color: #424242;
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
                            <h3 class="mb-3">
                                <i class="iconoir-edit me-3"></i>Identidade da Plataforma
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Configure as informações principais que definem a identidade e presença online da sua plataforma
                            </p>
                        </div>
                    </div>

                    <!-- Dicas Importantes -->
                    <div class="col-12">
                        <div class="tips-box">
                            <h6><i class="iconoir-light-bulb me-2"></i>Dicas Importantes</h6>
                            <ul class="tips-list">
                                <li><strong>Nome da Plataforma:</strong> Escolha um nome memorável e profissional</li>
                                <li><strong>SEO:</strong> Use palavras-chave relevantes para melhor posicionamento no Google</li>
                                <li><strong>Descrição:</strong> Seja claro e atrativo, pois aparece ao compartilhar o site</li>
                                <li><strong>Telegram:</strong> Use apenas o nome do grupo, sem @ ou links</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" id="configForm">
                            <div class="row">
                                
                                <!-- Nome da Plataforma -->
                                <div class="col-lg-6 mb-4">
                                    <div class="config-card p-4 h-100">
                                        <div class="config-icon icon-brand">
                                            <i class="iconoir-crown"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Nome da Plataforma</h5>
                                        <p class="card-subtitle text-muted mb-3">
                                            Nome principal exibido no dashboard e página inicial do cassino. 
                                            Escolha algo memorável e profissional.
                                        </p>
                                        <input type="text" name="nome" class="form-control config-input" 
                                               value="<?= htmlspecialchars($config['nome']); ?>" 
                                               placeholder="Ex: Casino Premium"
                                               maxlength="50" required>
                                        <div class="character-counter">
                                            <span id="nome-count">0</span>/50 caracteres
                                        </div>
                                        <div class="preview-box">
                                            <strong>Prévia:</strong> <span id="nome-preview"><?= htmlspecialchars($config['nome']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div class="col-lg-6 mb-4">
                                    <div class="config-card p-4 h-100">
                                        <div class="config-icon icon-description">
                                            <i class="iconoir-text"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Descrição da Plataforma</h5>
                                        <p class="card-subtitle text-muted mb-3">
                                            Descrição exibida quando o site é compartilhado em redes sociais. 
                                            Seja atrativo e informativo.
                                        </p>
                                        <textarea name="descricao" class="form-control config-input config-textarea" 
                                                  placeholder="Ex: A melhor plataforma de jogos online com os maiores prêmios!"
                                                  maxlength="160" required><?= htmlspecialchars($config['descricao']); ?></textarea>
                                        <div class="character-counter">
                                            <span id="descricao-count">0</span>/160 caracteres (recomendado para SEO)
                                        </div>
                                        <div class="preview-box">
                                            <strong>Prévia do compartilhamento:</strong><br>
                                            <span id="descricao-preview"><?= htmlspecialchars($config['descricao']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Grupo Telegram -->
                                <div class="col-lg-6 mb-4">
                                    <div class="config-card p-4 h-100">
                                        <div class="config-icon icon-social">
                                            <i class="iconoir-telegram"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Grupo do Telegram</h5>
                                        <p class="card-subtitle text-muted mb-3">
                                            Nome do grupo no Telegram para suporte. Usado quando usuários clicam em "Contato". 
                                            <strong>Apenas o nome, sem @ ou links.</strong>
                                        </p>
                                        <input type="text" name="grupoplataforma" class="form-control config-input" 
                                               value="<?= htmlspecialchars($config['grupoplataforma']); ?>" 
                                               placeholder="Ex: fivegamingoficial"
                                               maxlength="32" required>
                                        <div class="character-counter">
                                            <span id="grupo-count">0</span>/32 caracteres
                                        </div>
                                        <div class="preview-box">
                                            <strong>Link gerado:</strong> https://t.me/<span id="grupo-preview"><?= htmlspecialchars($config['grupoplataforma']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Texto Slide (Marquee) -->
                                <div class="col-lg-6 mb-4">
                                    <div class="config-card p-4 h-100">
                                        <div class="config-icon icon-marquee">
                                            <i class="iconoir-movie"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Texto Deslizante</h5>
                                        <p class="card-subtitle text-muted mb-3">
                                            Texto animado exibido na página inicial, abaixo dos banners. 
                                            Use para promoções ou informações importantes.
                                        </p>
                                        <input type="text" name="marquee" class="form-control config-input" 
                                               value="<?= htmlspecialchars($config['marquee']); ?>" 
                                               placeholder="Ex: 🎰 Bem-vindos ao maior cassino online! Ganhe até R$ 10.000 no primeiro depósito! 🎰"
                                               maxlength="200" required>
                                        <div class="character-counter">
                                            <span id="marquee-count">0</span>/200 caracteres
                                        </div>
                                        <div class="preview-box" style="overflow: hidden; white-space: nowrap;">
                                            <strong>Animação:</strong> 
                                            <div style="animation: marquee 10s linear infinite;">
                                                <span id="marquee-preview"><?= htmlspecialchars($config['marquee']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keywords SEO -->
                                <div class="col-12 mb-4">
                                    <div class="config-card p-4">
                                        <div class="config-icon icon-seo">
                                            <i class="iconoir-search"></i>
                                        </div>
                                        <h5 class="fw-bold mb-3">Palavras-chave para SEO</h5>
                                        <p class="card-subtitle text-muted mb-3">
                                            Palavras-chave para melhorar a visibilidade no Google. Separe por vírgulas. 
                                            Use termos que seu público-alvo pesquisa.
                                        </p>
                                        <textarea name="keyword" class="form-control config-input config-textarea" 
                                                  placeholder="Ex: tigrinho pagante, slots online, cassino brasileiro, jogos de aposta, casa de slots"
                                                  maxlength="300" required><?= htmlspecialchars($config['keyword']); ?></textarea>
                                        <div class="character-counter">
                                            <span id="keyword-count">0</span>/300 caracteres
                                        </div>
                                        <div class="preview-box">
                                            <strong>Palavras-chave identificadas:</strong><br>
                                            <div id="keyword-tags"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campo oculto para nome_site (compatibilidade) -->
                                <input type="hidden" name="nome_site" value="<?= htmlspecialchars($config['nome_site'] ?? $config['nome']); ?>">
                            </div>

                            <!-- Botão de Salvar -->
                            <div class="text-center mt-5">
                                <button type="submit" class="save-button">
                                    <i class="iconoir-check me-3"></i>Salvar Configurações
                                </button>
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
    <style>
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
    </style>

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
                    <img src="/uploads/logo.png.webp" alt="" height="20" class="me-1">
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
        }

        // Contador de caracteres e preview em tempo real
        function setupCharacterCounters() {
            const fields = [
                {
                    input: 'input[name="nome"]',
                    counter: 'nome-count',
                    preview: 'nome-preview',
                    max: 50
                },
                {
                    input: 'textarea[name="descricao"]',
                    counter: 'descricao-count',
                    preview: 'descricao-preview',
                    max: 160
                },
                {
                    input: 'input[name="grupoplataforma"]',
                    counter: 'grupo-count',
                    preview: 'grupo-preview',
                    max: 32
                },
                {
                    input: 'input[name="marquee"]',
                    counter: 'marquee-count',
                    preview: 'marquee-preview',
                    max: 200
                },
                {
                    input: 'textarea[name="keyword"]',
                    counter: 'keyword-count',
                    preview: 'keyword-tags',
                    max: 300,
                    special: 'keywords'
                }
            ];

            fields.forEach(field => {
                const input = document.querySelector(field.input);
                const counter = document.getElementById(field.counter);
                const preview = document.getElementById(field.preview);

                function updateField() {
                    const length = input.value.length;
                    counter.textContent = length;
                    
                    // Atualizar cor do contador
                    counter.parentElement.className = 'character-counter';
                    if (length > field.max * 0.8) {
                        counter.parentElement.classList.add('warning');
                    }
                    if (length > field.max * 0.95) {
                        counter.parentElement.classList.add('danger');
                    }

                    // Atualizar preview
                    if (field.special === 'keywords') {
                        updateKeywordTags(input.value, preview);
                    } else if (preview) {
                        preview.textContent = input.value || 'Digite algo...';
                    }
                }

                input.addEventListener('input', updateField);
                updateField(); // Executar na inicialização
            });
        }

        // Função para criar tags de keywords
        function updateKeywordTags(keywords, container) {
            const tags = keywords.split(',')
                .map(tag => tag.trim())
                .filter(tag => tag.length > 0);

            container.innerHTML = '';
            
            if (tags.length === 0) {
                container.innerHTML = '<span style="color: #6c757d;">Digite palavras-chave separadas por vírgula</span>';
                return;
            }

            tags.forEach(tag => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2';
                badge.style.fontSize = '0.8rem';
                badge.textContent = tag;
                container.appendChild(badge);
            });
        }

        // Validação do formulário
        function setupFormValidation() {
            const form = document.getElementById('configForm');
            
            form.addEventListener('submit', function(e) {
                const nome = document.querySelector('input[name="nome"]').value.trim();
                const descricao = document.querySelector('textarea[name="descricao"]').value.trim();
                const grupo = document.querySelector('input[name="grupoplataforma"]').value.trim();
                
                // Validações básicas
                if (nome.length < 3) {
                    e.preventDefault();
                    showToast('error', 'O nome da plataforma deve ter pelo menos 3 caracteres!');
                    return;
                }
                
                if (descricao.length < 10) {
                    e.preventDefault();
                    showToast('error', 'A descrição deve ter pelo menos 10 caracteres!');
                    return;
                }
                
                // Validação do grupo Telegram (sem @ ou espaços)
                if (grupo.includes('@') || grupo.includes(' ') || grupo.includes('t.me')) {
                    e.preventDefault();
                    showToast('error', 'Use apenas o nome do grupo do Telegram, sem @, espaços ou links!');
                    return;
                }
                
                // Loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="iconoir-hourglass me-3"></i>Salvando...';
                submitBtn.disabled = true;
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.config-card');
            
            cards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        // Auto-save em localStorage
        function setupAutoSave() {
            const inputs = document.querySelectorAll('input, textarea');
            
            inputs.forEach(input => {
                // Carregar valor salvo
                const savedValue = localStorage.getItem(`config_names_${input.name}`);
                if (savedValue && savedValue !== input.value) {
                    input.style.borderColor = '#ffc107';
                    input.title = 'Valor alterado não salvo';
                }
                
                // Salvar ao alterar
                input.addEventListener('input', function() {
                    localStorage.setItem(`config_names_${this.name}`, this.value);
                });
            });
            
            // Limpar localStorage quando salvar
            document.getElementById('configForm').addEventListener('submit', function() {
                inputs.forEach(input => {
                    localStorage.removeItem(`config_names_${input.name}`);
                });
            });
        }

        // Dicas contextuais
        function setupTooltips() {
            const inputs = document.querySelectorAll('.config-input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    const card = this.closest('.config-card');
                    card.style.borderColor = '#667eea';
                    card.style.boxShadow = '0 0 0 4px rgba(102, 126, 234, 0.2)';
                });
                
                input.addEventListener('blur', function() {
                    const card = this.closest('.config-card');
                    card.style.borderColor = 'rgba(0,0,0,0.08)';
                    card.style.boxShadow = 'none';
                });
            });
        }

        // Prévia em tempo real do texto deslizante
        function setupMarqueePreview() {
            const marqueeInput = document.querySelector('input[name="marquee"]');
            const marqueePreview = document.getElementById('marquee-preview');
            
            marqueeInput.addEventListener('input', function() {
                marqueePreview.textContent = this.value || 'Digite seu texto deslizante...';
            });
        }

        // Validação do grupo Telegram em tempo real
        function setupTelegramValidation() {
            const grupoInput = document.querySelector('input[name="grupoplataforma"]');
            
            grupoInput.addEventListener('input', function() {
                const value = this.value;
                const preview = document.getElementById('grupo-preview');
                
                // Remover caracteres inválidos automaticamente
                const cleanValue = value.replace(/[@\s]/g, '').replace(/t\.me\//g, '');
                if (cleanValue !== value) {
                    this.value = cleanValue;
                }
                
                preview.textContent = cleanValue || 'seugrupo';
                
                // Feedback visual
                if (value.includes('@') || value.includes(' ') || value.includes('t.me')) {
                    this.style.borderColor = '#dc3545';
                    this.style.background = 'rgba(220, 53, 69, 0.1)';
                } else {
                    this.style.borderColor = '#28a745';
                    this.style.background = 'rgba(40, 167, 69, 0.1)';
                }
            });
        }

        // Formatação inteligente das keywords
        function setupKeywordFormatting() {
            const keywordInput = document.querySelector('textarea[name="keyword"]');
            
            keywordInput.addEventListener('blur', function() {
                // Auto-formatação: remover espaços extras e normalizar vírgulas
                let keywords = this.value
                    .split(',')
                    .map(keyword => keyword.trim())
                    .filter(keyword => keyword.length > 0)
                    .join(', ');
                
                if (keywords !== this.value) {
                    this.value = keywords;
                    updateKeywordTags(keywords, document.getElementById('keyword-tags'));
                }
            });
        }

        // Sugestões de SEO
        function setupSEOSuggestions() {
            const keywordInput = document.querySelector('textarea[name="keyword"]');
            const nomeInput = document.querySelector('input[name="nome"]');
            
            function showSEOTips() {
                const keywords = keywordInput.value.toLowerCase();
                const nome = nomeInput.value.toLowerCase();
                
                const tips = [];
                
                if (!keywords.includes('cassino')) {
                    tips.push('Considere adicionar "cassino" nas palavras-chave');
                }
                
                if (!keywords.includes('slots')) {
                    tips.push('Adicione "slots" para melhor SEO');
                }
                
                if (keywords.split(',').length < 5) {
                    tips.push('Use pelo menos 5 palavras-chave para melhor cobertura');
                }
                
                if (nome && !keywords.includes(nome)) {
                    tips.push(`Inclua "${nome}" nas palavras-chave`);
                }
                
                // Exibir dicas se houver
                if (tips.length > 0) {
                    const tipsContainer = document.createElement('div');
                    tipsContainer.className = 'alert alert-info mt-3';
                    tipsContainer.innerHTML = `
                        <strong><i class="iconoir-light-bulb me-2"></i>Dicas de SEO:</strong>
                        <ul class="mb-0 mt-2">
                            ${tips.map(tip => `<li>${tip}</li>`).join('')}
                        </ul>
                    `;
                    
                    // Remover dicas anteriores
                    const oldTips = keywordInput.closest('.config-card').querySelector('.alert-info');
                    if (oldTips) oldTips.remove();
                    
                    keywordInput.closest('.config-card').appendChild(tipsContainer);
                }
            }
            
            keywordInput.addEventListener('blur', showSEOTips);
            nomeInput.addEventListener('blur', showSEOTips);
        }

        // Indicador de mudanças não salvas
        function setupChangeIndicator() {
            const inputs = document.querySelectorAll('input, textarea');
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
                        // Manter cor padrão - apenas adicionar uma borda sutil
                        const card = input.closest('.config-card');
                        card.style.borderColor = '#667eea';
                        card.style.boxShadow = '0 0 0 2px rgba(102, 126, 234, 0.2)';
                    } else {
                        const card = input.closest('.config-card');
                        card.style.borderColor = 'rgba(0,0,0,0.08)';
                        card.style.boxShadow = 'none';
                    }
                });
                
                // Mostrar/esconder indicador no botão
                const saveButton = document.querySelector('.save-button');
                if (hasChanges) {
                    saveButton.innerHTML = '<i class="iconoir-warning-triangle me-3"></i>Salvar Alterações';
                    saveButton.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%)';
                } else {
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
            setupCharacterCounters();
            setupFormValidation();
            setupAnimations();
            setupAutoSave();
            setupTooltips();
            setupMarqueePreview();
            setupTelegramValidation();
            setupKeywordFormatting();
            setupSEOSuggestions();
            setupChangeIndicator();
            
            // Easter egg: Shake nos campos vazios importantes
            setTimeout(() => {
                const importantFields = ['input[name="nome"]', 'textarea[name="descricao"]'];
                importantFields.forEach(selector => {
                    const field = document.querySelector(selector);
                    if (!field.value.trim()) {
                        field.style.animation = 'shake 0.5s ease-in-out';
                        setTimeout(() => field.style.animation = '', 500);
                    }
                });
            }, 2000);
            
            // Atalhos de teclado
            document.addEventListener('keydown', function(e) {
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('configForm').submit();
                }
            });
        });

        // Animação de shake para campos vazios
        const shakeKeyframes = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        
        const style = document.createElement('style');
        style.textContent = shakeKeyframes;
        document.head.appendChild(style);
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