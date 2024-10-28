<!-- sidebar.php -->

    <nav>
        <ul>
            <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
            
            <li class="has-submenu">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-utensils"></i> Recipes
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="/recipes/add.php">Add Recipe</a></li>
                    <li><a href="/recipes/edit.php">Edit Recipe</a></li>
                    <li><a href="/recipes/delete.php">Delete Recipe</a></li>
                </ul>
            </li>
            
            <li class="has-submenu">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-star"></i> Reviews
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="/reviews/write.php">Write Review</a></li>
                    <li><a href="/reviews/edit.php">Edit Review</a></li>
                    <li><a href="/reviews/delete.php">Delete Review</a></li>
                </ul>
            </li>
            
            <li class="has-submenu">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-book"></i> Books
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="/books/create.php">Create Book</a></li>
                    <li><a href="/books/edit.php">Edit Book</a></li>
                    <li><a href="/books/delete.php">Delete Book</a></li>
                </ul>
            </li>
            
            <li class="has-submenu">
                <a href="#" onclick="toggleSubmenu(this)">
                    <i class="fas fa-pepper-hot"></i> Ingredients
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="/ingredients/add.php">Add Ingredient</a></li>
                    <li><a href="/ingredients/edit.php">Edit Ingredient</a></li>
                    <li><a href="/ingredients/delete.php">Delete Ingredient</a></li>
                </ul>
            </li>
        </ul>
    </nav>
