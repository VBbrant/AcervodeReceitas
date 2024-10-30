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

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="recipe-card">';
        echo '<img src="' . BASE_URL . $row['imagem'] . '" alt="' . htmlspecialchars($row['titulo']) . '" class="recipe-image">';
        echo '<h3>' . htmlspecialchars($row['titulo']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['descricao']) . '</p>';
        echo '<span class="rating">' . htmlspecialchars($row['avaliacao']) . ' estrelas</span>';
        echo '</div>';
    }
} else {
    echo '<p>Nenhuma receita encontrada para os filtros selecionados.</p>';
}
?>
    