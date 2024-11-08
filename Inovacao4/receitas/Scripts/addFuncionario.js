function generateRegistrationLink() {
    const uniqueCode = generateUniqueCode();  // Gera o código único
    
    // Modifica registrationURL adicionando o restante do caminho e o código único
    registrationURL += "receitas/Paginas/registro.php?code=" + uniqueCode;
    
    // Exibe o link de registro no campo de entrada
    const registrationLinkContainer = document.getElementById('registrationLinkContainer');
    const registrationLinkInput = document.getElementById('registrationLink');
    registrationLinkContainer.style.display = 'block';
    registrationLinkInput.value = registrationURL;
}

// Exemplo de função para gerar um código único (pode ser ajustada)
function generateUniqueCode() {
    return 'xxxx-xxxx-4xxx-yxxx-xxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}
