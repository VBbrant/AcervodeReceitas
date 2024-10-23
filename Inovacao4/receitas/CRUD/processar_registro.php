<?php
// processar_registro.php
include_once('../conn.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $login = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    if (!empty($nome) && !empty($login) && !empty($senha)) {
        // Verifica se o login já existe
        $sql_check = "SELECT * FROM usuario WHERE email = '$login'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) == 0) {
            // Criptografando a senha
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

            // Inserindo novo usuário
            $sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$nome', '$login', '$senha_hashed')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Registro realizado com sucesso!'); window.location.href='../PAGE/login.php';</script>";
            } else {
                echo "Erro: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Login já existente. Escolha outro.'); window.location.href='../PAGE/registro.php';</script>";
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../PAGE/registro.php';</script>";
    }
}
?>
