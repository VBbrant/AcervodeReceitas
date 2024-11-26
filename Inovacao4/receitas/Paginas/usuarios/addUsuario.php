<?php require_once '../../../config.php'; require_once ROOT_PATH . 'receitas/conn.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/home3.css">
</head>
<body class="usuario">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
<div class="container my-4" id="lista">
    <h2 class="text-center">Adicionar Novo Usuário</h2>
    <form method="POST" action="../../CRUD/processarAdicionar.php" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="usuario">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <div class="mb-3">
            <label for="imagem_perfil" class="form-label">Imagem de Perfil:</label>
            <input type="file" class="form-control" id="imagem_perfil" name="imagem_perfil" accept="image/*">
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Adicionar -->
            <button type="submit" class="btn btn-primary" style="width: 590px;">Adicionar Usuário</button>
        </div>
    </form>
</div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

