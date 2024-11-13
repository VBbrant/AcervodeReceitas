<?php
require_once("../caminho.php");
include_once ROOT_PATH . "Config/Conexao.php";

session_start();

// Verifica se o ID do livro foi passado via GET
if (isset($_GET['id'])) {
    // Salva o ID do livro na sessão
    $_SESSION['id_livro'] = (int) $_GET['id'];

    // Redireciona para o dompf.php
    header("Location: dompf.php");
    exit();
} else {
    // Caso não exista ID, exibe uma mensagem de erro ou redireciona para uma página padrão
    echo "ID do livro não foi fornecido!";
    exit();
}
