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

// Funções originais mantidas
function get_jackpot_atual()
{
    global $mysqli;
    $qry = "SELECT jackpot FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['jackpot'];
}

function update_jackpot($jackpot)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET jackpot = ? WHERE id = 1");
    $qry->bind_param("i", $jackpot);
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jackpot_selecionado = intval($_POST['jackpot']);

    // LÓGICA DE UPLOAD ORIGINAL RESTAURADA
    if (!empty($_FILES['custom_jackpot']['name'])) {
        $upload_dir = "../uploads/";
        
        // Criar diretório se não existir
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['custom_jackpot']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            // Usar sempre .webp como na versão original
            $custom_img = $upload_dir . "jackpot_custom.webp";
            
            if (move_uploaded_file($_FILES["custom_jackpot"]["tmp_name"], $custom_img)) {
                $jackpot_selecionado = 5;  // Forçar seleção do jackpot customizado
                $toastType = 'success';
                $toastMessage = 'Imagem personalizada carregada e jackpot atualizado!';
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao fazer upload da imagem. Verifique as permissões.';
            }
        } else {
            $toastType = 'error';
            $toastMessage = 'Formato de arquivo não suportado. Use JPG, PNG, GIF ou WEBP.';
        }
    }

    if (update_jackpot($jackpot_selecionado)) {
        if ($toastType !== 'error') {
            $toastType = 'success';
            $toastMessage = 'Jackpot atualizado com sucesso!';
        }
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o jackpot. Tente novamente.';
    }
    
    // Redirect para evitar resubmissão do formulário
    if ($toastType === 'success') {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&jackpot=" . $jackpot_selecionado);
        exit();
    }
}

// Verificar se veio de um redirect de sucesso
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $toastType = 'success';
    $toastMessage = 'Jackpot atualizado com sucesso!';
}

$jackpot_atual = get_jackpot_atual();

// Array com informações dos jackpots
$jackpots = [
    1 => ['name' => 'Clássico', 'description' => 'Design tradicional de jackpot'],
    2 => ['name' => 'Dourado', 'description' => 'Estilo premium dourado'],
    3 => ['name' => 'Neon', 'description' => 'Visual vibrante e moderno'],
    4 => ['name' => 'Minimalista', 'description' => 'Design limpo e elegante'],
    5 => ['name' => 'Personalizado', 'description' => 'Jackpot customizado'],
    6 => ['name' => 'Especial', 'description' => 'Edição especial exclusiva']
];
?>

