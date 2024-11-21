<!-- Barra de Pesquisa -->
<div class="text-end">
    <form method="POST" class="mb-3">
        <div class="input-group input-group-sm" style="max-width: 400px;"> <!-- Diminui o tamanho da barra -->
            <!-- Campo de pesquisa -->
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Pesquisar por nome ou apelido" 
                value="<?php echo htmlspecialchars($search); ?>" 
                id="searchInput"
            >
            <!-- Botão de limpar dentro do campo -->
            <button type="button" 
                    id="clearSearchButton"
                    class="btn btn-outline-secondary"
                    aria-label="Limpar pesquisa">
                &times;
            </button>
            <!-- Botão de lupa -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<!-- Link para FontAwesome para os ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    // JavaScript para o botão "X" (limpar campo de pesquisa)
    document.getElementById('clearSearchButton').addEventListener('click', function () {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
            searchInput.focus();
        }
    });
</script>
