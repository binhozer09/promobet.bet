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

// Manter as funções originais exatamente como estavam
function get_banners() {
    global $mysqli;
    $qry = "SELECT * FROM floats";
    $result = mysqli_query($mysqli, $qry);
    $banners = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }
    return $banners;
}

function update_banner($id, $titulo, $status, $img = null) {
    global $mysqli;

    if ($img) {
        $qry = $mysqli->prepare("UPDATE floats SET titulo = ?, status = ?, img = ? WHERE id = ?");
        $qry->bind_param("sisi", $titulo, $status, $img, $id);
    } else {
        $qry = $mysqli->prepare("UPDATE floats SET titulo = ?, status = ? WHERE id = ?");
        $qry->bind_param("sii", $titulo, $status, $id);
    }

    return $qry->execute();
}

// Manter a lógica de processamento original
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $status = intval($_POST['status']);

    // Buscar a imagem atual no banco de dados
    $query = "SELECT img FROM floats WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $banner = $result->fetch_assoc();
    $img = $banner['img']; // Manter o nome da imagem atual

    // Verificar se uma nova imagem foi enviada
    if (!empty($_FILES['img']['name'])) {
        $upload_dir = "../image/"; // A pasta uploads está um nível acima
        $img_path = $upload_dir . basename($img); // Usar o mesmo nome da imagem que já está no banco

        // Mover a nova imagem para o caminho correto, substituindo a imagem existente
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
            // A imagem foi movida com sucesso
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar a imagem. Tente novamente.';
        }
    }

    // Atualizar o banner no banco de dados
    if (update_banner($id, $titulo, $status, $img)) {
        $toastType = 'success';
        $toastMessage = 'Icon Float atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o banner. Tente novamente.';
    }
}

$banners = get_banners();
?>

