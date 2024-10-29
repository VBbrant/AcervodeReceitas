<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Avaliação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
        body {
            background-image: url('https://placehold.co/800x600');
            background-size: cover;
            background-position: center;
            filter: sepia(1);
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-opacity-50">
    <div class="bg-black bg-opacity-50 p-8 rounded-lg text-white w-3/4">
        <h1 class="text-4xl font-bold mb-8 text-center">Remover Avaliação</h1>


        <form method="POST" action="">
            <div class="mb-4">
                <h2 class="text-xl font-semibold">Selecionar Avaliação <i class="fas fa-arrow-right"></i></h2>
            </div>

            <div class="space-y-4">
                <!-- Primeira Avaliação -->
                <div class="flex items-center bg-gray-200 bg-opacity-50 p-4 rounded-lg">
                    <div class="w-16 h-16 bg-gray-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shapes text-3xl text-gray-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-black">Título da Avaliação 1</h3>
                        <p class="text-sm text-black">Descrição breve da avaliação 1.</p>
                        <div class="flex items-center text-sm text-black mt-2">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Hoje • 23 min</span>
                        </div>
                    </div>
                    <input type="radio" name="idAvaliacao" value="1" class="ml-auto">
                </div>

                <!-- Segunda Avaliação -->
                <div class="flex items-center bg-gray-200 bg-opacity-50 p-4 rounded-lg">
                    <div class="w-16 h-16 bg-gray-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shapes text-3xl text-gray-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-black">Título da Avaliação 2</h3>
                        <p class="text-sm text-black">Descrição breve da avaliação 2.</p>
                        <div class="flex items-center text-sm text-black mt-2">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Hoje • 30 min</span>
                        </div>
                    </div>
                    <input type="radio" name="idAvaliacao" value="2" class="ml-auto">
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <button type="button" class="text-black">Cancelar</button>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Deletar</button>
            </div>
        </form>
    </div>
</body>
</html>
