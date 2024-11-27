    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>receitas/Scripts/Script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.3.1/typeahead.bundle.min.js"></script>
<script>
$(document).ready(function () {
    // Inicialização do Typeahead.js
    const searchInput = $("#headerSearchInput");

    searchInput.typeahead(
    {
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'results',
        source: function (query, syncResults, asyncResults) {
            $.ajax({
                url: "http://localhost/AcervodeReceitas/Inovacao4/receitas/elementoPagina/pesquisa.php",
                type: "GET",
                data: { query: query },
                success: function (data) {
                    console.log("Resposta do servidor:", data); // Adicione esta linha para ver o que está sendo retornado.
                    try {
                        const parsedData = JSON.parse(data);
                        const formattedResults = parsedData.map(item => ({
                            name: item.nome,
                            category: item.categoria
                        }));
                        asyncResults(formattedResults);
                    } catch (error) {
                        console.error("Erro ao analisar JSON:", error);
                    }
                }
                ,
                error: function (xhr, status, error) {
                    console.error("Erro na requisição AJAX:", status, error);
                }
            });
        },
        display: 'name',
        templates: {
            suggestion: function (data) {
                return `
                    <div>
                        <strong>${data.name}</strong> <small>(${data.category})</small>
                    </div>
                `;
            }
        }
    }
);


    // Evento de seleção
    searchInput.bind("typeahead:select", function (ev, suggestion) {
        alert(`Selecionado: ${suggestion.name} (${suggestion.category})`);
        // Redirecionar ou realizar ações específicas
    });
});
</script>

</body>
</html>
