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

function get_tema_atual()
{
    global $mysqli;
    $qry = "SELECT tema FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['tema'];
}

function update_tema($tema)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET tema = ? WHERE id = 1");
    $qry->bind_param("i", $tema);
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tema_selecionado = intval($_POST['tema']);

    if (update_tema($tema_selecionado)) {
        $toastType = 'success';
        $toastMessage = 'Tema atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o tema. Tente novamente.';
    }
}

$tema_atual = get_tema_atual();

// Organizar temas por categorias
$theme_categories = [
    'Clássicos' => ['description' => 'Temas tradicionais e elegantes', 'themes' => range(1, 10)],
    'Modernos' => ['description' => 'Designs contemporâneos e minimalistas', 'themes' => range(11, 20)],
    'Vibrantes' => ['description' => 'Cores intensas e design arrojado', 'themes' => range(21, 30)],
    'Luxo' => ['description' => 'Sofisticação e premium', 'themes' => range(31, 40)],
    'Especiais' => ['description' => 'Edições limitadas e exclusivas', 'themes' => range(41, 51)]
];
?>

<head>
    <?php $title = "Personalização de Temas"; ?>
    <?php include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .theme-card {
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
        
        .theme-card::before {
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
        
        .theme-card.selected::before {
            opacity: 1;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .theme-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .theme-card.selected {
            border-color: #56ab2f;
            box-shadow: 0 0 0 2px rgba(86, 171, 47, 0.3);
        }
        
        .theme-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .theme-preview.loading {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 25%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0.1) 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 2s infinite;
        }
        
        .theme-preview.error {
            background: #303231;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            text-align: center;
            padding: 20px;
        }
        
        .theme-preview.error::before {
            content: '🎨\A Tema ' attr(data-theme-id) '\A Preview indisponível';
            white-space: pre-line;
            line-height: 1.5;
        }
        
        .theme-card:hover .theme-preview {
            transform: scale(1.05);
        }
        
        .theme-info {
            padding: 20px;
            position: relative;
        }
        
        .theme-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .theme-badge {
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
        
        .badge-premium {
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
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
        
        .category-section {
            margin-bottom: 48px;
        }
        
        .category-header {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border-left: 4px solid #667eea;
        }
        
        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .category-description {
            color: rgba(255,255,255,0.8);
            margin-bottom: 16px;
        }
        
        .category-stats {
            display: flex;
            gap: 24px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
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
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            opacity: 0;
            transform: translateY(100px);
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
        
        .theme-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .current-theme-banner {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .current-theme-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .theme-selector-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: 205px;
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
        
        .theme-card:hover .theme-selector-overlay {
            opacity: 1;
        }
        
        .theme-card.selected .theme-selector-overlay {
            opacity: 1;
            background: rgba(86, 171, 47, 0.8);
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
        
        .view-mode-toggle {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }
        
        .view-btn {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255,255,255,0.2);
            background: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            transition: all 0.3s ease;
        }
        
        .view-btn.active, .view-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.2);
            color: white;
        }
        
        .theme-grid.grid-view {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }
        
        .theme-grid.list-view {
            grid-template-columns: 1fr;
        }
        
        .theme-grid.list-view .theme-card {
            display: flex;
            align-items: center;
            height: 120px;
        }
        
        .theme-grid.list-view .theme-preview {
            width: 200px;
            height: 100%;
            flex-shrink: 0;
        }
        
        .theme-grid.compact-view {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        
        .theme-grid.compact-view .theme-preview {
            height: 150px;
        }
        
        .theme-grid.compact-view .theme-info {
            padding: 16px;
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
        
        .theme-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .theme-card:nth-child(1) { animation-delay: 0.1s; }
        .theme-card:nth-child(2) { animation-delay: 0.2s; }
        .theme-card:nth-child(3) { animation-delay: 0.3s; }
        .theme-card:nth-child(4) { animation-delay: 0.4s; }
        .theme-card:nth-child(5) { animation-delay: 0.5s; }
        
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
            .theme-grid {
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
                                <i class="iconoir-color-palette me-3"></i>Personalização de Temas
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Escolha o tema perfeito para sua plataforma e crie uma experiência única
                            </p>
                        </div>
                    </div>

                    <!-- Tema Atual -->
                    <div class="col-12">
                        <div class="current-theme-banner">
                            <div class="current-theme-icon">
                                <i class="iconoir-check"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Tema Atual: Tema <?= $tema_atual ?></h5>
                                <p class="mb-0 opacity-75">
                                    Este é o tema ativo na sua plataforma. Os usuários estão vendo este design.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Barra de Filtros e Busca -->
                    <div class="col-12">
                        <div class="filter-bar">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="filter-btn active" data-category="all">Todos os Temas</button>
                                <?php foreach ($theme_categories as $category => $info): ?>
                                    <button class="filter-btn" data-category="<?= strtolower($category) ?>"><?= $category ?></button>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="search-box">
                                <i class="iconoir-search search-icon"></i>
                                <input type="text" id="themeSearch" class="search-input" placeholder="Buscar temas...">
                            </div>
                            
                            <div class="view-mode-toggle">
                                <button class="view-btn active" data-view="grid" title="Visualização em Grade">
                                    <i class="iconoir-view-grid"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" id="themeForm">
                            <!-- Container para todas as categorias -->
                            <div id="themesContainer">
                                
                                <?php foreach ($theme_categories as $category => $info): ?>
                                    <div class="category-section" data-category="<?= strtolower($category) ?>">
                                        <div class="category-header">
                                            <div class="category-title">
                                                <i class="iconoir-design-pencil"></i>
                                                <?= $category ?>
                                            </div>
                                            <div class="category-description">
                                                <?= $info['description'] ?>
                                            </div>
                                            <div class="category-stats">
                                                <span><i class="iconoir-palette me-1"></i><?= count($info['themes']) ?> temas disponíveis</span>
                                                <span><i class="iconoir-star me-1"></i>Qualidade premium</span>
                                            </div>
                                        </div>
                                        
                                        <div class="theme-grid grid-view" id="themeGrid">
                                            <?php foreach ($info['themes'] as $theme_id): ?>
                                                <div class="theme-card <?= ($tema_atual == $theme_id) ? 'selected' : '' ?>" 
                                                     data-theme-id="<?= $theme_id ?>"
                                                     data-category="<?= strtolower($category) ?>"
                                                     onclick="selectTheme(<?= $theme_id ?>, this)">
                                                    
                                                    <div class="position-relative">
                                                        <img src="/uploads/tema<?= $theme_id ?>.png" 
                                                             class="theme-preview" 
                                                             alt="Tema <?= $theme_id ?>"
                                                             loading="lazy">
                                                        
                                                        <div class="theme-selector-overlay">
                                                            <?= ($tema_atual == $theme_id) ? 
                                                                '<i class="iconoir-check me-2"></i>Tema Ativo' : 
                                                                '<i class="iconoir-eye me-2"></i>Selecionar Tema' ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="theme-info">
                                                        <div class="theme-name">
                                                            Tema <?= $theme_id ?>
                                                            <?php if ($tema_atual == $theme_id): ?>
                                                                <span class="theme-badge badge-current">Atual</span>
                                                            <?php elseif ($theme_id > 40): ?>
                                                                <span class="theme-badge badge-premium">Premium</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <input type="radio" 
                                                               name="tema" 
                                                               value="<?= $theme_id ?>" 
                                                               id="tema<?= $theme_id ?>" 
                                                               <?= ($tema_atual == $theme_id) ? 'checked' : '' ?>
                                                               style="display: none;">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <!-- Estado de "Nenhum resultado" -->
                                <div id="noResults" class="no-results" style="display: none;">
                                    <div class="no-results-icon">
                                        <i class="iconoir-search"></i>
                                    </div>
                                    <h4>Nenhum tema encontrado</h4>
                                    <p>Tente ajustar sua busca ou filtros para encontrar o tema perfeito</p>
                                </div>
                            </div>
                            
                            <!-- Botão Salvar Flutuante -->
                            <button type="submit" class="save-button" id="saveButton">
                                <i class="iconoir-check me-2"></i>Aplicar Tema
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container para mensagens discretas (substitui toast) -->
    <div id="messageContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000;"></div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        // Função para tratar erro de imagem - VERSÃO MELHORADA
        function handleImageError(img) {
            const themeId = img.dataset.themeId;
            
            // Lista de caminhos alternativos mais abrangente
            const alternativePaths = [
                `/uploads/tema${themeId}.png`,
                `/uploads/temas/tema${themeId}.png`,
                `/uploads/themes/tema${themeId}.png`,
                `/assets/images/tema${themeId}.png`,
                `/assets/images/themes/tema${themeId}.png`,
                `/images/tema${themeId}.png`,
                `/images/themes/tema${themeId}.png`,
                `/uploads/tema${themeId}.jpg`,
                `/uploads/tema${themeId}.jpeg`,
                `/uploads/tema${themeId}.webp`,
                `/assets/tema${themeId}.png`,
                `./uploads/tema${themeId}.png`,
                `../uploads/tema${themeId}.png`
            ];
            
            console.log(`Tentando carregar tema ${themeId}...`);
            tryAlternativePath(img, alternativePaths, 0);
        }
        
        function tryAlternativePath(img, paths, index) {
            if (index >= paths.length) {
                console.log(`Todas as tentativas falharam para tema ${img.dataset.themeId}, criando placeholder`);
                createPlaceholderImage(img);
                return;
            }
            
            const currentPath = paths[index];
            console.log(`Tentando carregar: ${currentPath}`);
            
            // Criar nova imagem para teste
            const testImg = new Image();
            
            testImg.onload = function() {
                console.log(`Sucesso ao carregar: ${currentPath}`);
                img.src = currentPath;
                img.classList.remove('error', 'loading');
                img.style.display = 'block';
                img.style.objectFit = 'cover';
            };
            
            testImg.onerror = function() {
                console.log(`Falhou ao carregar: ${currentPath}`);
                // Tentar próximo caminho
                setTimeout(() => {
                    tryAlternativePath(img, paths, index + 1);
                }, 100); // Pequeno delay entre tentativas
            };
            
            // Definir timeout para cada tentativa
            setTimeout(() => {
                if (!testImg.complete) {
                    console.log(`Timeout para: ${currentPath}`);
                    testImg.onerror();
                }
            }, 3000);
            
            testImg.src = currentPath;
        }
        
        function createPlaceholderImage(img) {
            const themeId = img.dataset.themeId;
            const canvas = document.createElement('canvas');
            canvas.width = 400;
            canvas.height = 250;
            const ctx = canvas.getContext('2d');
            
            // Gradiente de fundo baseado no ID do tema
            const hue = (parseInt(themeId) * 25) % 360;
            const gradient = ctx.createLinearGradient(0, 0, 400, 250);
            gradient.addColorStop(0, `hsl(${hue}, 70%, 60%)`);
            gradient.addColorStop(0.5, `hsl(${hue + 20}, 60%, 50%)`);
            gradient.addColorStop(1, `hsl(${hue + 40}, 70%, 40%)`);
            
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, 400, 250);
            
            // Adicionar padrão geométrico
            ctx.globalAlpha = 0.3;
            ctx.fillStyle = 'white';
            for (let i = 0; i < 10; i++) {
                for (let j = 0; j < 8; j++) {
                    if ((i + j) % 2 === 0) {
                        ctx.fillRect(i * 40, j * 31.25, 20, 15.625);
                    }
                }
            }
            ctx.globalAlpha = 1;
            
            // Adicionar sombra ao texto
            ctx.shadowColor = 'rgba(0,0,0,0.5)';
            ctx.shadowBlur = 4;
            ctx.shadowOffsetX = 2;
            ctx.shadowOffsetY = 2;
            
            // Título principal
            ctx.fillStyle = 'white';
            ctx.font = 'bold 32px Arial, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(`TEMA ${themeId}`, 200, 100);
            
            // Subtítulo
            ctx.font = '18px Arial, sans-serif';
            ctx.fillStyle = 'rgba(255,255,255,0.9)';
            ctx.fillText('Preview Personalizado', 200, 130);
            
            // Ícone grande
            ctx.font = '48px Arial, sans-serif';
            ctx.fillText('🎨', 200, 180);
            
            // Remover sombra
            ctx.shadowColor = 'transparent';
            
            // Borda decorativa
            ctx.strokeStyle = 'rgba(255,255,255,0.6)';
            ctx.lineWidth = 3;
            ctx.strokeRect(10, 10, 380, 230);
            
            // Aplicar a imagem gerada
            img.src = canvas.toDataURL('image/png', 0.9);
            img.classList.remove('error', 'loading');
            img.style.display = 'block';
            img.style.objectFit = 'cover';
            
            console.log(`Placeholder criado para tema ${themeId}`);
        }
        
        function handleImageLoad(img) {
            img.classList.remove('loading', 'error');
            img.style.animation = 'fadeInUp 0.5s ease-out';
            img.style.objectFit = 'cover';
            console.log(`Imagem carregada com sucesso: tema ${img.dataset.themeId}`);
        }
        
        // Função para forçar recarregamento de imagens
        function reloadAllImages() {
            const images = document.querySelectorAll('.theme-preview');
            images.forEach(img => {
                const themeId = img.dataset.themeId;
                if (themeId) {
                    img.classList.add('loading');
                    img.style.display = 'block';
                    
                    // Tentar carregar a imagem padrão primeiro
                    const newImg = new Image();
                    newImg.onload = function() {
                        img.src = this.src;
                        handleImageLoad(img);
                    };
                    newImg.onerror = function() {
                        handleImageError(img);
                    };
                    newImg.src = `/uploads/tema${themeId}.png?t=${Date.now()}`; // Cache buster
                }
            });
        }

        // Adicionar botão para recarregar imagens
        function addReloadButton() {
            const filterBar = document.querySelector('.filter-bar');
            if (filterBar && !document.getElementById('reloadImagesBtn')) {
                const reloadBtn = document.createElement('button');
                reloadBtn.id = 'reloadImagesBtn';
                reloadBtn.className = 'filter-btn';
                reloadBtn.innerHTML = '<i class="iconoir-refresh me-2"></i>Recarregar Imagens';
                reloadBtn.title = 'Forçar recarregamento de todas as imagens';
                reloadBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="iconoir-hourglass me-2"></i>Recarregando...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        reloadAllImages();
                        this.innerHTML = '<i class="iconoir-refresh me-2"></i>Recarregar Imagens';
                        this.disabled = false;
                    }, 1000);
                });
                
                filterBar.appendChild(reloadBtn);
            }
        }
        
        let selectedTheme = <?= $tema_atual ?>;
        let currentView = 'grid';

        // Pré-carregar imagens críticas
        function preloadCriticalImages() {
            const currentThemeImg = new Image();
            currentThemeImg.src = `/uploads/tema<?= $tema_atual ?>.png`;
            
            // Pré-carregar os primeiros 5 temas da categoria ativa
            for (let i = 1; i <= 5; i++) {
                const img = new Image();
                img.src = `/uploads/tema${i}.png`;
            }
        }
        
        // Sistema de cache de imagens
        const imageCache = new Map();
        
        function loadImageWithCache(src) {
            return new Promise((resolve, reject) => {
                if (imageCache.has(src)) {
                    resolve(imageCache.get(src));
                    return;
                }
                
                const img = new Image();
                img.onload = () => {
                    imageCache.set(src, img.src);
                    resolve(img.src);
                };
                img.onerror = reject;
                img.src = src;
            });
        }
        
        // Melhorar o sistema de lazy loading
        function setupAdvancedLazyLoading() {
            const images = document.querySelectorAll('.theme-preview');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const themeId = img.dataset.themeId;
                        
                        if (!themeId) {
                            console.error('Tema ID não encontrado para imagem:', img);
                            observer.unobserve(img);
                            return;
                        }
                        
                        // Adicionar classe de loading
                        img.classList.add('loading');
                        
                        // Tentar carregar a imagem principal primeiro
                        const mainPath = `/uploads/tema${themeId}.png`;
                        
                        loadImageWithCache(mainPath)
                            .then(src => {
                                img.src = src;
                                handleImageLoad(img);
                            })
                            .catch(() => {
                                console.log(`Falha ao carregar imagem principal para tema ${themeId}, tentando alternativas...`);
                                handleImageError(img);
                            });
                        
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '100px', // Aumentar margem para carregar antes
                threshold: 0.1
            });

            images.forEach(img => {
                // Garantir que o data-theme-id existe
                if (!img.dataset.themeId) {
                    // Extrair do alt ou src
                    const altMatch = img.alt.match(/Tema (\d+)/);
                    const srcMatch = img.src.match(/tema(\d+)/);
                    
                    if (altMatch) {
                        img.dataset.themeId = altMatch[1];
                    } else if (srcMatch) {
                        img.dataset.themeId = srcMatch[1];
                    }
                }
                
                // Inicializar com loading
                img.classList.add('loading');
                imageObserver.observe(img);
            });
        }
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
                    <h5 class="me-auto my-0">Temas</h5>
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

        // Função para selecionar tema
        function selectTheme(themeId, element) {
            // Remover seleção de todos os cards
            document.querySelectorAll('.theme-card').forEach(card => {
                card.classList.remove('selected');
                const overlay = card.querySelector('.theme-selector-overlay');
                overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Tema';
            });

            // Selecionar o card clicado
            element.classList.add('selected');
            const overlay = element.querySelector('.theme-selector-overlay');
            overlay.innerHTML = '<i class="iconoir-check me-2"></i>Tema Selecionado';

            // Marcar o radio button
            document.getElementById('tema' + themeId).checked = true;
            
            // Atualizar tema selecionado
            selectedTheme = themeId;
            
            // Mostrar botão salvar se tema diferente do atual
            const saveButton = document.getElementById('saveButton');
            if (themeId != <?= $tema_atual ?>) {
                saveButton.classList.add('show');
                saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Tema ' + themeId;
            } else {
                saveButton.classList.remove('show');
            }
        }

        // Sistema de filtros
        function setupFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const categoryElements = document.querySelectorAll('.category-section');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.dataset.category;
                    
                    categoryElements.forEach(section => {
                        if (category === 'all' || section.dataset.category === category) {
                            section.style.display = 'block';
                            section.style.animation = 'fadeInUp 0.5s ease-out';
                        } else {
                            section.style.display = 'none';
                        }
                    });

                    updateNoResultsState();
                });
            });
        }

        // Sistema de busca
        function setupSearch() {
            const searchInput = document.getElementById('themeSearch');
            const themeCards = document.querySelectorAll('.theme-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                themeCards.forEach(card => {
                    const themeId = card.dataset.themeId;
                    const category = card.dataset.category;
                    
                    if (searchTerm === '' || 
                        `tema ${themeId}`.includes(searchTerm) || 
                        category.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Mostrar/ocultar seções vazias
                document.querySelectorAll('.category-section').forEach(section => {
                    const visibleCards = section.querySelectorAll('.theme-card[style*="block"], .theme-card:not([style*="none"])');
                    if (visibleCards.length === 0 && searchTerm !== '') {
                        section.style.display = 'none';
                    } else if (searchTerm === '') {
                        section.style.display = 'block';
                    }
                });

                updateNoResultsState();
            });
        }

        // Sistema de visualização
        function setupViewModes() {
            const viewButtons = document.querySelectorAll('.view-btn');
            const themeGrids = document.querySelectorAll('.theme-grid');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const view = this.dataset.view;
                    currentView = view;
                    
                    themeGrids.forEach(grid => {
                        grid.className = `theme-grid ${view}-view`;
                    });
                    
                    // Salvar preferência
                    localStorage.setItem('theme_view_mode', view);
                });
            });
            
            // Carregar preferência salva
            const savedView = localStorage.getItem('theme_view_mode');
            if (savedView) {
                const viewBtn = document.querySelector(`[data-view="${savedView}"]`);
                if (viewBtn) {
                    viewBtn.click();
                }
            }
        }

        // Atualizar estado de "nenhum resultado"
        function updateNoResultsState() {
            const visibleSections = Array.from(document.querySelectorAll('.category-section')).filter(
                section => section.style.display !== 'none'
            );
            const noResults = document.getElementById('noResults');
            
            if (visibleSections.length === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        // Sistema de loading lazy para imagens
        function setupLazyLoading() {
            // Usar o sistema avançado
            setupAdvancedLazyLoading();
        }

        // Validação de formulário
        function setupFormValidation() {
            const form = document.getElementById('themeForm');
            
            form.addEventListener('submit', function(e) {
                const selectedInput = document.querySelector('input[name="tema"]:checked');
                
                if (!selectedInput) {
                    e.preventDefault();
                    return false;
                }
                
                if (selectedInput.value == <?= $tema_atual ?>) {
                    e.preventDefault();
                    return false;
                }
                
                // Loading state
                const submitBtn = document.getElementById('saveButton');
                submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Aplicando Tema...';
                submitBtn.disabled = true;
                
                // Não adicionar overlay - deixar mais discreto
                
                // Permitir o envio do formulário
                return true;
            });
        }

        // Atalhos de teclado
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Números 1-9 para selecionar temas rápidos
                if (e.key >= '1' && e.key <= '9' && !e.ctrlKey && !e.altKey) {
                    const themeNumber = parseInt(e.key);
                    const themeCard = document.querySelector(`[data-theme-id="${themeNumber}"]`);
                    if (themeCard && themeCard.style.display !== 'none') {
                        e.preventDefault();
                        selectTheme(themeNumber, themeCard);
                        themeCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                // Ctrl + S para salvar
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('themeForm').submit();
                }
                
                // Ctrl + F para focar na busca
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('themeSearch').focus();
                }
                
                // Setas para navegar entre temas
                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    const visibleCards = Array.from(document.querySelectorAll('.theme-card')).filter(
                        card => card.style.display !== 'none'
                    );
                    const currentIndex = visibleCards.findIndex(card => card.classList.contains('selected'));
                    
                    if (currentIndex !== -1) {
                        e.preventDefault();
                        let newIndex;
                        
                        if (e.key === 'ArrowLeft') {
                            newIndex = currentIndex > 0 ? currentIndex - 1 : visibleCards.length - 1;
                        } else {
                            newIndex = currentIndex < visibleCards.length - 1 ? currentIndex + 1 : 0;
                        }
                        
                        const newCard = visibleCards[newIndex];
                        const themeId = parseInt(newCard.dataset.themeId);
                        selectTheme(themeId, newCard);
                        newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                // Enter para aplicar tema selecionado
                if (e.key === 'Enter' && e.target.tagName !== 'INPUT') {
                    const saveButton = document.getElementById('saveButton');
                    if (saveButton.classList.contains('show')) {
                        e.preventDefault();
                        document.getElementById('themeForm').submit();
                    }
                }
            });
        }

        // Preview em tempo real (simulação)
        function setupPreview() {
            const themeCards = document.querySelectorAll('.theme-card');
            
            themeCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    // Adicionar efeito de preview suave
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                    
                    // Mostrar informações adicionais no hover
                    const themeId = this.dataset.themeId;
                    const category = this.dataset.category;
                    
                    // Criar tooltip se não existir
                    if (!this.querySelector('.theme-tooltip')) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'theme-tooltip';
                        tooltip.style.cssText = `
                            position: absolute;
                            top: -40px;
                            left: 50%;
                            transform: translateX(-50%);
                            background: rgba(0,0,0,0.9);
                            color: white;
                            padding: 8px 16px;
                            border-radius: 8px;
                            font-size: 0.8rem;
                            white-space: nowrap;
                            z-index: 1000;
                            opacity: 0;
                            transition: opacity 0.3s ease;
                        `;
                        tooltip.textContent = `Categoria: ${category.charAt(0).toUpperCase() + category.slice(1)}`;
                        this.appendChild(tooltip);
                        
                        setTimeout(() => tooltip.style.opacity = '1', 100);
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    
                    // Remover tooltip
                    const tooltip = this.querySelector('.theme-tooltip');
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                        setTimeout(() => tooltip.remove(), 300);
                    }
                });
            });
        }

        // Estatísticas e analytics
        function setupAnalytics() {
            const themeCards = document.querySelectorAll('.theme-card');
            
            themeCards.forEach(card => {
                card.addEventListener('click', function() {
                    const themeId = this.dataset.themeId;
                    
                    // Simular analytics (você pode integrar com seu sistema de analytics)
                    console.log(`Theme ${themeId} clicked at ${new Date().toISOString()}`);
                    
                    // Salvar no localStorage para analytics locais
                    const analytics = JSON.parse(localStorage.getItem('theme_analytics') || '{}');
                    analytics[themeId] = (analytics[themeId] || 0) + 1;
                    localStorage.setItem('theme_analytics', JSON.stringify(analytics));
                });
            });
        }

        // Sistema de favoritos
        function setupFavorites() {
            const favorites = JSON.parse(localStorage.getItem('favorite_themes') || '[]');
            
            // Adicionar ícones de favorito aos cards
            document.querySelectorAll('.theme-card').forEach(card => {
                const themeId = parseInt(card.dataset.themeId);
                const isFavorite = favorites.includes(themeId);
                
                const favoriteBtn = document.createElement('button');
                favoriteBtn.className = 'favorite-btn';
                favoriteBtn.style.cssText = `
                    position: absolute;
                    top: 12px;
                    right: 12px;
                    width: 32px;
                    height: 32px;
                    border: none;
                    background: rgba(0,0,0,0.7);
                    color: ${isFavorite ? '#ff6b6b' : 'rgba(255,255,255,0.7)'};
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    z-index: 10;
                `;
                favoriteBtn.innerHTML = isFavorite ? '❤️' : '🤍';
                favoriteBtn.title = isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos';
                
                favoriteBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleFavorite(themeId, this);
                });
                
                card.querySelector('.position-relative').appendChild(favoriteBtn);
            });
        }

        function toggleFavorite(themeId, button) {
            let favorites = JSON.parse(localStorage.getItem('favorite_themes') || '[]');
            const index = favorites.indexOf(themeId);
            
            if (index > -1) {
                favorites.splice(index, 1);
                button.innerHTML = '🤍';
                button.style.color = 'rgba(255,255,255,0.7)';
                button.title = 'Adicionar aos favoritos';
            } else {
                favorites.push(themeId);
                button.innerHTML = '❤️';
                button.style.color = '#ff6b6b';
                button.title = 'Remover dos favoritos';
            }
            
            localStorage.setItem('favorite_themes', JSON.stringify(favorites));
        }

        // Sistema de comparação
        function setupComparison() {
            let comparisonMode = false;
            let selectedForComparison = [];
            
            // Adicionar botão de comparação se não existir
            const filterBar = document.querySelector('.filter-bar');
            const compareBtn = document.createElement('button');
            compareBtn.className = 'filter-btn';
            compareBtn.innerHTML = '<i class="iconoir-compare me-2"></i>Comparar Temas';
            compareBtn.addEventListener('click', toggleComparisonMode);
            filterBar.appendChild(compareBtn);
            
            function toggleComparisonMode() {
                comparisonMode = !comparisonMode;
                selectedForComparison = [];
                
                if (comparisonMode) {
                    compareBtn.classList.add('active');
                    compareBtn.innerHTML = '<i class="iconoir-cancel me-2"></i>Cancelar Comparação';
                } else {
                    compareBtn.classList.remove('active');
                    compareBtn.innerHTML = '<i class="iconoir-compare me-2"></i>Comparar Temas';
                    document.querySelectorAll('.comparison-selected').forEach(el => {
                        el.classList.remove('comparison-selected');
                    });
                }
            }
        }

        // Inicialização completa
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar pré-carregamento
            preloadCriticalImages();
            
            setupFilters();
            setupSearch();
            setupViewModes();
            setupLazyLoading();
            setupFormValidation();
            setupKeyboardShortcuts();
            setupPreview();
            setupAnalytics();
            setupFavorites();
            setupComparison();
            
            // Verificar se todas as imagens carregaram após 5 segundos
            setTimeout(() => {
                const loadingImages = document.querySelectorAll('.theme-preview.loading');
                loadingImages.forEach(img => {
                    if (img.classList.contains('loading')) {
                        handleImageError(img);
                    }
                });
            }, 5000);
            
            // Mostrar dica inicial apenas uma vez
            setTimeout(() => {
                if (!localStorage.getItem('theme_tips_shown')) {
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
                        <div>Use as teclas 1-9 para selecionar temas rapidamente!</div>
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
                    
                    localStorage.setItem('theme_tips_shown', 'true');
                }
            }, 2000);
            
            // Remover completamente o beforeunload que pode causar loops
            // window.addEventListener('beforeunload', function() {
            //     const selectedInput = document.querySelector('input[name="tema"]:checked');
            //     if (selectedInput && selectedInput.value != <?= $tema_atual ?>) {
            //         return 'Você tem alterações não salvas. Deseja sair mesmo assim?';
            //     }
            // });
            
            // Auto-save de preferências (sem interferir no formulário)
            setInterval(() => {
                const preferences = {
                    viewMode: currentView,
                    lastVisited: Date.now(),
                    selectedTheme: selectedTheme
                };
                try {
                    localStorage.setItem('theme_preferences', JSON.stringify(preferences));
                } catch (e) {
                    console.log('Erro ao salvar preferências:', e);
                }
            }, 30000); // A cada 30 segundos
            
            // Debug: Verificar se há eventos que podem estar causando reload
            ['beforeunload', 'unload', 'pagehide'].forEach(eventType => {
                window.addEventListener(eventType, function(e) {
                    console.log(`Evento ${eventType} detectado:`, e);
                }, true);
            });
            
            // Verificar se o formulário está sendo submetido múltiplas vezes
            let formSubmitted = false;
            const form = document.getElementById('themeForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (formSubmitted) {
                        console.log('Formulário já foi submetido, bloqueando envio duplo');
                        e.preventDefault();
                        return false;
                    }
                    formSubmitted = true;
                    
                    // Resetar flag após 5 segundos
                    setTimeout(() => {
                        formSubmitted = false;
                    }, 5000);
                });
            }
            
            // Debug: Mostrar status das imagens no console
            setTimeout(() => {
                const totalImages = document.querySelectorAll('.theme-preview').length;
                const loadedImages = document.querySelectorAll('.theme-preview.loaded').length;
                const errorImages = document.querySelectorAll('.theme-preview.error').length;
                console.log(`Imagens: ${loadedImages} carregadas, ${errorImages} com erro, ${totalImages} total`);
            }, 10000);
        });

        // Service Worker para cache de imagens (opcional)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(console.error);
        }
    </script>

    <!-- Exibir mensagem baseada nas ações (SEM recarregamento automático) -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Apenas atualizar o visual, SEM recarregar a página
                <?php if ($toastType === 'success'): ?>
                    const banner = document.querySelector('.current-theme-banner');
                    if (banner) {
                        banner.style.background = 'linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%)';
                        const message = banner.querySelector('p');
                        if (message) {
                            message.textContent = 'Tema aplicado com sucesso! ✅';
                            message.style.fontWeight = '600';
                        }
                        
                        // Atualizar tema atual no banner
                        const title = banner.querySelector('h5');
                        if (title) {
                            title.textContent = 'Tema Atual: Tema <?= $tema_atual ?>';
                        }
                        
                        // Atualizar visual dos cards
                        document.querySelectorAll('.theme-card').forEach(card => {
                            card.classList.remove('selected');
                            const overlay = card.querySelector('.theme-selector-overlay');
                            if (overlay) {
                                overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Tema';
                            }
                        });
                        
                        // Marcar o tema atual como selecionado
                        const currentCard = document.querySelector(`[data-theme-id="<?= $tema_atual ?>"]`);
                        if (currentCard) {
                            currentCard.classList.add('selected');
                            const overlay = currentCard.querySelector('.theme-selector-overlay');
                            if (overlay) {
                                overlay.innerHTML = '<i class="iconoir-check me-2"></i>Tema Ativo';
                            }
                        }
                        
                        // Esconder botão salvar
                        const saveButton = document.getElementById('saveButton');
                        if (saveButton) {
                            saveButton.classList.remove('show');
                            saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Tema';
                            saveButton.disabled = false;
                        }
                        
                        // Atualizar tema selecionado globalmente
                        selectedTheme = <?= $tema_atual ?>;
                    }
                <?php elseif ($toastType === 'error'): ?>
                    const banner = document.querySelector('.current-theme-banner');
                    if (banner) {
                        banner.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)';
                        const message = banner.querySelector('p');
                        if (message) {
                            message.textContent = 'Erro ao aplicar tema. Tente novamente. ❌';
                            message.style.fontWeight = '600';
                        }
                        
                        // Reabilitar botão salvar
                        const saveButton = document.getElementById('saveButton');
                        if (saveButton) {
                            saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Tema';
                            saveButton.disabled = false;
                        }
                    }
                <?php endif; ?>
                
                // Limpar parâmetros da URL para evitar loops
                if (window.location.search || window.location.hash) {
                    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            });
        </script>
    <?php endif; ?>

</body>
</html>