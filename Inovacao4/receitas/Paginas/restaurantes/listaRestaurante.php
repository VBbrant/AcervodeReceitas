<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Consulta para obter todos os restaurantes
$sql = "SELECT * FROM restaurante";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Restaurantes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/lista.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="restaurante">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4">
    <h2 class="text-center">Lista de Restaurantes</h2>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa">
        <input type="hidden" name="type" value="restaurante">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAllRestaurantes" class="custom-checkbox" onclick="toggleAllCheckboxes(this)" style="display: none;">
                        <label for="selectAllRestaurantes" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($restaurante = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" id="checkboxRestaurante<?php echo $restaurante['idRestaurante']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $restaurante['idRestaurante']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkboxRestaurante<?php echo $restaurante['idRestaurante']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td><?php echo htmlspecialchars($restaurante['nome']); ?></td>
                    <td><?php echo htmlspecialchars($restaurante['endereco']); ?></td>
                    <td><?php echo htmlspecialchars($restaurante['telefone']); ?></td>
                    <td class="text-end">
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/verRestaurante.php?id=<?php echo $restaurante['idRestaurante']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/editarRestaurante.php?id=<?php echo $restaurante['idRestaurante']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/excluirRestaurante.php?id=<?php echo $restaurante['idRestaurante']; ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este restaurante?');">
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
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/restaurantes/addRestaurante.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Restaurante
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>

