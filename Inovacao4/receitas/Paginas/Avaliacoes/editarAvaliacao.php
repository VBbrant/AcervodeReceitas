<?php
// editarAvaliacao.php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idAvaliacao = $_GET['id'] ?? null;

if (!$idAvaliacao) {
    echo "<script>alert('ID de avaliação não fornecido!'); window.history.back();</script>";
    exit;
}

$sql_avaliacao = "SELECT d.idDegustacao, d.nota_degustacao, d.idReceita, c.comentario_texto
                FROM degustacao d
                LEFT JOIN comentario c ON d.idDegustacao = c.idDegustacao
                WHERE d.idDegustacao = ?
";
$stmt = $conn->prepare($sql_avaliacao);
$stmt->bind_param("i", $idAvaliacao);
$stmt->execute();
$result = $stmt->get_result();
$avaliacao = $result->fetch_assoc();

if (!$avaliacao) {
    echo "<script>alert('Avaliação não encontrada!'); window.history.back();</script>";
    exit;
}

$sql_receitas = "SELECT idReceita, nome_rec FROM receita";
$result_receitas = $conn->query($sql_receitas);
$receitas = [];
while ($row = $result_receitas->fetch_assoc()) {
    $receitas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Avaliação</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body class="ingrediente">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4" id="lista" style="max-width: 700px !important; max-height: auto !important;">
        <h2 class="text-center">Editar Avaliação</h2>
        <form method="POST" action="../../CRUD/processarEditar.php">
            <input type="hidden" name="form_type" value="avaliacao">
            <input type="hidden" name="idAvaliacao" value="<?php echo htmlspecialchars($avaliacao['idDegustacao']); ?>">
            <input type="hidden" name="idDegustador" value="<?= $_SESSION['idFun']?>">

            <div class="mb-3">
                <label for="idReceita" class="form-label">Selecionar Receita:</label>
                <select class="form-select" id="idReceita" name="idReceita" required>
                    <option value="">Selecione uma receita</option>
                    <?php foreach ($receitas as $receita): ?>
                        <option value="<?php echo $receita['idReceita']; ?>" 
                            <?php echo $receita['idReceita'] == $avaliacao['idReceita'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($receita['nome_rec']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="nota_degustacao" class="form-label">Nota de Degustação (0 a 10):</label>
                <select class="form-select" id="nota_degustacao" name="avaliacao" required>
                    <?php for ($i = 0; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>" 
                            <?php echo ($i == $avaliacao['nota_degustacao']) ? 'selected' : ''; ?>>
                            <?php echo $i; ?> &#9733;
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="comentario_texto" class="form-label">Comentário:</label>
                <textarea class="form-control" id="comentario_texto" name="comentario_texto" rows="3"><?php echo htmlspecialchars($avaliacao['comentario_texto']); ?></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <!-- Botão de Voltar -->
                <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>

                <!-- Botão de Editar -->
                <button type="submit" class="btn btn-primary" style="width: 590px;">Salvar Alterações</button>
            </div>
        </form>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

