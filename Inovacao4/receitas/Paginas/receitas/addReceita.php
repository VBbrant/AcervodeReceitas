<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
?>
<?php

$sql_medidas = "SELECT idMedida, sistema AS nome FROM medida";
$result_medidas = $conn->query($sql_medidas);
$medidas = [];

while ($row = $result_medidas->fetch_assoc()) {
    $medidas[] = $row;
}



$sql_ingredientes = "SELECT idIngrediente, nome FROM ingrediente";
$result_ingredientes = $conn->query($sql_ingredientes);
$ingredientes = [];
while ($row = $result_ingredientes->fetch_assoc()) {
    $ingredientes[] = $row;
}

$sql_cozinheiros = "SELECT f.idFun, f.nome 
                    FROM funcionario f 
                    JOIN cargo c ON f.idCargo = c.idCargo 
                    WHERE c.nome = 'Cozinheiro'";
$result_cozinheiros = $conn->query($sql_cozinheiros);

$cozinheiros = [];
while ($row = $result_cozinheiros->fetch_assoc()) {
    $cozinheiros[] = $row;
}

$sql_categorias = "SELECT idCategoria, nome FROM categoria";
$result_categorias = $conn->query($sql_categorias);
$categorias = [];
while ($row = $result_categorias->fetch_assoc()) {
    $categorias[] = $row;
}


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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita2.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Adicionar Nova Receita</h2>
        <form id="recipeForm" onsubmit="updateIngredientsJson()" method="POST" action="../../CRUD/processarAdicionar.php" enctype="multipart/form-data">
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
                <label for="cozinheiro" class="form-label">Cozinheiro:</label>
                <select class="form-select" id="cozinheiro" name="id_cozinheiro" required>
                    <option value="">Selecione o Cozinheiro</option>
                    <?php foreach ($cozinheiros as $cozinheiro): ?>
                        <option value="<?= $cozinheiro['idFun'] ?>"><?= $cozinheiro['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select class="form-select" id="categoria" name="id_categoria" required>
                    <option value="">Selecione a Categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['idCategoria'] ?>"><?= $categoria['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="link_imagem" class="form-label">Link da Imagem:</label>
                <input type="text" class="form-control" id="link_imagem" name="link_imagem" 
                    placeholder="Insira o link da imagem ou faça o upload abaixo" oninput="toggleImageInput()">
            </div>

            <div class="mb-3">
                <label for="arquivo_imagem" class="form-label">Upload da Imagem:</label>
                <input type="file" class="form-control" id="arquivo_imagem" name="arquivo_imagem" 
                    accept="image/*" onchange="toggleLinkInput()">
            </div>

            <!-- Div -->
            <div class="mb-3">
                <label for="ingredientSearch" class="form-label">Ingredientes</label>
                <div class="input-group mb-2" style="position: relative;">
                    <input type="text" id="ingredientSearch" class="form-control" placeholder="Pesquisar ingrediente...">
                    <span class="input-group-text" onclick="filterIngredients()"><i class="fas fa-search"></i></span>
                    <button type="button" class="btn btn-primary" id="addIngredientOptions">+</button>
                    <div id="ingredientList" class="list-group"></div>
                </div>
                <div id="selectedIngredients" class="mb-3"></div>
            </div>

            <input type="hidden" name="ingredientes" id="ingredientesJson">
            <button type="submit" class="btn btn-primary w-100">Adicionar Receita</button>
        </form>
        <div id="additionalOptions" class="dropdown-menu">
            <a href="<?= BASE_URL;?>receitas/Paginas/Ingredientes/addIngrediente.php" class="dropdown-item">Adicionar Ingrediente</a>
            <a href="<?= BASE_URL;?>receitas/Paginas/medidas/addMedida.php" class="dropdown-item">Adicionar Medida</a>
        </div>

        <script>
            const ingredientsData = <?php echo json_encode($ingredientes); ?>;
            const measurementsData = <?php echo json_encode($medidas); ?>; // Lista de medidas (idMedida e nome)
        </script>
        <script src="<?= BASE_URL;?>receitas/Scripts/addReceita.php"></script>

    
        <?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
