<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Editar Avaliação</title>
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
<body class="bg-opacity-50 bg-black min-h-screen flex items-center justify-center">
    <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg w-3/4">
        <h1 class="text-4xl font-bold text-center mb-8">Editar Avaliação</h1>

        <!-- Formulário de Edição -->
        <form action="" method="POST">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="idReceita">ID da Receita</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="idReceita" name="idReceita" type="text" value="0001"/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="idCozinheiro">ID Cozinheiro</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="idCozinheiro" name="idCozinheiro" type="text" value="0001"/>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="idAvaliacao">ID Avaliação</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="idAvaliacao" name="idAvaliacao" type="text" value="0001"/>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">A receita é...</label>
                <div class="relative mt-2">
                    <img alt="Imagem de um prato de feijão à mengão" class="w-full h-48 object-cover rounded-lg shadow-lg" src="https://storage.googleapis.com/a1aa/image/ZnrRl1HwrRJWJF3Mb09gIEJ38KaYwKDAj3wx5sNHynGVua6E.jpg"/>
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center rounded-lg">
                        <h2 class="text-white text-2xl font-bold">FEIJÃO À MENGÃO</h2>
                        <p class="text-white text-center">Uma versão especial do tradicional feijão brasileiro, com temperos e ingredientes que remetem ao estilo do Flamengo.</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Edite sua Avaliação</label>
                <div class="flex items-center mt-2">
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                        <i class="fas fa-star text-gray-300 text-2xl"></i>
                    </div>
                    <input type="hidden" name="avaliacao" value="8.1"/>
                    <span class="ml-2 text-2xl font-bold">8,1</span>
                </div>
            </div>

            <div class="mb-4">
                <textarea class="w-full h-24 p-4 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" name="descricaoAvaliacao" placeholder="Escreva sua avaliação...">Uma versão especial do tradicional feijão brasileiro, com temperos e ingredientes que remetem ao estilo do Flamengo.</textarea>
            </div>

            <div class="text-center">
                <button class="bg-black text-white py-2 px-4 rounded-lg shadow-lg hover:bg-gray-800" type="submit">Editar</button>
            </div>
        </form>
    </div>
</body>
</html>
