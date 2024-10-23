<?php
session_start();
include_once('../conn.php');

// Verifica se o formulário de login ou registro foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Se for um formulário de login
    if (isset($_POST['login']) && isset($_POST['senha'])) {
        $login = mysqli_real_escape_string($conn, $_POST['login']);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        if (!empty($login) && !empty($senha)) {
            // Consulta para encontrar o usuário pelo email
            $sql = "SELECT u.idLogin, u.senha, f.idCargo 
                    FROM usuario u 
                    JOIN funcionario f ON u.idLogin = f.idLogin 
                    WHERE u.email = '$login'";
                    
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $usuario = mysqli_fetch_assoc($result);
                
                // Verifica se a senha está correta
                if (password_verify($senha, $usuario['senha'])) {
                    // Inicia a sessão com idLogin e idCargo
                    $_SESSION['idLogin'] = $usuario['idLogin'];
                    $_SESSION['idCargo'] = $usuario['idCargo'];

                    // Redireciona com base no cargo
                    switch ($usuario['idCargo']) {
                        case 6: // ADM
                            header("Location: ../Paginas/Home.php");
                            break;
                        case 7: // Cozinheiro
                            header("Location: ../paginas/cozinheiro_dashboard.php");
                            break;
                        case 8: // Editor
                            header("Location: ../paginas/editor_dashboard.php");
                            break;
                        case 9: // Degustador
                            header("Location: ../paginas/degustador_dashboard.php");
                            break;
                        case 10: // Analista de Sistema
                            header("Location: ../paginas/analista_dashboard.php");
                            break;
                        default:
                            echo "<script>alert('Cargo não reconhecido.'); window.location.href='../Paginas/Login.php';</script>";
                            break;
                    }
                    exit();
                } else {
                    echo "<script>alert('Senha incorreta.'); window.location.href='../Paginas/login.php';</script>";
                }
            } else {
                echo "<script>alert('Login não encontrado.'); window.location.href='../Paginas/login.php';</script>";
            }
        } else {
            echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../paginas/login.php';</script>";
        }
    }

    // Se for um formulário de registro
    elseif (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {
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
                    echo "<script>alert('Registro realizado com sucesso!'); window.location.href='../Paginas/login.php';</script>";
                } else {
                    echo "Erro: " . mysqli_error($conn);
                }
            } else {
                echo "<script>alert('Login já existente. Escolha outro.'); window.location.href='../Paginas/registro.php';</script>";
            }
        } else {
            echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../Paginas/registro.php';</script>";
        }
    }
}
?>
