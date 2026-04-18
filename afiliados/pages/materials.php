<?php
$currentMonth = date('F Y');
?>
<div class="dashboard">
    <nav class="navbar">
        <div class="navbar-left">
            <h1 class="page-title">Materiais Promocionais</h1>
        </div>
        <div class="navbar-right">
            <div class="btn-group">
                <button class="btn btn-outline active">Todos</button>
                <button class="btn btn-outline">Banners</button>
                <button class="btn btn-outline">Vídeos</button>
                <button class="btn btn-outline">Textos</button>
            </div>
        </div>
    </nav>

    <!-- Grid de Materiais -->
    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <!-- Banner -->
        <div class="card">
            <img src="https://images.unsplash.com/photo-1579226905180-636b76d96082?w=800&h=400&fit=crop" 
                 alt="Banner Promocional" 
                 class="w-full h-40 object-cover rounded-lg mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-medium">Banner Principal</h3>
                    <p class="text-sm text-gray-600">800x400px • PNG</p>
                </div>
                <button class="btn btn-outline btn-sm">
                    <i class="fas fa-download"></i>
                </button>
            </div>
            <div class="text-sm mb-4">
                <label class="block font-medium mb-2">Código para Embed</label>
                <div class="bg-gray-50 p-2 rounded-lg break-all">
                    &lt;a href="https://casino.com/?ref=ABC123"&gt;&lt;img src="banner.png"&gt;&lt;/a&gt;
                </div>
            </div>
            <button class="btn btn-outline w-full">
                <i class="fas fa-copy"></i>
                Copiar Código
            </button>
        </div>

        <!-- Vídeo -->
        <div class="card">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop" 
                     alt="Thumbnail do Vídeo" 
                     class="w-full h-40 object-cover rounded-lg mb-4">
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-play-circle text-4xl text-white"></i>
                </div>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-medium">Vídeo Tutorial</h3>
                    <p class="text-sm text-gray-600">MP4 • 2:30 min</p>
                </div>
                <button class="btn btn-outline btn-sm">
                    <i class="fas fa-download"></i>
                </button>
            </div>
            <div class="text-sm mb-4">
                <label class="block font-medium mb-2">Link do Vídeo</label>
                <div class="bg-gray-50 p-2 rounded-lg break-all">
                    https://video.casino.com/tutorial.mp4
                </div>
            </div>
            <button class="btn btn-outline w-full">
                <i class="fas fa-copy"></i>
                Copiar Link
            </button>
        </div>

        <!-- Template de Texto -->
        <div class="card">
            <div class="bg-gray-50 p-4 rounded-lg mb-4 h-40 overflow-auto">
                <p class="text-sm">🎰 Quer ganhar bônus exclusivos no cassino? 
                   Cadastre-se agora usando meu link e ganhe 100% de bônus no primeiro depósito! 
                   Plus: 50 free spins para começar apostando! 🎲
                   
                   👉 Link: [seu-link-aqui]
                   
                   #Casino #Apostas #Bonus</p>
            </div>
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-medium">Post para Instagram</h3>
                    <p class="text-sm text-gray-600">Texto • Social Media</p>
                </div>
                <button class="btn btn-outline btn-sm">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <div class="text-sm mb-4">
                <label class="block font-medium mb-2">Instruções</label>
                <p class="text-gray-600">Substitua [seu-link-aqui] pelo seu link de afiliado</p>
            </div>
            <button class="btn btn-outline w-full">
                <i class="fas fa-copy"></i>
                Copiar Texto
            </button>
        </div>

        <!-- Story Template -->
        <div class="card">
            <img src="https://images.unsplash.com/photo-1606167668584-78701c57f13d?w=800&h=400&fit=crop" 
                 alt="Story Template" 
                 class="w-full h-40 object-cover rounded-lg mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-medium">Story Template</h3>
                    <p class="text-sm text-gray-600">1080x1920px • PNG</p>
                </div>
                <button class="btn btn-outline btn-sm">
                    <i class="fas fa-download"></i>
                </button>
            </div>
            <div class="text-sm mb-4">
                <label class="block font-medium mb-2">Instruções</label>
                <p class="text-gray-600">Template otimizado para Stories do Instagram</p>
            </div>
            <button class="btn btn-outline w-full">
                <i class="fas fa-download"></i>
                Download
            </button>
        </div>
    </div>
</div>