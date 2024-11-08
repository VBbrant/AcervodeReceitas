<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idAvaliacao = $_GET['id'] ?? null;
if (!$idAvaliacao) {
    echo "ID da avaliação não fornecido.";
    exit;
}

$sql = "SELECT d.nota_degustacao, d.data_degustacao, r.nome_rec, c.comentario_texto
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Detalhes da Avaliação</h2>
    <form>
        <div class="mb-3">
            <label class="form-label">Receita:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['nome_rec']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Data da Degustação:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['data_degustacao']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Nota de Degustação:</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($avaliacao['nota_degustacao']); ?> &#9733;" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Comentário:</label>
            <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($avaliacao['comentario_texto']); ?></textarea>
        </div>
        <div class="text-end">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/editarAvaliacao.php?id=<?php echo $idAvaliacao; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
