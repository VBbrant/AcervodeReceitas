<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idFuncionario = $_GET['id'] ?? null;
if (!$idFuncionario) {
    echo "ID do funcionario não fornecido.";
    exit;
}

// Consulta para obter os detalhes do ingrediente
$sql = "SELECT * FROM funcionario WHERE idFun = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idFuncionario);
$stmt->execute();
$result = $stmt->get_result();
$funcionario = $result->fetch_assoc();
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Detalhes do funcionario</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Nome:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($funcionario['nome']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">RG:</label>
            <input type="number" class="form-control" value="<?php echo htmlspecialchars($funcionario['rg']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de nascimento:</label>
            <input type="date" class="form-control" value="<?php echo htmlspecialchars($funcionario['data_nascimento']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Date de admissão:</label>
            <input type="date" class="form-control" value="<?php echo htmlspecialchars($funcionario['data_admissao']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Salário:</label>
            <input type="number" class="form-control" value="<?php echo htmlspecialchars($funcionario['salario']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Apelido:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($funcionario['nome_fantasia']); ?>" readonly>
        </div>

        <div class="text-end">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/editarFuncionario.php?id=<?php echo $idFuncionario; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>