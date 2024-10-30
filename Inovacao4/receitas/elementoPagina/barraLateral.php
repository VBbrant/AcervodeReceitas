<!-- sidebar.php -->

<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php"><i class="fas fa-home"></i> Início</a></li>
        
        <li class="has-submenu">
            <a href="<?php echo BASE_URL;?>receitas/Paginas/receitas/verReceita.php" onclick="toggleSubmenu(this)">
                <i class="fas fa-utensils"></i> Receitas
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php">Adicionar Receita</a></li>
                <li><a href="/recipes/edit.php">Editar Receita</a></li>
                <li><a href="/recipes/delete.php">Excluir Receita</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" onclick="toggleSubmenu(this)">
                <i class="fas fa-star"></i> Avaliações
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="/reviews/write.php">Escrever Avaliação</a></li>
                <li><a href="/reviews/edit.php">Editar Avaliação</a></li>
                <li><a href="/reviews/delete.php">Excluir Avaliação</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" onclick="toggleSubmenu(this)">
                <i class="fas fa-book"></i> Livros
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="/books/create.php">Criar Livro</a></li>
                <li><a href="/books/edit.php">Editar Livro</a></li>
                <li><a href="/books/delete.php">Excluir Livro</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" onclick="toggleSubmenu(this)">
                <i class="fas fa-pepper-hot"></i> Ingredientes
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="submenu">
                <li><a href="/ingredients/add.php">Adicionar Ingrediente</a></li>
                <li><a href="/ingredients/edit.php">Editar Ingrediente</a></li>
                <li><a href="/ingredients/delete.php">Excluir Ingrediente</a></li>
            </ul>
        </li>
    </ul>
</nav>
