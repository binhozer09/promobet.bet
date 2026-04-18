<?php

session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
header_remove('X-Frame-Options');
header_remove('Content-Security-Policy');
header('Content-Security-Policy: frame-ancestors *');
header('Access-Control-Allow-Origin: *');
header('Cross-Origin-Resource-Policy: cross-origin');

$allHeaders = headers_list();
error_log('Headers atuais: ' . print_r($allHeaders, true));
$is_refresh = false;
if (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0') {
    $is_refresh = true;
    error_log('Pixqr.php: Página sendo recarregada');
}

$recovering_from_error = isset($_GET['recover']) && $_GET['recover'] === 'true';

if ((!isset($_GET['paymentCode']) || !isset($_GET['valorPix'])) && !$is_refresh && !$recovering_from_error) {
    if (isset($_SESSION['pix_data'])) {
        $pixQr = $_SESSION['pix_data']['paymentCode'];
        $pixQrBase64 = $_SESSION['pix_data']['paymentCodeBase64'] ?? '';
        $pixValor = $_SESSION['pix_data']['valorPix'];
    } else {
        error_log('Pixqr.php: Parâmetros insuficientes e sem dados na sessão');
        header('Location: /deposit?error=missing_params');
        exit;
    }
} else {
    $pixQr = $_GET['paymentCode'] ?? ($_SESSION['pix_data']['paymentCode'] ?? '');
    $pixQrBase64 = $_GET['paymentCodeBase64'] ?? ($_SESSION['pix_data']['paymentCodeBase64'] ?? '');
    $pixValor = floatval($_GET['valorPix'] ?? ($_SESSION['pix_data']['valorPix'] ?? 0));
    
    $_SESSION['pix_data'] = [
        'paymentCode' => $pixQr,
        'paymentCodeBase64' => $pixQrBase64,
        'valorPix' => $pixValor,
        'timestamp' => time()
    ];
}

$transactionId = $_GET['transactionId'] ?? ($_SESSION['pix_data']['transactionId'] ?? (date('Ymd') . rand(100000, 999999)));

