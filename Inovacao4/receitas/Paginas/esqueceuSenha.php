<?php require_once "../../config.php"; include ROOT_PATH . 'receitas/conn.php';?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha - SaborArte</title>
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
                <div class="d-flex align-items-center">
                    <img src="<?php echo BASE_URL; ?>receitas/imagens/logo.png" alt="SaborArte" class="logo-img" height="40">
                </div>
            </div>
        </nav>
    </header>

    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="width: 400px;">
        <div class="login-box p-5 rounded">
            <h2 class="text-center mb-4">Esqueceu a Senha?</h2>
            <p class="text-center">Digite seu e-mail para receber um link de recuperação.</p>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Enviar Link</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    require '../../../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $token = bin2hex(random_bytes(16));
        $link_registro = BASE_URL . "receitas/CRUD/resetSenha.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'senaclpoo@gmail.com';
            $mail->Password = 'oormdbnavvkuiqgl'; // Certifique-se de usar variáveis de ambiente para senhas.
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('senaclpoo@gmail.com', 'SABOR + ARTE');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha';
            $mail->Body = "Olá,<br><br>Para redefinir sua senha, clique no link abaixo:<br>
                <a href='$link_registro'>$link_registro</a><br><br>
                Caso não tenha solicitado, ignore este e-mail.<br><br>Equipe Sabor + Arte.";

            $mail->send();
            echo "<div class='alert alert-success text-center'>E-mail de recuperação enviado para $email!</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-danger text-center'>Erro ao enviar e-mail: {$mail->ErrorInfo}</div>";
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
