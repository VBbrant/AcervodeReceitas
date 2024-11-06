<?php
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'] ?? '';

    switch ($form_type) {
        case 'receita':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nome_rec = $_POST['nome_rec'];
                $data_criacao = $_POST['data_criacao'];
                $modo_preparo = $_POST['modo_preparo'];
                $num_porcao = $_POST['num_porcao'];
                $descricao = $_POST['descricao'];
                $inedita = $_POST['inedita'];
                $id_cozinheiro = $_POST['id_cozinheiro'];
                $id_categoria = $_POST['id_categoria'];
    
                $link_imagem = null;
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
                        echo "Erro ao fazer upload da imagem.";
                        exit;
                    }
                } elseif (!empty($_POST['link_imagem'])) {
                    $link_imagem = $_POST['link_imagem'];
                }
    
    
                $conn->begin_transaction();
    
                try {
                    $sql_receita = "INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem, arquivo_imagem, idCozinheiro, idCategoria) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql_receita);
                    $stmt->bind_param("sssissssii", $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, $inedita, $link_imagem, $arquivo_imagem, $id_cozinheiro, $id_categoria);
                    $stmt->execute();
                    
                    $id_receita = $conn->insert_id;
                    $ingredientes = isset($_POST['ingredientes']) ? json_decode($_POST['ingredientes'], true) : null;
    
                    // Verifique se há ingredientes para inserir
                    if (is_array($ingredientes) && count($ingredientes) > 0) {
                        $sql_ingrediente = "INSERT INTO receita_ingrediente (idReceita, idIngrediente, idMedida, quantidade) VALUES (?, ?, ?, ?)";
                        $stmt_ingrediente = $conn->prepare($sql_ingrediente);
    
                        foreach ($ingredientes as $ingrediente) {
                            $idIngrediente = $ingrediente['idIngrediente'];
                            $quantidade = $ingrediente['quantidade'];
                            $idMedida = $ingrediente['idMedida'];
    
                            $stmt_ingrediente->bind_param("iiid", $id_receita, $idIngrediente, $idMedida, $quantidade);
                            $stmt_ingrediente->execute();
                        }
                        
                        echo "Ingredientes adicionados com sucesso!";
                    } else {
                        echo "Nenhum ingrediente foi selecionado.";
                    }
    
    
                    $conn->commit();
                    echo "<script>alert('Receita adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/home.php';</script>";
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>";
                } finally {
                    $stmt->close();
                    if (isset($stmt_ingrediente)) {
                        $stmt_ingrediente->close();
                    
                    }
    
                }
            }
            break;
        
        case 'ingrediente' :
            $nome_ingrediente = $_POST['nome'];
            $descricao = $_POST['descricao'];

            $sql_ingrediente = "INSERT INTO ingrediente (nome, descricao) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_ingrediente);
            $stmt->bind_param("ss", $nome_ingrediente, $descricao);

            try {
                $stmt->execute();
                echo "<script>alert('Ingrediente adicionado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/ingredientes/listaIngrediente.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                $stmt->close();

            }
            break;
        case 'medida':
            $sistema = $_POST['nome_medida'];
            $sql_sistema = "INSERT INTO medida (sistema) VALUES (?)";
            $stmt = $conn->prepare($sql_sistema);
            $stmt->bind_param("s", $sistema);

            try{
                $stmt->execute();
                echo "<script>alert('Medida adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/medidas/listaMedida.php';</script>";
            } catch (Exception $e){
                echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>"; 
            } finally {
                $stmt->close();
            }
            break;
        default:
            echo "Tipo de formulário não encontrado";
            break;
    }
}
$conn->close();
?>
