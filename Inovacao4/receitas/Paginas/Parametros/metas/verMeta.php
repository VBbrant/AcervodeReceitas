<?php session_start();
require_once '../../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$usuarioTipo = $_SESSION['Cargo'];

$idMeta = $_GET['id'] ?? null;
if (!$idMeta) {
    echo "ID da meta não fornecido.";
    exit;
}

$sql = "SELECT m.metaReceitas, m.receitasAtingidas, m.dataInicio, m.dataFinal, f.nome AS nome_func, r.nome_rec
        FROM Metas m
        LEFT JOIN funcionario f ON m.idCozinheiro = f.idFun
        LEFT JOIN receita r ON r.idCozinheiro = f.idFun
        WHERE m.idMeta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idMeta);
$stmt->execute();
$result = $stmt->get_result();
$meta = $result->fetch_assoc();
$stmt->close();

if (!$meta) {
    echo "Meta não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Detalhes da Meta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4" id="lista">
    <h2 class="text-center">Detalhes da Meta</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Cozinheiro:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($meta['nome_func']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Meta de Receitas:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($meta['metaReceitas']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Receitas Feitas:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($meta['receitasAtingidas']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Início:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($meta['dataInicio']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Data Final:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($meta['dataFinal']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Receitas Escritas:</label>
            <ul class="list-group">
                <?php
                if ($meta['nome_rec']) {
                    echo "<li class='list-group-item'>" . htmlspecialchars($meta['nome_rec']) . "</li>";
                } else {
                    echo "<li class='list-group-item'>Nenhuma receita registrada.</li>";
                }
                ?>
            </ul>
        </div>

        
            <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>
            <?php if ($usuarioTipo === 'ADM'): ?>
                <!-- Botão de Editar -->
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/medidas/editarMedida.php?id=<?php echo $idMedida; ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

