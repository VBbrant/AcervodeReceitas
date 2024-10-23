// Variáveis para a barra lateral e o ícone de menu
let menuIcon = document.getElementById('menuIcon');
let sidebar = document.getElementById('sidebar');  // Certifique-se de que a barra lateral tenha esse ID
let menuOpen = false;

// Função para abrir e fechar a barra lateral
menuIcon.addEventListener('click', function () {
    menuOpen = !menuOpen;  // Alterna entre abrir e fechar

    if (menuOpen) {
        sidebar.style.transform = 'translateX(0)';  // Abre a barra lateral
        rotateMenuIcon(true);  // Roda as barras
    } else {
        sidebar.style.transform = 'translateX(-100%)';  // Fecha a barra lateral
        rotateMenuIcon(false);  // Volta as barras ao estado original
    }
});

// Função para animar as três barras do ícone de menu
function rotateMenuIcon(open) {
    let iconBars = menuIcon.querySelectorAll('span');  // Seleciona as barras dentro do ícone

    if (open) {
        // Animação para formar um "X"
        iconBars[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        iconBars[1].style.opacity = '0';  // A barra do meio some
        iconBars[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
    } else {
        // Volta ao formato de três barras verticais
        iconBars[0].style.transform = 'rotate(0) translate(0, 0)';
        iconBars[1].style.opacity = '1';
        iconBars[2].style.transform = 'rotate(0) translate(0, 0)';
    }
}

// Controle da visibilidade do header ao rolar a página
let lastScrollTop = 0;
let header = document.querySelector('.header');  // Atualizado para pegar o header com a classe

window.addEventListener('scroll', function () {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (menuOpen) {
        header.classList.remove('hidden');
        return;
    }

    if (currentScroll > lastScrollTop) {
        header.classList.add('hidden');  // Esconde ao rolar para baixo
    } else {
        header.classList.remove('hidden');  // Mostra ao rolar para cima
    }
    lastScrollTop = currentScroll;
});

// Mostrar o pop-up ao clicar no ícone de usuário
let userIcon = document.getElementById('userIcon');
let userPopup = document.querySelector('.header-user-popup');

userIcon.addEventListener('click', function () {
    if (userPopup.style.display === 'block') {
        userPopup.style.display = 'none';
    } else {
        userPopup.style.display = 'block';
    }
});

// Fechar o pop-up se clicar fora dele
window.addEventListener('click', function (e) {
    if (!userIcon.contains(e.target) && !userPopup.contains(e.target)) {
        userPopup.style.display = 'none';
    }
});
