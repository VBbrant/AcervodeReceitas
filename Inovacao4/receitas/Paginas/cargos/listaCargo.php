<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT * FROM cargo";
if (!empty($search)) {
    $sql .= " WHERE nome LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("s", $likeSearch);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Cargos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'receitas/Style/lista.css';?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">      
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4">
    <h2 class="text-center">Lista de Cargos</h2>
    <?php include ROOT_PATH . 'receitas/elementoPagina/barraPesquisa.php';?>

    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="cargo">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <!-- Checkbox para selecionar todos -->
                        <input type="checkbox" id="selectAll" class="custom-checkbox" onclick="toggleAllCheckboxes(this)" style="display: none;">
                        <label for="selectAll" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </th>
                    <th>Nome do cargo</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody class="selected-row">
                <?php while ($categoria = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <!-- Checkbox customizado para cada linha -->
                        <input type="checkbox" id="checkbox<?php echo $categoria['idCargo']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $categoria['idCargo']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkbox<?php echo $categoria['idCargo']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td class="nome-cell"><?php echo htmlspecialchars($categoria['nome']); ?></td>
                    <td class="text-end acoes-cell">
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/cargo/verCargo.php?id=<?php echo $categoria['idCargo']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>

                        <!-- Condição para não exibir editar e excluir para cargos com id 6, 7, 8, 9, 10 -->
                        <?php if (!in_array($categoria['idCargo'], [6, 7, 8, 9, 10])): ?>
                            <a href="<?php echo BASE_URL; ?>receitas/Paginas/cargo/editarCargo.php?id=<?php echo $categoria['idCargo']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="<?php echo BASE_URL; ?>receitas/Paginas/cargos/excluirCargo.php?id=<?php echo $categoria['idCargo']; ?>" 
                            class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este cargo?');">
                                <i class="fas fa-trash-alt"></i> Excluir
                            </a>
                        <?php else: ?>
                            <span class="btn btn-secondary btn-sm disabled">
                                <i class="fas fa-lock"></i> Editar
                            </span>
                            <span class="btn btn-secondary btn-sm disabled">
                                <i class="fas fa-lock"></i> Excluir
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-end">
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/cargos/addCargo.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Cargo
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>

