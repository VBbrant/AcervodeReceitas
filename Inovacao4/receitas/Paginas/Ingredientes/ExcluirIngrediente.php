<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idIngrediente = $_GET['id'] ?? null;
if (!$idIngrediente) {
    echo "ID do ingrediente não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome do ingrediente para o log
        $sql_nome_ingrediente = "SELECT nome FROM ingrediente WHERE idIngrediente = ?";
        $stmt_nome_ingrediente = $conn->prepare($sql_nome_ingrediente);
        $stmt_nome_ingrediente->bind_param("i", $idIngrediente);
        $stmt_nome_ingrediente->execute();
        $stmt_nome_ingrediente->bind_result($nome_ingrediente);
        $stmt_nome_ingrediente->fetch();
        $stmt_nome_ingrediente->close();

        // Exclui ingrediente
        $sql_delete_ingrediente = "DELETE FROM ingrediente WHERE idIngrediente = ?";
        $stmt_delete_ingrediente = $conn->prepare($sql_delete_ingrediente);
        $stmt_delete_ingrediente->bind_param("i", $idIngrediente);
        $stmt_delete_ingrediente->execute();
        $stmt_delete_ingrediente->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão do ingrediente '$nome_ingrediente' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/ingredientes/listaIngrediente.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o ingrediente: " . $e->getMessage();
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
