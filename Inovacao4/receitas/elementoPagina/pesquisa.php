<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

session_start();

require_once "../conn.php";

// Mostrar erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Receber o termo de busca
$query = $_GET['query'] ?? '';

if (strlen($query) > 0) {
    $query = "%" . $query . "%"; // Adicionar curingas
    $results = [];

    try {
        // Consulta SQL
        $sql = " SELECT 'Receita' AS categoria, idReceita AS id, nome_rec AS nome 
                FROM receita 
                WHERE nome_rec LIKE ? 
                UNION 
                SELECT 'Livro' AS categoria, idLivro AS id, titulo AS nome 
                FROM livro 
                WHERE titulo LIKE ?
                UNION 
                SELECT 'Funcionário' AS categoria, idfun AS id, nome AS nome 
                FROM funcionario 
                WHERE nome LIKE ?";
            
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar SQL: " . $conn->error);
        }

        $stmt->bind_param("sss", $query, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        $stmt->close();

        // Retornar JSON
        echo json_encode($results, JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode([]); // Retornar vazio se não houver query
}
?>
