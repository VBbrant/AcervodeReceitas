<!-- sidebar.php -->

<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php"><i class="fas fa-home"></i> Início</a></li>

        <?php if ($userRole == 'ADM' || $userRole == 'Cozinheiro' || $userRole == 'Degustador' || $userRole == 'Editor') : ?>
            <!-- Receitas -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/verReceita.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-utensils"></i> Receitas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php">Adicionar Receita</a></li>
                    <?php if ($userRole == 'Cozinheiro' || $userRole == 'ADM') : ?>
                        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/minhasReceitas.php"><i class="fas fa-utensils"></i> Minhas Receitas</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        

        <?php if ($userRole == 'ADM' || $userRole == 'Degustador') : ?>
            <!-- Avaliações -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/listaAvaliacao.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-star"></i> Avaliações
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/addAvaliacao.php">Escrever Avaliação</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($userRole == 'ADM' || $userRole == 'Editor') : ?>
            <!-- Livros -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/listaLivro.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-book"></i> Livros
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/addLivro.php">Criar Livro</a></li>
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/meusLivros.php"><i class="fas fa-book"></i> Meus Livros</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($userRole == 'ADM' || $userRole == 'Cozinheiro') : ?>
            <!-- Ingredientes -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/listaIngrediente.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-pepper-hot"></i> Ingredientes
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/addIngrediente.php">Adicionar Ingrediente</a></li>
                </ul>
            </li>

            <!-- Medidas -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/listaMedida.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-ruler"></i> Medidas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/addMedida.php">Adicionar Medida</a></li>
                </ul>
            </li>

            <!-- Categorias -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/listaCategoria.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-tags"></i> Categorias
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/addCategoria.php">Adicionar Categoria</a></li>
                </ul>
            </li>

            <!-- Metas -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/metas/listaMeta.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-bullseye"></i> Metas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/metas/addMeta.php">Adicionar Meta</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if ($userRole == 'ADM') : ?>
            <!-- Funcionários -->
            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/listaFuncionario.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user"></i> Funcionários
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/addFuncionario.php">Adicionar Funcionário</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/listaUsuario.php" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user"></i> Usuarios
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/addUsuario.php">Adicionar Usuario</a></li>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</nav>
