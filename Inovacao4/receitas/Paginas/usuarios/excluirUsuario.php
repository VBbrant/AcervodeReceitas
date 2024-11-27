<?php
session_start();
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idUsuarioAtual = $_SESSION['idLogin'];

$idUsuario = $_GET['id'] ?? null;
if (!$idUsuario) {
    echo "ID do usuário não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();

    try {
        // Busca o nome do usuário para o log
        $sql_nome_usuario = "SELECT nome FROM funcionario WHERE idFun = ?";
        $stmt_nome_usuario = $conn->prepare($sql_nome_usuario);
        $stmt_nome_usuario->bind_param("i", $idUsuario);
        $stmt_nome_usuario->execute();
        $stmt_nome_usuario->bind_result($nome_usuario);
        $stmt_nome_usuario->fetch();
        $stmt_nome_usuario->close();

        // Verifica e exclui dependências, como metas
        $sql_delete_metas = "DELETE FROM metas WHERE idCozinheiro = ?";
        $stmt_delete_metas = $conn->prepare($sql_delete_metas);
        $stmt_delete_metas->bind_param("i", $idUsuario);
        $stmt_delete_metas->execute();
        $stmt_delete_metas->close();

        // Exclui o próprio usuário
        $sql_delete_usuario = "DELETE FROM usuario WHERE idLogin = ?";
        $stmt_delete_usuario = $conn->prepare($sql_delete_usuario);
        $stmt_delete_usuario->bind_param("i", $idUsuario);
        $stmt_delete_usuario->execute();
        $stmt_delete_usuario->close();

        $conn->commit();

        registrarLog($conn, $idUsuarioAtual, "exclusao", "Exclusão do usuário '$nome_usuario' realizada com sucesso!");
        header("Location: " . BASE_URL . "receitas/Paginas/usuarios/listaUsuario.php?excluido=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir o usuário: " . $e->getMessage();
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
    <title>Excluir Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Confirmar Exclusão</h2>
        <p>Tem certeza de que deseja excluir este usuário?</p>
        
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/excluirUsuario.php?id=<?php echo $idUsuario; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/listaUsuario.php?id=<?php echo $idUsuario; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
