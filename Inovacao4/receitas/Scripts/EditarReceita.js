function toggleImageInput() {
    const linkInput = document.getElementById('link_imagem');
    const fileInput = document.getElementById('arquivo_imagem');

    fileInput.disabled = linkInput.value.trim() !== '';
}

function toggleLinkInput() {
    const linkInput = document.getElementById('link_imagem');
    const fileInput = document.getElementById('arquivo_imagem');
    const imagemAtual = document.getElementById('imagemAtual');

    if (fileInput.files.length > 0) {
        linkInput.value = '';
        linkInput.disabled = true;
    } else {
        linkInput.disabled = false;
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const addIngredientOptionsButton = document.getElementById('addIngredientOptions');
    const additionalOptionsMenu = document.getElementById('additionalOptions');

    addIngredientOptionsButton.addEventListener('click', function() {
        additionalOptionsMenu.style.display = additionalOptionsMenu.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('#addIngredientOptions') && !event.target.closest('#additionalOptions')) {
            additionalOptionsMenu.style.display = 'none';
        }
    });
});




// Renderiza os ingredientes existentes como tags ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    selectedIngredients.forEach(ingredient => renderSelectedIngredient(ingredient));
    updateIngredientsJson();
});

// Função para renderizar um ingrediente selecionado como tag
function renderSelectedIngredient(ingredient) {
    const tag = document.createElement('div');
    tag.className = 'ingredient-tag';
    tag.style.display = 'flex';
    tag.style.alignItems = 'center';
    tag.style.marginBottom = '5px';
    tag.dataset.id = ingredient.idIngrediente;

    tag.innerHTML = `
        <span style="margin-right: 8px;">${ingredient.nome}</span>
        <input type="number" class="form-control form-control-sm ingredient-quantity" placeholder="Quantidade" min="1" required 
               value="${ingredient.quantidade || ''}" style="width: 80px; margin-right: 8px;">
        <select class="form-select form-select-sm ingredient-measure" required style="width: 100px; margin-right: 8px;">
            ${measurementsData.map(m => `<option value="${m.idMedida}" ${m.idMedida == ingredient.idMedida ? 'selected' : ''}>${m.nome}</option>`).join('')}
        </select>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeTag(this)">x</button>
    `;

    tag.querySelector('.ingredient-quantity').addEventListener('input', updateIngredientsJson);
    tag.querySelector('.ingredient-measure').addEventListener('change', updateIngredientsJson);

    document.getElementById('selectedIngredients').appendChild(tag);
}

// Atualiza o campo JSON oculto com os ingredientes selecionados
function updateIngredientsJson() {
    const ingredientTags = document.querySelectorAll('.ingredient-tag');
    const selectedIngredients = Array.from(ingredientTags).map(tag => ({
        idIngrediente: tag.dataset.id,
        quantidade: tag.querySelector('.ingredient-quantity').value,
        idMedida: tag.querySelector('.ingredient-measure').value
    }));
    
    document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
}

// Remove uma tag de ingrediente
function removeTag(button) {
    const tag = button.parentElement;
    tag.remove();
    updateIngredientsJson();
}

// Função para filtrar ingredientes com base no termo de pesquisa
function filterIngredients() {
    const searchTerm = document.getElementById('ingredientSearch').value.toLowerCase();
    const listContainer = document.getElementById('ingredientList');
    listContainer.innerHTML = ''; // Limpa a lista atual

    // Filtra os ingredientes com base no termo digitado
    const filteredIngredients = ingredientsData.filter(ingredient => 
        ingredient.nome && ingredient.nome.toLowerCase().includes(searchTerm)
    );

    // Exibe cada ingrediente filtrado como um item na lista de pesquisa
    filteredIngredients.forEach(ingredient => {
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item';
        listItem.textContent = ingredient.nome;

        // Adiciona o evento de clique para selecionar o ingrediente
        listItem.onclick = () => selectIngredient(ingredient);
        listContainer.appendChild(listItem);
    });
    
    // Exibe ou oculta a lista com base nos resultados
    listContainer.style.display = filteredIngredients.length ? 'block' : 'none';
}

// Função para adicionar um ingrediente da lista de pesquisa como tag
function selectIngredient(ingredient) {
    // Define o objeto do novo ingrediente a ser renderizado
    const newIngredient = {
        idIngrediente: ingredient.idIngrediente,
        nome: ingredient.nome,
        quantidade: '', // Inicializa com quantidade vazia para preenchimento
        idMedida: measurementsData[0].idMedida // Define o primeiro valor de medida como padrão
    };

    renderSelectedIngredient(newIngredient);
    updateIngredientsJson();
    document.getElementById('ingredientList').style.display = 'none'; // Oculta a lista após a seleção
}

// Adiciona o evento de input ao campo de pesquisa
document.getElementById('ingredientSearch').addEventListener('input', filterIngredients);



