let exclusaoAtivada = false;

function ativarExclusaoMassa() {
    exclusaoAtivada = !exclusaoAtivada;

    document.querySelectorAll('.checkbox-cell').forEach(cell => {
        cell.style.width = exclusaoAtivada ? '5%' : '0%'; 
        cell.style.visibility = exclusaoAtivada ? 'visible' : 'hidden'; 
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
    const tds = row.querySelectorAll('td');

    if (checkbox.checked) {
        tds.forEach(td => {
            td.style.backgroundColor = 'rgb(230, 26, 26)'; 
        });
    } else {
        tds.forEach(td => {
            td.style.backgroundColor = ''; 
        });
    }
}

function confirmarExclusaoEmMassa() {
    return confirm("Tem certeza que deseja excluir os itens selecionados?");
}
