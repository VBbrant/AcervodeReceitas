<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idIngrediente = $_GET['id'] ?? null;
if (!$idIngrediente) {
    echo "ID do ingrediente não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $sql_delete_ingrediente = "DELETE FROM ingrediente WHERE idIngrediente = ?";
        $stmt_delete_ingrediente = $conn->prepare($sql_delete_ingrediente);
        $stmt_delete_ingrediente->bind_param("i", $idIngrediente);
        $stmt_delete_ingrediente->execute();

        $conn->commit();
        
        header("Location: " . BASE_URL . "receitas/Paginas/ingredientes/listaIngrediente.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o ingrediente: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Ingrediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir este ingrediente?</p>
        
        <!-- Botão para confirmar a exclusão -->
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/excluirIngrediente.php?id=<?php echo $idIngrediente; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/listaIngrediente.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
