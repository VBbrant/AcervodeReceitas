<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Adicionar receita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container">
        <h2>Adicionar Nova Receita</h2>
        <form method="POST" action="../CRUD/processarAdicionar.php">
            <div class="form-group">
                <label for="nome_rec">Nome da Receita:</label>
                <input type="text" class="form-control" id="nome_rec" name="nome_rec" required>
            </div>
            <div class="form-group">
                <label for="data_criacao">Data de Criação:</label>
                <input type="date" class="form-control" id="data_criacao" name="data_criacao">
            </div>
            <div class="form-group">
                <label for="modo_preparo">Modo de Preparo:</label>
                <textarea class="form-control" id="modo_preparo" name="modo_preparo" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="num_porcao">Número de Porções:</label>
                <input type="number" class="form-control" id="num_porcao" name="num_porcao">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="inedita">Inédita:</label>
                <select class="form-control" id="inedita" name="inedita">
                    <option value="S">Sim</option>
                    <option value="N">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="link_imagem">Link da Imagem:</label>
                <input type="text" class="form-control" id="link_imagem" name="link_imagem">
            </div>

            <!-- Lista pesquisável de ingredientes -->
            <div class="form-group ingredient-search">
                <label for="ingredientes">Ingredientes:</label>
                <input type="text" class="form-control" id="ingredientes" name="ingredientes" placeholder="Pesquise ingredientes...">
                <button type="button" class="add-button" onclick="addIngredient()">+</button>
                <div class="ingredient-list" id="ingredientList">
                    <?php
                    $sql = "SELECT nome FROM ingrediente";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="ingredient-item">' . htmlspecialchars($row['nome']) . '</div>';
                    }
                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Adicionar Receita</button>
        </form>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
