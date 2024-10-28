<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Categoria</title>
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
        input[type="text"] {
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
        .add-btn {
            background: blue;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Adicionar Categoria</h1>
    <form action="processar_categoria.php" method="post">
        <label for="category-name">Nome Categoria:</label>
        <input type="text" id="category-name" name="category-name" required>

        <label for="category-description">Descrição da Categoria:</label>
        <input type="text" id="category-description" name="category-description" required>

        <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Cancelar</button>
        <button type="submit" class="add-btn">Adicionar</button>
    </form>

    <h2>Categorias Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID Categoria</th>
                <th>Nome Categoria</th>
                <th>List Item</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Exemplo de dados de categorias (substitua por dados do seu banco de dados)
            $categorias = [
                ['id' => 1, 'nome' => 'Carnes'],
                ['id' => 2, 'nome' => 'Verduras'],
                ['id' => 3, 'nome' => 'Frutas']
            ];

            foreach ($categorias as $categoria) {
                echo "<tr>
                        <td>{$categoria['id']}</td>
                        <td>{$categoria['nome']}</td>
                        <td><a href='editar_categoria.php?id={$categoria['id']}'>Editar</a> | <a href='remover_categoria.php?id={$categoria['id']}'>Remover</a></td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
