<?php
session_start();
require_once '../../../config.php';
require_once '../../../../vendor/autoload.php'; // Caminho para o autoload do mPDF

// Recupera o ID do livro da sessão
$idLivro = $_SESSION['idLivro'] ?? null;
if (!$idLivro) {
    die("ID do livro não fornecido.");
}

// Instancia o mPDF
$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4', // Tamanho A3
    'orientation' => 'P' // Orientação retrato (portrait), use 'L' para paisagem (landscape)
]);

// Inicia a captura do conteúdo HTML
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui o conteúdo HTML com a consulta de banco de dados
include 'conteudoLivro.php'; // Caminho para o HTML completo com o layout do livro
$html = ob_get_clean();

// Define o conteúdo HTML para o mPDF
$mpdf->WriteHTML($html);

// Envia o PDF para o navegador como download
$mpdf->Output("LivroPDF_$idLivro.pdf", \Mpdf\Output\Destination::DOWNLOAD);

header("Location: listaLivro.php");
