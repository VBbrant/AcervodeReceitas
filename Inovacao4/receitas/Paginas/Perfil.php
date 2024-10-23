<?php
session_start(); // Inicia a sessão
if (!isset($_SESSION['idFun'])) {
    header("Location: ../Paginas/Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Usuário</title>
    <link rel="stylesheet" href="../Style/perfil.css">
</head>
<body>
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
                <label>Senha</label>
                <input type="password" value="<?= isset($usuario['senha']) ? $usuario['senha'] : 'N/A' ?>" readonly>
            </div>
            <div class="perfil-item">
                <label>Cargo</label>
                <input type="text" value="<?= isset($usuario['cargo']) ? $usuario['cargo'] : 'N/A' ?>" readonly>
            </div>
        </div>
        <div class="receita-info">
            <h2>Detalhes da Receita</h2>
            <div class="receita-item">
                <label>Nome</label>
                <input type="text" value="<?= isset($nome) ? $nome : 'N/A' ?>" readonly>
            </div>
            <div class="receita-item">
                <label>Data de Criação</label>
                <input type="text" value="<?= isset($dataCriacao) ? $dataCriacao : 'N/A' ?>" readonly>
            </div>
            <div class="receita-item">
                <label>Modo de Preparo</label>
                <textarea readonly><?= isset($modoPreparo) ? $modoPreparo : 'N/A' ?></textarea>
            </div>
            <div class="receita-item">
                <label>Número de Porções</label>
                <input type="text" value="<?= isset($numPorcao) ? $numPorcao : 'N/A' ?>" readonly>
            </div>
            <div class="receita-item">
                <label>Descrição</label>
                <textarea readonly><?= isset($descricao) ? $descricao : 'N/A' ?></textarea>
            </div>
            <div class="receita-item">
                <label>Imagem</label>
                <input type="text" value="<?= isset($imagem) ? $imagem : 'N/A' ?>" readonly>
            </div>
        </div>
    </div>
</body>
</html>
