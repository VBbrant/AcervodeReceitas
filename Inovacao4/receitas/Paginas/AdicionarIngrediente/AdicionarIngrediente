<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Ingrediente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-image: url('https://placehold.co/800x600');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="bg-white bg-opacity-70 p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h1 class="text-4xl font-bold text-center mb-8">
            Adicionar Ingrediente
        </h1>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nome-ingrediente">
                    Nome Ingrediente
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nome-ingrediente" name="nome-ingrediente" placeholder="Digite aqui" type="text" value="<?php echo $ingredient_name; ?>" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="descricao-ingrediente">
                    Descrição do Ingrediente
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="descricao-ingrediente" name="descricao-ingrediente" placeholder="Digite aqui" rows="4"><?php echo $ingredient_description; ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="foto-ingrediente">
                    Foto do Ingrediente
                </label>
                <div class="relative">
                    <img src="<?php echo $ingredient_photo; ?>" alt="Placeholder image of an ingredient, such as a jar of salt" class="w-full h-48 object-cover rounded-lg">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-white text-4xl"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="reset">
                    Cancelar
                </button>
                <button class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Adicionar
                </button>
            </div>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Ingrediente Adicionado</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id-ingrediente">
                        ID Ingrediente
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id-ingrediente" type="text" value="<?php echo $ingredient_id; ?>" disabled />
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome-ingrediente-list">
                        Nome Ingrediente
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nome-ingrediente-list" type="text" value="<?php echo $ingredient_name; ?>" disabled />
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
