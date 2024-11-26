<?php
session_start();
require_once '../../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Base da consulta para a tabela "Metas" com junção para obter o nome do cozinheiro
$sql = "SELECT m.*, f.nome AS cozinheiro 
        FROM Metas m
        LEFT JOIN funcionario f ON m.idCozinheiro = f.idFun";

if (!empty($search)) {
    $sql .= " WHERE m.descricao LIKE ? OR f.nome LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
}

$stmt->execute();
$result = $stmt->get_result();

$currentDate = new DateTime();


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Metas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'receitas/Style/lista.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4" id="lista2">
    <h2 class="text-center">Lista de Metas</h2>
    <?php include ROOT_PATH . 'receitas/elementoPagina/barraPesquisa.php';?>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="meta">
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
                    <th>Cozinheiro</th>
                    <th>Meta de Receitas</th>
                    <th>Receitas Feitas</th>
                    <th>Data Início</th>
                    <th>Data Final</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="selected-row">
                <?php while ($meta = $result->fetch_assoc()):
                    $dataFinal = new DateTime($meta['dataFinal']);
                    $interval = $currentDate->diff($dataFinal);
                    $diasRestantes = (int)$interval->format('%R%a');

                    $rowClass = '';
                    if ($meta['receitasAtingidas'] >= $meta['metaReceitas']) {
                        $rowClass = 'table-success'; // Meta alcançada
                    } elseif ($diasRestantes < 0) {
                        $rowClass = 'table-danger'; // Prazo expirado
                    } elseif ($diasRestantes <= 30) {
                        $rowClass = 'table-warning'; // Perto do prazo
                    }
                ?>
                <tr class="<?php echo $rowClass; ?>">
                    <td class="checkbox-cell">
                        <input type="checkbox" id="checkbox<?php echo $meta['idMeta']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $meta['idMeta']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkbox<?php echo $meta['idMeta']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td><?php echo htmlspecialchars($meta['cozinheiro']); ?></td>
                    <td><?php echo htmlspecialchars($meta['metaReceitas']); ?></td>
                    <td><?php echo htmlspecialchars($meta['receitasAtingidas']); ?></td>
                    <td><?php echo htmlspecialchars($meta['dataInicio']); ?></td>
                    <td><?php echo htmlspecialchars($meta['dataFinal']); ?></td>
                    <td class="acoes-cell">
                        
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/verMeta.php?id=<?php echo $meta['idMeta']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <?php if ($diasRestantes >= -30): ?>
                            <a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/editarMeta.php?id=<?php echo $meta['idMeta']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/excluirMeta.php?id=<?php echo $meta['idMeta']; ?>" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-end">
            <button type="button" class="btn btn-warning" id="btnExcluirMassa" onclick="ativarExclusaoMassa()">
                <i class="fas fa-trash-alt"></i> Excluir em Massa
            </button>
            <button type="submit" class="btn btn-danger" style="display: none;" id="btnExcluirSelecionados">
                <i class="fas fa-trash-alt"></i> Excluir Selecionados
            </button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/parametros/metas/addMeta.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Meta
            </a>
        </div>
    </form>
</div>

<style>
.table-success {
    background-color: #28a745 !important; /* Verde mais intenso */
    color: #fff !important; /* Texto branco para contraste */
}

.table-danger {
    background-color: #dc3545 !important; /* Vermelho mais intenso */
    color: #fff !important; /* Texto branco */
}

.table-warning {
    background-color: #ffc107 !important; /* Amarelo mais intenso */
    color: #000 !important; /* Texto preto */
}
</style>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

