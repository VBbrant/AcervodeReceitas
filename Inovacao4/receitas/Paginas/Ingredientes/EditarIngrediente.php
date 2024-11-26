<?php 
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Verifica se o ID do ingrediente foi fornecido
$id_ingrediente = $_GET['id'] ?? null;
if (!$id_ingrediente) {
    echo "ID do ingrediente não fornecido.";
    exit;
}

// Busca o ingrediente existente no banco de dados
$sql = "SELECT * FROM ingrediente WHERE idIngrediente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ingrediente);
$stmt->execute();
$result = $stmt->get_result();
$ingrediente = $result->fetch_assoc();
$stmt->close();

if (!$ingrediente) {
    echo "Ingrediente não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Ingrediente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    <div class="container my-4">
        <h2 class="text-center">Editar Ingrediente</h2>
        <form method="POST" action="../../CRUD/processarEditar.php">
            <input type="hidden" name="form_type" value="ingrediente">
            <input type="hidden" name="id_ingrediente" value="<?php echo htmlspecialchars($id_ingrediente); ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do ingrediente:</label>
                <input type="text" class="form-control" id="nome" name="nome" 
                       value="<?php echo htmlspecialchars($ingrediente['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="5" required><?php echo htmlspecialchars($ingrediente['descricao']); ?></textarea>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <!-- Botão de Voltar -->
                <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>

                <!-- Botão de Editar -->
                <button type ="submit" class="btn btn-primary" id="submit">
                    Salvar
                </button>
            </div>
        </form>
    </div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

