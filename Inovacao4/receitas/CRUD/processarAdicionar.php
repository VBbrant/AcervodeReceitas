<?php
require_once "../../config.php";
require_once ROOT_PATH . "receitas/conn.php";


// Receita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome_rec'], $_POST['data_criacao'], $_POST['modo_preparo'])) {

        $nome_rec = $_POST['nome_rec'];
        $data_criacao = $_POST['data_criacao'];
        $modo_preparo = $_POST['modo_preparo'];
        $num_porcao = $_POST['num_porcao'];
        $descricao = $_POST['descricao'];
        $inedita = $_POST['inedita'];
        $id_cozinheiro = $_POST['id_cozinheiro'];
        $link_imagem = $_POST['link_imagem'];

        $caminho_imagem = null;

        // Verifica se um arquivo de imagem foi enviado
        if (!empty($_FILES['arquivo_imagem']['name'])) {
            $diretorioDestino = ROOT_PATH . "receitas/imagens/";
            $extensao = pathinfo($_FILES['arquivo_imagem']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = $nome_rec . "." . $extensao;
            $caminho_imagem = "receitas/imagens/" . $nomeArquivo;

            if (!move_uploaded_file($_FILES['arquivo_imagem']['tmp_name'], $diretorioDestino . $nomeArquivo)) {
                echo "Erro ao fazer upload da imagem.";
                exit;
            }
        } elseif (!empty($link_imagem)) {
            $caminho_imagem = $link_imagem;
        }

        $conn->begin_transaction();

        try {
            $sql_receita = "INSERT INTO receita (nome_rec, data_criacao, modo_preparo, num_porcao, descricao, inedita, link_imagem, idCozinheiro) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_receita);
            $stmt->bind_param("sssssssi", $nome_rec, $data_criacao, $modo_preparo, $num_porcao, $descricao, $inedita, $caminho_imagem, $id_cozinheiro);
            $stmt->execute();

            $conn->commit();
            echo "Receita adicionada com sucesso!";
            header("Location: ". BASE_URL . "receitas/Paginas/home.php");
        } catch (Exception $e) {
            $conn->rollback();
            echo "Erro ao adicionar a receita: " . $e->getMessage();
        } finally {
            $stmt->close();
        }

    }
    //Funcionario
     elseif (isset($_POST['id-funcionario'], $_POST['nome-funcionario'], $_POST['rg'])) {
        $id_funcionario = $_POST['id-funcionario'];
        $nome_funcionario = $_POST['nome-funcionario'];
        $rg = $_POST['rg'];
        $data_admissao = $_POST['data-admissao'];
        $salario = $_POST['salario'];
        $cargo = $_POST['cargo'];
        $nome_fantasia = $_POST['nome-fantasia'];

        echo "<script>alert('Funcionário incluído com sucesso!');</script>";

    } else {
        echo "Erro: Formulário inválido ou campos ausentes.";
    }
}

$conn->close();

?>
