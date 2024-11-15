<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idRestaurante = $_GET['id'] ?? null;
if (!$idRestaurante) {
    echo "ID do restaurante não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome do restaurante para o log
        $sql_nome_restaurante = "SELECT nome FROM restaurante WHERE idRestaurante = ?";
        $stmt_nome_restaurante = $conn->prepare($sql_nome_restaurante);
        $stmt_nome_restaurante->bind_param("i", $idRestaurante);
        $stmt_nome_restaurante->execute();
        $stmt_nome_restaurante->bind_result($nome_restaurante);
        $stmt_nome_restaurante->fetch();
        $stmt_nome_restaurante->close();

        // Exclui restaurante
        $sql_delete_restaurante = "DELETE FROM restaurante WHERE idRestaurante = ?";
        $stmt_delete_restaurante = $conn->prepare($sql_delete_restaurante);
        $stmt_delete_restaurante->bind_param("i", $idRestaurante);
        $stmt_delete_restaurante->execute();
        $stmt_delete_restaurante->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão do restaurante '$nome_restaurante' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/restaurantes/listaRestaurantes.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o restaurante: " . $e->getMessage();
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
    <title>Excluir Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir este restaurante?</p>
        <form method="POST" action="excluirRestaurante.php?id=<?php echo $idRestaurante; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/listaRestaurantes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
