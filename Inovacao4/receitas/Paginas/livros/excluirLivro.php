<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idLivro = $_GET['id'] ?? null;
if (!$idLivro) {
    echo "ID do livro não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome do livro para o log
        $sql_nome_livro = "SELECT titulo FROM livro WHERE idLivro = ?";
        $stmt_nome_livro = $conn->prepare($sql_nome_livro);
        $stmt_nome_livro->bind_param("i", $idLivro);
        $stmt_nome_livro->execute();
        $stmt_nome_livro->bind_result($nome_livro);
        $stmt_nome_livro->fetch();
        $stmt_nome_livro->close();

        // Exclui livro
        $sql_delete_livro = "DELETE FROM livro WHERE idLivro = ?";
        $stmt_delete_livro = $conn->prepare($sql_delete_livro);
        $stmt_delete_livro->bind_param("i", $idLivro);
        $stmt_delete_livro->execute();
        $stmt_delete_livro->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão do livro '$nome_livro' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/livros/listaLivro.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o livro: " . $e->getMessage();
    }
}

function registrarLog($conn, $idUsuario, $tipo, $descricao) {
    $sql_log = "INSERT INTO log_sistema (idUsuario, tipo_acao, acao, data) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("iss", $idUsuario, $tipo, $descricao);
    $stmt_log->execute();
    $stmt_log->close();
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
