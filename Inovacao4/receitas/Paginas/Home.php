<!-- index.php -->
<?php require_once '../../config.php'; ?>
<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/home3.css">
    
</head>
<body>
    <div class="background-container"></div>
   
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

    <?php include ROOT_PATH . 'receitas/conn.php';

    $sql_featured = "SELECT r.idReceita, r.nome_rec, r.descricao, r.link_imagem, f.nome as chef_name 
                    FROM receita r 
                    LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun 
                    ORDER BY r.data_criacao DESC 
                    LIMIT 4";
    $result_featured = $conn->query($sql_featured);

    $sql_reviews = "SELECT r.idReceita, r.nome_rec, f.nome as reviewer_name, r.descricao 
                    FROM receita r 
                    LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun 
                    ORDER BY r.data_criacao DESC 
                    LIMIT 2";
    $result_reviews = $conn->query($sql_reviews);
    ?>
  
    <div class="main-banner position-relative">
        <img src="../imagens/banner.png" class="w-100" alt="Background" style="height: 300px; object-fit: cover;">
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
            <h1 class="display-4 fw-bold">CRIE E COMPARTILHE<br>NOVAS RECEITAS</h1>
        </div>
    </div>

    <section class="featured-recipes py-5">
        <div class="container">
            <h2 class="text-center mb-4">RECEITAS EM DESTAQUE</h2>
            
            <div class="row g-4">
                <?php
                if ($result_featured->num_rows > 0) {
                    while($recipe = $result_featured->fetch_assoc()) {
                ?>
                    <div class="col-md-6">
                        <!-- Altere o link do href para apontar para "verReceita.php" com o ID da receita -->
                        <a href="receitas/verReceitaIndividual.php?id=<?php echo $recipe['idReceita']; ?>" class="text-decoration-none">
                            <div class="recipe-card position-relative rounded-4 overflow-hidden">
                                <img src="<?php echo BASE_URL . htmlspecialchars($recipe['link_imagem']); ?>" 
                                    class="w-100" 
                                    alt="<?php echo htmlspecialchars($recipe['nome_rec']); ?>"
                                    style="height: 200px; object-fit: cover;">
                                <div class="recipe-info position-absolute bottom-0 start-0 w-100 p-3 text-white"
                                    style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                    <h3 class="h5 mb-2"><?php echo htmlspecialchars(strtoupper($recipe['nome_rec'])); ?></h3>
                                    <p class="small mb-0"><?php echo htmlspecialchars($recipe['descricao']); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="receitas/verReceita.php" class="btn btn-dark rounded-pill px-4">Mais Receitas</a>
            </div>
        </div>
    </section>



    <!-- Featured Books Section -->
    <section class="featured-books py-5">
        <div class="container">
            <h2 class="text-center mb-4">LIVROS EM DESTAQUE</h2>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="book-card text-center">
                        <div class="book-cover mb-3">
                            <img src="../imagens/livro.jpg" alt="Saboreie" class="rounded-4" style="width: 200px; height: 280px; object-fit: cover;">
                        </div>
                        <h3 class="h5">SABOREIE</h3>
                        <p class="text-muted">por David Luiz</p>
                    </div>
                </div>
                <!-- Add more book cards as needed -->
            </div>
            
            <div class="text-center mt-4">
                <a href="livros.php" class="btn btn-dark rounded-pill px-4">Mais Livros</a>
            </div>
        </div>
    </section>

    <!-- Featured Reviews Section -->
    <section class="featured-reviews py-5">
        <div class="container">
            <h2 class="text-center mb-4">AVALIAÇÕES EM DESTAQUE</h2>
            
            <div class="row g-4">
                <?php
                if ($result_reviews->num_rows > 0) {
                    while($review = $result_reviews->fetch_assoc()) {
                ?>
                    <div class="col-md-6">
                        <div class="review-card bg-dark text-white p-4 rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="reviewer-avatar me-3">
                                    <img src="path/to/default-avatar.jpg" class="rounded-circle" width="50" height="50" alt="">
                                </div>
                                <div>
                                    <h4 class="h6 mb-1"><?php echo htmlspecialchars($review['reviewer_name']); ?></h4>
                                    <div class="stars">
                                        <?php for($i = 0; $i < 5; $i++) { ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0"><?php echo htmlspecialchars($review['descricao']); ?></p>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <footer class="mt-4 text-center">
        <p>&copy; 2024 SaborArte. Todos os direitos reservados.</p>
    </footer>


<?php include "../elementoPagina/rodape.php"; ?>
