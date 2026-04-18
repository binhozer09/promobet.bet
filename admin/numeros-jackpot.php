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

// Manter as funções originais
function get_numero_jackpot_atual()
{
    global $mysqli;
    $qry = "SELECT numero_jackpot FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['numero_jackpot'];
}

function update_numero_jackpot($numero_jackpot)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET numero_jackpot = ? WHERE id = 1");
    $qry->bind_param("i", $numero_jackpot);
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_jackpot_selecionado = intval($_POST['numero_jackpot']);

    if (update_numero_jackpot($numero_jackpot_selecionado)) {
        $toastType = 'success';
        $toastMessage = 'Estilo de numeração atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar a numeração. Tente novamente.';
    }
}

$numero_jackpot_atual = get_numero_jackpot_atual();

// Array com informações dos estilos de numeração
$numeracoes = [
    1 => ['name' => 'Clássico', 'description' => 'Numeração tradicional e elegante'],
    2 => ['name' => 'Moderno', 'description' => 'Estilo contemporâneo e limpo'],
    3 => ['name' => 'Neon', 'description' => 'Visual vibrante e luminoso'],
    4 => ['name' => 'Premium', 'description' => 'Design sofisticado e luxuoso'],
    5 => ['name' => 'Minimalista', 'description' => 'Simplicidade e funcionalidade']
];
?>

