<?php
session_start();
require_once '../../../config.php';

$idLivro = $_GET['id'] ?? null;
if (!$idLivro) {
    die("ID do livro não fornecido.");
}

// Armazena o ID na sessão e redireciona para a página de geração de PDF
$_SESSION['idLivro'] = $idLivro;
header("Location: gerarPDF.php");
exit();
