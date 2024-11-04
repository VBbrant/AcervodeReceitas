//addReceita--------------------------------------------------------------------------------------------------
let selectedIngredients = [];

// Adiciona evento de input para pesquisa em tempo real
document.getElementById('ingredientSearch').addEventListener('input', filterIngredients);

function filterIngredients() {
    const searchTerm = document.getElementById('ingredientSearch').value.toLowerCase();
    const listContainer = document.getElementById('ingredientList');
    listContainer.innerHTML = '';  
    // Filtra e exibe apenas ingredientes que correspondem ao termo
    ingredientsData.filter(ingredient =>
        ingredient.nome.toLowerCase().includes(searchTerm)
    ).forEach(ingredient => {
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item';
        listItem.textContent = ingredient.nome;
        listItem.onclick = () => selectIngredient(ingredient);
        listContainer.appendChild(listItem);
    });
    
    // Exibe ou oculta a lista com base nos resultados
    listContainer.style.display = ingredientsData.length ? 'block' : 'none';
}


function selectIngredient(ingredient) {
    const newIngredient = {
        idIngrediente: ingredient.idIngrediente,
        quantidade: '',
        idMedida: measurementsData[0].idMedida
    };
    
    selectedIngredients.push(newIngredient);
    renderSelectedIngredient(newIngredient, ingredient.nome);
    updateIngredientsJson();
    document.getElementById('ingredientList').style.display = 'none';
}

// Função para renderizar visualmente o ingrediente selecionado com campos de quantidade e medida
function renderSelectedIngredient(ingredient, ingredientName) {
    const tag = document.createElement('div');
    tag.className = 'ingredient-tag';
    tag.style.display = 'flex';
    tag.style.alignItems = 'center';
    tag.style.marginBottom = '5px';
    tag.dataset.id = ingredient.idIngrediente;

    tag.innerHTML = `
        <span style="margin-right: 8px;">${ingredientName}</span>
        <input type="number" class="form-control form-control-sm ingredient-quantity" placeholder="Quantidade" min="1" required style="width: 80px; margin-right: 8px;">
        <select class="form-select form-select-sm ingredient-measure" required style="width: 100px; margin-right: 8px;">
            ${measurementsData.map(m => `<option value="${m.idMedida}">${m.nome}</option>`).join('')}
        </select>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeTag(this)">x</button>
    `;

    // Adiciona o evento de mudança nos campos de quantidade e medida para atualizar o JSON
    tag.querySelector('.ingredient-quantity').addEventListener('input', updateIngredientsJson);
    tag.querySelector('.ingredient-measure').addEventListener('change', updateIngredientsJson);

    document.getElementById('selectedIngredients').appendChild(tag);
}

function removeTag(button) {
    const tag = button.parentElement;
    const idIngrediente = tag.dataset.id;
    
    selectedIngredients = selectedIngredients.filter(ing => ing.idIngrediente != idIngrediente);
    
    tag.remove();
    updateIngredientsJson();
}

// Atualiza o campo hidden com os ingredientes em JSON
function updateIngredientsJson() {
    selectedIngredients = Array.from(document.querySelectorAll('.ingredient-tag')).map(tag => ({
        idIngrediente: tag.dataset.id,
        quantidade: tag.querySelector('.ingredient-quantity').value,
        idMedida: tag.querySelector('.ingredient-measure').value
    }));
    
    // Atualiza o campo hidden com o JSON atualizado
    document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
}

document.addEventListener('click', (event) => {
    if (!event.target.closest('.input-group') && !event.target.closest('#ingredientList')) {
        document.getElementById('ingredientList').style.display = 'none';
    }
});

// Evento para exibir ou ocultar o menu suspenso
document.getElementById('addIngredientOptions').addEventListener('click', () => {
    document.getElementById('additionalOptions').classList.toggle('show');
});

function toggleImageInput() {
    const linkInput = document.getElementById('link_imagem');
    const fileInput = document.getElementById('arquivo_imagem');

    fileInput.disabled = linkInput.value.trim() !== '';
}

function toggleLinkInput() {
    const linkInput = document.getElementById('link_imagem');
    const fileInput = document.getElementById('arquivo_imagem');

    if (fileInput.files.length > 0) {
        linkInput.value = '';
        linkInput.disabled = true;
    } else {
        linkInput.disabled = false;
    }
}