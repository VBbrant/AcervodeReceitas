<?php
require_once '../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

session_start();
$idUsuario = $_SESSION['idLogin'];

$query = "UPDATE notificacoes_temp SET visto = 1 WHERE idUsuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
