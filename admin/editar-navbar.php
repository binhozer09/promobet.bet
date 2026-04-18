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
function get_navbar_atual()
{
    global $mysqli;
    $qry = "SELECT navbar FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['navbar'];
}

function update_navbar($navbar)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET navbar = ? WHERE id = 1");
    $qry->bind_param("i", $navbar);
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $navbar_selecionado = intval($_POST['navbar']);

    if (update_navbar($navbar_selecionado)) {
        $toastType = 'success';
        $toastMessage = 'Navbar atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o navbar. Tente novamente.';
    }
}

$navbar_atual = get_navbar_atual();

// Array com informações dos navbars
$navbars = [
    1 => ['name' => 'Clássico', 'description' => 'Design tradicional e elegante'],
    2 => ['name' => 'Moderno', 'description' => 'Interface contemporânea e limpa'],
    3 => ['name' => 'Minimalista', 'description' => 'Simplicidade e funcionalidade'],
    4 => ['name' => 'Premium', 'description' => 'Sofisticação e luxo'],
    5 => ['name' => 'Dinâmico', 'description' => 'Visual inovador e interativo']
];
?>

<head>
    <?php $title = "Personalização de Navbar"; ?>
    <?php include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .navbar-card {
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
        
        .navbar-card::before {
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
        
        .navbar-card.selected::before {
            opacity: 1;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .navbar-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .navbar-card.selected {
            border-color: #56ab2f;
            box-shadow: 0 0 0 2px rgba(86, 171, 47, 0.3);
        }
        
        .navbar-preview {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .navbar-card:hover .navbar-preview {
            transform: scale(1.05);
        }
        
        .navbar-info {
            padding: 20px;
            position: relative;
        }
        
        .navbar-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-description {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 16px;
        }
        
        .navbar-badge {
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
        
        .current-navbar-banner {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .current-navbar-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .navbar-selector-overlay {
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
        
        .navbar-card:hover .navbar-selector-overlay {
            opacity: 1;
        }
        
        .navbar-card.selected .navbar-selector-overlay {
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
        
        .navbar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .loading-skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 2s infinite;
            border-radius: 16px;
            height: 280px;
        }
        
        @keyframes loading-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
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
        
        .navbar-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .navbar-card:nth-child(1) { animation-delay: 0.1s; }
        .navbar-card:nth-child(2) { animation-delay: 0.2s; }
        .navbar-card:nth-child(3) { animation-delay: 0.3s; }
        .navbar-card:nth-child(4) { animation-delay: 0.4s; }
        .navbar-card:nth-child(5) { animation-delay: 0.5s; }
        
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
            .navbar-grid {
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
                                <i class="iconoir-nav-arrow-up me-3"></i>Personalização de Navbar
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Escolha o estilo de navegação perfeito para sua plataforma
                            </p>
                        </div>
                    </div>

                    <!-- Navbar Atual -->
                    <div class="col-12">
                        <div class="current-navbar-banner">
                            <div class="current-navbar-icon">
                                <i class="iconoir-check"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Navbar Atual: <?= isset($navbars[$navbar_atual]) ? $navbars[$navbar_atual]['name'] : 'Navbar ' . $navbar_atual ?></h5>
                                <p class="mb-0 opacity-75">
                                    <?= isset($navbars[$navbar_atual]) ? $navbars[$navbar_atual]['description'] : 'Este é o estilo de navbar ativo na sua plataforma.' ?>
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
                                <input type="text" id="navbarSearch" class="search-input" placeholder="Buscar estilos de navbar...">
                            </div>
                        </div>
                    </div>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" id="navbarForm">
                            <!-- Container para navbars -->
                            <div id="navbarsContainer">
                                <div class="navbar-grid" id="navbarGrid">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="navbar-card <?= ($navbar_atual == $i) ? 'selected' : '' ?>" 
                                             data-navbar-id="<?= $i ?>"
                                             data-category="<?= $i <= 2 ? 'classic' : 'modern' ?>"
                                             onclick="selectNavbar(<?= $i ?>, this)">
                                            
                                            <div class="position-relative">
                                                <img src="/uploads/navbar<?= $i ?>.png" 
                                                     class="navbar-preview" 
                                                     alt="Navbar <?= $i ?>"
                                                     onerror="handleImageError(this, <?= $i ?>)">
                                                
                                                <div class="navbar-selector-overlay">
                                                    <?= ($navbar_atual == $i) ? 
                                                        '<i class="iconoir-check me-2"></i>Navbar Ativo' : 
                                                        '<i class="iconoir-eye me-2"></i>Selecionar Navbar' ?>
                                                </div>
                                            </div>
                                            
                                            <div class="navbar-info">
                                                <div class="navbar-name">
                                                    <?= isset($navbars[$i]) ? $navbars[$i]['name'] : 'Navbar ' . $i ?>
                                                    <?php if ($navbar_atual == $i): ?>
                                                        <span class="navbar-badge badge-current">Atual</span>
                                                    <?php else: ?>
                                                        <span class="navbar-badge badge-available">Disponível</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="navbar-description">
                                                    <?= isset($navbars[$i]) ? $navbars[$i]['description'] : 'Estilo de navegação único e funcional' ?>
                                                </div>
                                                
                                                <input type="radio" 
                                                       name="navbar" 
                                                       value="<?= $i ?>" 
                                                       id="navbar<?= $i ?>" 
                                                       <?= ($navbar_atual == $i) ? 'checked' : '' ?>
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
                                    <h4>Nenhum navbar encontrado</h4>
                                    <p>Tente ajustar sua busca ou filtros para encontrar o estilo perfeito</p>
                                </div>
                            </div>
                            
                            <!-- Botão Salvar Flutuante -->
                            <button type="submit" class="save-button" id="saveButton">
                                <i class="iconoir-check me-2"></i>Aplicar Navbar
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
        let selectedNavbar = <?= $navbar_atual ?>;

        // Função para tratar erro de imagem - versão simplificada
        function handleImageError(img, navbarId) {
            // Se a imagem não carregar, ocultar o card ou mostrar mensagem simples
            const card = img.closest('.navbar-card');
            if (card) {
                card.style.opacity = '0.6';
                img.style.display = 'none';
                
                // Criar mensagem simples de imagem não encontrada
                const placeholder = document.createElement('div');
                placeholder.style.cssText = `
                    width: calc(100% - 40px);
                    height: 120px;
                    background: #404040;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: rgba(255,255,255,0.6);
                    font-size: 0.9rem;
                    text-align: center;
                    border-radius: 12px;
                    margin: 20px;
                `;
                placeholder.innerHTML = `Navbar ${navbarId}<br>não encontrado`;
                
                img.parentNode.appendChild(placeholder);
            }
        }

        // Função para selecionar navbar
        function selectNavbar(navbarId, element) {
            // Remover seleção de todos os cards
            document.querySelectorAll('.navbar-card').forEach(card => {
                card.classList.remove('selected');
                const overlay = card.querySelector('.navbar-selector-overlay');
                overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Navbar';
            });

            // Selecionar o card clicado
            element.classList.add('selected');
            const overlay = element.querySelector('.navbar-selector-overlay');
            overlay.innerHTML = '<i class="iconoir-check me-2"></i>Navbar Selecionado';

            // Marcar o radio button
            document.getElementById('navbar' + navbarId).checked = true;
            
            // Atualizar navbar selecionado
            selectedNavbar = navbarId;
            
            // Mostrar botão salvar se navbar diferente do atual
            const saveButton = document.getElementById('saveButton');
            if (navbarId != <?= $navbar_atual ?>) {
                saveButton.classList.add('show');
                saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Navbar ' + navbarId;
            } else {
                saveButton.classList.remove('show');
            }
        }

        // Sistema de filtros
        function setupFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const navbarCards = document.querySelectorAll('.navbar-card');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.dataset.category;
                    
                    navbarCards.forEach(card => {
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
            const searchInput = document.getElementById('navbarSearch');
            const navbarCards = document.querySelectorAll('.navbar-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                navbarCards.forEach(card => {
                    const navbarId = card.dataset.navbarId;
                    const name = card.querySelector('.navbar-name').textContent.toLowerCase();
                    const description = card.querySelector('.navbar-description').textContent.toLowerCase();
                    
                    if (searchTerm === '' || 
                        name.includes(searchTerm) || 
                        description.includes(searchTerm) ||
                        `navbar ${navbarId}`.includes(searchTerm)) {
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
            const visibleCards = Array.from(document.querySelectorAll('.navbar-card')).filter(
                card => card.style.display !== 'none'
            );
            const noResults = document.getElementById('noResults');
            const navbarGrid = document.getElementById('navbarGrid');
            
            if (visibleCards.length === 0) {
                noResults.style.display = 'block';
                navbarGrid.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                navbarGrid.style.display = 'grid';
            }
        }

        // Validação de formulário
        function setupFormValidation() {
            const form = document.getElementById('navbarForm');
            
            form.addEventListener('submit', function(e) {
                const selectedInput = document.querySelector('input[name="navbar"]:checked');
                
                if (!selectedInput) {
                    e.preventDefault();
                    return false;
                }
                
                if (selectedInput.value == <?= $navbar_atual ?>) {
                    e.preventDefault();
                    return false;
                }
                
                // Loading state
                const submitBtn = document.getElementById('saveButton');
                submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Aplicando Navbar...';
                submitBtn.disabled = true;
                
                return true;
            });
        }

        // Atalhos de teclado
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Números 1-5 para selecionar navbars
                if (e.key >= '1' && e.key <= '5' && !e.ctrlKey && !e.altKey) {
                    const navbarNumber = parseInt(e.key);
                    const navbarCard = document.querySelector(`[data-navbar-id="${navbarNumber}"]`);
                    if (navbarCard && navbarCard.style.display !== 'none') {
                        e.preventDefault();
                        selectNavbar(navbarNumber, navbarCard);
                        navbarCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('navbarForm').submit();
                }
                
                // Ctrl + F para focar na busca
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('navbarSearch').focus();
                }
            });
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            setupFilters();
            setupSearch();
            setupFormValidation();
            setupKeyboardShortcuts();
            
            // Mostrar dica inicial
            setTimeout(() => {
                if (!localStorage.getItem('navbar_tips_shown')) {
                    const helpDiv = document.createElement('div');
                    helpDiv.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 16px 20px;
                        border-radius: 12px;
                        font-size: 0.9rem;
                        max-width: 300px;
                        z-index: 1000;
                        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
                        animation: slideInRight 0.5s ease-out;
                    `;
                    helpDiv.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <strong>💡 Dica</strong>
                            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer;">✕</button>
                        </div>
                        <div>Use as teclas 1-5 para selecionar navbars rapidamente!</div>
                    `;
                    document.body.appendChild(helpDiv);
                    
                    // Adicionar animação
                    const style = document.createElement('style');
                    style.textContent = '@keyframes slideInRight { from { transform: translateX(100%); } to { transform: translateX(0); } }';
                    document.head.appendChild(style);
                    
                    // Remover automaticamente após 5 segundos
                    setTimeout(() => {
                        if (helpDiv.parentNode) {
                            helpDiv.style.animation = 'slideInRight 0.5s ease-out reverse';
                            setTimeout(() => helpDiv.remove(), 500);
                        }
                    }, 5000);
                    
                    localStorage.setItem('navbar_tips_shown', 'true');
                }
            }, 2000);
        });
    </script>

</body>
</html>