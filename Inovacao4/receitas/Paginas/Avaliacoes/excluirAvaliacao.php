<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idAvaliacao = $_GET['id'] ?? null;
if (!$idAvaliacao) {
    echo "ID da avaliação não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $sql_delete_avaliacao = "DELETE FROM degustacao WHERE idDegustacao = ?";
        $stmt_delete_avaliacao = $conn->prepare($sql_delete_avaliacao);
        $stmt_delete_avaliacao->bind_param("i", $idAvaliacao);
        $stmt_delete_avaliacao->execute();

        $conn->commit();
        
        header("Location: " . BASE_URL . "receitas/Paginas/avaliacoes/listaAvaliacao.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a avaliação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Avaliação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir esta avaliação?</p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/excluirAvaliacao.php?id=<?php echo $idAvaliacao; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/listaAvaliacao.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
