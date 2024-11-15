<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Inicia a sessão se não estiver iniciada
}
require_once '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';

$idLivro = $_GET['id'] ?? $_SESSION['idLivro'];

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
    <style>
        /* Ajuste para A3, com conteúdo centralizado */
        body { 
            background-color: #ffffff; /* branco */
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            overflow: hidden;
        }
        
        .capa, .pagina {
            width: 297mm;   /* Largura A3 em retrato */
            height: 420mm;  /* Altura A3 em retrato */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-color: #ffffff; /* branco */
        }

        /* Capa específica */
        .capa {
            background-color: #f5f5dc; /* beige */
        }
        
        /* Estilo do conteúdo */
        .livro-img {
            max-height: 400px; 
            width: auto; 
            margin: 0 auto;
        }

        h1, p, h2 {
            text-align: center;
            margin: 10px 0;
        }

        /* Estrutura de cada receita com nova página e alinhamento central */
        .nova-pagina {
            page-break-before: always;
        }

        /* Container para centralizar e limitar largura */
        .container-padding {
            border: 2px solid #000000; /* preto */
            border-radius: 8px;
            padding: 20px;
            max-width: 90%;  /* Centralização */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Centraliza e dimensiona imagens de receitas */
        .img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 300px;
            height: 300px;
            margin: 0 auto;
            overflow: hidden;
        }

        .img-container img {
            max-width: 100%;
            max-height: 100%;
        }

        /* Estilo dos post-its */
        .post-it {
            background-color: #ffeb3b; /* amarelo */
            border: 2px solid #d32f2f; /* vermelho escuro */
            padding: 15px;
            border-radius: 10px;
            margin: 10px auto;
            max-width: 85%;
            position: relative;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        /* Post-it ponto vermelho */
        .post-it::before {
            content: '';
            width: 15px;
            height: 15px;
            background-color: #d32f2f; /* vermelho escuro */
            position: absolute;
            top: -10px;
            left: 15px;
            border-radius: 50%;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        /* Informações do chef */
        .chef-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #000000; /* preto */
            border-radius: 20px;
            padding: 0.5rem 1rem;
            color: #ffffff; /* branco */
        }
        
        .chef-info i { color: #ff0000; font-size: 1.2rem; } /* vermelho */

        /* Esconde elementos de navegação em PDF */
        .btn, .carousel-indicators, .carousel-control-prev, .carousel-control-next { display: none; }
    </style>
</head>
<body>
    <!-- Primeira Página: Capa do Livro -->
    <div class="container-padding capa" style="background-color: #d2b48c !important;">
        <?php if ($livro): ?>
            <img src="<?php echo !empty($livro['link_imagem']) ? $livro['link_imagem'] : BASE_URL . $livro['arquivo_imagem']; ?>" alt="Imagem do Livro" class="livro-img rounded">
            <h1><?php echo htmlspecialchars($livro['titulo']); ?></h1>
            <p><strong>ISBN:</strong> <?php echo htmlspecialchars($livro['isbn']); ?></p>
            <p><strong>Editor:</strong> <?php echo htmlspecialchars($livro['editor']); ?></p>
        <?php else: ?>
            <p>Livro não encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Página de Receitas -->
    <?php if (!empty($receitas)): ?>
        <?php foreach ($receitas as $index => $receita): ?>
            <div class="container-padding pagina nova-pagina" style="background-color: #808080 !important;">
                <h2><?php echo htmlspecialchars($receita['nome_rec']); ?></h2>
                
                <?php if (!empty($receita['link_imagem']) || !empty($receita['arquivo_imagem'])): ?>
                    <div class="img-container">
                        <img src="<?php echo !empty($receita['link_imagem']) ? $receita['link_imagem'] : BASE_URL . $receita['arquivo_imagem']; ?>" alt="Imagem da Receita">
                    </div>
                <?php endif; ?>
                <div class="chef-info">
                    <?php if (!empty($receita['perfil'])) : ?>
                        <img src="<?php echo htmlspecialchars(BASE_URL . 'receitas/imagens/perfil/' . $receita['perfil']); ?>" class="rounded-circle" width="40" height="40" alt="Avatar">
                    <?php else : ?>
                        <i class="fas fa-user"></i>
                    <?php endif ?>
                    <span>Chefe <?php echo htmlspecialchars($receita['chef']); ?></span>
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
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
