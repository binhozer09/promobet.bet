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
$csrf = new CSRF_Protect();

checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

?>

<audio id="background-music" preload="auto">
    Seu navegador não suporta o elemento de áudio.
</audio>

<style>
  .tag {
  width: 16px;
  height: 32px;
  border-radius: 4px;
  background: #22c55e;
  margin-right: 10px;
}
</style>

<div class="topbar d-print-none">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">    
        
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">                        
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                        <i class="iconoir-menu-scale"></i>
                    </button>
                </li> 
                <li class="mx-3 welcome-text">
                </li>                   
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                
                <li class="music-toggle" id="musicToggle" title="Ativar/desativar música">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="toggleMusic">
                        <i class="fa-solid fa-music" id="musicIcon"></i> <!-- Ícone padrão -->
                    </a>
                </li>
                
            <!--    <li class="dropdown">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                       <img src="assets/images/flags/br.png" alt="" class="thumb-sm rounded-circle" id="current-flag">
                    </a>
                    <div class="dropdown-menu" id="language-selector">
                        <a class="dropdown-item" href="#" data-lang="en">
                            <img src="assets/images/flags/us_flag.jpg" alt="" height="15" class="me-2">English
                        </a>
                        <a class="dropdown-item" href="#" data-lang="pt">
                            <img src="assets/images/flags/br.png" alt="" height="15" class="me-2">Português
                        </a>
                    </div>
                </li> -->

                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <img src="assets/images/users/F.png" alt="" class="thumb-lg rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                            <div class="flex-shrink-0">
                                <img src="assets/images/users/F.png" alt="" class="thumb-md rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13"><?=$_SESSION['data_adm']['nome'];?></h6>
                                <small class="text-muted mb-0">Plataforma: <?=$dataconfig['nome'];?></small>
                            </div>
                        </div>
                        <div class="dropdown-divider mt-0"></div>
                        <small class="text-muted px-2 pb-1 d-block">Configurações</small>
                        <a class="dropdown-item" href="administradores"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Operadores</a>
                        
                        <a class="dropdown-item text-danger" href="sair"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Sair</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    const musicFiles = [
        './bom_dia_magnata.mp3',
        './bom_dia_chefe.mp3',
        './conquistar_grandes_sonhos.mp3'
    ];

    let isMusicOn = localStorage.getItem('isMusicOn') === 'true'; 
    const musicElement = document.getElementById('background-music');
    let lastMusic = '';
    function getRandomMusic() {
        let newMusic;
        do {
            newMusic = musicFiles[Math.floor(Math.random() * musicFiles.length)];
        } while (newMusic === lastMusic);
        lastMusic = newMusic;
        return newMusic;
    }

    musicElement.src = getRandomMusic();

    document.getElementById('musicToggle').addEventListener('click', function() {
        isMusicOn = !isMusicOn;
        localStorage.setItem('isMusicOn', isMusicOn);
        const musicIcon = document.getElementById('musicIcon');

        if (isMusicOn) {
            musicIcon.classList.remove('fa-microphone-slash');
            musicIcon.classList.add('fa-music');
            musicElement.play();
        } else {
            musicIcon.classList.remove('fa-music');
            musicIcon.classList.add('fa-microphone-slash');
            musicElement.pause();
        }
    });

    window.addEventListener('load', () => {
        musicElement.volume = 0.2;

        if (isMusicOn) {
            musicElement.play().catch(error => {
                console.log('A reprodução automática foi bloqueada pelo navegador.');
            });
            const musicIcon = document.getElementById('musicIcon');
            musicIcon.classList.remove('fa-microphone-slash'); 
            musicIcon.classList.add('fa-music'); 
        } else {
            const musicIcon = document.getElementById('musicIcon');
            musicIcon.classList.remove('fa-music'); 
            musicIcon.classList.add('fa-microphone-slash'); 
        }
    });

    musicElement.addEventListener('ended', function() {
        musicElement.src = getRandomMusic();
        if (isMusicOn) {
            musicElement.play();
        }
    });
</script>