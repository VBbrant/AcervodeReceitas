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
                    $diretorioDestino = ROOT_PATH . "receitas/imagens/receita/";
                    $extensao = strtolower(pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION));
                    
                    $nomeArquivo = preg_replace('/[^\w-]/', '', $nome_rec) . "_" . uniqid() . "." . $extensao;
                    $arquivo_imagem = "receitas/imagens/receita/" . $nomeArquivo;
    
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

        case 'categoria':
            $categoria = $_POST['nome'];
            $sql_categoria = "INSERT INTO categoria (nome) VALUES (?)";
            $stmt = $conn->prepare($sql_categoria);
            $stmt->bind_param("s", $categoria);

            try{
                $stmt->execute();
                echo "<script>alert('Medida adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/categorias/listaCategoria.php';</script>";
            } catch (Exception $e){
                echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>"; 
            } finally {
                $stmt->close();
            }
            break;
        case 'livro':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $titulo = $_POST['nome'];
                $isbn = $_POST['codigo'];
                $id_editor = $_POST['id_editor'];
                
                $link_imagem = null;
                $arquivo_imagem = null;
        
                // Verifica se há um upload de imagem ou um link de imagem
                if (isset($_FILES['arquivo_imagem']) && $_FILES['arquivo_imagem']['error'] === UPLOAD_ERR_OK) {
                    $diretorioDestino = ROOT_PATH . "livros/imagens/livro/";
                    $extensao = strtolower(pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION));
                    
                    $nomeArquivo = preg_replace('/[^\w-]/', '', $titulo) . "_" . uniqid() . "." . $extensao;
                    $arquivo_imagem = "livros/imagens/livro/" . $nomeArquivo;
        
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
                    // Insere o livro na tabela `livro`
                    $sql_livro = "INSERT INTO livro (titulo, isbn, idEditor, link_imagem, arquivo_imagem) 
                                    VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql_livro);
                    $stmt->bind_param("ssiss", $titulo, $isbn, $id_editor, $link_imagem, $arquivo_imagem);
                    $stmt->execute();
                    
                    $id_livro = $conn->insert_id;
        
                    // Processa as receitas associadas ao livro
                    $receitas = isset($_POST['idReceita']) ? explode(',', $_POST['idReceita']) : [];
        
                    if (count($receitas) > 0) {
                        $sql_livro_receita = "INSERT INTO livro_receita (idLivro, idReceita) VALUES (?, ?)";
                        $stmt_livro_receita = $conn->prepare($sql_livro_receita);
        
                        foreach ($receitas as $idReceita) {
                            $stmt_livro_receita->bind_param("ii", $id_livro, $idReceita);
                            $stmt_livro_receita->execute();
                        }
        
                        echo "Receitas associadas ao livro com sucesso!";
                    } else {
                        echo "Nenhuma receita foi associada.";
                    }
        
                    $conn->commit();
                    echo "<script>alert('Livro adicionado com sucesso!'); window.location.href='" . BASE_URL . "livros/Paginas/home.php';</script>";
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>";
                } finally {
                    $stmt->close();
                    if (isset($stmt_livro_receita)) {
                        $stmt_livro_receita->close();
                    }
                }
            }
            break;
            
            
            
        case 'avaliacao':
            $idReceita = $_POST['idReceita'] ?? null;
            $notaDegustacao = $_POST['nota_degustacao'] ?? null;
            $comentarioTexto = $_POST['comentario_texto'] ?? null;
            $dataDegustacao = date("Y-m-d");
            $idDegustador = 8; // Ajuste para um ID válido ou obtenha-o da sessão
            
            if (!$idReceita || $notaDegustacao === null || !$idDegustador) {
                echo "<script>alert('Dados incompletos ou degustador inválido!'); window.history.back();</script>";
                exit;
            }
            
            $conn->begin_transaction();
            
            try {
                $sql_degustacao = "INSERT INTO degustacao (data_degustacao, nota_degustacao, idDegustador, idReceita) VALUES (?, ?, ?, ?)";
                $stmt_degustacao = $conn->prepare($sql_degustacao);
                $stmt_degustacao->bind_param("sdii", $dataDegustacao, $notaDegustacao, $idDegustador, $idReceita);
                $stmt_degustacao->execute();
                
                $idDegustacao = $stmt_degustacao->insert_id;
            
                if ($comentarioTexto) {
                    $sql_comentario = "INSERT INTO comentario (idDegustacao, comentario_texto) VALUES (?, ?)";
                    $stmt_comentario = $conn->prepare($sql_comentario);
                    $stmt_comentario->bind_param("is", $idDegustacao, $comentarioTexto);
                    $stmt_comentario->execute();
                    $stmt_comentario->close();
                }
            
                $conn->commit();
            
                echo "<script>alert('Degustação adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/avaliacoes/listaAvaliacao.php';</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Erro ao processar a degustação: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                $stmt_degustacao->close();
            }
            
            break;
        case 'funcionario':
            $nome = $_POST['nome'] ?? null;
            $rg = $_POST['rg'] ?? null;
            $data_nascimento = $_POST['data_nascimento'] ?? null;
            $data_admissao = $_POST['data_admissao'] ?? null;
            $salario = $_POST['salario'] ?? null;
            $nome_fantasia = $_POST['nome_fantasia'] ?? null;
            $idLogin = $_POST['idLogin'] ?? null;
            $idCargo = $_POST['idCargo'] ?? null;
    
            if (!$nome) {
                echo "<script>alert('Nome do funcionário é obrigatório!'); window.history.back();</script>";
                exit;
            }
    
            $conn->begin_transaction();
    
            try {
                if ($idLogin) {
                    // Associa o funcionário a um usuário existente
                    $sql_funcionario = "INSERT INTO funcionario (nome, rg, data_nascimento, data_admissao, salario, nome_fantasia, idLogin, idCargo) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_funcionario = $conn->prepare($sql_funcionario);
                    $stmt_funcionario->bind_param("ssssdsii", $nome, $rg, $data_nascimento, $data_admissao, $salario, $nome_fantasia, $idLogin, $idCargo);
                    $stmt_funcionario->execute();
                } else {
                    // Gerar link de registro
                    $token = bin2hex(random_bytes(16)); // Gera um token único para o link
                    $link_registro = BASE_URL ."receitas/Paginas/registro.php?token=$token";
                    
                    // Armazenar o token temporariamente
                    $sql_token = "INSERT INTO registro_tokens (token, nome, rg, data_nascimento, data_admissao, salario, nome_fantasia, idCargo) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_token = $conn->prepare($sql_token);
                    $stmt_token->bind_param("sssssdsi", $token, $nome, $rg, $data_nascimento, $data_admissao, $salario, $nome_fantasia, $idCargo);
                    $stmt_token->execute();
    
                    echo "Link de registro gerado: <a href='$link_registro'>$link_registro</a>";
                }
    
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Erro ao adicionar funcionário: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                $stmt_close = $stmt_funcionario ?? $stmt_token;
                $stmt_close->close();
            }
            break;
            

        default:
            echo "Tipo de formulário não encontrado";
            break;
    }
}
$conn->close();
?>
