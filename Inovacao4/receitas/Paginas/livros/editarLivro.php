<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idLivro = $_GET['id'] ?? null;

if ($idLivro) {
    // Consultar dados do livro
    $sql_livro = "SELECT * FROM livro WHERE idLivro = ?";
    $stmt_livro = $conn->prepare($sql_livro);
    $stmt_livro->bind_param("i", $idLivro);
    $stmt_livro->execute();
    $livro = $stmt_livro->get_result()->fetch_assoc();

    // Consultar receitas associadas ao livro
    $sql_receitas_livro = "SELECT idReceita FROM livro_receita WHERE idLivro = ?";
    $stmt_receitas = $conn->prepare($sql_receitas_livro);
    $stmt_receitas->bind_param("i", $idLivro);
    $stmt_receitas->execute();
    $receitas_livro = $stmt_receitas->get_result()->fetch_all(MYSQLI_ASSOC);
    $receitas_livro_ids = array_column($receitas_livro, 'idReceita');
}

// Carregar todas as receitas e editores disponíveis
$sql_receitas = "SELECT idReceita, nome_rec FROM receita";
$result_receitas = $conn->query($sql_receitas);
$receitas = $result_receitas->fetch_all(MYSQLI_ASSOC);

$sql_editores = "SELECT f.idFun, f.nome FROM funcionario f JOIN cargo c ON f.idCargo = c.idCargo WHERE c.nome = 'Editor'";
$result_editores = $conn->query($sql_editores);
$editores = $result_editores->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Livro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/addLivro.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="ingrediente">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

    <div class="container my-4" id="lista">
        <h2 class="text-center">Editar Livro</h2>
        <form method="POST" action="../../CRUD/processarEditar.php" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="livro">
            <input type="hidden" name="idLivro" value="<?= $livro['idLivro'] ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Livro:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= $livro['titulo'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="codigo" class="form-label">ISBN:</label>
                <input type="number" class="form-control" id="codigo" name="codigo" value="<?= $livro['isbn'] ?>">
            </div>

            <div class="mb-3">
                <label for="editor" class="form-label">Editor:</label>
                <select class="form-select" id="editor" name="id_editor" required>
                    <option value="">Selecione o editor</option>
                    <?php foreach ($editores as $editor): ?>
                        <option value="<?= $editor['idFun'] ?>" <?= $livro['idEditor'] == $editor['idFun'] ? 'selected' : '' ?>>
                            <?= $editor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Seleção de receitas -->
            <div class="mb-3">
                <label for="recipeSearch" class="form-label">Receitas:</label>
                <div class="input-group" style="position: relative;">
                    <input type="text" id="recipeSearch" class="form-control" placeholder="Pesquisar receita...">
                    <button type="button" id="searchButton" class="btn"><i class="fas fa-search"></i></button>
                    <a class="btn" id="addButton" href="<?= BASE_URL;?>receitas/Paginas/receitas/addReceita.php">
                        <i class="fas fa-plus"></i>
                    </a>
                    <div id="recipeList" class="dropdown-list"></div>
                </div>
                
                <div id="selectedRecipes" class="recipe-container mt-3">
                    <?php foreach ($receitas as $receita): ?>
                        <?php if (in_array($receita['idReceita'], $receitas_livro_ids)): ?>
                            <span class="badge bg-primary"><?= $receita['nome_rec'] ?> <span class="remove" onclick="removeRecipe(<?= $receita['idReceita'] ?>)">x</span></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <input type="hidden" name="idReceita" id="selectedRecipe" value="<?= implode(',', $receitas_livro_ids) ?>">


            <div class="mb-3">
                <label for="link_imagem" class="form-label">Link da Imagem:</label>
                <input type="text" class="form-control" id="link_imagem" name="link_imagem"
                       value="<?= $livro['link_imagem'] ?>" placeholder="Insira o link da imagem ou faça o upload abaixo" oninput="toggleImageInput()">
            </div>

            <div class="mb-3">
                <label for="arquivo_imagem" class="form-label">Upload da Imagem:</label>
                <input type="file" class="form-control" id="arquivo_imagem" name="arquivo_imagem" accept="image/*" onchange="toggleLinkInput()">
                <input type="hidden" name="imagem_atual" value="<?= $livro['arquivo_imagem'] ?>">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <!-- Botão de Voltar -->
                <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>

                <!-- Botão de Editar -->
                <button type="submit" class="btn btn-primary" style="width: 590px;">Salva Alterações</button>
            </div>
        </form>
    </div>

    <script>
    const receitas = <?php echo json_encode($receitas); ?>;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const recipeSearchInput = document.getElementById('recipeSearch');
    const recipeList = document.getElementById('recipeList');
    const selectedRecipesContainer = document.getElementById('selectedRecipes');
    const searchButton = document.getElementById('searchButton');
    const selectedRecipeIds = document.getElementById('selectedRecipe');

    let selectedRecipes = <?= json_encode($receitas_livro_ids) ?>.map(id => receitas.find(r => r.idReceita == id));

    // Exibe a lista de receitas ao clicar no botão de pesquisa
    searchButton.addEventListener('click', function () {
        if (recipeList.childElementCount > 0) {
            toggleRecipeList(true);
        } else {
            renderRecipeList('');
            toggleRecipeList(true);
        }
    });

    // Fecha a lista ao clicar fora dela
    document.addEventListener('click', function (event) {
        if (!recipeList.contains(event.target) && !recipeSearchInput.contains(event.target) && event.target !== searchButton) {
            toggleRecipeList(false);
        }
    });

    // Renderiza a lista conforme a pesquisa
    recipeSearchInput.addEventListener('input', function () {
        const query = recipeSearchInput.value.toLowerCase();
        renderRecipeList(query);
        toggleRecipeList(true);
    });

    function renderRecipeList(query) {
        recipeList.innerHTML = '';
        const filteredReceitas = receitas.filter(receita => receita.nome_rec.toLowerCase().includes(query));

        if (filteredReceitas.length === 0) {
            recipeList.innerHTML = '<div style="padding: 8px;">Nenhuma receita encontrada</div>';
        } else {
            filteredReceitas.forEach(receita => {
                const recipeItem = document.createElement('div');
                recipeItem.textContent = receita.nome_rec;
                recipeItem.dataset.id = receita.idReceita;
                recipeItem.addEventListener('click', () => selectRecipe(receita));
                recipeList.appendChild(recipeItem);
            });
        }
    }

    function selectRecipe(receita) {
        if (selectedRecipes.some(selected => selected.idReceita === receita.idReceita)) return;

        selectedRecipes.push(receita);
        updateSelectedRecipes();
        toggleRecipeList(false);
        recipeSearchInput.value = '';
    }

    function updateSelectedRecipes() {
        selectedRecipesContainer.innerHTML = '';
        selectedRecipeIds.value = selectedRecipes.map(recipe => recipe.idReceita).join(',');

        selectedRecipes.forEach(recipe => {
            const recipeTag = document.createElement('span');
            recipeTag.classList.add('recipe-tag');
            recipeTag.textContent = recipe.nome_rec;

            const removeButton = document.createElement('span');
            removeButton.classList.add('remove');
            removeButton.textContent = ' x';
            removeButton.onclick = () => removeRecipe(recipe.idReceita);

            recipeTag.appendChild(removeButton);
            selectedRecipesContainer.appendChild(recipeTag);
        });
    }

    function removeRecipe(idReceita) {
        selectedRecipes = selectedRecipes.filter(recipe => recipe.idReceita !== idReceita);
        updateSelectedRecipes();
    }

    function toggleRecipeList(show) {
        recipeList.style.display = show ? 'block' : 'none';
    }

    // Inicializa a exibição com as receitas já selecionadas
    updateSelectedRecipes();
});

    </script>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
