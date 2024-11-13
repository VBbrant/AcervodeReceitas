<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";


$sql_livros = "SELECT idLivro, titulo AS nome, isbn AS codigo, idEditor FROM livro";
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

$sql_editores = "SELECT f.idFun, f.nome 
                    FROM funcionario f 
                    JOIN cargo c ON f.idCargo = c.idCargo 
                    WHERE c.nome = 'Editor'";
$result_editores = $conn->query($sql_editores);

$editores = [];
while ($row = $result_editores->fetch_assoc()) {
    $editores[] = $row;
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Adicionar Livro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/addLivro.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Adicionar Novo Livro</h2>
        <form method="POST" action="../../CRUD/processarAdicionar.php" enctype="multipart/form-data">
         <input type="hidden" name="form_type" value="livro">
            <div class="mb-3">
                <label for="" class="form-label">Nome do Livro:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">isbn</label>
                <input type="number" class="form-control" id="codigo" name="codigo">
            </div>

            <div class="mb-3">
                <label for="cozinheiro" class="form-label">Editor:</label>
                <select class="form-select" id="editor" name="id_editor" required>
                    <option value="">Selecione o editor</option>
                    <?php foreach ($editores as $editor): ?>
                        <option value="<?= $editor['idFun'] ?>"><?= $editor['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campo de pesquisa e seleção de receitas -->
            <div class="mb-3">
                <label for="recipeSearch" class="form-label">Receitas</label>
                <div class="input-group" style="position: relative;">
                    <input type="text" id="recipeSearch" class="form-control" placeholder="Pesquisar receita...">
                    <button type="button" id="searchButton" class="btn"><i class="fas fa-search"></i></button>
                    <a class="btn" id="addButton" href="<?= BASE_URL;?>receitas/Paginas/receitas/addReceita.php">
                        <i class="fas fa-plus"></i>
                    </a>
                    <div id="recipeList" class="dropdown-list"></div>
                </div>
                <div id="selectedRecipes" class="recipe-container mt-3"></div>
            </div>

            <!-- Campo oculto para armazenar os IDs das receitas selecionadas -->
            <input type="hidden" name="idReceita" id="selectedRecipeIds">

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
            <div class="d-flex justify-content-end mt-3">
                <button id="btnVoltar" onclick="voltarPagina()" style="background-color: gray; color: white; padding: 10px 20px; border: none; cursor: pointer; font-size: 16px;">Voltar</button>
                <button type="submit" class="btn btn-primary w-100">Adicionar Categoria</button>
            </div>
        </form>
    </div>

    <script>
    // Mock de dados para receitas (apenas para teste)
    const receitas = <?php echo json_encode($receitas); ?>;
    </script>
    <script src="<?php echo BASE_URL . 'receitas/Scripts/addLivro.js';?>"></script>
  
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
