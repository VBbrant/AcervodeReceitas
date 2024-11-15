<?php session_start();
require_once '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';

$idLivro = $_GET['id'] ?? null;

// Query para buscar detalhes do livro
$sqlLivro = "SELECT l.titulo, l.isbn, l.link_imagem, l.arquivo_imagem, f.nome AS editor 
             FROM livro l
             LEFT JOIN funcionario f ON l.idEditor = f.idFun
             WHERE l.idLivro = ?";
$stmtLivro = $conn->prepare($sqlLivro);
$stmtLivro->bind_param("i", $idLivro);
$stmtLivro->execute();
$resultLivro = $stmtLivro->get_result();
$livro = $resultLivro->fetch_assoc();

// Query para buscar receitas relacionadas ao livro
$sqlReceitas = "SELECT r.idReceita, r.nome_rec, r.link_imagem, r.arquivo_imagem, r.descricao, r.modo_preparo,
                       c.nome AS categoria, f.nome AS chef, u.imagem_perfil AS perfil
                FROM livro_receita lr
                JOIN receita r ON lr.idReceita = r.idReceita    
                LEFT JOIN categoria c ON r.idCategoria = c.idCategoria
                LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun
                LEFT JOIN usuario u ON f.idLogin = u.idLogin
                WHERE lr.idLivro = ?";
