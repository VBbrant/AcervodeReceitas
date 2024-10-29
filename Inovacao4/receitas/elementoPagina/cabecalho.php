<?php require_once CONFIG_PATH;?>
<header class="header">
    <nav class="navbar fixed-top">
        <div class="container-fluid header-content">
            <!-- Left side - Menu button and Logo -->
            <div class="d-flex align-items-center">
                <button class="btn menu-button me-3" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php" class="navbar-brand">
                    <img src="<?php echo BASE_URL; ?>receitas/imagens/logo.png" alt="SaborArte" class="logo-img" height="40">
                </a>
                <!-- Exibe o caminho do arquivo no sistema (opcional)
                <p><?php echo ROOT_PATH . 'receitas/imagens/logo.png'; ?></p>
                <p><?php echo CONFIG_PATH . 'receitas/imagens/logo.png'; ?></p>-->
            </div>

            <!-- Right side - Search and Profile -->
            <div class="d-flex align-items-center">
                <div class="search-container me-3 d-none d-md-block">
                    <form class="d-flex" role="search">
                        <input class="form-control search-input" type="search" placeholder="Buscar receitas..." aria-label="Search">
                        <button class="btn search-button" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <button class="btn profile-button" onclick="toggleProfile()" aria-label="Toggle profile">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <?php include ROOT_PATH . 'receitas/elementoPagina/barraLateral.php'; ?>
    </div>

    <!-- Profile Dropdown -->
    <div class="profile-dropdown" id="profileDropdown">
        <?php include ROOT_PATH . 'receitas/elementoPagina/perfil.php'; ?>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeAll()"></div>
</header>
