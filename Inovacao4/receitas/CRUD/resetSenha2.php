<?php 
require_once "../../config.php"; 
include ROOT_PATH . 'receitas/conn.php';
require '../../../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
$conexao = $conn;
// Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $novaSenha = $_POST['senha'];
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    $query = "UPDATE usuario SET senha = ? WHERE email = (SELECT email FROM senha_recuperacao WHERE token = ?)";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("ss", $novaSenhaHash, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Exibe a mensagem de sucesso
        echo "<div class='alert alert-success text-center'>Senha redefinida com sucesso!</div>";
    
        // Remove o token da tabela de recuperação após a atualização
        $conexao->query("DELETE FROM senha_recuperacao WHERE token = '$token'");
    
        // Redireciona para a página de login
        header("Location: " . BASE_URL . "receitas/paginas/login.php");
        exit(); // Garante que o script pare de executar após o redirecionamento
    
    } else {
        echo "<div class='alert alert-danger text-center'>Token inválido ou expirado.</div>";
    }
    

    $stmt->close();
}


$conexao->close();
?>