<?php
//addAvaliação.php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$sql_livros = "SELECT idLivro, titulo AS nome FROM livro";
$result_livros = $conn->query($sql_livros);
$livros = [];
while ($row = $result_livros->fetch_assoc()) {
    $livros[] = $row;
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
    <title>SaborArte - Adicionar Avaliação</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita3.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Adicionar Avaliação</h2>
        <form method="POST" action="../../CRUD/processarAdicionar.php">
            <input type="hidden" name="form_type" value="avaliacao">
            
            <!-- Selecionar Receita -->
            <div class="mb-3">
                <label for="idReceita" class="form-label">Selecionar Receita:</label>
                <select class="form-select" id="idReceita" name="idReceita" required>
                    <option value="">Selecione uma receita</option>
                    <?php foreach ($receitas as $receita): ?>
                        <option value="<?php echo $receita['idReceita']; ?>">
                            <?php echo htmlspecialchars($receita['nome_rec']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nota de Degustação (Select com Estrelas) -->
            <div class="mb-3">
                <label for="nota_degustacao" class="form-label">Nota de Degustação (0 a 10):</label>
                <select class="form-select" id="nota_degustacao" name="nota_degustacao" required>
                    <option value="">Selecione uma nota</option>
                    <?php for ($i = 0; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>">
                            <?php echo $i; ?> &#9733;
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Campo para o Comentário -->
            <div class="mb-3">
                <label for="comentario_texto" class="form-label">Comentário:</label>
                <textarea class="form-control" id="comentario_texto" name="comentario_texto" rows="3" placeholder="Escreva seu comentário"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Enviar Avaliação</button>
        </form>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

