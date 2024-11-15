<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idMeta = $_GET['id'] ?? null;
if (!$idMeta) {
    echo "ID da meta não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca as informações da meta para o log
        $sql_meta = "SELECT idMeta, metaReceitas, dataInicio, dataFinal FROM metas WHERE idMeta = ?";
        $stmt_meta = $conn->prepare($sql_meta);
        $stmt_meta->bind_param("i", $idMeta);
        $stmt_meta->execute();
        $stmt_meta->bind_result($id_meta, $meta_receitas, $data_inicio, $data_final);
        $stmt_meta->fetch();
        $stmt_meta->close();

        // Exclui a meta
        $sql_delete_meta = "DELETE FROM metas WHERE idMeta = ?";
        $stmt_delete_meta = $conn->prepare($sql_delete_meta);
        $stmt_delete_meta->bind_param("i", $idMeta);
        $stmt_delete_meta->execute();
        $stmt_delete_meta->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão da meta ID '$id_meta' com meta de receitas '$meta_receitas' (Período: $data_inicio a $data_final) realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/metas/listaMetas.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a meta: " . $e->getMessage();
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
    <title>Excluir Meta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir esta meta?</p>
        <form method="POST" action="excluirMeta.php?id=<?php echo $idMeta; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/listaMetas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
