<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idUsuario = $_GET['id'] ?? null;

if (!$idUsuario) {
    echo "ID do usuário não fornecido.";
    exit;
}

// Consulta para pegar os dados do usuário
$sql_usuario = "SELECT * FROM usuario WHERE idLogin = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $idUsuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

// Verifica se o usuário existe
if ($result_usuario->num_rows === 0) {
    echo "Usuário não encontrado.";
    exit;
}

$usuario = $result_usuario->fetch_assoc();

// Consulta para pegar os dados do funcionário associado (se houver)
$sql_funcionario = "SELECT * FROM funcionario WHERE idLogin = ?";
$stmt_funcionario = $conn->prepare($sql_funcionario);
$stmt_funcionario->bind_param("i", $idUsuario);
$stmt_funcionario->execute();
$result_funcionario = $stmt_funcionario->get_result();

// Se existir um funcionário associado
$funcionario = $result_funcionario->fetch_assoc();

// Consulta para pegar o cargo do funcionário (caso exista)
$sql_cargo = "SELECT nome FROM cargo WHERE idCargo = ?";
$stmt_cargo = $conn->prepare($sql_cargo);
if ($funcionario) {
    $stmt_cargo->bind_param("i", $funcionario['idCargo']);
    $stmt_cargo->execute();
    $result_cargo = $stmt_cargo->get_result();
    $cargo = $result_cargo->fetch_assoc();
} else {
    $cargo = null; // Caso não tenha um funcionário associado
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Usuário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">  
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">  

    <style>
        body{background-color: black !important;}
        #pagina{background-color: white !important;}
    </style>     
</head>
<body class="usuario">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4" id="lista">
    <h2 class="text-center">Visualizar Usuário</h2>
    <form class="form-horizontal" disabled>
        <!-- Informações do Usuário -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="imagem_perfil" class="form-label">Imagem de Perfil</label>
            <?php if ($usuario['imagem_perfil']): ?>
                <img src="<?php echo BASE_URL . 'receitas/imagens/perfil/' . $usuario['imagem_perfil']; ?>" alt="Imagem de Perfil" class="rounded-circle" style="width: 80px; height: 80px;">
            <?php else: ?>
                <i class="fas fa-user-circle fa-5x"></i>
            <?php endif; ?>
        </div>

        <!-- Informações do Funcionário Associado -->
        <?php if ($funcionario): ?>
            <h3 class="mt-4">Informações do Funcionário</h3>
            <div class="mb-3">
                <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                <input type="text" class="form-control" id="nome_fantasia" value="<?php echo htmlspecialchars($funcionario['nome_fantasia']); ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo</label>
                <input type="text" class="form-control" id="cargo" value="<?php echo htmlspecialchars($cargo['nome'] ?? 'Nenhum cargo associado'); ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="data_admissao" class="form-label">Data de Admissão</label>
                <input type="text" class="form-control" id="data_admissao" value="<?php echo htmlspecialchars($funcionario['data_admissao']); ?>" disabled>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Este usuário não está associado a nenhum funcionário.
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center">
            <!-- Botão de Voltar -->
            <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Botão de Editar -->
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/editarUsuario.php?id=<?php echo $idUsuario; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
