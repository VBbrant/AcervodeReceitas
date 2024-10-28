<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ingrediente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://placehold.co/800x600?text=Background+image+of+spices+in+bags');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-opacity-50 bg-black">
    <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg w-3/4 max-w-2xl">
        <h1 class="text-3xl font-bold text-center mb-6">Editar Ingrediente</h1>
        
        <!-- Formulário para editar o ingrediente -->
        <form action="" method="POST">
            <div class="relative mb-6">
                <input type="text" placeholder="Pesquise sua Ingrediente" class="w-full p-3 pl-10 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                <i class="fas fa-bars absolute left-3 top-3 text-gray-500"></i>
                <i class="fas fa-search absolute right-3 top-3 text-gray-500"></i>
            </div>

            <div class="flex items-center mb-6">
                <img src="https://placehold.co/80x80" alt="Placeholder image of an ingredient" class="w-20 h-20 rounded-full mr-4">
                <div>
                    <h2 class="text-xl font-bold">Title</h2>
                    <p class="text-gray-600">Descrição do ingrediente exemplo.</p>
                    <div class="flex items-center text-gray-500 text-sm mt-2">
                        <i class="fas fa-clock mr-1"></i>
                        <span>Today • 23 min.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="nome-ingrediente" class="block text-gray-700">Nome Ingrediente</label>
                    <input type="text" id="nome-ingrediente" name="nome-ingrediente" value="<?php echo $ingredient_name; ?>" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                </div>
                <div>
                    <label for="id-ingrediente" class="block text-gray-700">ID Ingrediente</label>
                    <input type="text" id="id-ingrediente" value="<?php echo $ingredient_id; ?>" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400" disabled>
                </div>
            </div>

            <div class="mb-6">
                <label for="descricao-ingrediente" class="block text-gray-700">Descrição do Ingrediente</label>
                <textarea id="descricao-ingrediente" name="descricao-ingrediente" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 h-32"><?php echo $ingredient_description; ?></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="reset" class="px-6 py-2 rounded bg-gray-400 text-white">Cancelar</button>
                <button type="submit" class="px-6 py-2 rounded bg-black text-white">Editar</button>
            </div>
        </form>
    </div>
</body>
</html>
