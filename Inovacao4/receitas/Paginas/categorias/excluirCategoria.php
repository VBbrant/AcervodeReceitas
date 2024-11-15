<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idCategoria = $_GET['id'] ?? null;
if (!$idCategoria) {
    echo "ID da categoria não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome da categoria para o log
        $sql_nome_categoria = "SELECT nome FROM categoria WHERE idCategoria = ?";
        $stmt_nome_categoria = $conn->prepare($sql_nome_categoria);
        $stmt_nome_categoria->bind_param("i", $idCategoria);
        $stmt_nome_categoria->execute();
        $stmt_nome_categoria->bind_result($nome_categoria);
        $stmt_nome_categoria->fetch();
        $stmt_nome_categoria->close();

        // Exclui categoria
        $sql_delete_categoria = "DELETE FROM categoria WHERE idCategoria = ?";
        $stmt_delete_categoria = $conn->prepare($sql_delete_categoria);
        $stmt_delete_categoria->bind_param("i", $idCategoria);
        $stmt_delete_categoria->execute();
        $stmt_delete_categoria->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão da categoria '$nome_categoria' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/categorias/listaCategoria.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a categoria: " . $e->getMessage();
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
    <title>Excluir Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Confirmar Exclusão</h2>
    <p>Tem certeza de que deseja excluir esta categoria?</p>
    <form method="POST">
        <button type="submit" class="btn btn-danger">Excluir</button>
        <a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/listaCategoria.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
