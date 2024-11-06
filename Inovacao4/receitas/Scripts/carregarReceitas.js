document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const viewMode = urlParams.get("view") || "grid"; // Define o modo padrão como "grid" (grade)
    const recipesContainer = document.getElementById(viewMode === "grid" ? "recipesGrid" : "recipesList");
    const categoryFilter = document.getElementById("categoryFilter");
    const ratingFilter = document.getElementById("ratingFilter");

    function carregarReceitas() {
        const category = categoryFilter ? categoryFilter.value : "";
        const rating = ratingFilter ? ratingFilter.value : "";

        // Define a URL de requisição com os filtros e modo de exibição
        fetch(`filtro.php?category=${category}&rating=${rating}&view=${viewMode}`)
            .then(response => response.text())
            .then(html => {
                recipesContainer.innerHTML = html;
                aplicarEstilo(viewMode);
            })
            .catch(error => {
                console.error("Erro ao carregar receitas:", error);
            });
    }

    function aplicarEstilo(viewMode) {
        if (viewMode === "lista") {
            // Ajusta o estilo dos elementos em modo lista
            document.querySelectorAll(".recipe-card").forEach(card => {
                card.classList.add("recipe-list-item");
                card.querySelector(".recipe-image").classList.add("thumbnail-image");
            });
        }
    }

    // Eventos para recarregar receitas ao mudar filtros
    if (categoryFilter) categoryFilter.addEventListener("change", carregarReceitas);
    if (ratingFilter) ratingFilter.addEventListener("change", carregarReceitas);

    // Carrega as receitas ao iniciar a página
    carregarReceitas();
});
