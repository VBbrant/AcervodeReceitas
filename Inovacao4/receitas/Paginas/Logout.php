<?php require_once '../../config.php';
session_start();

// Destrói todas as variáveis de sessão
session_unset(); 


session_destroy(); 


header("Location: " . ROOT_PATH . "receitas/Paginas/Login.php");
exit();
?>