// Salvar transactionId na sessão
if (isset($_GET['transactionId'])) {
    $_SESSION['pix_data']['transactionId'] = $_GET['transactionId'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depósito</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: transparent !important;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .header {
            background-color: transparent !important;
            color: white;
            padding: 16px 20px;
            position: relative;
            text-align: center;
        }

        .header h1 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .back-btn {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .content {
            padding: 20px;
        }

        .transaction-id {
            background-color: #f5f7fa;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .transaction-id strong {
            display: block;
            color: #333;
            font-size: 1.1rem;
            margin-top: 5px;
        }

        .amount-container {
            text-align: center;
            margin-bottom: 24px;
        }

        .amount-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 4px;
        }

        .amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a2342;
        }

        .instruction-text {
            text-align: center;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .qr-container {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
            position: relative;
        }

        .qr-frame {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 16px;
            position: relative;
            background-color: white;
        }

        #qrcode img {
            width: 200px;
            height: 200px;
            display: block;
        }

        .pix-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 5px;
            border-radius: 5px;
            width: 40px;
            height: 40px;
        }

        .copy-btn {
            display: block;
            width: 100%;
            background-color: #fabb40;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background-color: #f5a623;
        }

        .copy-btn svg {
            margin-left: 8px;
            width: 18px;
            height: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: #999;
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider::before {
            margin-right: .5em;
        }

        .divider::after {
            margin-left: .5em;
        }

        .notice-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            font-size: 0.85rem;
            color: #666;
            margin-top: 16px;
            border-left: 4px solid #fabb40;
        }

        .notice-box p {
            margin-bottom: 8px;
        }

        .notice-box p:last-child {
            margin-bottom: 0;
        }

        .notice-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        /* Estilos para o toast de notificação */
        .toast {
            visibility: hidden;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            max-width: 90%;
        }

        .toast.show {
            visibility: visible;
            animation: fadeInOut 3s ease;
        }

        .toast svg {
            margin-right: 12px;
            min-width: 24px;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; bottom: 0; }
            15% { opacity: 1; bottom: 30px; }
            85% { opacity: 1; bottom: 30px; }
            100% { opacity: 0; bottom: 60px; }
        }

        .timer-container {
            text-align: center;
            margin-top: 16px;
            color: #666;
            font-size: 0.9rem;
        }

        .timer {
            font-weight: 600;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        </div>
        
        <div class="content">
            <div class="transaction-id">
                ID da transação
                <strong><?= $transactionId ?></strong>
            </div>

            <div class="amount-container">
                <div class="amount-label">Valor</div>
                <div class="amount">R$ <?= number_format($pixValor, 2, ",", ".") ?></div>
            </div>

            <p class="instruction-text">
                Abra o app com sua chave PIX cadastrada, escaneie o QR Code ou copie e cole o código.
            </p>

            <div class="qr-container">
                <div class="qr-frame">
                    <div id="qrcode"></div>
                    <img src="https://img.icons8.com/fluent/512/pix.png" alt="PIX" class="pix-logo">
                </div>
            </div>

            <button class="copy-btn" id="copyButton">
                PIX CÓPIA E COLA
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
            </button>

            <div class="timer-container">
                Este QR Code expira em <span class="timer" id="countdown">15:00</span>
            </div>

            <div class="divider">Informações importantes</div>

            <div class="notice-box">
                <span class="notice-title">ATENÇÃO:</span>
                <p>• Pagamento será direcionado para uma plataforma de jogos. NÃO EFETUE O PAGAMENTO se não foi você que gerou esse pix.</p>
                <p>• Ao confirmar o Pix, você aceita que: 1) Valor é para jogos e/ou apostas; 2) O Recebedor é só intermediário bancário; 3) O pagamento deste QRCode é sua responsabilidade.</p>
                <p>• Não somos o beneficiário do valor, somos apenas uma processadora de pagamentos.</p>
                <p>• Após o pagamento ser bem-sucedido, você pode retornar ao lobby do jogo e aguardar a adição de pontos!</p>
            </div>
        </div>
    </div>
    
    <!-- Toast de Notificação -->
    <div id="toast" class="toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span>Código PIX copiado com sucesso!</span>
    </div>

    <script>
        // Gerar QR Code
        function generateQRCode(data) {
            try {
                const qr = qrcode(0, 'M');
                qr.addData(data);
                qr.make();
                return qr.createImgTag(4);
            } catch (error) {
                console.error("Erro ao gerar QR Code:", error);
                return '<p>Erro ao gerar QR Code. Tente novamente mais tarde.</p>';
            }
        }

        // Inicializar QR Code na página
        document.getElementById('qrcode').innerHTML = generateQRCode("<?= htmlspecialchars($pixQr) ?>");

        // Função para mostrar toast de notificação
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('toast');
            const icon = toast.querySelector('svg');
            
            // Atualizar ícone baseado no tipo de notificação
            if (isSuccess) {
                icon.innerHTML = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>';
            } else {
                icon.innerHTML = '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>';
            }
            
            toast.querySelector('span').textContent = message;
            toast.className = "toast show";
            
            // Esconder toast após a animação
            setTimeout(() => {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }

        // Evento para botão de cópia
        document.getElementById('copyButton').addEventListener('click', async () => {
            const pixCode = "<?= htmlspecialchars($pixQr) ?>";
            
            try {
                await navigator.clipboard.writeText(pixCode);
                showToast("Código PIX copiado com sucesso!", true);
                
                // Feedback visual no botão
                const copyBtn = document.getElementById('copyButton');
                const originalText = copyBtn.innerHTML;
                
                copyBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg> COPIADO COM SUCESSO!
                `;
                copyBtn.style.backgroundColor = "#4CAF50";
                copyBtn.style.color = "white";
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                    copyBtn.style.backgroundColor = "#fabb40";
                    copyBtn.style.color = "#333";
                }, 2000);
                
            } catch (error) {
                console.error("Erro ao copiar o código:", error);
                showToast("Falha ao copiar o código PIX. Tente novamente.", false);
            }
        });

        // Configurar timer de contagem regressiva
        function startCountdown(minutes, seconds) {
            const countdownElement = document.getElementById('countdown');
            let totalSeconds = minutes * 60 + seconds;
            
            const interval = setInterval(() => {
                if (totalSeconds <= 0) {
                    clearInterval(interval);
                    countdownElement.textContent = "EXPIRADO";
                    showToast("O QR Code expirou. Por favor, gere um novo.", false);
                    return;
                }
                
                const mins = Math.floor(totalSeconds / 60);
                const secs = totalSeconds % 60;
                
                countdownElement.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                totalSeconds--;
            }, 1000);
        }
        
        // Iniciar contagem regressiva de 15 minutos
        startCountdown(15, 0);

        // ========== POLLING PARA VERIFICAR STATUS DO PAGAMENTO ==========
        const realTransactionId = "<?= htmlspecialchars($transactionId) ?>";
        let pollingInterval = null;
        let pollingAttempts = 0;
        const maxPollingAttempts = 180; // 15 minutos (5 segundos * 180 = 900 segundos)

        function checkPaymentStatus() {
            pollingAttempts++;

            if (pollingAttempts > maxPollingAttempts) {
                clearInterval(pollingInterval);
                console.log('Polling encerrado: tempo máximo atingido');
                return;
            }

            fetch('/api/pix/status?transactionId=' + encodeURIComponent(realTransactionId))
                .then(response => response.json())
                .then(data => {
                    console.log('Status check:', data);

                    if (data.status && data.paid === true) {
                        // Pagamento confirmado!
                        clearInterval(pollingInterval);

                        // Mostrar toast de sucesso
                        showToast("Pagamento confirmado! Redirecionando...", true);

                        // Mudar visual da página
                        document.querySelector('.container').innerHTML = `
                            <div style="text-align: center; padding: 40px 20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <h2 style="color: #4CAF50; margin: 20px 0;">Pagamento Confirmado!</h2>
                                <p style="color: #666; margin-bottom: 20px;">Seu saldo foi creditado com sucesso.</p>
                                <p style="color: #999; font-size: 0.9rem;">Redirecionando em 3 segundos...</p>
                            </div>
                        `;

                        // Redirecionar após 3 segundos
                        setTimeout(() => {
                            window.top.location.href = '/';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar status:', error);
                });
        }

        // Iniciar polling a cada 5 segundos
        pollingInterval = setInterval(checkPaymentStatus, 5000);

        // Verificar imediatamente na primeira vez
        checkPaymentStatus();
    </script>
</body>
</html>