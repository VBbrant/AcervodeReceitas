<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_rec = $_POST['nome_rec'];
    $data_criacao = $_POST['data_criacao'];
    $modo_preparo = $_POST['modo_preparo'];
    $num_porcao = $_POST['num_porcao'];
    $descricao = $_POST['descricao'];
    $inedita = $_POST['inedita'];
    $link_imagem = $_POST['link_imagem'];
    $ingredientes = json_decode($_POST['ingredientes'], true); // Espera-se JSON com ingredientes, medida e quantidade

    // Adiciona a receita ao banco de dados
    $stmt = $conn->prepare("INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, $inedita, $link_imagem);
    $stmt->execute();
    $recipe_id = $stmt->insert_id; // Pega o ID da nova receita para referência
    $stmt->close();

    // Verifica se há ingredientes para adicionar
    if (!empty($ingredientes) && is_array($ingredientes)) {
        foreach ($ingredientes as $ingrediente_data) {
            $ingrediente_nome = $ingrediente_data['nome'];
            $quantidade = $ingrediente_data['quantidade'];
            $sistema = $ingrediente_data['sistema'];

            // Adiciona o ingrediente ao banco de dados ou ignora se já existir
            $stmt = $conn->prepare("INSERT INTO ingrediente (nome) VALUES (?) ON DUPLICATE KEY UPDATE idIngrediente=LAST_INSERT_ID(idIngrediente)");
            $stmt->bind_param("s", $ingrediente_nome);
            $stmt->execute();
            $ingrediente_id = $stmt->insert_id;
            $stmt->close();

            // Adiciona a medida para o ingrediente
            $stmt = $conn->prepare("INSERT INTO medida (quantidade, sistema) VALUES (?, ?) ON DUPLICATE KEY UPDATE idMedida=LAST_INSERT_ID(idMedida)");
            $stmt->bind_param("ds", $quantidade, $sistema);
            $stmt->execute();
            $medida_id = $stmt->insert_id;
            $stmt->close();

            // Associa o ingrediente e a medida à receita na tabela intermediária
            $stmt = $conn->prepare("INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $recipe_id, $ingrediente_id, $medida_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redireciona para a página inicial após a inserção
    header("Location: " . BASE_URL . "receitas/Paginas/Home.php");
    exit();
}
?>