<head>
    <?php $title = "Personalização de Numeração"; ?>
    <?php include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .number-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            margin-bottom: 24px;
        }
        
        .number-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .number-card.selected::before {
            opacity: 1;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .number-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .number-card.selected {
            border-color: #56ab2f;
            box-shadow: 0 0 0 2px rgba(86, 171, 47, 0.3);
        }
        
        .number-preview {
            width: 100%;
            height: 180px;
            object-fit: contain;
            transition: transform 0.3s ease;
            padding: 15px;
            border-radius: 8px;
            margin: 15px;
            width: calc(100% - 30px);
        }
        
        .number-card:hover .number-preview {
            transform: scale(1.05);
        }
        
        .number-info {
            padding: 20px;
            position: relative;
        }
        
        .number-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .number-description {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 16px;
        }
        
        .number-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: auto;
        }
        
        .badge-current {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            color: white;
        }
        
        .badge-available {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
            opacity: 0;
            transform: translateY(100px);
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .save-button.show {
            opacity: 1;
            transform: translateY(0);
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
        
        .current-number-banner {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .current-number-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .number-selector-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .number-card:hover .number-selector-overlay {
            opacity: 1;
        }
        
        .number-card.selected .number-selector-overlay {
            opacity: 1;
            background: rgba(86, 171, 47, 0.8);
        }
        
        .filter-bar {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 32px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid rgba(255,255,255,0.2);
            background: transparent;
            color: rgba(255,255,255,0.8);
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .filter-btn.active, .filter-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.2);
            color: white;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 20px 12px 50px;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
            background: rgba(255,255,255,0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
            font-size: 1.1rem;
        }
        
        .number-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .number-demo {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            border: 2px dashed rgba(255,255,255,0.3);
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
        
        .number-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .number-card:nth-child(1) { animation-delay: 0.1s; }
        .number-card:nth-child(2) { animation-delay: 0.2s; }
        .number-card:nth-child(3) { animation-delay: 0.3s; }
        .number-card:nth-child(4) { animation-delay: 0.4s; }
        .number-card:nth-child(5) { animation-delay: 0.5s; }
        
        .no-results {
            text-align: center;
            padding: 80px 20px;
            color: rgba(255,255,255,0.6);
        }
        
        .no-results-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 24px;
            opacity: 0.7;
        }
        
        @media (max-width: 768px) {
            .number-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                min-width: auto;
            }
            
            .save-button {
                position: static;
                transform: none;
                opacity: 1;
                margin-top: 24px;
            }
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
                                <i class="iconoir-hashtag me-3"></i>Personalização de Numeração
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Escolha o estilo de números perfeito para sua plataforma
                            </p>
                        </div>
                    </div>

                    <!-- Numeração Atual -->
                    <div class="col-12">
                        <div class="current-number-banner">
                            <div class="current-number-icon">
                                <i class="iconoir-hashtag"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Numeração Atual: <?= isset($numeracoes[$numero_jackpot_atual]) ? $numeracoes[$numero_jackpot_atual]['name'] : 'Estilo ' . $numero_jackpot_atual ?></h5>
                                <p class="mb-0 opacity-75">
                                    <?= isset($numeracoes[$numero_jackpot_atual]) ? $numeracoes[$numero_jackpot_atual]['description'] : 'Este é o estilo de numeração ativo na sua plataforma.' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de Filtros -->
                    <div class="col-12">
                        <div class="filter-bar">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="filter-btn active" data-category="all">Todos os Estilos</button>
                                <button class="filter-btn" data-category="classic">Clássicos</button>
                                <button class="filter-btn" data-category="modern">Modernos</button>
                            </div>
                            
                            <div class="search-box">
                                <i class="iconoir-search search-icon"></i>
                                <input type="text" id="numberSearch" class="search-input" placeholder="Buscar estilos de numeração...">
                            </div>
                        </div>
                    </div>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" id="numberForm">
                            <!-- Container para numerações -->
                            <div id="numbersContainer">
                                <div class="number-grid" id="numberGrid">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="number-card <?= ($numero_jackpot_atual == $i) ? 'selected' : '' ?>" 
                                             data-number-id="<?= $i ?>"
                                             data-category="<?= $i <= 2 ? 'classic' : 'modern' ?>"
                                             onclick="selectNumber(<?= $i ?>, this)">
                                            
                                            <div class="position-relative">
                                                <img src="/uploads/numero<?= $i ?>.png" 
                                                     class="number-preview" 
                                                     alt="Numeração <?= $i ?>"
                                                     onerror="handleImageError(this, <?= $i ?>)">
                                                
                                                <div class="number-selector-overlay">
                                                    <?= ($numero_jackpot_atual == $i) ? 
                                                        '<i class="iconoir-check me-2"></i>Estilo Ativo' : 
                                                        '<i class="iconoir-eye me-2"></i>Selecionar Estilo' ?>
                                                </div>
                                            </div>
                                            
                                            <div class="number-info">
                                                <div class="number-name">
                                                    <?= isset($numeracoes[$i]) ? $numeracoes[$i]['name'] : 'Estilo ' . $i ?>
                                                    <?php if ($numero_jackpot_atual == $i): ?>
                                                        <span class="number-badge badge-current">Atual</span>
                                                    <?php else: ?>
                                                        <span class="number-badge badge-available">Disponível</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="number-description">
                                                    <?= isset($numeracoes[$i]) ? $numeracoes[$i]['description'] : 'Estilo de numeração único e funcional' ?>
                                                </div>
                                                
                                                <!-- Demo dos números -->
                                                <div class="number-demo" id="demo<?= $i ?>">
                                                    1234567890
                                                </div>
                                                
                                                <input type="radio" 
                                                       name="numero_jackpot" 
                                                       value="<?= $i ?>" 
                                                       id="numero_jackpot<?= $i ?>" 
                                                       <?= ($numero_jackpot_atual == $i) ? 'checked' : '' ?>
                                                       style="display: none;">
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                
                                <!-- Estado de "Nenhum resultado" -->
                                <div id="noResults" class="no-results" style="display: none;">
                                    <div class="no-results-icon">
                                        <i class="iconoir-search"></i>
                                    </div>
                                    <h4>Nenhum estilo encontrado</h4>
                                    <p>Tente ajustar sua busca ou filtros para encontrar o estilo perfeito</p>
                                </div>
                            </div>
                            
                            <!-- Botão Salvar Flutuante -->
                            <button type="submit" class="save-button" id="saveButton">
                                <i class="iconoir-check me-2"></i>Aplicar Numeração
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        let selectedNumber = <?= $numero_jackpot_atual ?>;

        // Função para tratar erro de imagem
        function handleImageError(img, numberId) {
            const card = img.closest('.number-card');
            if (card) {
                card.style.opacity = '0.6';
                img.style.display = 'none';
                
                // Criar mensagem simples de imagem não encontrada
                const placeholder = document.createElement('div');
                placeholder.style.cssText = `
                    width: calc(100% - 30px);
                    height: 180px;
                    background: #404040;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: rgba(255,255,255,0.6);
                    font-size: 0.9rem;
                    text-align: center;
                    border-radius: 8px;
                    margin: 15px;
                `;
                placeholder.innerHTML = `Estilo ${numberId}<br>não encontrado`;
                
                img.parentNode.appendChild(placeholder);
            }
        }

        // Função para selecionar numeração
        function selectNumber(numberId, element) {
            // Remover seleção de todos os cards
            document.querySelectorAll('.number-card').forEach(card => {
                card.classList.remove('selected');
                const overlay = card.querySelector('.number-selector-overlay');
                overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Estilo';
            });

            // Selecionar o card clicado
            element.classList.add('selected');
            const overlay = element.querySelector('.number-selector-overlay');
            overlay.innerHTML = '<i class="iconoir-check me-2"></i>Estilo Selecionado';

            // Marcar o radio button
            document.getElementById('numero_jackpot' + numberId).checked = true;
            
            // Atualizar numeração selecionada
            selectedNumber = numberId;
            
            // Mostrar botão salvar se numeração diferente da atual
            const saveButton = document.getElementById('saveButton');
            if (numberId != <?= $numero_jackpot_atual ?>) {
                saveButton.classList.add('show');
                saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Estilo ' + numberId;
            } else {
                saveButton.classList.remove('show');
            }
        }

        // Sistema de filtros
        function setupFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const numberCards = document.querySelectorAll('.number-card');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.dataset.category;
                    
                    numberCards.forEach(card => {
                        const cardCategory = card.dataset.category;
                        
                        if (category === 'all' || cardCategory === category) {
                            card.style.display = 'block';
                            card.style.animation = 'fadeInUp 0.5s ease-out';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    updateNoResultsState();
                });
            });
        }

        // Sistema de busca
        function setupSearch() {
            const searchInput = document.getElementById('numberSearch');
            const numberCards = document.querySelectorAll('.number-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                numberCards.forEach(card => {
                    const numberId = card.dataset.numberId;
                    const name = card.querySelector('.number-name').textContent.toLowerCase();
                    const description = card.querySelector('.number-description').textContent.toLowerCase();
                    
                    if (searchTerm === '' || 
                        name.includes(searchTerm) || 
                        description.includes(searchTerm) ||
                        `estilo ${numberId}`.includes(searchTerm) ||
                        `numeração ${numberId}`.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });

                updateNoResultsState();
            });
        }

        // Atualizar estado de "nenhum resultado"
        function updateNoResultsState() {
            const visibleCards = Array.from(document.querySelectorAll('.number-card')).filter(
                card => card.style.display !== 'none'
            );
            const noResults = document.getElementById('noResults');
            const numberGrid = document.getElementById('numberGrid');
            
            if (visibleCards.length === 0) {
                noResults.style.display = 'block';
                numberGrid.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                numberGrid.style.display = 'grid';
            }
        }

        // Validação de formulário
        function setupFormValidation() {
            const form = document.getElementById('numberForm');
            
            form.addEventListener('submit', function(e) {
                const selectedInput = document.querySelector('input[name="numero_jackpot"]:checked');
                
                if (!selectedInput) {
                    e.preventDefault();
                    return false;
                }
                
                if (selectedInput.value == <?= $numero_jackpot_atual ?>) {
                    e.preventDefault();
                    return false;
                }
                
                // Loading state
                const submitBtn = document.getElementById('saveButton');
                submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Aplicando Estilo...';
                submitBtn.disabled = true;
                
                return true;
            });
        }

        // Atalhos de teclado
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Números 1-5 para selecionar estilos
                if (e.key >= '1' && e.key <= '5' && !e.ctrlKey && !e.altKey) {
                    const numberNumber = parseInt(e.key);
                    const numberCard = document.querySelector(`[data-number-id="${numberNumber}"]`);
                    if (numberCard && numberCard.style.display !== 'none') {
                        e.preventDefault();
                        selectNumber(numberNumber, numberCard);
                        numberCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('numberForm').submit();
                }
                
                // Ctrl + F para focar na busca
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('numberSearch').focus();
                }
            });
        }

        // Animação dos demos de números
        function setupNumberDemo() {
            const demos = document.querySelectorAll('.number-demo');
            
            demos.forEach((demo, index) => {
                // Efeito de contagem animada
                let count = 0;
                const target = 1234567890;
                const duration = 2000;
                const increment = target / (duration / 16);
                
                function animate() {
                    count += increment;
                    if (count < target) {
                        demo.textContent = Math.floor(count).toString().padStart(10, '0');
                        requestAnimationFrame(animate);
                    } else {
                        demo.textContent = target.toString();
                    }
                }
                
                // Iniciar animação com delay baseado no índice
                setTimeout(() => {
                    if (demo.closest('.number-card').style.display !== 'none') {
                        animate();
                    }
                }, index * 200);
            });
        }

        // Função para smooth scroll para elementos
        function smoothScrollTo(element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Função para resetar filtros
        function resetFilters() {
            // Resetar botões de filtro
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector('.filter-btn[data-category="all"]').classList.add('active');
            
            // Limpar busca
            document.getElementById('numberSearch').value = '';
            
            // Mostrar todos os cards
            document.querySelectorAll('.number-card').forEach(card => {
                card.style.display = 'block';
            });
            
            updateNoResultsState();
        }

        // Função para preview de numeração
        function previewNumber(numberId) {
            const card = document.querySelector(`[data-number-id="${numberId}"]`);
            if (card) {
                card.style.transform = 'scale(1.05)';
                card.style.zIndex = '100';
                
                setTimeout(() => {
                    card.style.transform = '';
                    card.style.zIndex = '';
                }, 300);
            }
        }

        // Inicialização quando o DOM estiver carregado
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar todos os sistemas
            setupFilters();
            setupSearch();
            setupFormValidation();
            setupKeyboardShortcuts();
            setupNumberDemo();
            
            // Mostrar toast se houver mensagem PHP
            <?php if ($toastType && $toastMessage): ?>
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
            <?php endif; ?>
            
            // Configurar observador de intersecção para animações
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });
                
                document.querySelectorAll('.number-card').forEach(card => {
                    observer.observe(card);
                });
            }
            
            // Configurar lazy loading para imagens
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src || img.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                document.querySelectorAll('img.lazy').forEach(img => {
                    imageObserver.observe(img);
                });
            }
            
            // Adicionar listeners para teclas de atalho
            document.addEventListener('keydown', function(e) {
                // ESC para resetar filtros
                if (e.key === 'Escape') {
                    resetFilters();
                }
                
                // Enter para aplicar seleção quando foco estiver em um card
                if (e.key === 'Enter' && document.activeElement.classList.contains('number-card')) {
                    const numberId = document.activeElement.dataset.numberId;
                    if (numberId) {
                        selectNumber(parseInt(numberId), document.activeElement);
                    }
                }
            });
            
            // Configurar navegação por teclado
            document.querySelectorAll('.number-card').forEach((card, index) => {
                card.setAttribute('tabindex', '0');
                
                card.addEventListener('keydown', function(e) {
                    const cards = Array.from(document.querySelectorAll('.number-card:not([style*="display: none"])'));
                    const currentIndex = cards.indexOf(this);
                    
                    switch(e.key) {
                        case 'ArrowRight':
                        case 'ArrowDown':
                            e.preventDefault();
                            const nextIndex = (currentIndex + 1) % cards.length;
                            cards[nextIndex].focus();
                            break;
                            
                        case 'ArrowLeft':
                        case 'ArrowUp':
                            e.preventDefault();
                            const prevIndex = currentIndex === 0 ? cards.length - 1 : currentIndex - 1;
                            cards[prevIndex].focus();
                            break;
                            
                        case ' ':
                        case 'Enter':
                            e.preventDefault();
                            const numberId = this.dataset.numberId;
                            selectNumber(parseInt(numberId), this);
                            break;
                    }
                });
                
                // Efeitos visuais de foco
                card.addEventListener('focus', function() {
                    this.style.outline = '3px solid rgba(102, 126, 234, 0.5)';
                    this.style.outlineOffset = '2px';
                });
                
                card.addEventListener('blur', function() {
                    this.style.outline = '';
                    this.style.outlineOffset = '';
                });
            });
            
            // Configurar auto-save (opcional)
            let autoSaveTimeout;
            document.querySelectorAll('input[name="numero_jackpot"]').forEach(input => {
                input.addEventListener('change', function() {
                    clearTimeout(autoSaveTimeout);
                    autoSaveTimeout = setTimeout(() => {
                        // Implementar auto-save se necessário
                        console.log('Auto-save preparado para numeração:', this.value);
                    }, 2000);
                });
            });
            
            // Adicionar efeitos de performance
            if (CSS.supports('backdrop-filter', 'blur(10px)')) {
                document.querySelectorAll('.number-card').forEach(card => {
                    card.style.backdropFilter = 'blur(10px)';
                });
            }
            
            // Configurar preload de imagens
            document.querySelectorAll('.number-preview').forEach(img => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'image';
                link.href = img.src;
                document.head.appendChild(link);
            });
            
            console.log('Sistema de Personalização de Numeração carregado com sucesso!');
        });

        // Função para debug (remover em produção)
        window.debugNumberSystem = function() {
            console.log({
                selectedNumber: selectedNumber,
                currentNumber: <?= $numero_jackpot_atual ?>,
                visibleCards: document.querySelectorAll('.number-card:not([style*="display: none"])').length,
                totalCards: document.querySelectorAll('.number-card').length
            });
        };
    </script>

    <!-- Estilos adicionais para acessibilidade -->
    <style>
        @media (prefers-reduced-motion: reduce) {
            .number-card,
            .save-button,
            .number-preview {
                transition: none !important;
                animation: none !important;
            }
        }
        
        @media (prefers-contrast: high) {
            .number-card {
                border: 2px solid #000;
            }
            
            .number-card.selected {
                border-color: #00ff00;
            }
        }
        
        .number-card:focus-visible {
            outline: 3px solid #667eea;
            outline-offset: 2px;
        }
    </style>

</body>
</html>