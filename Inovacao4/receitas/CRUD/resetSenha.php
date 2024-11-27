<?php 
require_once "../../config.php"; 
include ROOT_PATH . 'receitas/conn.php';

$conexao = $conn;
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificação da validade do token
    $stmt = $conexao->prepare("SELECT email FROM senha_recuperacao WHERE token = ? AND expira_em >= NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        echo "<div class='alert alert-danger text-center'>Token inválido ou expirado.</div>";
        $token = null;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <header class="header">
        <nav class="navbar fixed-top">
            <div class="container-fluid">
                <img src="<?php echo BASE_URL; ?>receitas/imagens/logo.png" alt="SaborArte" height="40">
            </div>
        </nav>
    </header>
    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="width: 400px;">
        <div class="p-5 rounded">
            <h2 class="text-center mb-4">Redefinir Senha</h2>
            <?php if (isset($token)) : ?>
                <form action="resetSenha2.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="mb-3">
                        <label for="senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Redefinir Senha</button>
                    </div>
                </form>
            <?php else : ?>
                <div class="alert alert-danger text-center">Token inválido.</div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
