document.addEventListener("DOMContentLoaded", function() {
    const recipesGrid = document.getElementById("recipesGrid");
    const categoryFilter = document.getElementById("categoryFilter");
    const ratingFilter = document.getElementById("ratingFilter");

    function carregarReceitas() {
        const category = categoryFilter.value;
        const rating = ratingFilter.value;

        fetch(`filtro.php?category=${category}&rating=${rating}`)
            .then(response => response.text()) // Recebe como texto
            .then(html => {
                recipesGrid.innerHTML = html; // Insere o HTML diretamente
            })
            .catch(error => {
                console.error("Erro ao carregar receitas:", error);
            });
    }

    categoryFilter.addEventListener("change", carregarReceitas);
    ratingFilter.addEventListener("change", carregarReceitas);

    // Carrega as receitas inicialmente
    carregarReceitas();
});
