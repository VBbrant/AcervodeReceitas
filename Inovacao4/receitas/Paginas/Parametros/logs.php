<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Filtros (inicializados com valores vazios)
$cargoFiltro = '';
$crudFiltro = '';
$searchFiltro = '';

// Se existir um valor em um filtro, ele será atualizado no PHP
if (isset($_GET['cargo'])) {
    $cargoFiltro = $_GET['cargo'];
}
if (isset($_GET['crud'])) {
    $crudFiltro = $_GET['crud'];
}
if (isset($_GET['search'])) {
    $searchFiltro = $_GET['search'];
}

// Consulta para obter os logs com filtros aplicados
$sql = "SELECT 
            l.idLog, l.acao, l.data, f.nome AS usuario, c.nome AS cargo, l.tipo_acao 
        FROM log_sistema l
        LEFT JOIN funcionario f ON l.idUsuario = f.idFun
        LEFT JOIN cargo c ON f.idCargo = c.idCargo
        WHERE (? = '' OR c.nome LIKE ?) 
        AND (? = '' OR l.tipo_acao LIKE ?)
        AND (? = '' OR l.acao LIKE ?)
        ORDER BY l.data DESC";

echo $sql; // Para depurar a consulta

$stmt = $conn->prepare($sql);

// Vinculando os parâmetros
$searchFiltroParam = "%$searchFiltro%";
$cargoFiltroParam = "%$cargoFiltro%";
$crudFiltroParam = "%$crudFiltro%";
$stmt->bind_param('ssssss', $cargoFiltroParam, $cargoFiltroParam, $crudFiltroParam, $crudFiltroParam, $searchFiltroParam, $searchFiltroParam);

$stmt->execute();
$result = $stmt->get_result();

// Gerando a lista de logs dinamicamente
$output = '';
while ($row = $result->fetch_assoc()) {
    $output .= '<div class="list-group-item ' . getLogClass($row['tipo_acao']) . '">';
    $output .= '<h5 class="mb-1">' . htmlspecialchars($row['usuario']) . ' (' . htmlspecialchars($row['cargo']) . ')</h5>';
    $output .= '<p class="mb-1">' . htmlspecialchars($row['acao']) . '</p>';
    $output .= '<small>' . date('d/m/Y H:i', strtotime($row['data'])) . '</small>';
    $output .= '</div>';
}

// Retorna os logs filtrados como HTML
echo $output;

// Função para definir a classe do log com base no tipo de ação
function getLogClass($tipo_acao) {
    switch($tipo_acao) {
        case 'inclusao':
            return 'bg-success text-white';
        case 'exclusao':
            return 'bg-danger text-white';
        case 'edicao':
            return 'bg-warning text-dark';
        default:
            return 'bg-secondary text-white';
    }
}
?>
