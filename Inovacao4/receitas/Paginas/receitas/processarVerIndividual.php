<?php
// Verificar se o ID da receita foi passado
if (!isset($_GET['id'])) {
    echo "Receita não especificada.";
    exit;
}

$idReceita = intval($_GET['id']);

// Consulta ao banco de dados para obter os detalhes da receita, incluindo a categoria, média de nota_degustacao e nome do cozinheiro
$query = "SELECT r.nome_rec, r.data_criacao, r.modo_preparo, r.num_porcao, r.descricao, r.inedita, r.link_imagem, 
               c.descricao AS categoria,
               AVG(d.nota_degustacao) AS media_nota,
               func.nome AS nome_cozinheiro
        FROM receita r
        LEFT JOIN categoria c ON r.idCategoria = c.idCategoria
        LEFT JOIN degustacao d ON d.idReceita = r.idReceita
        LEFT JOIN funcionario func ON r.idCozinheiro = func.idFun
        WHERE r.idReceita = ?
        GROUP BY r.idReceita";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idReceita);
$stmt->execute();
$result = $stmt->get_result();
$receita = $result->fetch_assoc();

if (!$receita) {
    echo "Receita não encontrada.";
    exit;
}


// Consulta para obter ingredientes da receita
$queryIngredientes = "SELECT i.nome AS ingrediente, m.quantidade, m.sistema
                      FROM receita_ingrediente ri
                      JOIN ingrediente i ON ri.idIngrediente = i.idIngrediente
                      JOIN medida m ON ri.idMedida = m.idMedida
                      WHERE ri.idReceita = ?";
$stmtIngredientes = $conn->prepare($queryIngredientes);
$stmtIngredientes->bind_param("i", $idReceita);
$stmtIngredientes->execute();
$resultIngredientes = $stmtIngredientes->get_result();

$ingredientes = [];
while ($row = $resultIngredientes->fetch_assoc()) {
    $ingredientes[] = $row;
}

// Consulta para obter avaliações (degustações) e comentários
$queryAvaliacoes = "SELECT d.nota_degustacao, d.data_degustacao, c.comentario_texto, func.nome AS degustador
                    FROM degustacao d
                    LEFT JOIN comentario c ON c.idDegustacao = d.idDegustacao
                    LEFT JOIN funcionario func ON d.idDegustador = func.idFun
                    WHERE d.idReceita = ?";
$stmtAvaliacoes = $conn->prepare($queryAvaliacoes);
$stmtAvaliacoes->bind_param("i", $idReceita);
$stmtAvaliacoes->execute();
$resultAvaliacoes = $stmtAvaliacoes->get_result();

$avaliacoes = [];
while ($row = $resultAvaliacoes->fetch_assoc()) {
    // Verificar se as chaves existem antes de adicioná-las ao array
    $avaliacoes[] = [
        'nota_degustacao' => $row['nota_degustacao'] ?? 'N/A',
        'data_degustacao' => $row['data_degustacao'] ?? 'N/A',
        'comentario_texto' => $row['comentario_texto'] ?? 'Sem comentário',
        'degustador' => $row['degustador'] ?? 'Anônimo'
    ];
}
?>