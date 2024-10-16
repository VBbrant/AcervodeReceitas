<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Compartilhe Receitas</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/estilo2.css">
    
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="logo">SABOR<span style="color: red;">ARTE</span></div>
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="#">Início</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Receitas</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Livros</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Sobre</a></li>
            </ul>
        </nav>
        <div class="icons">
            <a href="#" class="text-white mr-3"><i class="fas fa-search"></i></a>
            <a href="#" class="text-white"><i class="fas fa-user"></i></a>
        </div>
    </div>
</header>

<!-- Receitas em Destaque -->
<div class="container mt-4">
    <h2 class="section-title">Crie e compartilhe novas receitas</h2>
    <div class="row featured-recipes">
        <div class="col-md-3 recipe-box">
            <img src="https://via.placeholder.com/150" alt="Churrasco Maracana">
            <p class="text-center">Churrasco Maracanã</p>
        </div>
        <div class="col-md-3 recipe-box">
            <img src="https://via.placeholder.com/150" alt="Feijão a Mengão">
            <p class="text-center">Feijão à Mengão</p>
        </div>
        <div class="col-md-3 recipe-box">
            <img src="https://via.placeholder.com/150" alt="Arrascaren de Sol">
            <p class="text-center">Arrascaren de Sol</p>
        </div>
        <div class="col-md-3 recipe-box">
            <img src="https://via.placeholder.com/150" alt="Strogonoff de BH">
            <p class="text-center">Strogonoff de BH</p>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-primary">Mais Receitas</button>
    </div>
</div>

<!-- Livros em Destaque -->
<div class="container mt-4">
    <h2 class="section-title">Livros em Destaque</h2>
    <div class="row featured-books">
        <div class="col-md-4 book-box text-center">
            <div class="p-3" style="background-color: #f7f7f7;">
                <h5>SABOREIE</h5>
                <p>por David Luiz</p>
            </div>
        </div>
        <div class="col-md-4 book-box text-center">
            <div class="p-3" style="background-color: #f7f7f7;">
                <h5>Fórmula Mágica do Chefe</h5>
                <p>por Mano Brown</p>
            </div>
        </div>
        <div class="col-md-4 book-box text-center">
            <div class="p-3" style="background-color: #f7f7f7;">
                <h5>Paixão na Receita</h5>
                <p>por Rubro Negro</p>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-primary">Mais Livros</button>
    </div>
</div>

<!-- Avaliações em Destaque -->
<div class="container mt-4 reviews">
    <h2 class="section-title">Avaliações em Destaque</h2>
    <div class="review-box">
        <h5>Tite</h5>
        <p>Inspirado no triunfo do Flamengo em dezembro de 1981...</p>
        <div class="star-rating">
            ★★★★★
        </div>
    </div>
    <div class="review-box">
        <h5>Zico</h5>
        <p>Uma versão especial do tradicional feijão brasileiro...</p>
        <div class="star-rating">
            ★★★★★
        </div>
    </div>
</div>

<!-- Footer (opcional) -->
<footer class="mt-4 text-center">
    <p>&copy; 2024 SaborArte. Todos os direitos reservados.</p>
</footer>

<!-- Bootstrap JS and FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
