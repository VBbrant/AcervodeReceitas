<?php require_once "../../config.php";?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/login.css">

</head>
<body>
    <header class="header">
        <nav class="navbar fixed-top">
            <div class="container-fluid header-content">
                <!-- Left side - Menu button and Logo -->
                <div class="d-flex align-items-center">
                        <img src="<?php echo BASE_URL; ?>receitas/imagens/logo.png" alt="SaborArte" class="logo-img" height="40">
                    </a>
                </div>

            </div>
        </nav>
    </header>


    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="width: 400px !important; height: auto !important;">
        <div class="login-box p-5 rounded">
            <h2 class="text-center mb-4">LOGIN</h2>
            <form action="../CRUD/processarConta.php" method="post" style="width: 400px !important; height: auto !important;">
                <input type="hidden" name="form_type" value="login">

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
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
