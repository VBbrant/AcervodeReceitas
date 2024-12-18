<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idCategoria = $_GET['id'] ?? null;
if (!$idCategoria) {
    echo "ID da categoria não fornecido.";
    exit;
}

$sql = "SELECT * FROM cargo WHERE idCargo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCategoria);
$stmt->execute();
$result = $stmt->get_result();
$categoria = $result->fetch_assoc();
$stmt->close();

if (!$categoria) {
    echo "Cargo não encontrada.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editer Cargo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/home3.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
<div class="container my-4">
    <h2 class="text-center">Editar Cargo</h2>
    <form method="POST" action="../../CRUD/processarEditar.php">
        <input type="hidden" name="form_type" value="cargo">
        <input type="hidden" name="id_cargo" value="<?php echo htmlspecialchars($idCategoria); ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do cargo:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button id="btnVoltar" onclick="voltarPagina()" style="background-color: gray; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">Voltar</button>
            <button type="submit" class="btn btn-primary w-100">Adicionar Cargo</button>
        </div>
    </form>
</div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
</body>
</html>
