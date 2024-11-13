<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idLivro = $_GET['id'] ?? null;
if (!$idLivro) {
    echo "ID do livro não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Recupera os dados do livro para verificar a imagem
        $sql_get_book = "SELECT arquivo_imagem FROM livro WHERE idLivro = ?";
        $stmt_get_book = $conn->prepare($sql_get_book);
        $stmt_get_book->bind_param("i", $idLivro);
        $stmt_get_book->execute();
        $result_book = $stmt_get_book->get_result();
        $book = $result_book->fetch_assoc();
        
        // Se o livro tem uma imagem, exclui da pasta
        if ($book['arquivo_imagem']) {
            $imagePath = "../../../receitas/imagens/livros/" . $book['arquivo_imagem'];
            if (file_exists($imagePath)) {
                unlink($imagePath);  // Deleta a imagem
            }
        }

        // Exclui o livro da tabela
        $sql_delete_book = "DELETE FROM livro WHERE idLivro = ?";
        $stmt_delete_book = $conn->prepare($sql_delete_book);
        $stmt_delete_book->bind_param("i", $idLivro);
        $stmt_delete_book->execute();

        $conn->commit();
        
        header("Location: " . BASE_URL . "receitas/Paginas/livros/listaLivro.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o livro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Livro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir este livro?</p>
        
        <!-- Formulário para confirmar a exclusão -->
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/livros/excluirLivro.php?id=<?php echo $idLivro; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/listaLivro.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
