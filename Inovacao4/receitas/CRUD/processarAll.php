<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../conn.php'; // Inclui a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['idFun'])) {
    header("Location: ../Paginas/Login.php");
    exit();
}

$idUsuario = $_SESSION['idFun'];

$query = "SELECT f.idFun, f.nome AS nomeFunc, f.nome_fantasia, u.email, u.senha, c.nome AS cargo
          FROM funcionario f
          JOIN usuario u ON f.idLogin = u.idLogin
          JOIN cargo c ON f.idCargo = c.idCargo
          WHERE f.idFun = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    echo "Nenhum usuário encontrado.";
    exit;
}

// VerReceita ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idReceita = $_GET['id'];

    // Consulta para obter detalhes da receita
    $sqlReceita = "SELECT nome_rec, data_criacao, modo_preparo, num_porcao, descricao, link_imagem 
                   FROM receita 
                   WHERE idReceita = ?";

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
            $error = "Receita não encontrada.";
        }
    } else {
        $error = "Erro ao preparar a consulta.";
    }
    mysqli_stmt_close($stmt);
} else {
    $error = "ID de receita não fornecido ou método inválido.";
}

mysqli_close($conn);
?>
