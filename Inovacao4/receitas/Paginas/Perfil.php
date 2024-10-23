<?php
session_start(); // Inicia a sessão
if (!isset($_SESSION['idLogin'])) {
    header("Location: ../Paginas/Login.php");
    exit();
}

$usuario = [
    'idFun' => $_SESSION['idFun'] ?? null,
    'nomeFunc' => $_SESSION['nome'] ?? null,
    'nome_fantasia' => $_SESSION['nome_fantasia'] ?? null,
    'email' => $_SESSION['email'] ?? null,
    'senha' => $_SESSION['senha'] ?? null,
    'cargo' => $_SESSION['cargo'] ?? null
];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Usuário</title>
    <link rel="stylesheet" href="../Style/estiloPerfil.css">
    <script src="../Scripts/javaScript.js"></script>

</head>
<body>
    <?php include '../elementoPagina/cabecalho.php'; ?>
    <div class="perfil-container">
        <h1>Área do Usuário</h1>
        <div class="perfil-info">
            <div class="perfil-item">
                <label>ID</label>
                <input type="text" value="<?= isset($usuario['idFun']) ? $usuario['idFun'] : 'N/A' ?>" readonly>
            </div>
            <div class="perfil-item">
                <label>Nome</label>
                <input type="text" value="<?= isset($usuario['nomeFunc']) ? $usuario['nomeFunc'] : 'N/A' ?>" readonly>
            </div>
            <div class="perfil-item">
                <label>Nome Fantasia</label>
                <input type="text" value="<?= isset($usuario['nome_fantasia']) ? $usuario['nome_fantasia'] : 'N/A' ?>" readonly>
            </div>
            <div class="perfil-item">
                <label>Email</label>
                <input type="email" value="<?= isset($usuario['email']) ? $usuario['email'] : 'N/A' ?>" readonly>
            </div>
            <div class="perfil-item">
                <label>Cargo</label>
                <input type="text" value="<?= isset($usuario['cargo']) ? $usuario['cargo'] : 'N/A' ?>" readonly>
            </div>
        </div>
    </div>
</body>
</html>

