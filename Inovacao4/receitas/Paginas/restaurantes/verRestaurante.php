<?php
// editarRestaurante.php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Recuperar o id do restaurante
$idRestaurante = $_GET['id'] ?? null;

if ($idRestaurante) {
    // Recuperar os dados do restaurante
    $sql = "SELECT nome, telefone, endereco FROM restaurante WHERE idRestaurante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idRestaurante);
    $stmt->execute();
    $stmt->bind_result($nome, $telefone, $endereco);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Restaurante</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita3.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body class="ingrediente">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4" id="lista">
        <h2 class="text-center">Ver Restaurante</h2>
        <form method="POST" action="../../CRUD/processarEditar.php">
            <input type="hidden" name="form_type" value="restaurante">
            <input type="hidden" name="idRestaurante" value="<?php echo $idRestaurante; ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Restaurante:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="telefone" 
                    name="telefone" 
                    value="<?php echo $telefone; ?>"
                    disabled
                    oninput="formatarTelefone(this)"
                    aria-describedby="telefoneHelp">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $endereco; ?>" disabled>
            </div>

            <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Editar -->
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/editarRestaurante.php?id=<?php echo $idRestaurante; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
        </form>
    </div>


<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

