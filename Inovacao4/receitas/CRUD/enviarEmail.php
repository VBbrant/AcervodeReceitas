<?php
require '../../../vendor/autoload.php'; // Caminho correto para o autoload gerado pelo Composer

// Importar as classes do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $email = $_POST['email'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $link_registro = $_POST['link_registro'] ?? '';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'senaclpoo@gmail.com';
        $mail->Password = 'oormdbnavvkuiqgl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('senaclpoo@gmail.com', 'SABOR + ARTE');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = 'Link de Registro do Funcionário';
        $mail->Body = "Olá $nome,<br><br>Para concluir o seu registro, acesse o link a seguir:<br><a href='$link_registro'>$link_registro</a><br><br>Este é o seu código de registro: <b>$token</b><br><br>Atenciosamente,<br>Equipe Sabor + Arte.";

        $mail->send();
        echo "E-mail enviado com sucesso para $email!";
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
?>
