<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

$idReceita = $_GET['id'] ?? null;
if (!$idReceita) {
    echo "ID da receita não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome da receita para o log
        $sql_nome_receita = "SELECT nome_rec FROM receita WHERE idReceita = ?";
        $stmt_nome_receita = $conn->prepare($sql_nome_receita);
        $stmt_nome_receita->bind_param("i", $idReceita);
        $stmt_nome_receita->execute();
        $stmt_nome_receita->bind_result($nome_receita);
        $stmt_nome_receita->fetch();
        $stmt_nome_receita->close();

        // Excluir imagem associada
        $sql_imagem = "SELECT arquivo_imagem FROM receita WHERE idReceita = ?";
        $stmt_imagem = $conn->prepare($sql_imagem);
        $stmt_imagem->bind_param("i", $idReceita);
        $stmt_imagem->execute();
        $stmt_imagem->bind_result($arquivo_imagem);
        $stmt_imagem->fetch();
        $stmt_imagem->close();

        if ($arquivo_imagem && file_exists(ROOT_PATH . $arquivo_imagem)) {
            unlink(ROOT_PATH . $arquivo_imagem);
        }

        // Exclui ingredientes associados
        $sql_delete_ingredientes = "DELETE FROM receita_ingrediente WHERE idReceita = ?";
        $stmt_delete_ingredientes = $conn->prepare($sql_delete_ingredientes);
        $stmt_delete_ingredientes->bind_param("i", $idReceita);
        $stmt_delete_ingredientes->execute();
        $stmt_delete_ingredientes->close();

        // Exclui receita
        $sql_delete_receita = "DELETE FROM receita WHERE idReceita = ?";
        $stmt_delete_receita = $conn->prepare($sql_delete_receita);
        $stmt_delete_receita->bind_param("i", $idReceita);
        $stmt_delete_receita->execute();
        $stmt_delete_receita->close();

        $conn->commit();

        registrarLog($conn, $idUsuario, "exclusao", "Exclusão da receita '$nome_receita' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/receitas/verReceita.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir a receita: " . $e->getMessage();
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
    <title>Excluir Receita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir esta receita?</p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/receitas/excluirReceita.php?id=<?php echo $idReceita; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/verReceitaIndividual.php?id=<?php echo $idReceita; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
