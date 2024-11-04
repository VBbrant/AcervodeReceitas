<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idReceita = $_GET['id'] ?? null; 

$sql_receita = "SELECT * FROM receita WHERE idReceita = ?";
$stmt_receita = $conn->prepare($sql_receita);
$stmt_receita->bind_param("i", $idReceita);
$stmt_receita->execute();
$receita = $stmt_receita->get_result()->fetch_assoc();

$sql_medidas = "SELECT idMedida, sistema AS nome FROM medida";
$result_medidas = $conn->query($sql_medidas);
$medidas = $result_medidas->fetch_all(MYSQLI_ASSOC);

$sql_ingredientes = "SELECT idIngrediente, nome FROM ingrediente";
$result_ingredientes = $conn->query($sql_ingredientes);
$ingredientes = $result_ingredientes->fetch_all(MYSQLI_ASSOC);

$sql_cozinheiros = "SELECT f.idFun, f.nome 
                    FROM funcionario f 
                    JOIN cargo c ON f.idCargo = c.idCargo 
                    WHERE c.nome = 'Cozinheiro'";
$result_cozinheiros = $conn->query($sql_cozinheiros);
$cozinheiros = $result_cozinheiros->fetch_all(MYSQLI_ASSOC);

$sql_categorias = "SELECT idCategoria, nome FROM categoria";
$result_categorias = $conn->query($sql_categorias);
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);

// Carrega os ingredientes associados à receita
$sql_receita_ingredientes = "
    SELECT ri.idIngrediente, i.nome, ri.quantidade, ri.idMedida
    FROM receita_ingrediente ri
    JOIN ingrediente i ON ri.idIngrediente = i.idIngrediente
    WHERE ri.idReceita = ?
