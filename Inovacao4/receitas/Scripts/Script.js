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
// Lista simulada de medidas (substituir por carregamento dinâmico)

function selectIngredient(ingredient) {
    if (!selectedIngredients.some(item => item.nome === ingredient)) {
        selectedIngredients.push({
            nome: ingredient,
            quantidade: '',
            sistema: medidas.length ? medidas[0].sistema : ''
        });
        updateSelectedIngredients();
    }
}

function updateSelectedIngredients() {
    const selectedIngredientsDiv = document.getElementById('selectedIngredients');
    selectedIngredientsDiv.innerHTML = '';

    selectedIngredients.forEach((ingredient, index) => {
        const ingredientTag = document.createElement('div');
        ingredientTag.classList.add('selected-ingredient', 'd-flex', 'align-items-center', 'gap-2', 'p-2', 'border', 'rounded');

        const ingredientName = document.createElement('span');
        ingredientName.textContent = ingredient.nome;
        ingredientTag.appendChild(ingredientName);

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

        // Dropdown de medida
        const measureSelect = document.createElement('select');
        measureSelect.classList.add('form-control', 'form-control-sm');
        measureSelect.style.width = '90px';
        measureSelect.onchange = () => {
            selectedIngredients[index].sistema = measureSelect.value;
            updateIngredientsJson();
        };

        // Opções de medidas
        medidas.forEach(medida => {
            const option = document.createElement('option');
            option.value = medida.sistema;
            option.textContent = medida.sistema;
            if (ingredient.sistema === medida.sistema) {
                option.selected = true;
            }
            measureSelect.appendChild(option);
        });

        ingredientTag.appendChild(measureSelect);

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

    updateIngredientsJson();
}

function updateIngredientsJson() {
    document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
}

