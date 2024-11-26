<?php require_once '../../../config.php'; require_once ROOT_PATH . 'receitas/conn.php';?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Adicionar Medida</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    <div class="container my-4" id="lista">
        <h2 class="text-center">Adicionar Nova Medida</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarAdicionar.php">
            <input type="hidden" name="form_type" value="medida">
            <div class="mb-3">
                <label for="nome_medida" class="form-label">Nome da Medida:</label>
                <input type="text" class="form-control" id="nome_medida" name="nome_medida" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <!-- Botão de Voltar -->
                <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>

                <!-- Botão de Editar -->
                <button type="submit" class="btn btn-primary" style="width: 590px;">Adicionar Medida</button>
            </div>
        </form>
    </div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

