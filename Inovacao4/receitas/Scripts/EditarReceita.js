
document.addEventListener('DOMContentLoaded', function() {
    selectedIngredients.forEach(ingredient => {
        renderSelectedIngredient(ingredient);
    });
    updateIngredientsJson();
});

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
              value="${ingredient.quantidade}" style="width: 80px; margin-right: 8px;">
        <select class="form-select form-select-sm ingredient-measure" required style="width: 100px; margin-right: 8px;">
            ${measurementsData.map(m => `<option value="${m.idMedida}" ${m.idMedida == ingredient.idMedida ? 'selected' : ''}>${m.nome}</option>`).join('')}
        </select>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeTag(this)">x</button>
    `;

    tag.querySelector('.ingredient-quantity').addEventListener('input', updateIngredientsJson);
    tag.querySelector('.ingredient-measure').addEventListener('change', updateIngredientsJson);

    document.getElementById('selectedIngredients').appendChild(tag);
}

function updateIngredientsJson() {
    const ingredientTags = document.querySelectorAll('.ingredient-tag');
    const selectedIngredients = Array.from(ingredientTags).map(tag => ({
        idIngrediente: tag.dataset.id,
        quantidade: tag.querySelector('.ingredient-quantity').value,
        idMedida: tag.querySelector('.ingredient-measure').value
    }));
    
    document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
}
  