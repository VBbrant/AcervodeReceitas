<!-- index.php -->
<?php
require_once '../../config.php';
include ROOT_PATH . 'receitas/conn.php';

$sql_featured = "SELECT r.idReceita, r.nome_rec, r.descricao, r.link_imagem, r.arquivo_imagem ,f.nome as chef_name 
                FROM receita r 
                LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun 
                ORDER BY r.data_criacao DESC 
                LIMIT 6";
$result_featured = $conn->query($sql_featured);

$sql_reviews = "SELECT r.idReceita, r.nome_rec, f.nome as reviewer_name, r.descricao 
                FROM receita r 
                LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun 
                ORDER BY r.data_criacao DESC 
                LIMIT 2";
$result_reviews = $conn->query($sql_reviews);

$sql_avaliacao = "SELECT d.nota_degustacao, d.data_degustacao, r.nome_rec, c.comentario_texto AS descricao, u.nome as nomeUsuario, f.nome, u.imagem_perfil as avatar
                FROM degustacao d
                LEFT JOIN receita r ON d.idReceita = r.idReceita
                LEFT JOIN funcionario f ON d.idDegustador = f.idFun
                LEFT JOIN usuario u ON f.idLogin = u.idLogin
                LEFT JOIN comentario c ON d.idDegustacao = c.idDegustacao";
$result_avaliacao = $conn->query($sql_avaliacao);

$sql_featured_books = "SELECT l.idLivro, l.titulo, l.link_imagem, l.arquivo_imagem, f.nome as editor_name
                       FROM livro l
                       LEFT JOIN funcionario f ON l.idEditor = f.idFun
                       ORDER BY l.idLivro DESC
                       LIMIT 3"; // Limita a 6 livros em destaque

$result_featured_books = $conn->query($sql_featured_books);


?>
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
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

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
                                <img src="<?php echo !empty($recipe['link_imagem']) ? $recipe['link_imagem'] : BASE_URL . $recipe['arquivo_imagem']; ?>" 
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
                <?php while ($book = $result_featured_books->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="book-card text-center">
                            <!-- Adicionando o link ao redor da imagem e do título para redirecionar para verLivro.php -->
                            <a href="<?= BASE_URL; ?>receitas/Paginas/livros/verLivro.php?id=<?php echo $book['idLivro']; ?>">
                                <div class="book-cover mb-3">
                                    <!-- Verifica se arquivo_imagem é NULL e, caso seja, usa o link_imagem como URL -->
                                    <img src="<?php echo !empty($book['link_imagem']) ? $book['link_imagem'] : BASE_URL .'/receitas' .$book['arquivo_imagem']; ?>" alt="Imagem do Livro" class="img-fluid rounded"
                                    alt="<?php echo htmlspecialchars($book['titulo']); ?>" 
                                        class="rounded-4" 
                                        style="width: 200px; height: 280px; object-fit: cover;">
                                </div>
                                <h3 class="h5"><?php echo htmlspecialchars($book['titulo']); ?></h3>
                            </a>
                            <p class="text-muted">por <?php echo htmlspecialchars($book['editor_name']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= BASE_URL . 'receitas/Paginas/livros/listaLivro.php' ?>" class="btn btn-dark rounded-pill px-4">Mais Livros</a>
            </div>
        </div>
    </section>



    <!-- Featured Reviews Section -->
    <section class="featured-reviews py-5">
        <div class="container">
            <h2 class="text-center mb-4">AVALIAÇÕES EM DESTAQUE</h2>

            <div class="row g-4">
                <?php
                if ($result_avaliacao->num_rows > 0) {
                    while($avaliacao = $result_avaliacao->fetch_assoc()) {
                        $fullStars = floor($avaliacao['nota_degustacao'] / 2); 
                        $hasHalfStar = ($avaliacao['nota_degustacao'] % 2) >= 1;

                        // Ajuste aqui para pegar o caminho da imagem do usuário que fez o comentário
                        $avatarPath = !empty($avaliacao['avatar']) ? BASE_URL . 'receitas/imagens/perfil/' . $avaliacao['avatar'] : BASE_URL . 'path/to/default-avatar.jpg';
                ?>
                    <div class="col-md-6">
                        <div class="review-card bg-dark text-white p-4 rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="reviewer-avatar me-3">
                                    <!-- Exibe a imagem do usuário -->
                                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" class="rounded-circle" width="50" height="50" alt="Avatar">
                                </div>
                                <div>
                                    <h4 class="h6 mb-1"><?php echo htmlspecialchars($avaliacao['nomeUsuario']); ?></h4>
                                    <div class="stars">
                                        <?php 
                                        for ($i = 0; $i < $fullStars; $i++) { 
                                            echo '<i class="fas fa-star text-warning"></i>';
                                        }
                                        if ($hasHalfStar) {
                                            echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                        }
                                        for ($i = $fullStars + $hasHalfStar; $i < 5; $i++) {
                                            echo '<i class="far fa-star text-warning"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <h5 class="h6 mb-1"><?php echo htmlspecialchars($avaliacao['nome_rec']); ?></h5>
                            <p class="mb-0"><?php echo htmlspecialchars($avaliacao['descricao']); ?></p>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>Nenhuma avaliação disponível.</p>";
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?= BASE_URL . 'receitas/Paginas/avaliacoes/listaAvaliacao.php'?>" class="btn btn-dark rounded-pill px-4">Mais avaliações</a>
            </div>
        </div>
    </section>


    <footer class="mt-4 text-center">
        <p>&copy; 2024 SaborArte. Todos os direitos reservados.</p>
    </footer>


<?php include "../elementoPagina/rodape.php"; ?>
