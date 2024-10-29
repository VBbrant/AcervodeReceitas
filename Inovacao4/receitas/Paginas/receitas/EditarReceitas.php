<html>
  <head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <style>
      body {
        background-image: url('https://placehold.co/800x600');
        background-size: cover;
        background-repeat: no-repeat;
        background-blend-mode: multiply;
        background-color: rgba(255, 165, 0, 0.5);
      }
    </style>
  </head>
  <body class="text-white font-sans">
    <div class="container mx-auto p-4">
      <h1 class="text-4xl font-bold text-center mb-4">Editar Receita</h1>
      
      <!-- Formulário para editar a receita -->
      <form action="" method="POST">
        <div class="flex justify-center mb-6">
          <div class="relative w-1/2">
            <input type="text" name="search" placeholder="Pesquise sua receita" class="w-full p-2 rounded-full pl-10 text-gray-700">
            <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
          </div>
        </div>
        
        <div class="bg-white bg-opacity-20 p-4 rounded-lg mb-6">
          <div class="flex items-center mb-4">
            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
              <i class="fas fa-image text-2xl text-gray-500"></i>
            </div>
            <div class="ml-4">
              <h2 class="text-xl font-bold">Título</h2>
              <p class="text-sm">Descrição breve da receita.</p>
              <p class="text-sm"><i class="fas fa-clock"></i> Hoje • 23 min</p>
            </div>
          </div>

          <!-- Seção para editar as informações principais da receita -->
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
              <label class="block text-sm">ID Receita</label>
              <input type="text" name="recipe_id" value="0001" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
            <div>
              <label class="block text-sm">Nome da receita</label>
              <input type="text" name="recipe_name" value="Feijoada" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
            <div>
              <label class="block text-sm">ID cozinheiro</label>
              <input type="text" name="cook_id" value="0001" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
          </div>
          
          <!-- Seção para editar os ingredientes -->
          <h3 class="text-lg font-bold mb-2">Editar Ingredientes</h3>
          <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label class="block text-sm">Quantidade</label>
              <input type="text" name="ingredients[0][quantity]" placeholder="Ex: 500g" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
            <div>
              <label class="block text-sm">Medida</label>
              <input type="text" name="ingredients[0][measure]" placeholder="Ex: Gramas" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
            <div>
              <label class="block text-sm">Ingrediente</label>
              <input type="text" name="ingredients[0][name]" placeholder="Ex: Feijão" class="w-full p-2 rounded bg-gray-200 text-gray-700">
            </div>
          </div>
          <button class="bg-black text-white px-4 py-2 rounded">Confirmar</button>
        </div>
        
        <!-- Seção para editar o modo de preparo -->
        <div class="bg-white bg-opacity-20 p-4 rounded-lg mb-6">
          <h3 class="text-lg font-bold mb-2">Editar Modo de Preparo</h3>
          <input type="text" name="preparation" placeholder="Editar modo de preparo" class="w-full p-2 rounded bg-gray-200 text-gray-700 mb-4">
          <button class="bg-black text-white px-4 py-2 rounded">Confirmar</button>
        </div>
      </form>
    </div>
  </body>
</html>