<head>
    <?php $title = "Personalização de Jackpot"; ?>
    <?php include 'partials/title-meta.php' ?>
    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
    
    <style>
        .jackpot-card {
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
        
        .jackpot-card::before {
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
        
        .jackpot-card.selected::before {
            opacity: 1;
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        
        .jackpot-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        
        .jackpot-card.selected {
            border-color: #56ab2f;
            box-shadow: 0 0 0 2px rgba(86, 171, 47, 0.3);
        }
        
        .jackpot-preview {
            width: 100%;
            height: 180px;
            object-fit: contain;
            transition: transform 0.3s ease;
            padding: 15px;
        }
        
        .jackpot-card:hover .jackpot-preview {
            transform: scale(1.05);
        }
        
        .jackpot-info {
            padding: 20px;
            position: relative;
        }
        
        .jackpot-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .jackpot-description {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 16px;
        }
        
        .jackpot-badge {
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
        
        .badge-custom {
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
        
        .save-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(86, 171, 47, 0.4);
        }
        
        .current-jackpot-banner {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .current-jackpot-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        /* ÁREA DE UPLOAD PERSONALIZADA - FUNCIONAL */
        .custom-upload-card {
            border: 2px dashed rgba(255,255,255,0.3);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(255,255,255,0.05);
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        .custom-upload-card:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.02);
        }
        
        .custom-upload-card.dragover {
            border-color: #56ab2f;
            background: rgba(86, 171, 47, 0.1);
            transform: scale(1.02);
        }
        
        .upload-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin: 0 auto 16px;
        }
        
        .custom-preview-img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 12px;
            border: 2px solid rgba(255,255,255,0.2);
            object-fit: contain;
        }
        
        .upload-text {
            color: rgba(255,255,255,0.9);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .upload-subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Overlay para seleção */
        .jackpot-selector-overlay {
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
            pointer-events: none;
        }
        
        .jackpot-card:hover .jackpot-selector-overlay {
            opacity: 1;
        }
        
        .jackpot-card.selected .jackpot-selector-overlay {
            opacity: 1;
            background: rgba(86, 171, 47, 0.8);
        }
        
        .jackpot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        
        /* Custom upload específico */
        .custom-jackpot-card {
            position: relative;
        }
        
        .custom-jackpot-card .jackpot-selector-overlay {
            pointer-events: auto;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .jackpot-grid {
                grid-template-columns: 1fr;
            }
            
            .save-button {
                position: static;
                transform: none;
                opacity: 1;
                margin-top: 24px;
                width: 100%;
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
                                <i class="iconoir-trophy me-3"></i>Personalização de Jackpot
                            </h3>
                            <p class="mb-0 opacity-75 fs-5">
                                Escolha o visual do jackpot perfeito para sua plataforma
                            </p>
                        </div>
                    </div>

                    <!-- Jackpot Atual -->
                    <div class="col-12">
                        <div class="current-jackpot-banner">
                            <div class="current-jackpot-icon">
                                <i class="iconoir-trophy"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Jackpot Atual: <?= isset($jackpots[$jackpot_atual]) ? $jackpots[$jackpot_atual]['name'] : 'Jackpot ' . $jackpot_atual ?></h5>
                                <p class="mb-0 opacity-75">
                                    <?= isset($jackpots[$jackpot_atual]) ? $jackpots[$jackpot_atual]['description'] : 'Este é o visual de jackpot ativo na sua plataforma.' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário Principal -->
                    <div class="col-12">
                        <form method="POST" action="" enctype="multipart/form-data" id="jackpotForm">
                            <div class="jackpot-grid">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <?php if ($i == 5): ?>
                                        <!-- Card Personalizado - FUNCIONAL -->
                                        <div class="jackpot-card custom-jackpot-card <?= ($jackpot_atual == $i) ? 'selected' : '' ?>" 
                                             data-jackpot-id="<?= $i ?>"
                                             style="cursor: pointer;">
                                            
                                            <div class="position-relative">
                                                <div class="custom-upload-card" id="customUploadArea">
                                                    <?php
                                                    // Verificar se existe imagem personalizada (original usa .webp)
                                                    $custom_img_found = false;
                                                    $custom_img_path = '';
                                                    
                                                    // Verificar primeiro .webp (como na versão original)
                                                    if (file_exists('../uploads/jackpot_custom.webp')) {
                                                        $custom_img_path = '/uploads/jackpot_custom.webp';
                                                        $custom_img_found = true;
                                                    } elseif (file_exists('../uploads/jackpot_custom.png')) {
                                                        $custom_img_path = '/uploads/jackpot_custom.png';
                                                        $custom_img_found = true;
                                                    }
                                                    
                                                    if ($custom_img_found): ?>
                                                        <img src="<?= $custom_img_path ?>?v=<?= time() ?>" class="custom-preview-img" alt="Custom Jackpot">
                                                        <div class="upload-text" style="margin-top: 10px; font-size: 0.8rem;">Clique para alterar imagem</div>
                                                    <?php else: ?>
                                                        <div class="upload-icon">
                                                            <i class="iconoir-cloud-upload"></i>
                                                        </div>
                                                        <div class="upload-text">Clique para fazer upload</div>
                                                        <div class="upload-subtitle">Escolha sua imagem personalizada (JPG, PNG, GIF, WEBP)</div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <input type="file" 
                                                       name="custom_jackpot" 
                                                       id="custom_jackpot" 
                                                       class="d-none" 
                                                       accept="image/*">
                                                
                                                <!-- Overlay removido para não interferir no clique -->
                                            </div>
                                            
                                            <div class="jackpot-info">
                                                <div class="jackpot-name">
                                                    <?= $jackpots[$i]['name'] ?>
                                                    <?php if ($jackpot_atual == $i): ?>
                                                        <span class="jackpot-badge badge-current">Atual</span>
                                                    <?php else: ?>
                                                        <span class="jackpot-badge badge-custom">Upload</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="jackpot-description">
                                                    <?= $jackpots[$i]['description'] ?>
                                                </div>
                                                
                                                <input type="radio" 
                                                       name="jackpot" 
                                                       value="<?= $i ?>" 
                                                       id="jackpot<?= $i ?>" 
                                                       <?= ($jackpot_atual == $i) ? 'checked' : '' ?>
                                                       style="display: none;">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Cards Padrão -->
                                        <div class="jackpot-card <?= ($jackpot_atual == $i) ? 'selected' : '' ?>" 
                                             data-jackpot-id="<?= $i ?>"
                                             onclick="selectJackpot(<?= $i ?>, this)">
                                            
                                            <div class="position-relative">
                                                <img src="/uploads/jackpot<?= $i ?>.png" 
                                                     class="jackpot-preview" 
                                                     alt="Jackpot <?= $i ?>"
                                                     onerror="handleImageError(this, <?= $i ?>)">
                                                
                                                <div class="jackpot-selector-overlay">
                                                    <?= ($jackpot_atual == $i) ? 
                                                        '<i class="iconoir-check me-2"></i>Jackpot Ativo' : 
                                                        '<i class="iconoir-eye me-2"></i>Selecionar Jackpot' ?>
                                                </div>
                                            </div>
                                            
                                            <div class="jackpot-info">
                                                <div class="jackpot-name">
                                                    <?= $jackpots[$i]['name'] ?>
                                                    <?php if ($jackpot_atual == $i): ?>
                                                        <span class="jackpot-badge badge-current">Atual</span>
                                                    <?php else: ?>
                                                        <span class="jackpot-badge badge-available">Disponível</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="jackpot-description">
                                                    <?= $jackpots[$i]['description'] ?>
                                                </div>
                                                
                                                <input type="radio" 
                                                       name="jackpot" 
                                                       value="<?= $i ?>" 
                                                       id="jackpot<?= $i ?>" 
                                                       <?= ($jackpot_atual == $i) ? 'checked' : '' ?>
                                                       style="display: none;">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            
                            <!-- Botão Salvar Flutuante -->
                            <button type="submit" class="save-button" id="saveButton">
                                <i class="iconoir-check me-2"></i>Aplicar Jackpot
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
        let selectedJackpot = <?= $jackpot_atual ?>;

        // FUNÇÃO DE UPLOAD CORRIGIDA E SIMPLIFICADA
        function setupCustomUpload() {
            const uploadArea = document.getElementById('customUploadArea');
            const fileInput = document.getElementById('custom_jackpot');
            const customCard = document.querySelector('[data-jackpot-id="5"]');
            
            // Click no card inteiro do personalizado - abre seletor de arquivos
            customCard.addEventListener('click', function(e) {
                e.stopPropagation();
                console.log('Clicou no card personalizado - abrindo seletor de arquivos');
                fileInput.click();
            });
            
            // Click direto na área de upload
            uploadArea.addEventListener('click', function(e) {
                e.stopPropagation();
                console.log('Clicou na área de upload - abrindo seletor de arquivos');
                fileInput.click();
            });
            
            // Mudança de arquivo - FUNCIONAL
            fileInput.addEventListener('change', function() {
                console.log('Arquivo selecionado:', this.files[0]);
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        uploadArea.innerHTML = `<img src="${e.target.result}" class="custom-preview-img" alt="Preview">`;
                        console.log('Preview da imagem carregado');
                    };
                    reader.readAsDataURL(file);
                    
                    // Selecionar automaticamente o jackpot personalizado
                    selectJackpot(5, customCard);
                }
            });
            
            // Drag and drop FUNCIONAL
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                this.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    
                    // Disparar evento de mudança manualmente
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        }

        // Função para tratar erro de imagem
        function handleImageError(img, jackpotId) {
            const canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 180;
            const ctx = canvas.getContext('2d');
            
            const hue = (jackpotId * 60) % 360;
            const gradient = ctx.createLinearGradient(0, 0, 300, 180);
            gradient.addColorStop(0, `hsl(${hue}, 70%, 60%)`);
            gradient.addColorStop(1, `hsl(${hue + 30}, 60%, 40%)`);
            
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, 300, 180);
            
            ctx.fillStyle = 'white';
            ctx.font = 'bold 48px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('🏆', 150, 100);
            
            ctx.font = 'bold 20px Arial';
            ctx.fillText(`Jackpot ${jackpotId}`, 150, 140);
            
            img.src = canvas.toDataURL();
        }

        // Função para selecionar jackpot
        function selectJackpot(jackpotId, element) {
            // Remover seleção de todos os cards
            document.querySelectorAll('.jackpot-card').forEach(card => {
                card.classList.remove('selected');
                const overlay = card.querySelector('.jackpot-selector-overlay');
                if (overlay) {
                    if (card.dataset.jackpotId === '5') {
                        overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Personalizado';
                    } else {
                        overlay.innerHTML = '<i class="iconoir-eye me-2"></i>Selecionar Jackpot';
                    }
                }
            });

            // Selecionar o card clicado
            element.classList.add('selected');
            const overlay = element.querySelector('.jackpot-selector-overlay');
            if (overlay) {
                overlay.innerHTML = '<i class="iconoir-check me-2"></i>Jackpot Selecionado';
            }

            // Marcar o radio button
            document.getElementById('jackpot' + jackpotId).checked = true;
            
            // Atualizar jackpot selecionado
            selectedJackpot = jackpotId;
            
            // Mostrar botão salvar se jackpot diferente do atual
            const saveButton = document.getElementById('saveButton');
            if (jackpotId != <?= $jackpot_atual ?>) {
                saveButton.classList.add('show');
                saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Jackpot ' + jackpotId;
            } else {
                saveButton.classList.remove('show');
            }
        }

        // Validação de formulário
        function setupFormValidation() {
            const form = document.getElementById('jackpotForm');
            
            form.addEventListener('submit', function(e) {
                const selectedInput = document.querySelector('input[name="jackpot"]:checked');
                
                if (!selectedInput) {
                    e.preventDefault();
                    alert('Por favor, selecione um jackpot.');
                    return false;
                }
                
                // Loading state
                const submitBtn = document.getElementById('saveButton');
                submitBtn.innerHTML = '<i class="iconoir-hourglass me-2"></i>Aplicando Jackpot...';
                submitBtn.disabled = true;
                
                return true;
            });
        }

        // Função de Toast
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            if (!toastPlacement) {
                toastPlacement = document.createElement('div');
                toastPlacement.id = 'toastPlacement';
                toastPlacement.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastPlacement);
            }
            
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

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            setupCustomUpload();
            setupFormValidation();
            
            console.log('Sistema de upload inicializado!');
            console.log('Input file:', document.getElementById('custom_jackpot'));
            console.log('Upload area:', document.getElementById('customUploadArea'));
            console.log('Custom card:', document.querySelector('[data-jackpot-id="5"]'));
        });
    </script>

    <!-- Toast container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Exibir mensagem baseada nas ações -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= $toastType ?>', '<?= addslashes($toastMessage) ?>');
                
                <?php if ($toastType === 'success'): ?>
                    // Atualizar interface sem reload para evitar loop infinito
                    setTimeout(() => {
                        updateInterfaceAfterSuccess();
                    }, 1000);
                <?php endif; ?>
            });
            
            function updateInterfaceAfterSuccess() {
                // Atualizar banner do jackpot atual
                const banner = document.querySelector('.current-jackpot-banner');
                if (banner) {
                    const title = banner.querySelector('h5');
                    const description = banner.querySelector('p');
                    
                    if (title) {
                        title.textContent = 'Jackpot Atual: <?= isset($jackpots[$jackpot_atual]) ? $jackpots[$jackpot_atual]['name'] : 'Jackpot ' . $jackpot_atual ?>';
                    }
                    
                    if (description) {
                        description.textContent = '<?= isset($jackpots[$jackpot_atual]) ? $jackpots[$jackpot_atual]['description'] : 'Este é o visual de jackpot ativo na sua plataforma.' ?>';
                    }
                }
                
                // Remover seleção de todos os cards
                document.querySelectorAll('.jackpot-card').forEach(card => {
                    card.classList.remove('selected');
                    const badge = card.querySelector('.jackpot-badge');
                    if (badge && !card.querySelector('[data-jackpot-id="5"]')) {
                        badge.className = 'jackpot-badge badge-available';
                        badge.textContent = 'Disponível';
                    }
                });
                
                // Marcar o jackpot atual como selecionado
                const currentCard = document.querySelector(`[data-jackpot-id="<?= $jackpot_atual ?>"]`);
                if (currentCard) {
                    currentCard.classList.add('selected');
                    const badge = currentCard.querySelector('.jackpot-badge');
                    if (badge) {
                        badge.className = 'jackpot-badge badge-current';
                        badge.textContent = 'Atual';
                    }
                }
                
                // Esconder botão salvar
                const saveButton = document.getElementById('saveButton');
                if (saveButton) {
                    saveButton.classList.remove('show');
                    saveButton.innerHTML = '<i class="iconoir-check me-2"></i>Aplicar Jackpot';
                    saveButton.disabled = false;
                }
                
                // Se foi upload personalizado, atualizar preview
                <?php if ($jackpot_atual == 5): ?>
                    const uploadArea = document.getElementById('customUploadArea');
                    if (uploadArea) {
                        // Forçar reload da imagem personalizada com timestamp
                        const timestamp = new Date().getTime();
                        <?php if (file_exists('../uploads/jackpot_custom.webp')): ?>
                            uploadArea.innerHTML = `<img src="/uploads/jackpot_custom.webp?v=${timestamp}" class="custom-preview-img" alt="Custom Jackpot"><div class="upload-text" style="margin-top: 10px; font-size: 0.8rem;">Clique para alterar imagem</div>`;
                        <?php endif; ?>
                    }
                <?php endif; ?>
                
                console.log('Interface atualizada com sucesso - sem reload!');
            }
        </script>
    <?php endif; ?>

</body>
</html>