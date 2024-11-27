<?php session_start();
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$idSessao  = $_SESSION['idFun'];
// Consulta para listar todas as avaliações e receitas associadas
$sql = "
    SELECT d.idDegustacao, d.nota_degustacao, d.data_degustacao, r.nome_rec, f.idFun    
    FROM degustacao d
    LEFT JOIN receita r ON d.idReceita = r.idReceita
    LEFT JOIN funcionario f ON d.idDegustacao = f.idFun
    WHERE idDegustador = $idSessao
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Avaliações</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'receitas/Style/lista.css';?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">      
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4" id="lista2">
    <h2 class="text-center">Lista de Avaliações</h2>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="avaliacao">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" onclick="toggleAllCheckboxes(this)" style="display: none;">
                        <label for="selectAll" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </th>
                    <th>Receita</th>
                    <th>Data</th>
                    <th>Nota</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody class="selected-row">
                <?php while ($avaliacao = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" id="checkbox<?php echo $avaliacao['idDegustacao']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $avaliacao['idDegustacao']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkbox<?php echo $avaliacao['idDegustacao']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td><?php echo htmlspecialchars($avaliacao['nome_rec']); ?></td>
                    <td><?php echo htmlspecialchars($avaliacao['data_degustacao']); ?></td>
                    <td><?php echo htmlspecialchars($avaliacao['nota_degustacao']); ?> &#9733;</td>
                    <td class="text-end acoes-cell">
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/verAvaliacao.php?id=<?php echo $avaliacao['idDegustacao']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/editarAvaliacao.php?id=<?php echo $avaliacao['idDegustacao']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/excluirAvaliacao.php?id=<?php echo $avaliacao['idDegustacao']; ?>" 
                        class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta avaliação?');">
                            <i class="fas fa-trash-alt"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-end">
            <?php if ($userRole == 'ADM') : ?>
                <button type="button" class="btn btn-warning" id="btnExcluirMassa" onclick="ativarExclusaoMassa()">
                    <i class="fas fa-trash-alt"></i> Excluir em Massa
                </button>
            <?php else: ?>
                <span class="btn btn-warning disabled">
                    <i class="fas fa-lock"></i> Excluir em massa
                </span>
            <?php endif;?>
            <button type="submit" class="btn btn-danger" style="display: none;" id="btnExcluirSelecionados">
                <i class="fas fa-trash-alt"></i> Excluir Selecionados
            </button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/avaliacoes/addAvaliacao.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Avaliação
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>