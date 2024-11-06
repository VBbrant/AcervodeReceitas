<!-- sidebar.php -->

<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php"><i class="fas fa-home"></i> Início</a></li>
        
        <!-- Receitas -->
        <li class="has-submenu">
            <a href="<?php echo BASE_URL;?>receitas/Paginas/receitas/verReceita.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-utensils"></i> Receitas
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php">Adicionar Receita</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/editarReceita.php">Editar Receita</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/excluirReceita.php">Excluir Receita</a></li>
            </ul>
        </li>

        <!-- Avaliações -->
        <li class="has-submenu">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/listaAvaliacao.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-star"></i> Avaliações
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/addAvaliacao.php">Escrever Avaliação</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/editarAvaliacao.php">Editar Avaliação</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/excluirAvaliacao.php">Excluir Avaliação</a></li>
            </ul>
        </li>

        <!-- Livros -->
        <li class="has-submenu">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/listaLivro.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-book"></i> Livros
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/addLivro.php">Criar Livro</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/editarLivro.php">Editar Livro</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/excluirLivro.php">Excluir Livro</a></li>
            </ul>
        </li>

        <!-- Ingredientes -->
        <li class="has-submenu">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/listaIngrediente.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-pepper-hot"></i> Ingredientes
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/addIngrediente.php">Adicionar Ingrediente</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/editarIngrediente.php">Editar Ingrediente</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/excluirIngrediente.php">Excluir Ingrediente</a></li>
            </ul>
        </li>

        <!-- Funcionários -->
        <li class="has-submenu">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/listaFuncionario.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-user"></i> Funcionários
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/addFuncionario.php">Adicionar Funcionário</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/editarFuncionario.php">Editar Funcionário</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/excluirFuncionario.php">Excluir Funcionário</a></li>
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
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/editarMedida.php">Editar Medida</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/excluirMedida.php">Excluir Medida</a></li>
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
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/editarCategoria.php">Editar Categoria</a></li>
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/excluirCategoria.php">Excluir Categoria</a></li>
            </ul>
        </li>
    </ul>
</nav>
