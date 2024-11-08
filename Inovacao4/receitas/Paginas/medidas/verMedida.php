<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idMedida = $_GET['id'] ?? null;
if (!$idMedida) {
    echo "ID da medida não fornecido.";
    exit;
}

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
    <title>SaborArte - Ver medidas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Detalhes da Medida</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Nome da Medida:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($medida['sistema']); ?>" readonly>
        </div>
        <div class="text-end">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/editarMedida.php?id=<?php echo $idMedida; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
