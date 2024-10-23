<?php
session_start(); // Inicia a sessão
include '../../conn.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['idFun'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: ../Paginas/Login.php");
    exit();
}

// Obtém o ID do funcionário da sessão
$idUsuario = $_SESSION['idFun'];

// Query para buscar os dados do funcionário
$query = "SELECT f.idFun, f.nome AS nomeFunc, f.nome_fantasia, u.email, u.senha, c.nome AS cargo
          FROM funcionario f
          JOIN usuario u ON f.idLogin = u.idLogin
          JOIN cargo c ON f.idCargo = c.idCargo
          WHERE f.idFun = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o resultado retornou algo
if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Nenhum usuário encontrado.";
    exit;
}

// Verifica se o ID da receita foi fornecido
if (isset($_GET['id'])) {
    $idReceita = $_GET['id'];

    $sqlReceita = "SELECT nome_rec, data_criacao, modo_preparo, num_porcao, descricao, link_imagem FROM receita WHERE idReceita = ?";

    if ($stmt = mysqli_prepare($conn, $sqlReceita)) {
        
        mysqli_stmt_bind_param($stmt, "i", $idReceita);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $receita = mysqli_fetch_assoc($result);
            $nome = $receita['nome_rec'];
            $dataCriacao = $receita['data_criacao'];
            $modoPreparo = $receita['modo_preparo'];
            $numPorcao = $receita['num_porcao'];
            $descricao = $receita['descricao'];
            $imagem = $receita['link_imagem'];
        } else {
            echo "Receita não encontrada.";
            exit;
        }
    } else {
        echo "Erro ao preparar a consulta.";
        exit;
    }
} else {
    echo "ID de receita não fornecido.";
    exit;
}

mysqli_close($conn); // Fecha a conexão com o banco de dados
?>
