<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Consulta para obter todos os ingredientes
$sql = "SELECT * FROM ingrediente";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Ingredientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center">Lista de Ingredientes</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($ingrediente = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($ingrediente['nome']); ?></td>
                <td><?php echo htmlspecialchars($ingrediente['descricao']); ?></td>
                <td>
                    <a href="editarIngrediente.php?id=<?php echo $ingrediente['idIngrediente']; ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver/Editar
                    </a>
                    <a href="<?php echo BASE_URL; ?>receitas/Paginas/ingredientes/excluirIngrediente.php?type=ingrediente&id=<?php echo $ingrediente['idIngrediente']; ?>" 
                       class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este ingrediente?');">
                        <i class="fas fa-trash-alt"></i> Excluir
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
</body>
</html>
