<?php
session_start();
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'] ?? '';

    switch ($form_type) {
        case 'login':
            $login = mysqli_real_escape_string($conn, $_POST['login'] ?? '');
            $senha = mysqli_real_escape_string($conn, $_POST['senha'] ?? '');

            if (!empty($login) && !empty($senha)) {
                // Consulta para verificar o usuário e dados do funcionário
                $sql = "SELECT u.idLogin, u.senha, f.idCargo, f.idFun, f.nome AS nomeFunc, f.nome_fantasia 
                        FROM usuario u 
                        JOIN funcionario f ON u.idLogin = f.idLogin 
                        WHERE u.email = '$login'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $usuario = mysqli_fetch_assoc($result);
                    
                    if (password_verify($senha, $usuario['senha'])) {
                        $_SESSION['idLogin'] = $usuario['idLogin'];
                        $_SESSION['idCargo'] = $usuario['idCargo'];
                        $_SESSION['idFun'] = $usuario['idFun'];
                        $_SESSION['nomeFunc'] = $usuario['nomeFunc'];
                        $_SESSION['nome_fantasia'] = $usuario['nome_fantasia'];

                        // Redireciona com base no cargo
                        switch ($usuario['idCargo']) {
                            case 6:
                                header("Location: ../Paginas/Home.php"); // ADM
                                break;
                            case 7:
                                header("Location: ../Paginas/cozinheiro_dashboard.php"); // Cozinheiro
                                break;
                            case 8:
                                header("Location: ../Paginas/editor_dashboard.php"); // Editor
                                break;
                            case 9:
                                header("Location: ../Paginas/degustador_dashboard.php"); // Degustador
                                break;
                            case 10:
                                header("Location: ../Paginas/analista_dashboard.php"); // Analista de Sistema
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
                echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../Paginas/login.php';</script>";
            }
            break;

        case 'registro':
            $token = $_POST['token'];
            $nome_usuario = $_POST['nome_usuario'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
        
            $conn->begin_transaction();
        
            try {
                $sql = "SELECT * FROM registro_tokens WHERE token = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $result = $stmt->get_result();
        
                if ($result->num_rows === 0) {
                    throw new Exception("Token inválido ou expirado.");
                }
        
                $registro = $result->fetch_assoc();
                $stmt->close();
        
                $sql_usuario = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql_usuario);
                $stmt->bind_param("sss", $nome_usuario, $email, $senha);
                $stmt->execute();
                $idLogin = $stmt->insert_id;
                $stmt->close();
        
                $sql_funcionario = "INSERT INTO funcionario (nome, rg, data_nascimento, data_admissao, salario, nome_fantasia, idLogin, idCargo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_funcionario);
                $stmt->bind_param("ssssdsii", $registro['nome'], $registro['rg'], $registro['data_nascimento'], $registro['data_admissao'], $registro['salario'], $registro['nome_fantasia'], $idLogin, $registro['idCargo']);
                $stmt->execute();
                $stmt->close();
        
                $sql_delete_token = "DELETE FROM registro_tokens WHERE token = ?";
                $stmt = $conn->prepare($sql_delete_token);
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->close();
        
                $conn->commit();
                echo "<script>alert('Registrado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/login.php';</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Erro ao registrar: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                if (isset($stmt) && $stmt !== null) {
                    $stmt->close();
                }
            }
            break;
            
            
        default:
            echo "<script>alert('Tipo de formulário não reconhecido.'); window.history.back();</script>";
            break;
    }
}
?>
