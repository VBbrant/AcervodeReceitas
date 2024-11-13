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
                $sql = "SELECT u.idLogin, u.senha, u.imagem_perfil, f.idCargo, f.idFun, f.nome AS nomeFunc, f.nome_fantasia, c.nome 
                        FROM usuario u 
                        JOIN funcionario f ON u.idLogin = f.idLogin
                        JOIN cargo c ON f.idCargo = c.idCargo 
                        WHERE u.email = '$login'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $usuario = mysqli_fetch_assoc($result);
                    
                    if (password_verify($senha, $usuario['senha'])) {
                        $_SESSION['idLogin'] = $usuario['idLogin'];
                        $_SESSION['idCargo'] = $usuario['idCargo'];
                        $_SESSION['idFun'] = $usuario['idFun'];
                        $_SESSION['nomeFunc'] = $usuario['nomeFunc'];
                        $_SESSION['apelido'] = $usuario['nome_fantasia'];
                        $_SESSION['cargo'] = $usuario['nome'];
                        $_SESSION['imagem_perfil'] = $usuario['imagem_perfil'] ?? null;

                        if ($usuario['imagemPerfil']) {
                            $_SESSION['imagem_perfil'] = $usuario['imagem_perfil'];
                        }

                        // Redireciona com base no cargo
                        switch ($usuario['idCargo']) {
                            case 6:
                                header("Location: ../Paginas/Home.php"); // ADM
                                break;
                            case 7:
                                header("Location: ../Paginas/Home.php"); // Cozinheiro
                                break;
                            case 8:
                                header("Location: ../Paginas/Home.php"); // Editor
                                break;
                            case 9:
                                header("Location: ../Paginas/Home.php"); // Degustador
                                break;
                            case 10:
                                header("Location: ../Paginas/Home.php"); // Analista de Sistema
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
        case 'perfil':
            // Defina o diretório de upload e obtenha as informações do usuário
            $diretorioUpload = ROOT_PATH . 'receitas/imagens/perfil/';
            $idUsuario = $_SESSION['idLogin']; // Supondo que o ID do usuário está salvo na sessão
        
            // Verifique se um arquivo foi enviado
            if (isset($_FILES['imagemPerfil']) && $_FILES['imagemPerfil']['error'] === UPLOAD_ERR_OK) {
                $caminhoTemporario = $_FILES['imagemPerfil']['tmp_name'];
                $nomeArquivo = $idUsuario . '_' . time() . '_' . $_FILES['imagemPerfil']['name'];
                $caminhoDestino = $diretorioUpload . $nomeArquivo;
        
                // Consulte a imagem anterior no banco de dados
                $sql = "SELECT imagem_perfil FROM usuario WHERE idLogin = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $idUsuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $usuario = $resultado->fetch_assoc();
        
                // Apague a imagem antiga se existir
                if ($usuario['imagem_perfil'] && file_exists($diretorioUpload . $usuario['imagem_perfil'])) {
                    unlink($diretorioUpload . $usuario['imagem_perfil']);
                }
        
                // Mova o novo arquivo para o diretório de upload
                if (move_uploaded_file($caminhoTemporario, $caminhoDestino)) {
                    $sqlAtualizar = "UPDATE usuario SET imagem_perfil = ? WHERE idLogin = ?";
                    $stmtAtualizar = $conn->prepare($sqlAtualizar);
                    $stmtAtualizar->bind_param("si", $nomeArquivo, $idUsuario);
                    $stmtAtualizar->execute();
                    
                    $_SESSION['imagem_perfil'] = $nomeArquivo;
        
                    // Redirecione para o perfil com uma mensagem de sucesso
                    echo "<script>window.history.back();</script>";
                } else {
                    echo "Erro ao mover o arquivo.";
                }
            } else {
                echo "Erro no envio da imagem.";
            }
            
            break;
            
        case "mudarSenha":
            $idUsuario = $_SESSION['idLogin']; // ID do usuário logado
            $senha_atual = $_POST['senha_atual'];
            $nova_senha = $_POST['nova_senha'];
            $repita_nova_senha = $_POST['repita_nova_senha'];
        
            // Verifica se as senhas novas são iguais
            if ($nova_senha !== $repita_nova_senha) {
                echo "As senhas não coincidem.";
                exit;
            }
        
            // Verifica a senha atual
            $sql_usuario = "SELECT senha FROM usuario WHERE idLogin = ?";
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bind_param("i", $idUsuario);
            $stmt_usuario->execute();
            $result_usuario = $stmt_usuario->get_result();
            $usuario = $result_usuario->fetch_assoc();
        
            if (!$usuario) {
                echo "Usuário não encontrado.";
                exit;
            }
        
            // Verifica se a senha atual corresponde
            if (!password_verify($senha_atual, $usuario['senha'])) {
                echo "Senha atual incorreta.";
                exit;
            }
        
            // Atualiza a senha no banco de dados
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT); // Gera um hash da nova senha
            $sql_atualizar_senha = "UPDATE usuario SET senha = ? WHERE idLogin = ?";
            $stmt_atualizar_senha = $conn->prepare($sql_atualizar_senha);
            $stmt_atualizar_senha->bind_param("si", $nova_senha_hash, $idUsuario);
        
            if ($stmt_atualizar_senha->execute()) {
                echo "Senha alterada com sucesso!";
                // Redireciona ou exibe sucesso
                echo "<script>window.history.back();</script>";
                exit;
            } else {
                echo "Erro ao atualizar a senha.";
                exit;
            }       
            break;
        default:
            echo "<script>alert('Tipo de formulário não reconhecido.'); window.history.back();</script>";
            break;
    }
}
?>
