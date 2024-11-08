document.addEventListener('DOMContentLoaded', function () {
    const recipeSearchInput = document.getElementById('recipeSearch');
    const recipeList = document.getElementById('recipeList');
    const selectedRecipesContainer = document.getElementById('selectedRecipes');
    const searchButton = document.getElementById('searchButton');
    const selectedRecipeIds = document.getElementById('selectedRecipeIds');

    let selectedRecipes = [];

    // Exibe a lista ao clicar no botão de pesquisa
    searchButton.addEventListener('click', function () {
        if (recipeList.childElementCount > 0) {
            toggleRecipeList(true);
        } else {
            renderRecipeList('');
            toggleRecipeList(true);
        }
    });

    // Fecha a lista ao clicar fora dela
    document.addEventListener('click', function (event) {
        if (!recipeList.contains(event.target) && !recipeSearchInput.contains(event.target) && event.target !== searchButton) {
            toggleRecipeList(false);
        }
    });

    // Exibe a lista de receitas conforme a pesquisa
    recipeSearchInput.addEventListener('input', function () {
        const query = recipeSearchInput.value.toLowerCase();
        renderRecipeList(query);
        toggleRecipeList(true);
    });

    // Renderiza a lista de receitas filtrada
    function renderRecipeList(query) {
        recipeList.innerHTML = '';
        const filteredReceitas = receitas.filter(receita => receita.nome_rec.toLowerCase().includes(query));

        if (filteredReceitas.length === 0) {
            const noResults = document.createElement('div');
            noResults.textContent = 'Nenhuma receita encontrada';
            noResults.style.padding = '8px';
            recipeList.appendChild(noResults);
        } else {
            filteredReceitas.forEach(receita => {
                const recipeItem = document.createElement('div');
                recipeItem.textContent = receita.nome_rec;
                recipeItem.dataset.id = receita.idReceita;
                recipeItem.addEventListener('click', () => selectRecipe(receita));
                recipeList.appendChild(recipeItem);
            });
        }
    }

    // Adiciona a receita selecionada como tag
    function selectRecipe(receita) {
        if (selectedRecipes.some(selected => selected.idReceita === receita.idReceita)) return;

        selectedRecipes.push(receita);
        updateSelectedRecipes();

        toggleRecipeList(false);
        recipeSearchInput.value = '';
    }

    // Atualiza a visualização das receitas selecionadas
    function updateSelectedRecipes() {
        selectedRecipesContainer.innerHTML = '';
        selectedRecipeIds.value = selectedRecipes.map(recipe => recipe.idReceita).join(',');

        selectedRecipes.forEach(recipe => {
            const recipeTag = document.createElement('span');
            recipeTag.classList.add('recipe-tag');
            recipeTag.textContent = recipe.nome_rec;

            const removeButton = document.createElement('span');
            removeButton.classList.add('remove');
            removeButton.textContent = 'x';
            removeButton.addEventListener('click', () => removeRecipe(recipe.idReceita));

            recipeTag.appendChild(removeButton);
            selectedRecipesContainer.appendChild(recipeTag);
        });
    }

    // Remove uma receita da lista de selecionados
    function removeRecipe(idReceita) {
        selectedRecipes = selectedRecipes.filter(recipe => recipe.idReceita !== idReceita);
        updateSelectedRecipes();
    }

    // Controla a exibição da lista de receitas
    function toggleRecipeList(show) {
        recipeList.style.display = show ? 'block' : 'none';
    }
});
