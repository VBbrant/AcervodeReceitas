<?php
// filtro.php
require_once "../../../config.php";

$category = $_GET['category'] ?? '';
$rating = $_GET['rating'] ?? '';

$sql = "
SELECT 
    r.idReceita, 
    r.nome_rec AS titulo, 
    r.descricao, 
    d.nota_degustacao AS avaliacao, 
    r.link_imagem AS imagem
FROM 
    receita r
JOIN 
    degustacao d ON r.idReceita = d.idReceita
";

$conditions = [];
if ($category) {
    $conditions[] = "categoria = '" . $conn->real_escape_string($category) . "'";
}
if ($rating) {
    $conditions[] = "avaliacao >= " . $conn->real_escape_string($rating);
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY avaliacao DESC";

$result = $conn->query($sql);
$recipes = [];

while ($row = $result->fetch_assoc()) {
    $recipes[] = [
        'idReceita' => $row['idReceita'],
        'titulo' => $row['titulo'],
        'descricao' => $row['descricao'],
        'avaliacao' => $row['avaliacao'],
        'imagem' => BASE_URL . $row['imagem'] // Inclui o domínio completo
    ];
}

header('Content-Type: application/json');
$jsonOutput = json_encode($recipes);

if ($jsonOutput === false) {
    die("Erro ao codificar JSON: " . json_last_error_msg());
}

echo $jsonOutput;

?>
