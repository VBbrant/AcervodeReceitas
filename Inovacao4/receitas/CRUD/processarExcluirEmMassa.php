<?php
require_once '../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$type = $_POST['type'] ?? null;
$itensSelecionados = $_POST['itensSelecionados'] ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $type && count($itensSelecionados) > 0) {
    $conn->begin_transaction();

    try {
        switch ($type) {
            case 'ingrediente':
                $stmt = $conn->prepare("DELETE FROM ingrediente WHERE idIngrediente = ?");
                break;
            case 'receita':
                $stmt = $conn->prepare("DELETE FROM receita WHERE idReceita = ?");
                break;
            case 'medida':
                $stmt = $conn->prepare("DELETE FROM medida WHERE idMedida = ?");
                break;
            default:
                throw new Exception("Tipo inválido para exclusão.");
        }

        foreach ($itensSelecionados as $id) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
        $conn->commit();

        $redirectUrl = BASE_URL . "receitas/Paginas/" . ($type == 'ingrediente' ? "ingredientes/listaIngrediente.php" : ($type == 'receita' ? "receitas/verReceita.php" : "medidas/listaMedida.php"));
        header("Location: $redirectUrl?excluido_massa=1");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erro ao excluir itens: " . $e->getMessage();
    }
} else {
    echo "Nenhum item selecionado para exclusão ou tipo não especificado.";
}
