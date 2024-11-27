<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

if ($_SESSION['cargo'] != 'ADM') {
    echo "<script>
        alert('Você não tem permissão para acessar essa página.');
        window.history.back();
    </script>";
    exit;
}

$idFuncionario = $_GET['id'] ?? null;
if (!$idFuncionario) {
    echo "ID da categoria não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $sql = "DELETE FROM funcionario WHERE idFun = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idFuncionario);
        $stmt->execute();
        $conn->commit();

        header("Location: " . BASE_URL . "receitas/Paginas/categorias/listaCategoria.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a categoria: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Confirmar Exclusão</h2>
    <p>Tem certeza de que deseja excluir este Funcionário?</p>
    <form method="POST">
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/listaFuncionario.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
