
document.addEventListener('DOMContentLoaded', () => {
    const categoryFilter = document.getElementById('categoryFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    const recipesGrid = document.getElementById('recipesGrid');

    function carregarReceitas() {
        const category = categoryFilter.value;
        const rating = ratingFilter.value;
        
        const url = `filtro.php?category=${category}&rating=${rating}`;
        
        // Requisição fetch para obter os dados JSON
        fetch(url)
            .then(response => response.json())
            .then(data => {
                recipesGrid.innerHTML = '';
                data.forEach(recipe => {
                    const recipeCard = document.createElement('div');
                    recipeCard.classList.add('recipe-card');
                    recipeCard.innerHTML = `
                        <img src="${recipe.imagem}" alt="${recipe.titulo}">
                        <div class="recipe-info">
                            <div class="rating">${Number(recipe.avaliacao).toFixed(1)}</div>
                            <h2>${recipe.titulo}</h2>
                            <p>${recipe.descricao}</p>
                            <div class="user-icon">
                                <img src="assets/user-icon.svg" alt="User">
                            </div>
                        </div>
                    `;
                    recipesGrid.appendChild(recipeCard);
                });
            })
            .catch(error => console.error('Erro ao carregar receitas:', error));
    }

    // Carregar receitas na primeira vez e ao mudar os filtros
    carregarReceitas();
    categoryFilter.addEventListener('change', carregarReceitas);
    ratingFilter.addEventListener('change', carregarReceitas);
});
