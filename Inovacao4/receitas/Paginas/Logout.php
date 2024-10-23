<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/ACERVODERECEITAS/Inovacao4/config.php';
session_start();

// Destrói todas as variáveis de sessão
session_unset(); 


session_destroy(); 


header("Location: " . ROOT_PATH . "/Paginas/Login.php");
exit();
?>
