<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .cancel-btn {
            background: gray;
            color: white;
        }
        .edit-btn {
            background: blue;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Editar Categoria: Carnes</h1>
    <form action="processar_edicao.php" method="post">
        <label for="search-category">Pesquise sua Categoria:</label>
        <input type="text" id="search-category" name="search-category" placeholder="Pesquise...">

        <label for="category-name">Nome Categoria:</label>
        <input type="text" id="category-name" name="category-name" value="Carnes">

        <label for="ingredient-id">ID Ingrediente:</label>
        <input type="text" id="ingredient-id" name="ingredient-id" value="001" readonly>

        <label for="category-description">Descrição da Categoria:</label>
        <textarea id="category-description" name="category-description" rows="4" placeholder="Digite aqui..."></textarea>

        <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Cancelar</button>
        <button type="submit" class="edit-btn">Editar</button>
    </form>
</div>

</body>
</html>
