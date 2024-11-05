let exclusaoAtivada = false;

function ativarExclusaoMassa() {
    exclusaoAtivada = !exclusaoAtivada; // Alterna entre ativado e desativado

    document.querySelectorAll('.checkbox-cell').forEach(cell => {
        cell.style.display = exclusaoAtivada ? 'table-cell' : 'none';
    });
    
    document.getElementById('btnExcluirSelecionados').style.display = exclusaoAtivada ? 'inline-block' : 'none';

    const btnExcluirMassa = document.getElementById('btnExcluirMassa');
    btnExcluirMassa.textContent = exclusaoAtivada ? "Cancelar" : "Excluir em Massa";
    btnExcluirMassa.classList.toggle('btn-secondary', exclusaoAtivada);
    btnExcluirMassa.classList.toggle('btn-warning', !exclusaoAtivada);

    if (!exclusaoAtivada) {
        document.querySelectorAll('input[name="itensSelecionados[]"]').forEach(checkbox => {
            checkbox.checked = false;
            highlightRow(checkbox);
        });
    }
}

function toggleAllCheckboxes(source) {
    const checkboxes = document.querySelectorAll('input[name="itensSelecionados[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
        highlightRow(checkbox);
    });
}

function highlightRow(checkbox) {
    const row = checkbox.closest('tr');
    if (checkbox.checked) {
        row.classList.add('selected-row');
    } else {
        row.classList.remove('selected-row');
    }
}

function confirmarExclusaoEmMassa() {
    return confirm("Tem certeza que deseja excluir os itens selecionados?");
}