$stmtReceitas = $conn->prepare($sqlReceitas);
$stmtReceitas->bind_param("i", $idLivro);
$stmtReceitas->execute();
$receitas = $stmtReceitas->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Livro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <style>
        body { background-color: blue; }
        .container-padding {
            padding: 20px;
            background-color: white;
            border: 2px solid black;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 1000px;
            display: flex; 
            flex-direction: column;
            align-items: center;
        }

        .chef-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: black;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        .chef-info i {
            color: red; /* Icon color */
            font-size: 1.2rem;
        }

        .chef-info span {
            font-size: 1rem;
            font-weight: bold;
            color: white;
        }

        .livro-img { max-height: 400px; width: auto; }
        
        /* Estilo das bolinhas de navegação */
        .carousel-indicators {
            display: flex;
            justify-content: center;
            margin-top: -10px;
        }

        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: blue !important;
            opacity: 0.5;
            border: none;
        }
        #ancora{
            display:block !important;
            width: 990px !important;
        }

        /* Estilo do contêiner dos post-its */
        .text-center {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Estilo dos post-its */
        .post-it {
            background-color: #ffeb3b;
            border: 2px solid #d32f2f;
            padding: 15px;
            border-radius: 10px;
            margin: 10px auto;
            position: relative;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            display: inline-block;
            width: auto;
            max-width: 980px;
        }

        .post-it::before {
            content: '';
            width: 20px;
            height: 20px;
            background-color: #d32f2f;
            position: absolute;
            top: -10px;
            left: 20px;
            border-radius: 50%;
        }

        /* Pino de quadro */
        .post-it::after {
            content: url(''); /* Substitua 'pino.png' com o caminho para a imagem do pino */
            position: absolute;
            top: -5px;
            left: calc(50% - 10px);
            width: 2px !important;
            height: 2px !important;
        }

        /* Botões de navegação */
        .carousel {position: relative; /* Definindo o container do carrossel como posição relativa */
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
            opacity: 0.6;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .carousel-control-prev:hover,
        .carousel-control-next:hover{
            background-color: grey !important;
            opacity: 0.8;
            width: 5%;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
        }

        .carousel-control-prev {
            left: 10px; /* Distância da borda esquerda */
        }

        .carousel-control-next {
            right: 10px; /* Distância da borda direita */
        }

        /* CSS para animações de entrada e saída */
        .carousel-item {
            position: absolute;
            width: 100%;
            top: 0;
            opacity: 0;
            transition: transform 0.8s ease, opacity 0.8s ease; /* Transição suave */
        }

        .carousel-item.active {
            position: relative;
            opacity: 1;
            transform: translateX(0); /* Posição inicial */
        }

        .carousel-item-next,
        .carousel-item-prev {
            transform: translateX(100%); /* Quando não ativo, movendo para fora da tela */
        }

        .carousel-item-prev {
            transform: translateX(-100%); /* Quando não ativo, movendo para o outro lado */
        }

        /* Caso queira animação de slide-in e slide-out, podemos usar transformação mais suave */

        .carousel-item-next,
        .carousel-item-prev {
            transition: transform 0.8s ease-in-out; /* Definindo transição suave ao mover */
        }


        .carousel-item.slide-in-right {
            animation: slide-in-right 0.8s forwards;
        }

        .carousel-item.slide-out-left {
            animation: slide-out-left 0.8s forwards;
        }

        .carousel-item.slide-in-left {
            animation: slide-in-left 0.8s forwards;
        }

        .carousel-item.slide-out-right {
            animation: slide-out-right 0.8s forwards;
        }

        @keyframes slide-in-right { from { transform: translateX(100%); } to { transform: translateX(0); } }
        @keyframes slide-out-left { from { transform: translateX(0); } to { transform: translateX(-100%); } }
        @keyframes slide-in-left { from { transform: translateX(-100%); } to { transform: translateX(0); } }
        @keyframes slide-out-right { from { transform: translateX(0); } to { transform: translateX(100%); } }
    </style>
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

    <!-- Primeira Página: Detalhes do Livro -->
    <div class="container-padding text-center">
        <?php if ($livro): ?>
            <img src="<?php echo !empty($livro['link_imagem']) ? $livro['link_imagem'] : BASE_URL . $livro['arquivo_imagem']; ?>" alt="Imagem do Livro" class="img-fluid livro-img rounded">
            <h1 class="mt-3"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($livro['isbn']); ?></p>
            <p><strong>Editor:</strong> <?php echo htmlspecialchars($livro['editor']); ?></p>
        <?php else: ?>
            <p>Livro não encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Segunda Página: Carrossel de Receitas -->
    <?php if (!empty($receitas)): ?>
        <div class="container-padding">
            
            <div id="carouselReceitas" class="carousel slide" data-bs-interval="false">
                
                <div class="carousel-indicators">
                    <?php foreach ($receitas as $index => $receita): ?>
                        <button type="button" data-bs-target="#carouselReceitas" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($receitas as $index => $receita): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="text-center">
                                <img src="<?php echo !empty($receita['link_imagem']) ? $receita['link_imagem'] : BASE_URL . $receita['arquivo_imagem']; ?>" alt="Imagem da Receita" class="img-fluid" style="max-height: 300px;">
                                <div class="post-it">
                                    <strong><h2><?php echo htmlspecialchars($receita['nome_rec']); ?></h2></strong>
                                    <div class="chef-info">
                                        <?php 
                                            if (!empty($receita['perfil'])) {
                                                $avatar = BASE_URL . 'receitas/imagens/perfil/' . $receita['perfil'];} else { $avatar = null;}
                                        ?>
                                        <?php if ($avatar !== null) : ?>
                                            <img src="<?php echo htmlspecialchars($avatar); ?>" class="rounded-circle" width="40" height="40" alt="Avatar">
                                        <?php else : ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif ?>
                                        <span>Chefe <?php echo htmlspecialchars($receita['chef']); ?></span>
                                    </div>
                                </div>
                                <div class="post-it"><strong>Categoria:</strong> <?php echo htmlspecialchars($receita['categoria']); ?></div>
                                <div class="post-it"><strong>Descrição:</strong> <?php echo htmlspecialchars($receita['descricao']); ?></div>
                                <div class="post-it"><strong>Ingredientes:</strong>
                                    <ul>
                                        <?php
                                        $sqlIngredientes = "SELECT i.nome, ri.quantidade, m.sistema 
                                                            FROM receita_ingrediente ri
                                                            JOIN ingrediente i ON ri.idIngrediente = i.idIngrediente
                                                            JOIN medida m ON ri.idMedida = m.idMedida
                                                            WHERE ri.idReceita = ?";
                                        $stmtIngredientes = $conn->prepare($sqlIngredientes);
                                        $stmtIngredientes->bind_param("i", $receita['idReceita']);
                                        $stmtIngredientes->execute();
                                        $ingredientes = $stmtIngredientes->get_result()->fetch_all(MYSQLI_ASSOC);
                                        foreach ($ingredientes as $ingrediente): ?>
                                            <li><?php echo htmlspecialchars($ingrediente['nome']) . ' - ' . htmlspecialchars($ingrediente['quantidade']) . ' ' . htmlspecialchars($ingrediente['sistema']); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="post-it"><strong>Modo de Preparo:</strong> <?php echo htmlspecialchars($receita['modo_preparo']); ?></div>
                                <div class="hidden" id="ancora"></div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselReceitas" data-bs-slide="prev">
                            <span style="background-color: grey !important; opacity: 0.8;" class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselReceitas" data-bs-slide="next">
                            <span style="background-color: grey !important; opacity: 0.8; z-index: 2;" class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>
    <?php endif; ?>

    <!-- Terceira Página: Voltar e Gerar PDF -->
    <div class="container-padding">
        <div class="d-flex justify-content-between">
            <button onclick="window.history.back()" class="btn btn-secondary">Voltar</button>
            <button onclick="gerarPDF()" class="btn btn-primary">Gerar PDF</button>
        </div>
    </div>

    <script>
        function gerarPDF() {
        const idLivro = "<?php echo $idLivro; ?>"; // Obtém o ID do livro
        window.open("PDFLivro.php?id=" + idLivro, "_blank"); // Abre o PDF em uma nova aba
        }
</script>

    </script>
    <?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

