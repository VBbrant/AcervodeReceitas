<?php
define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASSWORD', '#Ladynoir1');
define('BD_DATABASE', 'acervoreceita2');
define('BD_PORT', '3307'); // 3306 é a porta padrão


$conn = mysqli_connect(BD_HOST, BD_USER, BD_PASSWORD, BD_DATABASE, BD_PORT);


if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>
