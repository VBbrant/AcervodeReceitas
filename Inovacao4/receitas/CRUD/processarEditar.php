<?php
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Verifica se o ID da receita foi passado
$idReceita = $_GET['id'] ?? null;
if (!$idReceita) {
    echo "ID da receita não fornecido.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebendo os dados do formulário
    $nome_rec = $_POST['nome_rec'];
    $data_criacao = $_POST['data_criacao'];
    $modo_preparo = $_POST['modo_preparo'];
    $num_porcao = $_POST['num_porcao'];
    $descricao = $_POST['descricao'];
    $inedita = $_POST['inedita'];
    $id_cozinheiro = $_POST['id_cozinheiro'];
    $id_categoria = $_POST['id_categoria'];
    $link_imagem = $_POST['link_imagem'] ?? null;
    $ingredientes = json_decode($_POST['ingredientes'], true); // Decodifica JSON dos ingredientes

    $arquivo_imagem = null;

    // Verifica se um novo arquivo de imagem foi enviado e faz o upload
    if (isset($_FILES['arquivo_imagem']) && $_FILES['arquivo_imagem']['error'] === UPLOAD_ERR_OK) {
        $diretorioDestino = ROOT_PATH . "receitas/imagens/";
        $extensao = strtolower(pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION));

        // Gera um nome de arquivo único
        $nomeArquivo = preg_replace('/[^\w-]/', '', $nome_rec) . "_" . uniqid() . "." . $extensao;
        $arquivo_imagem = "receitas/imagens/" . $nomeArquivo;

        // Cria o diretório se não existir
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        // Move o arquivo para o diretório de destino
        if (!move_uploaded_file($_FILES['arquivo_imagem']['tmp_name'], $diretorioDestino . $nomeArquivo)) {
            echo "Erro ao fazer upload da imagem.";
            exit;
        }
    }

    // Inicia a transação
    $conn->begin_transaction();

    try {
        // Atualiza os dados principais da receita
        $sql_update_receita = "
            UPDATE receita 
            SET nome_rec = ?, data_criacao = ?, modo_preparo = ?, num_porcao = ?, descricao = ?, 
                inedita = ?, link_imagem = ?, arquivo_imagem = ?, idCozinheiro = ?, idCategoria = ?
            WHERE idReceita = ?
        ";
        $stmt = $conn->prepare($sql_update_receita);
        
        // Usa o novo arquivo de imagem, se existir, senão mantém o existente
        $imagem_final = $arquivo_imagem ?? $receita['arquivo_imagem'];
        
        $stmt->bind_param(
            "sssissssiii", 
            $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, 
            $inedita, $link_imagem, $imagem_final, $id_cozinheiro, $id_categoria, $idReceita
        );
        $stmt->execute();

        // Remove ingredientes antigos associados à receita
        $sql_delete_ingredientes = "DELETE FROM receita_ingrediente WHERE idReceita = ?";
        $stmt_delete = $conn->prepare($sql_delete_ingredientes);
        $stmt_delete->bind_param("i", $idReceita);
        $stmt_delete->execute();

        // Insere os ingredientes atualizados
        if (is_array($ingredientes) && count($ingredientes) > 0) {
            $sql_insert_ingrediente = "
                INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) 
                VALUES (?, ?, ?, ?)
            ";
            $stmt_ingrediente = $conn->prepare($sql_insert_ingrediente);

            foreach ($ingredientes as $ingrediente) {
                $idIngrediente = $ingrediente['idIngrediente'];
                $quantidade = $ingrediente['quantidade'];
                $idMedida = $ingrediente['idMedida'];

                $stmt_ingrediente->bind_param("iiid", $idReceita, $idIngrediente, $idMedida, $quantidade);
                $stmt_ingrediente->execute();
            }
        }

        // Confirma a transação
        $conn->commit();
        echo "Receita atualizada com sucesso!";
        
        // Redireciona para a página de visualização da receita ou exibe uma mensagem de sucesso
        header("Location: " . BASE_URL . "receitas/Paginas/receitas/verReceitaIndividual.php?id=" . $idReceita);
        exit;

    } catch (Exception $e) {
        // Reverte a transação em caso de erro
        $conn->rollback();
        echo "Erro ao atualizar a receita: " . $e->getMessage();
    }
}
