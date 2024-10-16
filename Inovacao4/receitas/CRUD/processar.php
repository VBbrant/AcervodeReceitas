<?php
session_start();

include_once('../conn.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    
    if (!empty($login) && !empty($senha)) {
        
        $sql = "SELECT * FROM usuarios WHERE login = '$login'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);
            
            
            if (password_verify($senha, $usuario['senha'])) {
                
                $_SESSION['login'] = $usuario['login'];
                $_SESSION['role'] = $usuario['role'];

                // Redirecionar o usuário com base no cargo
                switch ($usuario['role']) {
                    case 'adm':
                        header("Location: ../paginas/admin_dashboard.php");
                        break;
                    case 'cozinheiro':
                        header("Location: ../paginas/cozinheiro_dashboard.php");
                        break;
                    case 'cliente':
                        header("Location: ../paginas/cliente_dashboard.php");
                        break;
                    default:
                        echo "Cargo não reconhecido.";
                        break;
                }
                exit();
            } else {
                
                echo "<script>alert('Senha incorreta.'); window.location.href='../login.php';</script>";
            }
        } else {
            
            echo "<script>alert('Login não encontrado.'); window.location.href='../login.php';</script>";
        }
    } else {
        echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../login.php';</script>";
    }
}
?>
