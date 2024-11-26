<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$idReceita = $_GET['id'] ?? null;

if (!$idReceita) {
    echo "ID da receita não fornecido.";
    exit;
}

// Carrega os dados da receita
$sql_receita = "SELECT * FROM receita WHERE idReceita = ?";
$stmt_receita = $conn->prepare($sql_receita);
$stmt_receita->bind_param("i", $idReceita);
$stmt_receita->execute();
$receita = $stmt_receita->get_result()->fetch_assoc();

// Carrega as listas para o formulário
$sql_medidas = "SELECT idMedida, sistema AS nome FROM medida";
$medidas = $conn->query($sql_medidas)->fetch_all(MYSQLI_ASSOC);

$sql_ingredientes = "SELECT idIngrediente, nome FROM ingrediente";
$ingredientes = $conn->query($sql_ingredientes)->fetch_all(MYSQLI_ASSOC);

$sql_cozinheiros = "SELECT f.idFun, f.nome FROM funcionario f JOIN cargo c ON f.idCargo = c.idCargo WHERE c.nome = 'Cozinheiro'";
$cozinheiros = $conn->query($sql_cozinheiros)->fetch_all(MYSQLI_ASSOC);

$sql_categorias = "SELECT idCategoria, nome FROM categoria";
$categorias = $conn->query($sql_categorias)->fetch_all(MYSQLI_ASSOC);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="receita">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container d-flex justify-content-center align-items-center my-4"  id="lista" style="min-height: 80vh;">
        <div style="max-width: 600px; width: 100%;">
        <h2 class="text-center">Editar Receita</h2>
        <form id="recipeForm" method="POST" action="../../CRUD/processarEditar.php?id=<?php echo $idReceita; ?>" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="receita">
            <input type="hidden" name="id_receita" value="<?php echo $idReceita; ?>">
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
                <input value = "<?php echo BASE_URL . htmlspecialchars($receita['arquivo_imagem']); ?>" type="file" class="form-control" id="arquivo_imagem" name="arquivo_imagem" accept="image/*" onchange="toggleLinkInput()">
                <?php if ($receita['link_imagem']): ?>
                    <p>Imagem atual: <a href="<?php echo htmlspecialchars($receita['link_imagem']); ?>" target="_blank">Ver Imagem</a></p>
                <?php elseif ($receita['arquivo_imagem']): ?>
                    <p>Imagem atual: <a href="<?php echo BASE_URL . htmlspecialchars($receita['arquivo_imagem']); ?>" target="_blank">Ver Imagem</a></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="ingredientSearch" class="form-label">Ingredientes</label>
                <div class="input-group mb-2">
                    <input type="text" id="ingredientSearch" class="form-control" placeholder="Pesquisar ingrediente...">
                    <span class="input-group-text" onclick="filterIngredients()"><i class="fas fa-search"></i></span>
                    <button type="button" class="btn btn-primary" id="addIngredientOptions">+</button>
                    <div id="ingredientList" class="list-group" style="display: none;"></div>
                </div>
                <div id="selectedIngredients" class="mb-3"></div>
            </div>

            <div id="additionalOptions" class="dropdown-menu" style="display: none;">
                <a href="<?= BASE_URL;?>receitas/Paginas/Ingredientes/addIngrediente.php" class="dropdown-item">Adicionar Ingrediente</a>
                <a href="<?= BASE_URL;?>receitas/Paginas/medidas/addMedida.php" class="dropdown-item">Adicionar Medida</a>
            </div>

            <!-- Campo oculto para armazenar os ingredientes selecionados em JSON -->
            <input type="hidden" name="ingredientes" id="ingredientesJson">

            <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Editar -->
            <button type="submit" class="btn btn-primary" style="width: 510px;">Salvar Alterações</button>
        </div>
        </form>
        
        </div>
    </div>

<script>
    const ingredientsData = <?php echo json_encode($ingredientes); ?>; 
    const measurementsData = <?php echo json_encode($medidas); ?>; 
    const selectedIngredients = <?php echo json_encode($receita_ingredientes); ?>; 
    
</script>
<script src="<?php echo BASE_URL . 'receitas/Scripts/editarReceita.js';?>"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

