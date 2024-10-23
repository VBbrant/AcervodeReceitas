<!-- cabecalho.php -->
<header class="header">
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); 
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . '/ACERVODERECEITAS/Inovacao4/config.php'; ?> <!-- Inclui o arquivo de configuração -->
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/estilu.css">
    <?php include 'barraLateral.php'; ?>
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
            <a href="#" id="userProfileIcon" class="text-white"><i class="fas fa-user"></i></a> <!-- Ícone de usuário -->
        </div>
    </div>

    <!-- Popup do perfil que será exibido ao clicar no ícone de usuário -->
    <div id="perfilPopup" class="perfil-popup">
        <h4>Perfil do Usuário</h4>
        <p>Aqui você pode ver os detalhes do perfil.</p>
        <a href="<?php echo ROOT_PATH; ?>/Paginas/Perfil.php">Ir para o perfil completo</a>
    </div>
</header>
