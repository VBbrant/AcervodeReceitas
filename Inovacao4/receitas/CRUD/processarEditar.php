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
                $idFun = $_POST['idFun'];
                $nome = $_POST['nome'];
                $rg = $_POST['rg'];
                $data_nascimento = $_POST['data_nascimento'];
                $data_admissao = $_POST['data_admissao'];
                $salario = $_POST['salario'];
                $nome_fantasia = $_POST['nome_fantasia'];
                $telefone = $_POST['telefone'];
                $email = $_POST['email'];
                $idCargo = $_POST['idCargo'];
                $idRestaurante = $_POST['idRestaurante'];
                $idLogin = $_POST['idLogin'];

                try {
                    // Inicia a transação para garantir atomicidade
                    $conn->begin_transaction();

                    // Atualizar os dados do funcionário
                    $sql_funcionario = "UPDATE funcionario SET nome = ?, rg = ?, data_nascimento = ?, data_admissao = ?, salario = ?, nome_fantasia = ?, telefone = ?, email = ?, idCargo = ?, idRestaurante = ? WHERE idFun = ?";
                    $stmt = $conn->prepare($sql_funcionario);
                    $stmt->bind_param("ssssdsdssii", $nome, $rg, $data_nascimento, $data_admissao, $salario, $nome_fantasia, $telefone, $email, $idCargo, $idRestaurante, $idFun);
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao atualizar dados do funcionário.");
                    }

                    // Verificar se o e-mail foi alterado e atualizar o e-mail do usuário associado
                    $sql_usuario = "SELECT email FROM usuario WHERE idLogin = ?";
                    $stmt_usuario = $conn->prepare($sql_usuario);
                    $stmt_usuario->bind_param("i", $idLogin);
                    $stmt_usuario->execute();
                    $result = $stmt_usuario->get_result();
                    $usuario = $result->fetch_assoc();

                    // Se o e-mail do funcionário for diferente do e-mail do usuário, atualize o e-mail do usuário
                    if ($usuario['email'] !== $email) {
                        $sql_update_usuario = "UPDATE usuario SET email = ? WHERE idLogin = ?";
                        $stmt_update_usuario = $conn->prepare($sql_update_usuario);
                        $stmt_update_usuario->bind_param("si", $email, $idLogin);
                        if (!$stmt_update_usuario->execute()) {
                            throw new Exception("Erro ao atualizar e-mail do usuário.");
                        }
                    }

                    // Commit das transações
                    $conn->commit();

                    // Redirecionar ou mostrar mensagem de sucesso
                    echo "<script>window.location.href='" . BASE_URL . "receitas/Paginas/funcionarios/listaFuncionario.php';</script>";
                } catch (Exception $e) {
                    // Caso ocorra algum erro, faz o rollback
                    $conn->rollback();
                    echo "Erro: " . $e->getMessage();
                } finally {
                    // Fechar conexões e liberar recursos
                    $stmt_usuario->close();
                    if (isset($stmt_update_usuario)) {
                        $stmt_update_usuario->close();
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
            
            case 'restaurante':
                try {
                    $idRestaurante = $_POST['idRestaurante'];
                    $nome = $_POST['nome'];
                    $telefone = $_POST['telefone'];
                    $endereco = $_POST['endereco'];
        
                    // Atualizar o restaurante
                    $sql = "UPDATE restaurante SET nome = ?, telefone = ?, endereco = ? WHERE idRestaurante = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $nome, $telefone, $endereco, $idRestaurante);
                    $stmt->execute();
        
                    // Registrar log de edição
                    registrarLog($conn, $idUsuario, "edicao", "Restaurante '$nome' editado com sucesso!");
        
                    header("Location: ../Paginas/restaurantes/listaRestaurante.php");
                    exit;
                } catch (Exception $e) {
                    echo "Erro ao editar restaurante: " . $e->getMessage();
                } finally {
                    $stmt->close();
                    $conn->close();
                }
                break;
            case 'categoria':
                $id_categoria = $_POST["id_cargo"] ?? null;
                $categoria = $_POST["nome"];

                if($id_categoria){
                    $sql_categoria = "UPDATE cargo SET nome = ? WHERE idCargo =?";
                    $stmt = $conn->prepare($sql_categoria);
                    $stmt->bind_param("si", $categoria, $id_categoria);
                } else{
                    $sql_categoria = "INSERT INTO cargo (nome) VALUES (?)";
                    $stmt = $conn->prepare($sql_categoria);
                    $stmt->bind_param("s", $categoria);
                }
                $stmt->execute();
                registrarLog($conn, $idUsuario, "edicao", "Cargo '$categoria' editada com sucesso!");
                echo "<script>alert('Cargo atualizada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/cargos/listaCargo.php';</script>";
                break; 
            case 'usuario':
                $idLogin = $_POST['idLogin']; // ID do usuário a ser editado
                $nome_usuario = $_POST['nome'];
                $email = $_POST['email'];
            
                // Verifica se foi enviado uma nova senha
                if (!empty($_POST['senha'])) {
                    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);  // Criptografa a nova senha
                } else {
                    // Mantém a senha atual se o campo estiver vazio
                    $sql_current_password = "SELECT senha FROM usuario WHERE idLogin = ?";
                    $stmt = $conn->prepare($sql_current_password);
                    $stmt->bind_param("i", $idLogin);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $senha = $row['senha'];  // Mantém a senha atual
                    $stmt->close();
                }
            
                // Verifica se foi enviado uma nova imagem de perfil
                if (isset($_FILES['imagem_perfil']) && $_FILES['imagem_perfil']['error'] === UPLOAD_ERR_OK) {
                    $imagem_perfil = $_FILES['imagem_perfil']['name'];
                    $target_dir = ROOT_PATH . 'receitas/imagens/perfil/';
                    $target_file = $target_dir . basename($imagem_perfil);
                    
                    // Verifica o tipo de arquivo e move para o diretório de uploads
                    if (move_uploaded_file($_FILES['imagem_perfil']['tmp_name'], $target_file)) {
                        // Sucesso no upload
                    } else {
                        echo "<script>alert('Erro ao fazer upload da imagem.'); window.history.back();</script>";
                        exit;
                    }
                } else {
                    // Mantém a imagem atual se o campo estiver vazio
                    $sql_current_image = "SELECT imagem_perfil FROM usuario WHERE idLogin = ?";
                    $stmt = $conn->prepare($sql_current_image);
                    $stmt->bind_param("i", $idLogin);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $imagem_perfil = $row['imagem_perfil'];  // Mantém a imagem atual
                    $stmt->close();
                }
            
                $conn->begin_transaction();  // Inicia a transação
            
                try {
                    // Atualiza os dados do usuário
                    $sql_usuario = "UPDATE usuario SET nome = ?, email = ?, senha = ?, imagem_perfil = ? WHERE idLogin = ?";
                    $stmt = $conn->prepare($sql_usuario);
                    $stmt->bind_param("ssssi", $nome_usuario, $email, $senha, $imagem_perfil, $idLogin);
                    $stmt->execute();
                    $stmt->close();
            
                    $conn->commit();  // Confirma a transação
            
                    echo "<script>alert('Usuário atualizado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/usuarios/listaUsuario.php?idLogin=" . $idLogin . "';</script>";
                } catch (Exception $e) {
                    $conn->rollback();  // Reverte a transação em caso de erro
                    echo "<script>alert('Erro ao atualizar usuário: " . $e->getMessage() . "'); window.history.back();</script>";
                } finally {
                    if (isset($stmt) && $stmt !== null) {
                        $stmt->close();
                    }
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
