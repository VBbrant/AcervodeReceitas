<?php session_start();
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";
$idUsuario = $_SESSION['idLogin'];

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
                    $diretorioDestino = ROOT_PATH . "receitas/imagens/receita/";
                    $extensao = strtolower(pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION));
                    $nomeArquivo = preg_replace('/[^\w-]/', '', $nome_rec) . "_" . uniqid() . "." . $extensao;
                    $arquivo_imagem = "receitas/imagens/receita/" . $nomeArquivo;

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
                    SET nome_rec = ?, modo_preparo = ?, num_porcao = ?, descricao = ?, 
                        inedita = ?, link_imagem = ?, arquivo_imagem = ?, idCozinheiro = ?, idCategoria = ?
                    WHERE idReceita = ?
                ";
                $stmt = $conn->prepare($sql_update_receita);

                $imagem_final = $arquivo_imagem ?? null;

                $stmt->bind_param(
                    "ssissssiii", 
                    $nome_rec, $modo_preparo, $num_porcao, $descricao, 
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
                registrarLog($conn, $idUsuario, "edicao", "Receita '$nome_rec' editada com sucesso!");
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
                registrarLog($conn, $idUsuario, "edicao", "Ingrediente '$nome_ingrediente' editada com sucesso!");
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
                registrarLog($conn, $idUsuario, "edicao", "Sistema '$nome_medida' editada com sucesso!");
                echo "<script>alert('Medida atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/medidas/listaMedida.php';</script>";
                break;

            case 'categoria':
                $id_categoria = $_POST["id_categoria"] ?? null;
                $categoria = $_POST["nome"];

                if($id_categoria){
                    $sql_categoria = "UPDATE categoria SET nome = ? WHERE idCategoria =?";
                    $stmt = $conn->prepare($sql_categoria);
                    $stmt->bind_param("si", $categoria, $id_categoria);
                } else{
                    $sql_categoria = "INSERT INTO categoria (nome) VALUES (?)";
                    $stmt = $conn->prepare($sql_categoria);
                    $stmt->bind_param("s", $categoria);
                }
                $stmt->execute();
                registrarLog($conn, $idUsuario, "edicao", "Categoria '$categoria' editada com sucesso!");
                echo "<script>alert('Categoria atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/categorias/listaCategoria.php';</script>";
                break;

            case 'livro':
                if ($_POST['form_type'] == 'livro') {
                    try {
                        $idLivro = $_POST['idLivro'];
                        $nome = $_POST['nome'];
                        $isbn = $_POST['codigo'];
                        $idEditor = $_POST['id_editor'];
                        $linkImagem = $_POST['link_imagem'] ?? null;
                        $arquivoImagem = $_FILES['arquivo_imagem'] ?? null;
                    
                        // Update image if there's an upload
                        $imagemCaminho = null;
                        if (!empty($arquivoImagem['name'])) {
                            $result_livro = $conn->query("SELECT arquivo_imagem FROM livro WHERE idLivro = $idLivro");
                            
                            if ($result_livro) {
                                $livro = $result_livro->fetch_assoc();
                                if ($livro && $livro['arquivo_imagem']) {
                                    unlink(ROOT_PATH . $livro['arquivo_imagem']);
                                }
                            }
            
                            $ext = pathinfo($arquivoImagem['name'], PATHINFO_EXTENSION);
                            $imagemCaminho = "receitas/imagens/livro/" . uniqid() . "." . $ext;
                            move_uploaded_file($arquivoImagem['tmp_name'], ROOT_PATH . $imagemCaminho);
                        }
                    
                        // Update book details
                        $sql_livro = "UPDATE livro SET titulo = ?, isbn = ?, idEditor = ?, link_imagem = ?, arquivo_imagem = ? WHERE idLivro = ?";
                        $stmt_livro = $conn->prepare($sql_livro);
            
                        if (!$stmt_livro) {
                            throw new Exception("Failed to prepare statement: " . $conn->error);
                        }
            
                        $stmt_livro->bind_param("ssissi", $nome, $isbn, $idEditor, $linkImagem, $imagemCaminho, $idLivro);
                        $stmt_livro->execute();
                        registrarLog($conn, $idUsuario, "edicao", "Livro '$nome' editado com sucesso!");
                        
                        if ($stmt_livro->error) {
                            throw new Exception("Execute failed: " . $stmt->error);
                        }
                    } catch (Exception $e) {
                        // Handle the error, log it, or display an error message
                        echo "An error occurred: " . $e->getMessage();
                    } finally {
                        // Clean up resources
                        if (isset($stmt_livro) && $stmt_livro !== false) {
                            $stmt_livro->close();
                        }
                        if (isset($result_livro) && $result_livro !== false) {
                            $result_livro->close();
                        }
                        if ($_SESSION['cargo'] === "Editor") {
                            header("Location: " . BASE_URL . "/receitas/Paginas/livros/meusLivros.php?sucesso=1");
                            exit(); // Certifique-se de sair após o redirecionamento
                        } else{
                            header("Location: " . BASE_URL . "receitas/Paginas/livros/listaLivro.php?sucesso=1");
                            exit();
                        }
                    }
                }
                break;
                
            
            case 'avaliacao':
                $idAvaliacao = $_POST['idAvaliacao'] ?? null;
                $idReceita = $_POST['idReceita'] ?? null;
                $notaDegustacao = $_POST['avaliacao'] ?? null;
                $comentarioTexto = $_POST['comentario_texto'] ?? null;
            
                if (!$idAvaliacao || !$idReceita || $notaDegustacao === null) {
                    echo "<script>alert('Dados incompletos!'); window.history.back();</script>";
                    exit;
                }
                $conn->begin_transaction();
                try {
                    // Recuperar o nome da receita
                    $sql_receita = "SELECT nome_rec FROM receita WHERE idReceita = ?";
                    $stmt_receita = $conn->prepare($sql_receita);
                    $stmt_receita->bind_param("i", $idReceita);
                    $stmt_receita->execute();
                    $stmt_receita->bind_result($nome_receita);
                    $stmt_receita->fetch();
                    $stmt_receita->close();
            
                    $sql_update_degustacao = "
                        UPDATE degustacao 
                        SET nota_degustacao = ?, idReceita = ?
                        WHERE idDegustacao = ?
                    ";
                    $stmt_degustacao = $conn->prepare($sql_update_degustacao);
                    $stmt_degustacao->bind_param("dii", $notaDegustacao, $idReceita, $idAvaliacao);
                    $stmt_degustacao->execute();
            
                    $sql_check_comentario = "SELECT idComentario FROM comentario WHERE idDegustacao = ?";
                    $stmt_check = $conn->prepare($sql_check_comentario);
                    $stmt_check->bind_param("i", $idAvaliacao);
                    $stmt_check->execute();
                    $result_check = $stmt_check->get_result();
            
                    if ($result_check->num_rows > 0) {
                        $sql_update_comentario = "UPDATE comentario SET comentario_texto = ? WHERE idDegustacao = ?";
                        $stmt_comentario = $conn->prepare($sql_update_comentario);
                        $stmt_comentario->bind_param("si", $comentarioTexto, $idAvaliacao);
                    } else {
                        $sql_insert_comentario = "INSERT INTO comentario (idDegustacao, comentario_texto) VALUES (?, ?)";
                        $stmt_comentario = $conn->prepare($sql_insert_comentario);
                        $stmt_comentario->bind_param("is", $idAvaliacao, $comentarioTexto);
                    }
                    $stmt_comentario->execute();
            
                    $conn->commit();
                    registrarLog($conn, $idUsuario, "edicao", "Avaliação da receita '$nome_receita' editada com sucesso!");
                    echo "<script>alert('Avaliação atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/avaliacoes/listaAvaliacao.php';</script>";
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<script>alert('Erro ao atualizar a avaliação: " . $e->getMessage() . "'); window.history.back();</script>";
                } finally {
                    $stmt_degustacao->close();
                    if (isset($stmt_comentario)) $stmt_comentario->close();
                    $stmt_check->close();
                }
                break;
            
            case 'funcionario':
                $idFuncionario = $_POST['idFun'] ?? null;
                $nome = $_POST['nome'] ?? null;
                $rg = $_POST['rg'] ?? null;
                $data_nascimento = $_POST['data_nascimento'] ?? null;
                $data_admissao = $_POST['data_admissao'] ?? null;
                $salario = $_POST['salario'] ?? null;
                $nome_fantasia = $_POST['nome_fantasia'] ?? null;
                $idLogin = $_POST['idLogin'] ?? null;
                $idCargo = $_POST['idCargo'] ?? null;

                if (!$idFuncionario || !$nome || !$idCargo) {
                    echo "<script>alert('Dados incompletos para atualização do funcionário!'); window.history.back();</script>";
                    exit;
                }
        
                try {
                    $conn->begin_transaction();
        
                    $sql_update_funcionario = "UPDATE funcionario 
                        SET nome = ?, rg = ?, data_nascimento = ?, data_admissao = ?, salario = ?, nome_fantasia = ?, idLogin = ?, idCargo = ?
                        WHERE idFun = ?
                    ";
                    $stmt_funcionario = $conn->prepare($sql_update_funcionario);
                    $stmt_funcionario->bind_param("ssssdsiii", $nome, $rg, $data_nascimento, $data_admissao, $salario, $nome_fantasia, $idLogin, $idCargo, $idFuncionario);
                    $stmt_funcionario->execute();
        
                    $conn->commit();
                    registrarLog($conn, $idUsuario, "edicao", "Funcionario '$nome' editado(a) com sucesso!");
                    echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/funcionarios/listaFuncionario.php';</script>";
                } catch (Exception $e) {
                    var_dump($idFuncionario,$idCargo);
                    error_log(print_r($_POST, true));
                        error_log("Erro ao atualizar o funcionário: " . $e->getMessage());
                        $conn->rollback();
                        
                    
                } finally {
                    if (isset($stmt_funcionario)) {
                        $stmt_funcionario->close();
                    }
                }
                break;
            
            case 'meta':
                try {
                    $idMeta = $_POST['idMeta'];
                    $idCozinheiro = $_POST['idCozinheiro'];
                    $metaReceitas = $_POST['metaReceitas'];
                    $dataInicio = $_POST['dataInicio'];
                    $dataFinal = $_POST['dataFinal'];

                    // Recuperar o nome do cozinheiro
                    $sql_cozinheiro = "SELECT nome FROM funcionario WHERE idFun = ?";
                    $stmt_cozinheiro = $conn->prepare($sql_cozinheiro);
                    $stmt_cozinheiro->bind_param("i", $idCozinheiro);
                    $stmt_cozinheiro->execute();
                    $stmt_cozinheiro->bind_result($nome_cozinheiro);
                    $stmt_cozinheiro->fetch();
                    $stmt_cozinheiro->close();
    
                    $sql = "UPDATE Metas SET idCozinheiro = ?, metaReceitas = ?, dataInicio = ?, dataFinal = ? WHERE idMeta = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iissi", $idCozinheiro, $metaReceitas, $dataInicio, $dataFinal, $idMeta);
                    $stmt->execute();
                    registrarLog($conn, $idUsuario, "edicao", "Receita '$nome_cozinheiro' editada com sucesso!");

                    header("Location: ../Paginas/parametros/metas/listaMeta.php");
                    exit;
                } catch (Exception $e) {
                    echo "Erro ao editar meta: " . $e->getMessage();
                } finally {
                    $stmt->close();
                    $conn->close();
                }
                break;
        
            
                
            default:
                echo "<script>alert('Tipo de formulário não reconhecido.'); window.history.back();</script>";
                break;
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Ocorreu um erro ao processar o formulário.'); window.history.back();</script>";
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_delete)) $stmt_delete->close();
        if (isset($stmt_ingrediente)) $stmt_ingrediente->close();
        $conn->close();
    }
}
function registrarLog($conn, $idUsuario, $tipo, $descricao) {
    $sql_log = "INSERT INTO log_sistema (idUsuario, tipo_acao, acao, data) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);

    if ($stmt_log === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt_log->bind_param("iss", $idUsuario, $tipo, $descricao);

    if (!$stmt_log->execute()) {
        die('Erro ao executar a consulta: ' . $stmt_log->error);
    }

    $stmt_log->close();
}



?>
