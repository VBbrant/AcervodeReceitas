<?php
require_once "../../../config.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_rec = $_POST['nome_rec'];
    $data_criacao = $_POST['data_criacao'];
    $modo_preparo = $_POST['modo_preparo'];
    $num_porcao = $_POST['num_porcao'];
    $descricao = $_POST['descricao'];
    $inedita = $_POST['inedita'];
    $link_imagem = $_POST['link_imagem'];

    // Adiciona a receita ao banco de dados
    $stmt = $conn->prepare("INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, $inedita, $link_imagem);
    $stmt->execute();
    $recipe_id = $stmt->insert_id; // Pega o ID da nova receita para referência
    $stmt->close();

    // Adiciona os ingredientes ao banco de dados
    if (!empty($_POST['ingredientes'])) {
        $ingredientes = explode(',', $_POST['ingredientes']); // Supondo que estejam separados por vírgulas
        foreach ($ingredientes as $ingrediente) {
            $ingrediente = trim($ingrediente);
            if (!empty($ingrediente)) {
                $stmt = $conn->prepare("INSERT INTO ingredientes (nome) VALUES (?) ON DUPLICATE KEY UPDATE nome=nome");
                $stmt->bind_param("s", $ingrediente);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    header("Location: " . BASE_URL . "receitas/Paginas/Home.php"); // Redireciona para uma página de sucesso
    exit();
}
?>
