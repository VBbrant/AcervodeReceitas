<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idAvaliacao = $_GET['id'] ?? null;
if (!$idAvaliacao) {
    echo "ID da avaliação não fornecido.";
    exit;
}

$sql = "SELECT d.nota_degustacao, d.data_degustacao, r.nome_rec, c.comentario_texto, d.idDegustador
FROM degustacao d
LEFT JOIN receita r ON d.idReceita = r.idReceita
LEFT JOIN comentario c ON d.idDegustacao = c.idDegustacao
WHERE d.idDegustacao = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAvaliacao);
$stmt->execute();
$result = $stmt->get_result();
$avaliacao = $result->fetch_assoc();
$stmt->close();

if (!$avaliacao) {
    echo "Avaliação não encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Detalhes da Avaliação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4" id="lista">
    <h2 class="text-center">Detalhes da Avaliação</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Receita:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['nome_rec']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Data da Degustação:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['data_degustacao']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nota de Degustação:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['nota_degustacao']); ?> &#9733;" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Comentário:</label>
            <textarea class="form-control" rows="3" disabled><?php echo htmlspecialchars($avaliacao['comentario_texto']); ?></textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>
            <?php if ($userRole == 'ADM' || $_SESSION['idFun'] == $avaliacao['idDegustador']):?>
                <!-- Botão de Editar -->
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/editarAvaliacao.php?id=<?php echo $idAvaliacao; ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            <?php endif;?>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

