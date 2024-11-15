<?php session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idAvaliacao = $_GET['id'] ?? null;
if (!$idAvaliacao) {
    echo "ID da avaliação não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        $sql_receita = "SELECT r.nome_rec 
                        FROM receita r 
                        JOIN degustacao d ON r.idReceita = d.idReceita 
                        WHERE d.idDegustacao = ?";
        $stmt_receita = $conn->prepare($sql_receita);
        $stmt_receita->bind_param("i", $idAvaliacao); // Usar id da avaliação
        $stmt_receita->execute();
        $stmt_receita->bind_result($nome_receita);
        $stmt_receita->fetch();
        $stmt_receita->close();



        $sql_delete_avaliacao = "DELETE FROM degustacao WHERE idDegustacao = ?";
        $stmt_delete_avaliacao = $conn->prepare($sql_delete_avaliacao);
        $stmt_delete_avaliacao->bind_param("i", $idAvaliacao);
        $stmt_delete_avaliacao->execute();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão da avaliaçao '$nome_receita' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/avaliacoes/listaAvaliacao.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a avaliação: " . $e->getMessage();
    }
}
function registrarLog($conn, $idUsuario, $tipo, $descricao) {
    $sql_log = "INSERT INTO log_sistema (idUsuario, tipo_acao, acao, data) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    
    if ($stmt_log === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    
    $stmt_log->bind_param("iss", $idUsuario, $tipo, $descricao);

    if (!$stmt_log->execute()) {
        die('Erro ao executar a consulta: ' . $stmt_log->error);
    }

    $stmt_log->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Avaliação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body>
    <div class="container mt-5" id="formulario1">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir esta avaliação?</p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/excluirAvaliacao.php?id=<?php echo $idAvaliacao; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/listaAvaliacao.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
