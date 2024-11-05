<?php 
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idMedida = $_GET['id'] ?? null;
if (!$idMedida) {
    echo "ID da medida não fornecido.";
    exit;
}

// Obtém as informações da medida existente
$sql = "SELECT * FROM medida WHERE idMedida = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMedida);
$stmt->execute();
$result = $stmt->get_result();
$medida = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Medida</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    <div class="container my-4">
        <h2 class="text-center">Editar Medida</h2>
        <form method="POST" action="../../CRUD/processarEditar.php">
            <input type="hidden" name="form_type" value="medida">
            <input type="hidden" name="id" value="<?php echo $idMedida; ?>">
            <div class="mb-3">
                <label for="nome_medida" class="form-label">Nome da Medida:</label>
                <input type="text" class="form-control" id="nome_medida" name="nome" 
                       value="<?php echo htmlspecialchars($medida['nome']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </form>
    </div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

