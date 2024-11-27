<?php session_start();
$idUsuario = $_SESSION['idLogin'];

require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'] ?? '';

    switch ($form_type) {
        case 'receita':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nome_rec = $_POST['nome_rec'];
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
                    // Inserir a receita
                    $sql_receita = "INSERT INTO receita (nome_rec, modo_preparo, num_porcao, descricao, inedita, link_imagem, arquivo_imagem, idCozinheiro, idCategoria) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql_receita);
                    $stmt->bind_param("ssissssii", $nome_rec, $modo_preparo, $num_porcao, $descricao, $inedita, $link_imagem, $arquivo_imagem, $id_cozinheiro, $id_categoria);
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
        
                    // Atualizar as metas
                    $metaUpdateSql = "UPDATE Metas 
                        SET receitasAtingidas = receitasAtingidas + 1 
                        WHERE idCozinheiro = ? 
                        AND receitasAtingidas < metaReceitas";
        
                    $stmt = $conn->prepare($metaUpdateSql);
                    $stmt->bind_param("i", $id_cozinheiro);
                    $stmt->execute();
        
                    $conn->commit();
                    registrarLog($conn, $idUsuario, "inclusao", "Receita '$nome_rec' adicionada com sucesso!");
                    echo "<script>alert('Receita adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/home.php';</script>";
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>";
                } finally {
                    if (isset($stmt)) {
                        $stmt->close();
                    }
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
                registrarLog($conn, $idUsuario, "inclusao", "Ingrediente '$nome_ingrediente' adicionado com sucesso!");
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
                registrarLog($conn, $idUsuario, "inclusao", "Medida '$sistema' adicionada com sucesso!");
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
                registrarLog($conn, $idUsuario, "inclusao", "Categoria '$categoria' adicionada com sucesso!");
                echo "<script>alert('Medida adicionada com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/categorias/listaCategoria.php';</script>";
            } catch (Exception $e){
                echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>"; 
            } finally {
                $stmt->close();
            }
            break;
        case 'livro':
            if ($_POST['form_type'] == 'livro') {
                $nome = $_POST['nome'];
                $isbn = $_POST['codigo'];
                $idEditor = $_POST['id_editor'];
                $linkImagem = $_POST['link_imagem'] ?? null;
                $arquivoImagem = $_FILES['arquivo_imagem'] ?? null;
            
                // Caminho para armazenar a imagem se for feita a escolha por upload
                $imagemCaminho = null;
                if (!empty($arquivoImagem['name'])) {
                    $ext = pathinfo($arquivoImagem['name'], PATHINFO_EXTENSION);
                    $imagemCaminho = "receitas/imagens/livro/". uniqid() . "." . $ext;
                    move_uploaded_file($arquivoImagem['tmp_name'], ROOT_PATH . $imagemCaminho);
                }
            
                // Salvar no banco de dados
                $sql = "INSERT INTO livro (titulo, isbn, idEditor, link_imagem, arquivo_imagem) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiss", $nome, $isbn, $idEditor, $linkImagem, $imagemCaminho);
                $stmt->execute();
                
                // Associar receitas
                $idLivro = $conn->insert_id;
                if (!empty($_POST['idReceita'])) {
                    foreach (explode(',', $_POST['idReceita']) as $idReceita) {
                        $conn->query("INSERT INTO livro_receita (idLivro, idReceita) VALUES ($idLivro, $idReceita)");
                    }
                }
            
                registrarLog($conn, $idUsuario, "inclusao", "Livro '$nome' adicionado com sucesso!");

                if ($_SESSION['cargo'] === "Editor") {
                    header("Location: " . BASE_URL . "/receitas/Paginas/livros/meusLivros.php?sucesso=1");
                    exit(); // Certifique-se de sair após o redirecionamento
                } else{
                    header("Location: " . BASE_URL . "receitas/Paginas/livros/listaLivro.php?sucesso=1");
                    exit();
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
                // Recuperar o nome da receita
                $sql_receita = "SELECT nome_rec FROM receita WHERE idReceita = ?";
                $stmt_receita = $conn->prepare($sql_receita);
                $stmt_receita->bind_param("i", $idReceita);
                $stmt_receita->execute();
                $stmt_receita->bind_result($nome_receita);
                $stmt_receita->fetch();
                $stmt_receita->close();
                
                // Inserir a avaliação
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
                
                // Registrar log
                registrarLog($conn, $idUsuario, "inclusao", "Avaliação para a receita '$nome_receita' adicionada com sucesso!");
                
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
                registrarLog($conn, $idUsuario, "inclusao", "Funcionário '$nome' adicionado com sucesso!");
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Erro ao adicionar funcionário: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                $stmt_close = $stmt_funcionario ?? $stmt_token;
                $stmt_close->close();
            }
            break;
        case 'meta':
            try {
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
        
                if (!$nome_cozinheiro) {
                    echo "<script>alert('Cozinheiro não encontrado!'); window.history.back();</script>";
                    exit;
                }
        
                // Inserir a meta
                $sql = "INSERT INTO Metas (idCozinheiro, metaReceitas, dataInicio, dataFinal) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiss", $idCozinheiro, $metaReceitas, $dataInicio, $dataFinal);
                $stmt->execute();
        
                // Registrar log
                registrarLog($conn, $idUsuario, "inclusao", "Meta de receitas para o cozinheiro '$nome_cozinheiro' definida com sucesso!");
        
                header("Location: ../Paginas/parametros/metas/listaMeta.php");
                exit;
            } catch (Exception $e) {
                echo "Erro ao adicionar meta: " . $e->getMessage();
            } finally {
                $stmt->close();
                $conn->close();
            }
            break;
        case 'restaurante':
            try {
                $nome = $_POST['nome'];
                $telefone = $_POST['telefone'];
                $endereco = $_POST['endereco'];
    
                // Inserir o restaurante
                $sql = "INSERT INTO restaurante (nome, telefone, endereco) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nome, $telefone, $endereco);
                $stmt->execute();
    
                // Registrar log de inclusão
                registrarLog($conn, $idUsuario, "inclusao", "Restaurante '$nome' adicionado com sucesso!");
    
                header("Location: ../Paginas/restaurantes/listaRestaurante.php");
                exit;
            } catch (Exception $e) {
                echo "Erro ao adicionar restaurante: " . $e->getMessage();
            } finally {
                $stmt->close();
                $conn->close();
            }
            break;    
            
        case 'cargo':
            $categoria = $_POST['nome'];
            $sql_categoria = "INSERT INTO cargo (nome) VALUES (?)";
            $stmt = $conn->prepare($sql_categoria);
            $stmt->bind_param("s", $categoria);

            try{
                $stmt->execute();
                registrarLog($conn, $idUsuario, "inclusao", "Cargo '$categoria' adicionado com sucesso!");
                echo "<script>alert('Cargo adicionado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/cargos/listaCargo.php';</script>";
            } catch (Exception $e){
                echo "<script>alert('Erro ao processar o formulário: " . $e->getMessage() . "'); window.history.back();</script>"; 
            } finally {
                $stmt->close();
            }
            break;
        case 'usuario':
            $nome_usuario = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);  // Criptografando a senha
            $imagem_perfil = null;  // Inicializando a variável para a imagem de perfil (pode ser alterado para permitir upload de imagem)
        
            // Verifica se foi enviado uma imagem de perfil
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
            }
        
            $conn->begin_transaction();  // Inicia a transação para garantir que todos os dados sejam salvos corretamente
        
            try {
                // Verifica se já existe um usuário com o mesmo email
                $sql_check_email = "SELECT * FROM usuario WHERE email = ?";
                $stmt = $conn->prepare($sql_check_email);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    throw new Exception("Email já está cadastrado.");
                }
        
                // Insere o novo usuário na tabela usuario
                $sql_usuario = "INSERT INTO usuario (nome, email, senha, imagem_perfil) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_usuario);
                $stmt->bind_param("ssss", $nome_usuario, $email, $senha, $imagem_perfil);
                $stmt->execute();
                
                $idLogin = $stmt->insert_id;  // Obtém o ID do usuário inserido
                $stmt->close();
        
                $conn->commit();  // Confirma a transação
                
                echo "<script>alert('Usuário adicionado com sucesso!'); window.location.href='" . BASE_URL . "receitas/Paginas/usuarios/listaUsuario.php';</script>";
            } catch (Exception $e) {
                $conn->rollback();  // Reverte as alterações em caso de erro
                echo "<script>alert('Erro ao adicionar usuário: " . $e->getMessage() . "'); window.history.back();</script>";
            } finally {
                if (isset($stmt) && $stmt !== null) {
                    $stmt->close();
                }
            }
            break;
        default:
            echo "Tipo de formulário não encontrado";
            break;
    }
}

function registrarLog($conn, $idUsuario, $tipo, $descricao) {
    $sql_log = "INSERT INTO log_sistema (idUsuario, tipo_acao, acao, data) VALUES (?, ?, ?, NOW())";
    $stmt_log = $conn->prepare($sql_log);
    
    // Verificar se a preparação foi bem-sucedida
    if ($stmt_log === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    
    $stmt_log->bind_param("iss", $idUsuario, $tipo, $descricao);
    

    if (!$stmt_log->execute()) {
        die('Erro ao executar a consulta: ' . $stmt_log->error);
    }

    // Fechar a declaração
    $stmt_log->close();
}




$conn->close();
?>
