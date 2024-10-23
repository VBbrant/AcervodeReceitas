<!-- cabecalho.php -->
<header class="header">
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); 
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . '/ACERVODERECEITAS/Inovacao4/config.php'; ?> <!-- Inclui o arquivo de configuração -->
    
    <?php echo "<script src=' echo ROOT_PATH; /Scripts/javaScript.js'></script>";?> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/Style/estilu.css">
    
    <!-- Inclui a barra lateral -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . ROOT_PATH . '/elementoPagina/barraLateral.php'; ?>
    
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">SABOR<span style="color: red;">ARTE</span></div>
        <button id="toggleSidebar" class="btn btn-danger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </button>
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="<?php echo ROOT_PATH; ?>/Paginas/Home.php">Início</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?php echo ROOT_PATH; ?>/Paginas/receitas/addReceita.php">Receitas</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Livros</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Sobre</a></li>
            </ul>
        </nav>
        <div class="icons">
            <a href="#" class="text-white mr-3"><i class="fas fa-search"></i></a>
            <a href="#" id="userProfileIcon" class="text-white"><i class="fas fa-user"></i></a>
        </div>
    </div>

    <!-- Popup de perfil -->
    <div id="perfilPopup" class="perfil-popup">
        <h4>Perfil do Usuário</h4>
        <ul>
            <li><a href="<?php echo ROOT_PATH; ?>/Paginas/Perfil.php">Ver Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>/Paginas/logout.php">Sair</a></li>
        </ul>
    </div>
</header>
