<?php 
require_once '../../../config.php'; 
require_once ROOT_PATH . 'receitas/conn.php';

// Obtém o ID do usuário pela URL
$idUsuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verifica se o ID do usuário é válido
if ($idUsuario > 0) {
    $query = "SELECT * FROM usuario WHERE idLogin = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    if (!$usuario) {
        echo "Usuário não encontrado!";
        exit;
    }
} else {
    echo "ID do usuário inválido!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
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
    <h2 class="text-center">Editar Usuário</h2>
    <form method="POST" action="../../CRUD/processarEditarUsuario.php" enctype="multipart/form-data">
        <input type="hidden" name="idLogin" value="<?php echo $usuario['idLogin']; ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha (deixe em branco para manter a atual):</label>
            <input type="password" class="form-control" id="senha" name="senha">
        </div>
        <div class="mb-3">
            <label for="imagem_perfil" class="form-label">Imagem de Perfil (deixe vazio para manter a atual):</label>
            <input type="file" class="form-control" id="imagem_perfil" name="imagem_perfil" accept="image/*">
            <?php if ($usuario['imagem_perfil']): ?>
                <div class="mt-2">
                    <img src="<?php echo BASE_URL .'receitas/imagens/perfil/'  . $usuario['imagem_perfil']; ?>" alt="Imagem de Perfil" class="img-thumbnail" style="max-width: 150px; max-height: auto;">
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Salvar -->
            <button type="submit" class="btn btn-primary" style="width: 590px;">Salvar Alterações</button>
        </div>
    </form>
</div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

