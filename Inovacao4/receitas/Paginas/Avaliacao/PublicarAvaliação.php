
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Publicar Avaliação</title>
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
<body class="flex items-center justify-center min-h-screen bg-opacity-50 bg-black">
    <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg w-11/12 max-w-2xl">
        <h1 class="text-4xl font-bold text-center mb-6">Publicar Avaliação</h1>
        
        <!-- Formulário para enviar avaliação -->
        <form action="" method="POST">
            <div class="flex justify-between mb-4">
                <div class="w-1/2 pr-2">
                    <label class="block text-sm font-medium text-gray-700" for="idReceita">ID da Receita</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="idReceita" name="idReceita" type="text" value="0001" readonly/>
                </div>
                <div class="w-1/2 pl-2">
                    <label class="block text-sm font-medium text-gray-700" for="idCozinheiro">ID Cozinheiro</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="idCozinheiro" name="idCozinheiro" type="text" value="0001" readonly/>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">A receita é...</label>
                <div class="relative mt-2">
                    <img alt="Feijão à Mengão" class="w-full h-48 object-cover rounded-lg shadow-md" src="https://storage.googleapis.com/a1aa/image/sWjPSBftXA25YCmRzFkeyX018pop2f63fk7s3XfGP2HNnWNdC.jpg"/>
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-lg">
                        <div class="text-center text-white">
                            <h2 class="text-xl font-bold">FEIJÃO À MENGÃO</h2>
                            <p class="text-sm">Uma versão especial do tradicional feijão brasileiro.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Sua Avaliação</label>
                <div class="flex items-center mt-2">
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <input class="ml-2 text-xl font-bold text-black" name="avaliacao" type="hidden" value="8.1"/>
                    <span class="ml-2 text-xl font-bold text-black">8,1</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="descricaoAvaliacao">Descreva sua Avaliação</label>
                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" id="descricaoAvaliacao" name="descricaoAvaliacao" rows="4"></textarea>
            </div>

            <div class="text-center">
                <button class="bg-black text-white py-2 px-4 rounded-md shadow-md hover:bg-gray-800" type="submit">Enviar</button>
            </div>
        </form>
    </div>
</body>
</html>