";
$stmt_receita_ingredientes = $conn->prepare($sql_receita_ingredientes);
$stmt_receita_ingredientes->bind_param("i", $idReceita);
$stmt_receita_ingredientes->execute();
$receita_ingredientes = $stmt_receita_ingredientes->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Receita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita2.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Editar Receita</h2>
        <form id="recipeForm" onsubmit="updateIngredientsJson()" method="POST" action="../../CRUD/processarEditar.php?id=<?php echo $idReceita; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nome_rec" class="form-label">Nome da Receita:</label>
                <input type="text" class="form-control" id="nome_rec" name="nome_rec" value="<?php echo htmlspecialchars($receita['nome_rec']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_criacao" class="form-label">Data de Criação:</label>
                <input type="date" class="form-control" id="data_criacao" name="data_criacao" value="<?php echo htmlspecialchars($receita['data_criacao']); ?>">
            </div>
            <div class="mb-3">
                <label for="modo_preparo" class="form-label">Modo de Preparo:</label>
                <textarea class="form-control" id="modo_preparo" name="modo_preparo" rows="5"><?php echo htmlspecialchars($receita['modo_preparo']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="num_porcao" class="form-label">Número de Porções:</label>
                <input type="number" class="form-control" id="num_porcao" name="num_porcao" value="<?php echo htmlspecialchars($receita['num_porcao']); ?>">
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($receita['descricao']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="inedita" class="form-label">Inédita:</label>
                <select class="form-select" id="inedita" name="inedita">
                    <option value="S" <?php if ($receita['inedita'] == 'S') echo 'selected'; ?>>Sim</option>
                    <option value="N" <?php if ($receita['inedita'] == 'N') echo 'selected'; ?>>Não</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cozinheiro" class="form-label">Cozinheiro:</label>
                <select class="form-select" id="cozinheiro" name="id_cozinheiro" required>
                    <option value="">Selecione o Cozinheiro</option>
                    <?php foreach ($cozinheiros as $cozinheiro): ?>
                        <option value="<?= $cozinheiro['idFun'] ?>" <?php if ($receita['idCozinheiro'] == $cozinheiro['idFun']) echo 'selected'; ?>><?= $cozinheiro['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select class="form-select" id="categoria" name="id_categoria" required>
                    <option value="">Selecione a Categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['idCategoria'] ?>" <?php if ($receita['idCategoria'] == $categoria['idCategoria']) echo 'selected'; ?>><?= $categoria['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="link_imagem" class="form-label">Link da Imagem:</label>
                <input type="text" class="form-control" id="link_imagem" name="link_imagem" 
                      value="<?php echo htmlspecialchars($receita['link_imagem']); ?>"
                      placeholder="Insira o link da imagem ou faça o upload abaixo" oninput="toggleImageInput()">
            </div>

            <div class="mb-3">
                <label for="arquivo_imagem" class="form-label">Upload da Imagem:</label>
                <input type="file" class="form-control" id="arquivo_imagem" name="arquivo_imagem" accept="image/*" onchange="toggleLinkInput()">
                <?php if ($receita['link_imagem']): ?>
                    <p>Imagem atual: <a href="<?php echo htmlspecialchars($receita['link_imagem']); ?>" target="_blank">Ver Imagem</a></p>
                <?php elseif ($receita['arquivo_imagem']): ?>
                    <p>Imagem atual: <a href="<?php echo BASE_URL . htmlspecialchars($receita['arquivo_imagem']); ?>" target="_blank">Ver Imagem</a></p>
                <?php endif; ?>
            </div>


            <!-- Div de Ingredientes -->
            <div class="mb-3">
                <label for="ingredientSearch" class="form-label">Ingredientes</label>
                <div class="input-group mb-2" style="position: relative;">
                    <input type="text" id="ingredientSearch" class="form-control" placeholder="Pesquisar ingrediente...">
                    <span class="input-group-text" onclick="filterIngredients()"><i class="fas fa-search"></i></span>
                    <button type="button" class="btn btn-primary" id="addIngredientOptions">+</button>
                    <div id="ingredientList" class="list-group"></div>
                </div>
                <div id="selectedIngredients" class="mb-3"></div>
            </div>

            <input type="hidden" name="ingredientes" id="ingredientesJson">
            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </form>
        <div id="additionalOptions" class="dropdown-menu">
            <a href="<?= BASE_URL;?>receitas/Paginas/Ingredientes/addIngrediente.php" class="dropdown-item">Adicionar Ingrediente</a>
            <a href="<?= BASE_URL;?>receitas/Paginas/medidas/addMedida.php" class="dropdown-item">Adicionar Medida</a>
        </div>

        <script>
            const ingredientsData = <?php echo json_encode($ingredientes); ?>;
            const measurementsData = <?php echo json_encode($medidas); ?>;
            const selectedIngredients = <?php echo json_encode($receita_ingredientes); ?>; // Ingredientes já associados
        </script>
    </div>

    <script>
    
          document.addEventListener('DOMContentLoaded', function() {
          // Renderiza os ingredientes previamente selecionados ao carregar a página
          selectedIngredients.forEach(ingredient => {
              renderSelectedIngredient(ingredient);
          });
          updateIngredientsJson();
      });

      function renderSelectedIngredient(ingredient) {
          const tag = document.createElement('div');
          tag.className = 'ingredient-tag';
          tag.style.display = 'flex';
          tag.style.alignItems = 'center';
          tag.style.marginBottom = '5px';
          tag.dataset.id = ingredient.idIngrediente;

          // Gera o HTML da tag com os dados da receita
          tag.innerHTML = `
              <span style="margin-right: 8px;">${ingredient.nome}</span>
              <input type="number" class="form-control form-control-sm ingredient-quantity" placeholder="Quantidade" min="1" required 
                    value="${ingredient.quantidade}" style="width: 80px; margin-right: 8px;">
              <select class="form-select form-select-sm ingredient-measure" required style="width: 100px; margin-right: 8px;">
                  ${measurementsData.map(m => `<option value="${m.idMedida}" ${m.idMedida == ingredient.idMedida ? 'selected' : ''}>${m.nome}</option>`).join('')}
              </select>
              <button type="button" class="btn btn-sm btn-danger" onclick="removeTag(this)">x</button>
          `;

          // Eventos para atualizar o JSON ao modificar quantidade ou medida
          tag.querySelector('.ingredient-quantity').addEventListener('input', updateIngredientsJson);
          tag.querySelector('.ingredient-measure').addEventListener('change', updateIngredientsJson);

          document.getElementById('selectedIngredients').appendChild(tag);
      }

      // Função para atualizar o campo hidden com os dados dos ingredientes
      function updateIngredientsJson() {
          const ingredientTags = document.querySelectorAll('.ingredient-tag');
          const selectedIngredients = Array.from(ingredientTags).map(tag => ({
              idIngrediente: tag.dataset.id,
              quantidade: tag.querySelector('.ingredient-quantity').value,
              idMedida: tag.querySelector('.ingredient-measure').value
          }));
          
          document.getElementById('ingredientesJson').value = JSON.stringify(selectedIngredients);
      }
        

    </script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
