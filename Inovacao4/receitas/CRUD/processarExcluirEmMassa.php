<?php
session_start();
require_once '../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';
$idUsuario = $_SESSION['idLogin'];

$type = $_POST['type'] ?? null;
$itensSelecionados = $_POST['itensSelecionados'] ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $type && count($itensSelecionados) > 0) {
    $conn->begin_transaction();

    try {
        // Variável para armazenar os nomes dos itens para o log
        $nomesItens = [];

        // Preparar a declaração para o tipo de exclusão
        switch ($type) {
            case 'ingrediente':
                $stmt = $conn->prepare("DELETE FROM ingrediente WHERE idIngrediente = ?");
                break;
            case 'avaliacao':
                $stmt = $conn->prepare("DELETE FROM Degustacao WHERE idDegustacao = ?");
                break;
            case 'receita':
                $stmt = $conn->prepare("DELETE FROM receita WHERE idReceita = ?");
                break;
            case 'medida':
                $stmt = $conn->prepare("DELETE FROM medida WHERE idMedida = ?");
                break;
            case 'meta':
                $stmt = $conn->prepare("DELETE FROM metas WHERE idMeta = ?");
                break;
            case 'livro':
                $stmt = $conn->prepare("DELETE FROM livro WHERE idLivro = ?");
                break;
            case 'categoria':
                $stmt = $conn->prepare("DELETE FROM categoria WHERE idCategoria = ?");
                break;
            case 'usuario':
                $stmt = $conn->prepare("DELETE FROM usuario WHERE idLogin = ?");
                break;
            case 'funcionario':
                $stmt = $conn->prepare("DELETE FROM funcionario WHERE idFun = ?");
                break;
            case 'restaurante':
                $stmt = $conn->prepare("DELETE FROM restaurante WHERE idRestaurante = ?");
                break;
            case 'categoria':
                $stmt = $conn->prepare("DELETE FROM cargo WHERE idCargo = ?");
                break;
            default:
                throw new Exception("Tipo inválido para exclusão.");
        }

        foreach ($itensSelecionados as $id) {
            // Buscar o nome do item dependendo do tipo
            $nomeItem = '';
            if ($type == 'ingrediente') {
                $sql_nome = "SELECT nome FROM ingrediente WHERE idIngrediente = ?";
            } elseif ($type == 'avaliacao') {
                $sql_nome = "SELECT f.nome FROM degustacao d 
                             JOIN funcionario f ON d.idDegustador = f.idFun 
                             WHERE d.idDegustacao = ?";
            } elseif ($type == 'receita') {
                $sql_nome = "SELECT nome_rec FROM receita WHERE idReceita = ?";
            } elseif ($type == 'medida') {
                $sql_nome = "SELECT sistema FROM medida WHERE idMedida = ?";
            } elseif ($type == 'meta') {
                $sql_nome = "SELECT f.nome FROM metas m 
                             JOIN funcionario f ON m.idCozinheiro = f.idFun
                             WHERE idMeta = ?";
            } elseif ($type == 'livro') {
                $sql_nome = "SELECT titulo FROM livro WHERE idLivro = ?";
            } elseif ($type == 'categoria') {
                $sql_nome = "SELECT nome FROM categoria WHERE idCategoria = ?";
            } elseif ($type == 'usuario') {
                $sql_nome = "SELECT nome FROM usuario WHERE idLogin = ?";
            } elseif ($type == 'funcionario') {
                $sql_nome = "SELECT nome FROM funcionario WHERE idFun = ?";
            } elseif ($type == 'restaurante') {
                $sql_nome = "SELECT nome FROM restaurante WHERE idRestaurante = ?";
            } elseif ($type == 'cargo') {
                $sql_nome = "SELECT nome FROM cargo WHERE idCargo = ?";
            }

            if (isset($sql_nome)) {
                $stmt_nome = $conn->prepare($sql_nome);
                $stmt_nome->bind_param("i", $id);
                $stmt_nome->execute();
                $stmt_nome->bind_result($nomeItem);
                $stmt_nome->fetch();
                $stmt_nome->close();
            }

            if ($nomeItem) {
                $nomesItens[] = $nomeItem;
            }

            if ($type == 'receita') {
                // Buscar a imagem associada à receita
                $sql_imagem = "SELECT arquivo_imagem FROM receita WHERE idReceita = ?";
                $stmt_imagem = $conn->prepare($sql_imagem);
                $stmt_imagem->bind_param("i", $id);
                $stmt_imagem->execute();
                $stmt_imagem->bind_result($arquivo_imagem);
                $stmt_imagem->fetch();
                $stmt_imagem->close();

                // Excluir a imagem, se existir
                if ($arquivo_imagem && file_exists(BASE_URL . $arquivo_imagem)) {
                    unlink(BASE_URL . $arquivo_imagem);
                }
            } elseif ($type == 'livro') {
                // Buscar a imagem associada ao livro
                $sql_imagem = "SELECT arquivo_imagem FROM livro WHERE idLivro = ?";
                $stmt_imagem = $conn->prepare($sql_imagem);
                $stmt_imagem->bind_param("i", $id);
                $stmt_imagem->execute();
                $stmt_imagem->bind_result($arquivo_imagem);
                $stmt_imagem->fetch();
                $stmt_imagem->close();

                // Excluir a imagem, se existir
                if ($arquivo_imagem && file_exists(BASE_URL . $arquivo_imagem)) {
                    unlink(BASE_URL . $arquivo_imagem);
                }
            } elseif ($type == 'usuario') {
                // Buscar a imagem associada ao usuário
                $sql_imagem = "SELECT imagem_perfil FROM usuario WHERE idLogin = ?";
                $stmt_imagem = $conn->prepare($sql_imagem);
                $stmt_imagem->bind_param("i", $id);
                $stmt_imagem->execute();
                $stmt_imagem->bind_result($imagem_perfil);
                $stmt_imagem->fetch();
                $stmt_imagem->close();

                // Excluir a imagem, se existir
                if ($imagem_perfil && file_exists(BASE_URL . "receitas/imagens/perfil/" . $imagem_perfil)) {
                    unlink(BASE_URL . "receitas/imagens/perfil/" . $imagem_perfil);
                }
            }

            // Agora, execute a exclusão no banco
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        $conn->commit();

        // Registrar o log de exclusão em massa com os nomes dos itens
        $logDescricao = "Exclusão em massa de $type(s): " . implode(", ", $nomesItens);
        registrarLog($conn, $idUsuario, "outro", $logDescricao);

        // Redirecionamento para a página correta dependendo do tipo
        $redirectUrl = BASE_URL . "receitas/Paginas/" . ($type == 'ingrediente' ? "ingredientes/listaIngrediente.php" : 
            ($type == 'receita' ? "receitas/verReceita.php" : 
            ($type == 'medida' ? "medidas/listaMedida.php" :
            ($type == 'livro' ? "livros/listaLivro.php" :
            ($type == 'categoria' ? "categorias/listaCategoria.php" :
            ($type == 'usuario' ? "usuarios/listaUsuario.php" :
            ($type == 'avaliacao' ? "avaliacoes/listaAvaliacao.php" :
            ($type == 'restaurante' ? "restaurantes/listaRestaurante.php" :
            ($type == 'cargo' ? "cargos/listaCargo.php" :
            ($type == 'funcionario' ? "funcionarios/listaFuncionario.php" : ""))))))))));

        header("Location: $redirectUrl?excluido_massa=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir itens: " . $e->getMessage();
    }
} else {
    echo "Nenhum item selecionado para exclusão ou tipo não especificado.";
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
