<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";


$category = $_GET['category'] ?? '';
$rating = $_GET['rating'] ?? '';
$viewMode = $_GET['view'] ?? 'grid';

$sql = "
SELECT 
    r.idReceita, 
    r.nome_rec AS titulo, 
    r.descricao, 
    COALESCE(d.nota_degustacao, 0) AS avaliacao,
    r.link_imagem AS LIimagem,
    r.arquivo_imagem AS ARimagem
FROM 
    receita r
LEFT JOIN 
    degustacao d ON r.idReceita = d.idReceita
JOIN 
    categoria c ON r.idCategoria = c.idCategoria
";

$conditions = [];
if ($category) {
    $conditions[] = "c.nome = '" . $conn->real_escape_string($category) . "'";
}
if ($rating) {
    $conditions[] = "COALESCE(d.nota_degustacao, 0) >= " . $conn->real_escape_string($rating);
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY avaliacao DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imageSrc = !empty($row['LIimagem']) ? $row['LIimagem'] : BASE_URL . $row['ARimagem'];

        if ($viewMode === 'grid') {
            echo '<a href="verReceitaIndividual.php?id=' . $row['idReceita'] . '" class="recipe-link">';
            echo '<div class="recipe-card">';
            echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="' . htmlspecialchars($row['titulo']) . '" class="recipe-image">';
            echo '<h3>' . htmlspecialchars($row['titulo']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['descricao']) . '</p>';
            echo '<span class="rating fas fa-star text-warning">' . htmlspecialchars($row['avaliacao']) . '</span>';
            echo '</div></a>';
        } else {
            echo '<tr>';
            echo '<td><img src="' . htmlspecialchars($imageSrc) . '" alt="' . htmlspecialchars($row['titulo']) . '" class="thumbnail-image"></td>';
            echo '<td>' . htmlspecialchars($row['titulo']) . '</td>';
            echo '<td>' . htmlspecialchars($row['descricao']) . '</td>';
            echo '<td class="text-center">' . htmlspecialchars($row['avaliacao']) . ' <i class="fas fa-star text-warning"></i></td>';
            echo '<td class="text-end">';
            echo '<a href="' . BASE_URL . 'receitas/Paginas/receitas/verReceitaIndividual.php?id=' . $row['idReceita'] . '" class="btn btn-info btn-sm">Ver</a>';
            echo '</td></tr>';
        }
    }
} else {
    echo '<p>Nenhuma receita encontrada para os filtros selecionados.</p>';
}
?>
