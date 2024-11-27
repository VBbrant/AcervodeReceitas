<!-- sidebar.php -->
<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php"><i class="fas fa-home"></i> Início</a></li>

        <?php if ($userRole == 'ADM' || $userRole == 'Cozinheiro' || $userRole == 'Degustador' || $userRole == 'Editor') : ?>
            <!-- Receitas -->
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-utensils"></i> Receitas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                        <?php if ($userRole == 'ADM' || $userRole == 'Cozinheiro') : ?>
                            <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php"><i class="fas fa-pencil-alt"></i>Adicionar Receita</a></li>
                        <?php endif; ?>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/verReceita.php"><i class="fas fa-utensils"></i> Lista de Receitas</a></li>
                    <?php if ($userRole == 'Cozinheiro') : ?>
                        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/minhasReceitas.php"><i class="fas fa-utensils"></i> Minhas Receitas</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Avaliações -->
        <?php if ($userRole == 'ADM' || $userRole == 'Degustador') : ?>
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-star"></i> Avaliações
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/addAvaliacao.php"><i class="fas fa-pencil-alt"></i> Escrever Avaliação</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/listaAvaliacao.php"><i class="fas fa-list"></i> Lista de Avaliações</a></li>
                    <?php if ($userRole == 'Degustador') : ?>
                        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/MinhasAvaliacoes.php"><i class="fas fa-pencil-alt"></i> Minhas Avaliações</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Livros -->
        <?php if ($userRole == 'ADM' || $userRole == 'Editor') : ?>
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-book"></i> Livros
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/listaLivro.php"><i class="fas fa-list"></i> Lista de Livros</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/addLivro.php"><i class="fas fa-plus"></i> Criar Livro</a></li>
                    <?php if($userRole == 'Editor'):?>
                        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/meusLivros.php"><i class="fas fa-book-open"></i> Meus Livros</a></li>
                    <?php endif;?>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Ingredientes, Medidas, Categorias e Metas -->
        <?php if ($userRole == 'ADM' || $userRole == 'Cozinheiro') : ?>
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-pepper-hot"></i> Ingredientes
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/listaIngrediente.php"><i class="fas fa-list-ul"></i> Lista de Ingredientes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/addIngrediente.php"><i class="fas fa-plus-circle"></i> Adicionar Ingrediente</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-ruler"></i> Medidas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/listaMedida.php"><i class="fas fa-list"></i> Lista de Medidas</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/addMedida.php"><i class="fas fa-plus-square"></i> Adicionar Medida</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-tags"></i> Categorias
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/listaCategoria.php"><i class="fas fa-list-alt"></i> Lista de Categorias</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/addCategoria.php"><i class="fas fa-plus-circle"></i> Adicionar Categoria</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <!-- Funcionários, Usuarios, Restaurantes, Cargos e Parâmetros (exclusivo para ADM) -->
        <?php if ($userRole == 'ADM') : ?>
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user"></i> Funcionários
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/listaFuncionario.php"><i class="fas fa-users"></i> Lista de Funcionários</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/addFuncionario.php"><i class="fas fa-user-plus"></i> Adicionar Funcionário</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-store"></i> Restaurantes
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/listaRestaurante.php"><i class="fas fa-store-alt"></i> Lista de Restaurantes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/addRestaurante.php"><i class="fas fa-plus-circle"></i> Adicionar Restaurante</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user"></i> Usuarios
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/listaUsuario.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/addUsuario.php"><i class="fas fa-user-plus"></i> Adicionar Usuario</a></li>
                </ul>
            </li>

            <!-- Novo item de submenu para Cargos -->
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-cogs"></i> Cargos
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/cargos/listaCargo.php"><i class="fas fa-list"></i> Lista de Cargos</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/cargos/addCargo.php"><i class="fas fa-plus-circle"></i> Adicionar Cargo</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-cogs"></i> Parâmetros
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/listaMeta.php"><i class="fas fa-trophy"></i> Metas</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/logSistema.php"><i class="fas fa-file-alt"></i> Log do Sistema</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/parametros.php"><i class="fas fa-file-alt"></i> Parâmetros</a></li>
                </ul>
            </li>
        <?php endif; ?>
        <?php if ($userRole == 'Cozinheiro') : ?>
            <li class="has-submenu">
                <a href="javascript:void(0);" onclick="toggleSubmenu(this)">
                    <i class="fas fa-cogs"></i> Metas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/metas/minhasMetas.php"><i class="fas fa-trophy"></i> Metas</a></li>
                </ul>
            </li>
        <?php endif;?>
    </ul>
</nav>


<script>
    function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    if (submenu && submenu.classList.contains('submenu')) {
        submenu.classList.toggle('active');
    }
}

</script>

<style>
    .submenu {
    display: none;
    padding-left: 20px;
}

.submenu.active {
    display: block;
}

</style>