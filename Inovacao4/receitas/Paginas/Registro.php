<?php
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$token = $_GET['token'] ?? '';

// Verificar o token
$sql_token = "SELECT * FROM registro_tokens WHERE token = ?";
$stmt = $conn->prepare($sql_token);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token inválido ou expirado.");
}

$registro = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sabor e Arte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/estiloRegistro.css">
</head>
<body>
    <!-- Logo -->
    <div class="logo text-center py-3">
        <h1 class="display-4">SABOR <span class="text-danger">ARTE</span></h1>
    </div>
    <div class="container my-4">
        <h2 class="text-center">Registrar Funcionário</h2>
        <form method="POST" action="../CRUD/processarConta.php" onsubmit="return validarSenha();">
            <input type="hidden" name="form_type" value="registro">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="mb-3">
                <label for="nome_usuario" class="form-label">Nome de Usuário:</label>
                <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required>
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
                <label for="confirmarSenha" class="form-label">Confirmar Senha:</label>
                <input type="password" class="form-control" id="confirmarSenha" name="confirmarSenha" minlength="6" maxlength="15" required>
            </div>

            <!-- Div para exibir a mensagem de erro -->
            <div id="mensagemErro" class="text-danger mb-3"></div>

            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>
    </div>

<script>
function validarSenha() {
    const senha = document.getElementById("senha").value;
    const confirmarSenha = document.getElementById("confirmarSenha").value;
    const mensagemErro = document.getElementById("mensagemErro");

    if (senha.length < 6 || senha.length > 15) {
        mensagemErro.textContent = "A senha deve ter entre 6 e 15 caracteres.";
        return false;
    }

    if (senha !== confirmarSenha) {
        mensagemErro.textContent = "As senhas não coincidem.";
        return false;
    }

    mensagemErro.textContent = "";
    return true;
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
