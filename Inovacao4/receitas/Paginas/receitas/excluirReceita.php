<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Recebe o ID da receita para excluir
$idReceita = $_GET['id'] ?? null;
if (!$idReceita) {
    echo "ID da receita não fornecido.";
    exit;
}

// Verifica se a requisição é POST para confirmar a exclusão
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicia uma transação para garantir a integridade
    $conn->begin_transaction();
    
    try {
        // Remove os ingredientes associados à receita primeiro (caso exista uma tabela de associação)
        $sql_delete_ingredientes = "DELETE FROM receita_ingrediente WHERE idReceita = ?";
        $stmt_delete_ingredientes = $conn->prepare($sql_delete_ingredientes);
        $stmt_delete_ingredientes->bind_param("i", $idReceita);
        $stmt_delete_ingredientes->execute();

        // Exclui a receita
        $sql_delete_receita = "DELETE FROM receita WHERE idReceita = ?";
        $stmt_delete_receita = $conn->prepare($sql_delete_receita);
        $stmt_delete_receita->bind_param("i", $idReceita);
        $stmt_delete_receita->execute();

        // Confirma a exclusão
        $conn->commit();
        
        // Redireciona para a lista de receitas ou página de confirmação
        header("Location: " . BASE_URL . "receitas/Paginas/receitas/verReceita.php?excluido=1");
        exit;
    } catch (Exception $e) {
        // Em caso de erro, desfaz a transação
        $conn->rollback();
        echo "Erro ao excluir a receita: " . $e->getMessage();
    }
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
        
        <!-- Botão para confirmar a exclusão -->
        <form method="POST" action="excluirReceita.php?id=<?php echo $idReceita; ?>">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/verReceitaIndividual.php?id=<?php echo $idReceita; ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
