<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idIngrediente = $_GET['id'] ?? null;
if (!$idIngrediente) {
    echo "ID do ingrediente não fornecido.";
    exit;
}

// Consulta para obter os detalhes do ingrediente
$sql = "SELECT * FROM ingrediente WHERE idIngrediente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idIngrediente);
$stmt->execute();
$result = $stmt->get_result();
$ingrediente = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Ingredientes</title>
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
    <h2 class="text-center">Detalhes do Ingrediente</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Nome do Ingrediente:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($ingrediente['nome']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição:</label>
            <textarea class="form-control" rows="5" disabled><?php echo htmlspecialchars($ingrediente['descricao']); ?></textarea>
        </div>
        <div class="text-end">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/editarIngrediente.php?id=<?php echo $idIngrediente; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
