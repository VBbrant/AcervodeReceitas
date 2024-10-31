<?php

$receitas = $_POST['receitas']; 
$id_livro = $_POST['id_livro'];
$nome_livro = $_POST['nome_livro'];
$codigo_isbn = $_POST['codigo_isbn']; 
$nome_editor = $_POST['nome_editor']; 

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO livros (id_livro, nome_livro, codigo_isbn, nome_editor) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssss", $id_livro, $nome_livro, $codigo_isbn, $nome_editor);

$stmt->execute();

if ($stmt->errno) {
    echo "Error: " . $stmt->error;
} else {

    foreach ($receitas as $receita) {

        $receita_name = $receita['name']; 
        $ingredientes = $receita['ingredients']; 


    }

    echo "Livro created successfully!";
}

$stmt->close();
$conn->close();

?>

<?php


$conn = new mysqli("localhost", "username", "password", "database");


if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$id_livro = $_POST["id_livro"];
$nome_livro = $_POST["nome_livro"];
$id_editor = $_POST["id_editor"];
$label = $_POST["label"];
$value = $_POST["value"];

$sql = "INSERT INTO livros (id_livro, nome_livro, id_editor) VALUES ('$id_livro', '$nome_livro', '$id_editor')";

if ($conn->query($sql) === TRUE) {
    echo "Livro inserido com sucesso!";
} else {
    echo "Erro ao inserir o livro: " . $conn->error;
}

$sql = "INSERT INTO legendas (id_livro, label, value) VALUES ('$id_livro', '$label', '$value')";

if ($conn->query($sql) === TRUE) {
    echo "Legenda inserida com sucesso!";
} else {
    echo "Erro ao inserir a legenda: " . $conn->error;
}

$sql = "SELECT * FROM receitas WHERE id_livro = '$id_livro'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

        $id_receita = $row["id_receita"];
        $sql = "INSERT INTO receitas_livros (id_livro, id_receita) VALUES ('$id_livro', '$id_receita')";

        if ($conn->query($sql) === TRUE) {
            echo "Receita inserida com sucesso!";
        } else {
            echo "Erro ao inserir a receita: " . $conn->error;
        } } }
            ?>

