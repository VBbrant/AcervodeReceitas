<?php
    require_once "../../../config.php"; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>receitas/Style/receitas.css">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>receitas/Style/estiloCabecalho.css">
    <title>Adicionar Receita</title>
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container">
        <h2>Adicionar Nova Receita</h2>
        <form method="POST" action="../CRUD/processarAll.php">
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
            <button type="submit" class="btn btn-primary">Adicionar Receita</button>
        </form>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>