<?php
// filtro.php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

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
JOIN 
    categoria c ON r.idCategoria = c.idCategoria
";

$conditions = [];
if ($category) {
    $conditions[] = "c.descricao = '" . $conn->real_escape_string($category) . "'";
}
if ($rating) {
    $conditions[] = "d.nota_degustacao >= " . $conn->real_escape_string($rating);
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY avaliacao DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<a href="verReceitaIndividual.php?id=' . $row['idReceita'] . '" class="recipe-link">';
        echo '<div class="recipe-card">';  
        echo '<img src="' . BASE_URL . $row['imagem'] . '" alt="' . htmlspecialchars($row['titulo']) . '" class="recipe-image">';
        echo '<h3>' . htmlspecialchars($row['titulo']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['descricao']) . '</p>';
        echo '<span class="rating fas fa-star text-warning">' . htmlspecialchars($row['avaliacao']) . '</span>';
        echo '</div>';
        echo '</a>';
    }
} else {
    echo '<p>Nenhuma receita encontrada para os filtros selecionados.</p>';
}
?>
