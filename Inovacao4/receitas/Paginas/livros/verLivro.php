<?php
require_once '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';

// Conexão com o banco de dados
$idLivro = $_GET['id'] ?? null;

if ($idLivro) {
    // Consulta o livro pelo ID
    $sql_livro = "SELECT l.idLivro, l.titulo, l.isbn, l.link_imagem, l.arquivo_imagem, f.nome AS nome_editor
                  FROM livro l
                  JOIN funcionario f ON l.idEditor = f.idFun
                  WHERE l.idLivro = ?";
    $stmt = $conn->prepare($sql_livro);
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $livro = $stmt->get_result()->fetch_assoc();

    // Consulta as receitas associadas ao livro
    $sql_receitas = "SELECT r.idReceita, r.nome_rec
                     FROM livro_receita lr
                     JOIN receita r ON lr.idReceita = r.idReceita
                     WHERE lr.idLivro = ?";
    $stmt = $conn->prepare($sql_receitas);
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $receitas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $stmt->close();
} else {
    echo "Livro não encontrado.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Livro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/livroLista.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="conteudo-livro">
        <div class="container mt-5">
            <div class="text-center">
                <img src="<?php echo !empty($livro['link_imagem']) ? $livro['link_imagem'] : BASE_URL . $livro['arquivo_imagem']; ?>" alt="Imagem do Livro" class="img-fluid rounded">
                <h1 class="mt-4"><?php echo htmlspecialchars($livro['titulo']); ?></h1>
                <p class="lead">ISBN: <?php echo htmlspecialchars($livro['isbn']); ?></p>
                <a href="editarLivro.php?id=<?php echo $idLivro; ?>" class="btn btn-dark">Editar</a>
                <a href="excluirLivro.php?id=<?php echo $idLivro; ?>" class="btn btn-dark">Excluir</a>
            </div>

            <!-- Informações sobre o Editor -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h3>Editor</h3>
                <p><?php echo htmlspecialchars($livro['nome_editor']); ?></p>
            </div>

            <!-- Lista de Receitas do Livro -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h3>Receitas no Livro</h3>
                <ul>
                    <?php foreach ($receitas as $receita): ?>
                        <li>
                            <a href="verReceita.php?id=<?php echo $receita['idReceita']; ?>">
                                <?php echo htmlspecialchars($receita['nome_rec']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>                

    <?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
</body>
</html>
