/**addReceita.php */
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (session_status() == PHP_SESSION_NONE) {
        session_start(); 
    }
    if (!isset($_SESSION['idLogin'])) {
        header("Location: " . ROOT_PATH . "/Paginas/Login.php");
        exit();
    } ?>
    <header>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementoPagina/cabecalho.php';?>
    </header>
    
</body>
</html>

