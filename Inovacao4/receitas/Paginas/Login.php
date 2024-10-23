<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sabor e Arte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/estilo.css">
</head>
<body>
    <!-- Logo temporária -->
    <div class="logo text-center py-3">
        <h1 class="display-4">SABOR <span class="text-danger">ARTE</span></h1>
    </div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-box p-5 rounded">
            <h2 class="text-center mb-4">LOGIN</h2>
            <form action="../CRUD/processarConta.php" method="post">
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu Login" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua Senha" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">ACESSAR</button>
                </div>
                <p class="text-center mt-3">Não tem uma conta? <a href="registro.php">Registre-se aqui</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
