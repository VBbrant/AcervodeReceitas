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
const selectedIngredients = [];

// Função para selecionar um ingrediente da lista
function selectIngredient(ingredient) {
    // Evita duplicação de ingredientes
    if (!selectedIngredients.some(item => item.nome === ingredient)) {
        selectedIngredients.push({
            nome: ingredient,
            quantidade: '',
            sistema: ''
        });
        updateSelectedIngredients();
    }
}

// Função para atualizar a lista de ingredientes selecionados
function updateSelectedIngredients() {
    const selectedIngredientsDiv = document.getElementById('selectedIngredients');
    selectedIngredientsDiv.innerHTML = '';

    selectedIngredients.forEach((ingredient, index) => {
        const ingredientTag = document.createElement('div');
        ingredientTag.classList.add('selected-ingredient', 'd-flex', 'align-items-center', 'gap-2', 'p-2', 'border', 'rounded');

        // Nome do ingrediente
        const ingredientName = document.createElement('span');
        ingredientName.textContent = ingredient.nome;
        ingredientTag.appendChild(ingredientName);

        // Campo de quantidade
        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.classList.add('form-control', 'form-control-sm');
        quantityInput.style.width = '70px';
        quantityInput.placeholder = 'Quant.';
        quantityInput.value = ingredient.quantidade;
        quantityInput.onchange = () => {
            selectedIngredients[index].quantidade = quantityInput.value;
            updateIngredientsJson();
        };
        ingredientTag.appendChild(quantityInput);

        // Campo de sistema de medida
        const measureInput = document.createElement('input');
        measureInput.type = 'text';
        measureInput.classList.add('form-control', 'form-control-sm');
        measureInput.style.width = '90px';
        measureInput.placeholder = 'Unidade';
        measureInput.value = ingredient.sistema;
        measureInput.onchange = () => {
            selectedIngredients[index].sistema = measureInput.value;
            updateIngredientsJson();
        };
        ingredientTag.appendChild(measureInput);

        // Botão de remoção
        const removeBtn = document.createElement('span');
        removeBtn.classList.add('remove', 'text-danger', 'fw-bold');
        removeBtn.style.cursor = 'pointer';
        removeBtn.textContent = 'x';
        removeBtn.onclick = () => {
            selectedIngredients.splice(index, 1);
            updateSelectedIngredients();
            updateIngredientsJson();
        };
        ingredientTag.appendChild(removeBtn);

        selectedIngredientsDiv.appendChild(ingredientTag);
    });

    // Atualiza o campo JSON oculto com dados dos ingredientes
    updateIngredientsJson();
}

// Atualiza o campo oculto com JSON dos ingredientes selecionados
function updateIngredientsJson() {
    document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
}

// Função para pesquisar ingredientes (já implementada anteriormente)
function pesquisarIngrediente() {
    const searchValue = document.getElementById('ingredientes').value.toLowerCase();
    const ingredientItems = document.querySelectorAll('.ingredient-item');
    
    ingredientItems.forEach(item => {
        const ingredientName = item.textContent.toLowerCase();
        item.style.display = ingredientName.includes(searchValue) ? 'block' : 'none';
    });
}
