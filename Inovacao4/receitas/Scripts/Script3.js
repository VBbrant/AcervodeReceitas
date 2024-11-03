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


// Receitas------------------------------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    const recipesGrid = document.querySelector('.recipes-grid');

    function filterRecipes() {
        const category = categoryFilter.value;
        const rating = ratingFilter.value;

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

//addReceita--------------------------------------------------------------------------------------------------
document.getElementById('ingredientSearch').addEventListener('input', filterIngredients);


document.getElementById('addIngredientOptions').addEventListener('click', () => {
    const additionalOptions = document.getElementById('additionalOptions');
    additionalOptions.classList.toggle('show');
});



function filterIngredients() {
    const searchTerm = document.getElementById('ingredientSearch').value.toLowerCase();
    const listContainer = document.getElementById('ingredientList');
    listContainer.innerHTML = '';  

    const filteredIngredients = ingredientsData.filter(ingredient =>
        ingredient.nome.toLowerCase().includes(searchTerm)
    );

    filteredIngredients.forEach(ingredient => {
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item';
        listItem.textContent = ingredient.nome;
        listItem.onclick = () => selectIngredient(ingredient);
        listContainer.appendChild(listItem);
    });

    listContainer.style.display = filteredIngredients.length ? 'block' : 'none';
}

// Função para adicionar ingrediente como tag ao ser selecionado
function selectIngredient(ingredient) {
    const tagContainer = document.getElementById('selectedIngredients');

    // Cria a tag de ingrediente
    const tag = document.createElement('div');
    tag.className = 'ingredient-tag';
    tag.innerHTML = `
        <span>${ingredient.nome}</span>
        <input type="number" class="form-control form-control-sm ingredient-quantity" placeholder="Quantidade">
        <select class="form-select form-select-sm">
            ${measurementsData.map(m => `<option>${m}</option>`).join('')}
        </select>
        <span class="remove-tag" onclick="removeTag(this)">x</span>
    `;

    tagContainer.appendChild(tag);
    document.getElementById('ingredientList').style.display = 'none';  // Esconde a lista
}

// Função para remover tag de ingrediente
function removeTag(element) {
    element.parentElement.remove();
}

// Função para esconder a lista ao clicar fora do campo de busca
document.addEventListener('click', (event) => {
    if (!event.target.closest('.input-group') && !event.target.closest('#ingredientList')) {
        document.getElementById('ingredientList').style.display = 'none';
    }
});

//IMAGEM-ADD RECEITA-----------------------------------------------------------------------------------------
function toggleImageInput() {
    const linkField = document.getElementById("link_imagem");
    const fileField = document.getElementById("arquivo_imagem");

    if (linkField.value) {
        fileField.disabled = true;
    } else {
        fileField.disabled = false;
    }
}

function toggleLinkInput() {
    const linkField = document.getElementById("link_imagem");
    const fileField = document.getElementById("arquivo_imagem");

    if (fileField.files.length > 0) {
        linkField.disabled = true;
    } else {
        linkField.disabled = false;
    }
}

