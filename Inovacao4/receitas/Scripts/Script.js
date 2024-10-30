function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('overlay').classList.toggle('active');
}

function toggleProfile() {
    document.getElementById('profileDropdown').classList.toggle('active');
    document.getElementById('overlay').classList.toggle('active');
}

function closeAll() {
    document.getElementById('sidebar').classList.remove('active');
    document.getElementById('profileDropdown').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
}


// Receitas
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    const recipesGrid = document.querySelector('.recipes-grid');

    function filterRecipes() {
        const category = categoryFilter.value;
        const rating = ratingFilter.value;

        // Send AJAX request to fetch filtered recipes
        fetch(`filter-recipes.php?category=${category}&rating=${rating}`)
            .then(response => response.json())
            .then(data => {
                recipesGrid.innerHTML = '';
                data.forEach(recipe => {
                    const recipeCard = createRecipeCard(recipe);
                    recipesGrid.appendChild(recipeCard);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function createRecipeCard(recipe) {
        const card = document.createElement('div');
        card.className = 'recipe-card';
        card.innerHTML = `
            <img src="${recipe.imagem}" alt="${recipe.titulo}">
            <div class="recipe-info">
                <div class="rating">${parseFloat(recipe.avaliacao).toFixed(1)}</div>
                <h2>${recipe.titulo}</h2>
                <p>${recipe.descricao}</p>
                <div class="user-icon">
                    <img src="assets/user-icon.svg" alt="User">
                </div>
            </div>
        `;
        return card;
    }

    categoryFilter.addEventListener('change', filterRecipes);
    ratingFilter.addEventListener('change', filterRecipes);
});

//addReceita
function addIngredient() {
    const ingredientInput = document.getElementById('ingredientes');
    const ingredientList = document.getElementById('ingredientList');

    if (ingredientInput.value.trim() !== '') {
        const newIngredient = document.createElement('div');
        newIngredient.classList.add('ingredient-item');
        newIngredient.innerText = ingredientInput.value.trim();
        ingredientList.appendChild(newIngredient);
        ingredientInput.value = '';
    }
}