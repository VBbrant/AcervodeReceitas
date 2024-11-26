<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Filtros (se existirem na URL)
$anoFiltro = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
$cargoFiltro = isset($_GET['cargo']) ? $_GET['cargo'] : '';
$crudFiltro = isset($_GET['crud']) ? $_GET['crud'] : '';
$searchFiltro = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta para obter os logs com filtros aplicados
$sql = "SELECT 
            l.idLog, l.acao, l.data, f.nome AS usuario, c.nome AS cargo, l.tipo_acao 
        FROM log_sistema l
        LEFT JOIN funcionario f ON l.idUsuario = f.idFun
        LEFT JOIN cargo c ON f.idCargo = c.idCargo
        WHERE YEAR(l.data) = ? 
        AND (c.nome LIKE ?) 
        AND (l.tipo_acao LIKE ?)
        AND (l.acao LIKE ?)
        ORDER BY l.data DESC";

$stmt = $conn->prepare($sql);

// Vinculando os parâmetros corretamente
$cargoFiltroParam = $cargoFiltro ? "%$cargoFiltro%" : '%';
$crudFiltroParam = $crudFiltro ? "%$crudFiltro%" : '%';
$searchFiltroParam = $searchFiltro ? "%$searchFiltro%" : '%';

$stmt->bind_param(
    'ssss', 
    $anoFiltro, 
    $cargoFiltroParam, 
    $crudFiltroParam, 
    $searchFiltroParam
);

$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs do Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloParametros.css">
    <style>.bg-purple {background-color: #452188 !important;}</style>
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center mb-4">Histórico de Alterações do Sistema</h2>

    <!-- Barra de Pesquisa e Filtros -->
    <div class="row mb-4">
        
        <div class="col-md-3">
            <input type="text" class="form-control" name="search" placeholder="Pesquisar por ação..." 
                   value="<?php echo htmlspecialchars($searchFiltro); ?>" 
                   onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <form method="get">
                <select name="ano" class="form-select" onchange="this.form.submit()">
                    <?php
                    $anos = $conn->query("SELECT DISTINCT YEAR(data) AS ano FROM log_sistema ORDER BY ano DESC");
                    while ($ano = $anos->fetch_assoc()) {
                        echo "<option value='{$ano['ano']}'" . ($anoFiltro == $ano['ano'] ? ' selected' : '') . ">{$ano['ano']}</option>";
                    }
                    ?>
                </select>
        </div>
        <div class="col-md-3">
            <select name="cargo" class="form-select" onchange="this.form.submit()">
                <option value="">Filtrar por Cargo</option>
                <option value="Cozinheiro" <?php if($cargoFiltro == 'Cozinheiro') echo 'selected'; ?>>Cozinheiro</option>
                <option value="ADM" <?php if($cargoFiltro == 'ADM') echo 'selected'; ?>>ADM</option>
                <option value="Editor" <?php if($cargoFiltro == 'Editor') echo 'selected'; ?>>Editor</option>
                <option value="Degustador" <?php if($cargoFiltro == 'Degustador') echo 'selected'; ?>>Degustador</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="crud" class="form-select" onchange="this.form.submit()">
                <option value="">Filtrar por Ação CRUD</option>
                <option value="inclusao" <?php if($crudFiltro == 'inclusao') echo 'selected'; ?>>Inclusão</option>
                <option value="edicao" <?php if($crudFiltro == 'edicao') echo 'selected'; ?>>Edição</option>
                <option value="exclusao" <?php if($crudFiltro == 'exclusao') echo 'selected'; ?>>Exclusão</option>
                <option value="exclusaoEmMassa" <?php if($crudFiltro == 'exclusaoEmMassa') echo 'selected'; ?>>Exclusão em massa</option>
            </select>
        </div>
    </div>
    </form>

    <!-- Lista de Logs -->
    <div class="list-group" id="logList">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="list-group-item <?php echo getLogClass($row['tipo_acao']); ?>" data-acao="<?php echo strtolower($row['acao']); ?>" data-usuario="<?php echo strtolower($row['usuario']); ?>">
                <h5 class="mb-1"><?php echo htmlspecialchars($row['usuario']); ?> (<?php echo htmlspecialchars($row['cargo']); ?>)</h5>
                <p class="mb-1"><?php echo htmlspecialchars($row['acao']); ?></p>
                <small><?php echo date('d/m/Y H:i', strtotime($row['data'])); ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<script>
    // Função para filtrar a lista de logs com base no texto da pesquisa
    document.getElementById('searchInput').addEventListener('input', function () {
        var searchTerm = this.value.toLowerCase();
        var logs = document.querySelectorAll('#logList .list-group-item');

        logs.forEach(function (log) {
            var acao = log.getAttribute('data-acao');
            var usuario = log.getAttribute('data-usuario');
            
            // Mostrar ou esconder logs dependendo da correspondência com a pesquisa
            if (acao.includes(searchTerm) || usuario.includes(searchTerm)) {
                log.style.display = '';
            } else {
                log.style.display = 'none';
            }
        });
    });
</script>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

<?php
// Função para definir a classe do log com base no tipo de ação
function getLogClass($tipo_acao) {
    switch($tipo_acao) {
        case 'inclusao':
            return 'bg-success text-white'; // Verde para inclusão
        case 'exclusao':
            return 'bg-danger text-white'; // Vermelho para exclusão
        case 'edicao':
            return 'bg-warning text-dark'; // Amarelo para edição
        case 'exclusaoEmMassa':
            return 'bg-purple text-white'; // Roxo para exclusão em massa
        default:
            return 'bg-secondary text-white'; // Cor padrão
    }
}
?>
