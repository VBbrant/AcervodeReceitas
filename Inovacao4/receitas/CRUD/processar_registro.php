<?php
include_once('../conn.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $login = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($nome) && !empty($login) && !empty($senha) && !empty($role)) {
        // Verifica se o login já existe
        $sql_check = "SELECT * FROM cargo WHERE email = '$login'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) == 0) {
            $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, login, senha, role) VALUES ('$nome', '$login', '$senha_hashed', '$role')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Registro realizado com sucesso!'); window.location.href='../PAGE/login.php';</script>";
            } else {
                echo "Erro: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Login já existente. Escolha outro.'); window.location.href='../PAGE/registro.php';</script>";
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../PAGE/registro.php';</script>";
    }
}
?>
