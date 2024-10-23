<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título da Página</title>
    
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Link para o arquivo de ícones Bootstrap (para os ícones de sino e usuário) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" href="../Style/estiloCabecalho.css">
    <link rel="stylesheet" href="../Style/home.css">
</head>
<body>

    <header id="header" class="header fixed-top bg-white shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center">
        <div id="menuIcon">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div id="sidebar">
            <?php include '../elementoPagina/barraLateral.php'; ?>
        </div> 
            <!-- Logo -->
            <a href="#" class="navbar-brand">
                <span class="text-black">SABOR</span><span class="text-danger">ARTE</span>
            </a>
            
            <!-- Formulário de pesquisa -->
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            
            <div class="icons d-flex align-items-center">
                <div class="notification position-relative">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </div>
                <div class="user-icon ms-3" id="userIcon">
                    <i class="bi bi-person-circle"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Pop-up do usuário -->
    <div class="header-user-popup" id="userPopup">
        <ul>
            <li><a href="#">Perfil</a></li>
            <li><a href="#">Modo Escuro</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="#">Sair</a></li>
        </ul>
    </div>

    <!-- Scripts -->
    <?php include "../elementoPagina/rodape.php"; ?>
</body>
</html>
