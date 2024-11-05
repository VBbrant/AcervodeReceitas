<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idMedida = $_GET['id'] ?? null;
if (!$idMedida) {
    echo "ID da medida não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $sql_delete_medida = "DELETE FROM medida WHERE idMedida = ?";
        $stmt_delete_medida = $conn->prepare($sql_delete_medida);
        $stmt_delete_medida->bind_param("i", $idMedida);
        $stmt_delete_medida->execute();

        $conn->commit();
        
        header("Location: " . BASE_URL . "receitas/Paginas/medidas/listaMedida.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a medida: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Medida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir esta medida?</p>
        
        <!-- Botão para confirmar a exclusão -->
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/medidas/excluirMedida.php?id=<?php echo $idMedida; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/listaMedida.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
