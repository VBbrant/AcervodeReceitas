<?php 
// index.php
require_once '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - ver Receita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/receita.css">    
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php';?>
    <main>
        <h1 class="receitas-title" style="margin-top= 200px;">RECEITAS</h1>
        
        <div class="filters-container">
            <select id="categoryFilter" class="filter-select">
                <option value="">Definir Categoria</option>
                <option value="carnes">Carnes</option>
                <option value="massas">Massas</option>
                <option value="sobremesas">Sobremesas</option>
            </select>
            
            <select id="ratingFilter" class="filter-select">
                <option value="">Definir Avaliação</option>
                <option value="5">5+ estrelas</option>
                <option value="7">7+ estrelas</option>
                <option value="9">9+ estrelas</option>
            </select>
        </div>

        <div class="recipes-grid" id="recipesGrid"></div> <!-- Div para inserir receitas dinamicamente -->
    </main>
    <script src="<?php echo BASE_URL; ?>receitas/Scripts/carregarReceitas.js"></script>
<?php include ROOT_PATH . "receitas/elementoPagina/rodape.php"; ?>