<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idCategoria = $_GET['id'] ?? null;
if (!$idCategoria) {
    echo "ID da categoria não fornecido.";
    exit;
}

$sql = "SELECT * FROM categoria WHERE idCategoria = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCategoria);
$stmt->execute();
$result = $stmt->get_result();
$categoria = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte</title>
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
<div class="container my-4" id="lista">
    <h2 class="text-center">Detalhes da Categoria</h2>
    <div class="mb-3">
        <label class="form-label">Nome da Categoria:</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($categoria['nome']); ?>" disabled>
    </div>
    <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Editar -->
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/categorias/editarCategoria.php?id=<?php echo $idCategoria; ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

