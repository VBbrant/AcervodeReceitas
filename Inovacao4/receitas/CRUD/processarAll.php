<?php
require_once '../../../config.php';
include '../../conn.php';

$error = "";
$receita = null;

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

// Redireciona para verReceita.php com os dados da receita

exit;
?>