<head>
    <?php $title = "Gerenciamento de Float Icons";
    include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <!-- Estilos customizados modernos -->
    <style>
        .float-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 20px;
            background: #303231;
            color: white;
            overflow: hidden;
            position: relative;
            margin-bottom: 24px;
        }
        
        .float-card::before {
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
        
        .float-card.active::before {
            opacity: 1;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .float-card.inactive::before {
            opacity: 1;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        }
        
        .float-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .float-icon-container {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.1);
            overflow: hidden;
        }
        
        .float-icon-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .float-card:hover .float-icon-img {
            transform: scale(1.05);
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
        
        .action-button {
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 0 4px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            box-shadow: 0 4px 15px rgba(78, 205, 196, 0.3);
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .action-button:hover {
            transform: translateY(-3px);
        }
        
        .btn-edit:hover {
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.4);
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: absolute;
            top: 16px;
            right: 16px;
        }
        
        .status-active {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            color: white;
        }
        
        .status-inactive {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }
        
        .modal-content {
            border-radius: 20px;
            background: #303231;
            color: white;
            border: none;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px 20px 0 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .modal-body {
            padding: 32px;
        }
        
        .stats-card {
            background: rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .stats-label {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .search-box {
            position: relative;
            margin-bottom: 24px;
        }
        
        .search-input {
            width: 100%;
            padding: 16px 20px 16px 60px;
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
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.6);
            font-size: 1.2rem;
        }
        
        .filter-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
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
        
        .float-meta {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
        }
        
        .float-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
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
        
        .float-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        @media (max-width: 768px) {
            .float-grid {
                grid-template-columns: 1fr;
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
                                <i class="iconoir-float me-3"></i>Gerenciamento de Float Icons
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Configure os ícones flutuantes que aparecem na sua plataforma
                            </p>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card">
                            <div class="stats-number"><?= count($banners) ?></div>
                            <div class="stats-label">Total de Float Icons</div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card">
                            <?php 
                            $active_count = 0;
                            foreach($banners as $b) {
                                if($b['status'] == 1) $active_count++;
                            }
                            ?>
                            <div class="stats-number"><?= $active_count ?></div>
                            <div class="stats-label">Icons Ativos</div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="stats-card">
                            <?php 
                            $inactive_count = 0;
                            foreach($banners as $b) {
                                if($b['status'] == 0) $inactive_count++;
                            }
                            ?>
                            <div class="stats-number"><?= $inactive_count ?></div>
                            <div class="stats-label">Icons Inativos</div>
                        </div>
                    </div>

                    <!-- Barra de Busca e Filtros -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                            <!-- Barra de Pesquisa -->
                            <div class="search-box" style="flex: 1; min-width: 300px;">
                                <i class="iconoir-search search-icon"></i>
                                <input type="text" id="searchInput" class="search-input" placeholder="Pesquisar float icons...">
                            </div>
                            
                            <!-- Filtros -->
                            <div class="filter-buttons">
                                <button class="filter-btn active" data-filter="all">Todos</button>
                                <button class="filter-btn" data-filter="active">Ativos</button>
                                <button class="filter-btn" data-filter="inactive">Inativos</button>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Float Icons -->
                    <div class="col-12" id="floatContainer">
                        <?php if (empty($banners)): ?>
                            <div class="text-center py-5">
                                <h4>Nenhum float icon encontrado</h4>
                                <p class="text-muted">Os float icons configurados aparecerão aqui para edição</p>
                            </div>
                        <?php else: ?>
                            <div class="float-grid" id="floatsList">
                                <?php foreach ($banners as $banner): ?>
                                    <div class="float-item" 
                                         data-status="<?= $banner['status'] == 1 ? 'active' : 'inactive' ?>"
                                         data-title="<?= strtolower($banner['titulo']) ?>">
                                        <div class="float-card <?= $banner['status'] == 1 ? 'active' : 'inactive' ?> h-100">
                                            <div class="p-4 position-relative">
                                                <!-- Status Badge -->
                                                <span class="status-badge <?= $banner['status'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                                    <?= $banner['status'] == 1 ? 'Ativo' : 'Inativo' ?>
                                                </span>
                                                
                                                <!-- Ícone -->
                                                <div class="float-icon-container">
                                                    <img src="<?= $banner['img']; ?>" 
                                                         alt="<?= htmlspecialchars($banner['titulo']) ?>" 
                                                         class="float-icon-img"
                                                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div style=&quot;width:60px; height:60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white;&quot;><i class=&quot;iconoir-image&quot;></i></div>';">
                                                </div>
                                                
                                                <!-- Título -->
                                                <h5 class="fw-bold mb-3 text-center"><?= htmlspecialchars($banner['titulo']) ?></h5>
                                                
                                                <!-- Meta informações -->
                                                <div class="float-meta">
                                                    <div class="d-flex justify-content-between">
                                                        <span><i class="iconoir-hashtag me-1"></i>ID: <?= $banner['id'] ?></span>
                                                        <span><i class="iconoir-calendar me-1"></i><?= date('d/m/Y', strtotime($banner['criado_em'])) ?></span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Ações -->
                                                <div class="d-flex justify-content-center mt-4">
                                                    <button class="action-button btn-edit" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editBannerModal<?= $banner['id']; ?>">
                                                        <i class="iconoir-edit me-1"></i>Editar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de Edição - Mantendo a estrutura original -->
                                    <div class="modal fade" id="editBannerModal<?= $banner['id']; ?>" tabindex="-1" aria-labelledby="editBannerLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editBannerLabel">
                                                        <i class="iconoir-edit me-2"></i>Editar Float Icon
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?= $banner['id']; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label for="titulo" class="form-label fw-bold">
                                                                <i class="iconoir-text me-2"></i>Título
                                                            </label>
                                                            <input type="text" class="form-control" name="titulo" 
                                                                   value="<?= htmlspecialchars($banner['titulo']); ?>" 
                                                                   style="background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px 16px;"
                                                                   required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="img" class="form-label fw-bold">
                                                                <i class="iconoir-image me-2"></i>Imagem
                                                            </label>
                                                            
                                                            <!-- Preview da imagem atual -->
                                                            <div class="mb-3 text-center">
                                                                <img src="<?= $banner['img']; ?>" alt="Imagem atual" 
                                                                     style="max-width: 100px; max-height: 100px; border-radius: 12px; border: 2px solid rgba(255,255,255,0.2);"
                                                                     onerror="this.style.display='none';">
                                                            </div>
                                                            
                                                            <input type="file" class="form-control" name="img"
                                                                   style="background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px 16px;">
                                                            <small class="text-muted">Deixe em branco se não quiser alterar a imagem.</small>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label fw-bold">
                                                                <i class="iconoir-switch me-2"></i>Status
                                                            </label>
                                                            <select class="form-select" name="status"
                                                                    style="background: rgba(255,255,255,0.05); border: 2px solid rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 12px 16px;">
                                                                <option value="1" <?= $banner['status'] == 1 ? 'selected' : ''; ?>>✅ Ativo</option>
                                                                <option value="0" <?= $banner['status'] == 0 ? 'selected' : ''; ?>>❌ Inativo</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="text-center">
                                                            <button type="submit" class="action-button btn-edit">
                                                                <i class="iconoir-check me-1"></i>Salvar Alterações
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        // Sistema de busca
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            const floatItems = document.querySelectorAll('.float-item');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    floatItems.forEach(item => {
                        const title = item.dataset.title || '';
                        
                        if (title.includes(searchTerm) || searchTerm === '') {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        }

        // Sistema de filtros
        function setupFilters() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const floatItems = document.querySelectorAll('.float-item');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Atualizar botões ativos
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;
                    
                    floatItems.forEach(item => {
                        const status = item.dataset.status;
                        
                        if (filter === 'all' || filter === status) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        }

        // Animações dos cards
        function setupAnimations() {
            const cards = document.querySelectorAll('.float-card');
            
            cards.forEach((card) => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            setupSearch();
            setupFilters();
            setupAnimations();
        });
    </script>

</body>
</html>