<?php
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'] ?? '';

    try {
        switch ($form_type) {
            case 'receita':
                $idReceita = $_POST['id_receita'] ?? null;
                if (!$idReceita) {
                    echo "ID da receita não fornecido.";
                    exit;
                }

                $nome_rec = $_POST['nome_rec'];
                $data_criacao = $_POST['data_criacao'];
                $modo_preparo = $_POST['modo_preparo'];
                $num_porcao = $_POST['num_porcao'];
                $descricao = $_POST['descricao'];
                $inedita = $_POST['inedita'];
                $id_cozinheiro = $_POST['id_cozinheiro'];
                $id_categoria = $_POST['id_categoria'];
                $link_imagem = $_POST['link_imagem'] ?? null;
                $ingredientes = json_decode($_POST['ingredientes'], true);

                $arquivo_imagem = null;
                if (isset($_FILES['arquivo_imagem']) && $_FILES['arquivo_imagem']['error'] === UPLOAD_ERR_OK) {
                    $diretorioDestino = ROOT_PATH . "receitas/imagens/";
                    $extensao = strtolower(pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION));
                    $nomeArquivo = preg_replace('/[^\w-]/', '', $nome_rec) . "_" . uniqid() . "." . $extensao;
                    $arquivo_imagem = "receitas/imagens/" . $nomeArquivo;

                    if (!is_dir($diretorioDestino)) {
                        mkdir($diretorioDestino, 0777, true);
                    }

                    if (!move_uploaded_file($_FILES['arquivo_imagem']['tmp_name'], $diretorioDestino . $nomeArquivo)) {
                        echo "<script>alert('Erro ao fazer upload da imagem.'); window.history.back();</script>";
                        exit;
                    }
                }

                $conn->begin_transaction();
                $sql_update_receita = "
                    UPDATE receita 
                    SET nome_rec = ?, data_criacao = ?, modo_preparo = ?, num_porcao = ?, descricao = ?, 
                        inedita = ?, link_imagem = ?, arquivo_imagem = ?, idCozinheiro = ?, idCategoria = ?
                    WHERE idReceita = ?
                ";
                $stmt = $conn->prepare($sql_update_receita);

                $imagem_final = $arquivo_imagem ?? null;

                $stmt->bind_param(
                    "sssissssiii", 
                    $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, 
                    $inedita, $link_imagem, $imagem_final, $id_cozinheiro, $id_categoria, $idReceita
                );
                $stmt->execute();

                $sql_delete_ingredientes = "DELETE FROM receita_ingrediente WHERE idReceita = ?";
                $stmt_delete = $conn->prepare($sql_delete_ingredientes);
                $stmt_delete->bind_param("i", $idReceita);
                $stmt_delete->execute();

                if (is_array($ingredientes) && count($ingredientes) > 0) {
                    $sql_insert_ingrediente = "
                        INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) 
                        VALUES (?, ?, ?, ?)
                    ";
                    $stmt_ingrediente = $conn->prepare($sql_insert_ingrediente);

                    foreach ($ingredientes as $ingrediente) {
                        $idIngrediente = $ingrediente['idIngrediente'] ?? null;
                        $quantidade = $ingrediente['quantidade'] ?? null;
                        $idMedida = $ingrediente['idMedida'] ?? null;

                        if ($idIngrediente && $idMedida && $quantidade) {
                            $stmt_ingrediente->bind_param("iiid", $idReceita, $idIngrediente, $idMedida, $quantidade);
                            $stmt_ingrediente->execute();
                        }
                    }
                }

                $conn->commit();
                echo "<script>alert('Receita atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/receitas/verReceita.php';</script>";
                break;

            case 'ingrediente':
                
                $id_ingrediente = $_POST['id_ingrediente'] ?? null;
                $nome_ingrediente = $_POST['nome'];
                $descricao = $_POST['descricao'];

                if ($id_ingrediente) {
                    $sql_ingrediente = "UPDATE ingrediente SET nome = ?, descricao = ? WHERE idIngrediente = ?";
                    $stmt = $conn->prepare($sql_ingrediente);
                    $stmt->bind_param("ssi", $nome_ingrediente, $descricao, $id_ingrediente);
                } else {
                    $sql_ingrediente = "INSERT INTO ingrediente (nome, descricao) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql_ingrediente);
                    $stmt->bind_param("ss", $nome_ingrediente, $descricao);
                }
                $stmt->execute();
                echo "<script>alert('Ingrediente atualizado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/ingredientes/listaIngrediente.php';</script>";
                break;

            case 'medida':
                $id_medida = $_POST['id_medida'] ?? null;
                $nome_medida = $_POST['sistema'];

                if ($id_medida) {
                    $sql_medida = "UPDATE medida SET sistema = ? WHERE idMedida = ?";
                    $stmt = $conn->prepare($sql_medida);
                    $stmt->bind_param("si", $nome_medida, $id_medida);
                } else {   
                    $sql_medida = "INSERT INTO medida (sistema) VALUES (?)";
                    $stmt = $conn->prepare($sql_medida);
                    $stmt->bind_param("s", $nome_medida);
                }
                $stmt->execute();
                echo "<script>alert('Medida atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/medidas/listaMedida.php';</script>";
                break;

            default:
                echo "<script>alert('Tipo de formulário não reconhecido.'); window.history.back();</script>";
                break;
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Ocorreu um erro ao processar o formulário.'); window.history.back();</script>";
    } finally {
        // Fechando todas as declarações
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_delete)) $stmt_delete->close();
        if (isset($stmt_ingrediente)) $stmt_ingrediente->close();
        $conn->close();
    }
}
?>
