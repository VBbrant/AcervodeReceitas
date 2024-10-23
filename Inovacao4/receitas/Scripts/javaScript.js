let lastScrollTop = 0;
const header = document.querySelector('.header');
const sidebar = document.getElementById('sidebar');

window.addEventListener('scroll', function () {
    if (!sidebar.classList.contains('active')) {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop) {
            // Ao rolar para baixo, esconde o header
            header.classList.add('hidden');
        } else {
            // Ao rolar para cima, mostra o header
            header.classList.remove('hidden');
        }
        lastScrollTop = scrollTop;
    }
});

// Barra lateral -------------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.getElementById("toggleSidebar");
    const bars = toggleButton.getElementsByClassName("bar");

    toggleButton.addEventListener("click", function() {
        sidebar.classList.toggle("active");
        
        
        for (let i = 0; i < bars.length; i++) {
            bars[i].classList.toggle("rotate");
        }
    });
});


// Função para mostrar mais receitas--------------------------------------------------------------------------
document.getElementById('more-recipes-btn').addEventListener('click', function() {
    let hiddenRecipes = document.querySelectorAll('.extra-receitas.hidden');
    for (let i = 0; i < Math.min(hiddenRecipes.length, 6); i++) {
        hiddenRecipes[i].classList.remove('hidden');
    }

    // mudar comportamento do botão
    if (document.querySelectorAll('.extra-receitas.hidden').length === 0) {
        this.textContent = 'Ver todas as receitas';
        this.addEventListener('click', function() {
            window.location.href = 'Receitas.php';
        });
    }
});

// Função para mostrar mais livros ----------------------------------------------------------------------
document.getElementById('more-books-btn').addEventListener('click', function() {
    let hiddenBooks = document.querySelectorAll('.extra-livros.hidden');
    for (let i = 0; i < Math.min(hiddenBooks.length, 6); i++) {
        hiddenBooks[i].classList.remove('hidden');
    }

    // mudar comportamento do botao
    if (document.querySelectorAll('.extra-livros.hidden').length === 0) {
        this.textContent = 'Ver todos os livros';
        this.addEventListener('click', function() {
            window.location.href = 'Livros.php';
        });
    }
});

// Função para exibir/ocultar o popup de perfil ---------------------------------------------------------------
document.getElementById('userProfileIcon').addEventListener('click', function(event) {
    event.preventDefault();
    const perfilPopup = document.getElementById('perfilPopup');
    perfilPopup.classList.toggle('show');
});

// Função para alternar o modo escuro
document.getElementById('toggleDarkMode').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
});

