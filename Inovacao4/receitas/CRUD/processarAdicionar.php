<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se ainda não foi iniciada
}

include '../../conn.php'; 

$idUsuario = $_SESSION['idFun'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nome_rec = $_POST['nome_rec'];
    $data_criacao = $_POST['data_criacao'];
    $modo_preparo = $_POST['modo_preparo'];
    $num_porcao = $_POST['num_porcao'];
    $descricao = $_POST['descricao'];
    $inedita = $_POST['inedita'];
    $link_imagem = $_POST['link_imagem'];
    $idCozinheiro = $idUsuario;


    $sql = "INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem, idCozinheiro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssisssi", $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, $inedita, $link_imagem, $idCozinheiro);
        
        if ($stmt->execute()) {
            header("Location: ../Paginas/sucesso.php");
            exit();
        } else {
            echo "Erro ao inserir a receita: " . $stmt->error;
        }
    } else {
        echo "Erro ao preparar a consulta.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método inválido.";
    exit;
}

mysqli_close($conn);
?>
