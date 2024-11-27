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

//botãoVOltar-----------------------------
function voltarPagina() {
    window.history.back();
  }

function abrirConfiguracoes() {
window.location.href = "configuracoes.php";
}

//----------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("headerSearchInput");
    const searchResults = document.getElementById("headerSearchResults");

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            fetch(`http://localhost/AcervodeReceitas/Inovacao4/receitas/elementoPagina/pesquisa.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let resultsHtml = "";

                    if (data.length > 0) {
                        const groupedResults = data.reduce((groups, item) => {
                            if (!groups[item.categoria]) {
                                groups[item.categoria] = [];
                            }
                            groups[item.categoria].push(item);
                            return groups;
                        }, {});

                        for (const [categoria, itens] of Object.entries(groupedResults)) {
                            resultsHtml += `<div class="result-category-header">${categoria}</div>`;
                            itens.forEach(item => {
                                resultsHtml += `
                                    <div class="result-item-header" 
                                        data-category="${categoria}" 
                                        data-id="${item.id}" 
                                        data-name="${item.nome}">
                                        ${item.nome}
                                    </div>
                                `;
                            });
                        }
                    } else {
                        resultsHtml = `<div class="no-results">Nenhum resultado encontrado.</div>`;
                    }

                    searchResults.innerHTML = resultsHtml;
                    searchResults.style.display = "block";
                })
                .catch(error => {
                    console.error("Erro ao buscar resultados:", error);
                    searchResults.innerHTML = `<div class="no-results">Erro ao buscar resultados.</div>`;
                    searchResults.style.display = "block";
                });
        } else {
            searchResults.style.display = "none";
        }
    });

    // Redirecionamento ao clicar no item
    searchResults.addEventListener("click", (event) => {
        const item = event.target.closest(".result-item-header");
        if (item) {
            const category = item.getAttribute("data-category");
            const id = item.getAttribute("data-id");

            let url = "";
            if (category === "Receita") {
                url = `http://localhost/AcervodeReceitas/Inovacao4/receitas/paginas/receitas/verReceitaIndividual.php?id=${id}`;
            } else if (category === "Livro") {
                url = `http://localhost/AcervodeReceitas/Inovacao4/receitas/paginas/livros/verlivro.php?id=${id}`;
            } else if (category === "Funcionário") {
                url = `http://localhost/AcervodeReceitas/Inovacao4/receitas/paginas/funcionarios/verfuncionario.php?id=${id}`;
            }

            if (url) {
                window.location.href = url;
            }
        }
    });

    document.addEventListener("click", (event) => {
        if (!event.target.closest(".search-container-header")) {
            searchResults.style.display = "none";
        }
    });
});





  
