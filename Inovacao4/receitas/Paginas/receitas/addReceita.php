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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita1.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Adicionar Nova Receita</h2>
        <form method="POST" action="../../CRUD/processarAdicionar.php">
            <div class="mb-3">
                <label for="nome_rec" class="form-label">Nome da Receita:</label>
                <input type="text" class="form-control" id="nome_rec" name="nome_rec" required>
            </div>
            <div class="mb-3">
                <label for="data_criacao" class="form-label">Data de Criação:</label>
                <input type="date" class="form-control" id="data_criacao" name="data_criacao">
            </div>
            <div class="mb-3">
                <label for="modo_preparo" class="form-label">Modo de Preparo:</label>
                <textarea class="form-control" id="modo_preparo" name="modo_preparo" rows="5"></textarea>
            </div>
            <div class="mb-3">
                <label for="num_porcao" class="form-label">Número de Porções:</label>
                <input type="number" class="form-control" id="num_porcao" name="num_porcao">
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="inedita" class="form-label">Inédita:</label>
                <select class="form-select" id="inedita" name="inedita">
                    <option value="S">Sim</option>
                    <option value="N">Não</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="link_imagem" class="form-label">Link da Imagem:</label>
                <input type="text" class="form-control" id="link_imagem" name="link_imagem">
            </div>
            
            <!-- Lista pesquisável de ingredientes -->
            <div class="mb-3 ingredient-search">
            <label for="ingredientes" class="form-label">Ingredientes:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="ingredientes" placeholder="Pesquise ingredientes...">
                <button type="button" class="btn btn-primary" onclick="pesquisarIngrediente()">🔍</button>
                <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo BASE_URL; ?>receitas/Paginas/adicionarIngrediente.php'">+</button>
            </div>
            
            <div class="ingredient-list border rounded bg-white mt-1" id="ingredientList">
                <?php
                $sql = "SELECT nome FROM ingrediente";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<button type="button" class="ingredient-item btn btn-outline-secondary btn-sm w-100 text-start my-1" onclick="selectIngredient(\'' . htmlspecialchars($row['nome']) . '\')">' . htmlspecialchars($row['nome']) . '</button>';
                }
                ?>
            </div>
        </div>

        <!-- Div para exibir os ingredientes selecionados como tags -->
        <div class="mb-3">
            <label>Ingredientes Selecionados:</label>
            <div class="selected-ingredients d-flex flex-wrap gap-2 mt-2" id="selectedIngredients"></div>
        </div>


            <button type="submit" class="btn btn-primary w-100">Adicionar Receita</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
