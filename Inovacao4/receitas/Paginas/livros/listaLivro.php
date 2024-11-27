<?php 
session_start();
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$sql = "SELECT l.*, f.nome AS editor
        FROM livro l
        LEFT JOIN funcionario f ON l.idEditor = f.idFun";

if (!empty($search)) {
    $sql .= " WHERE l.titulo LIKE ? OR f.nome LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
}

$stmt->execute();
$result = $stmt->get_result();

$idEditorSessao = $_SESSION['idFun'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/lista.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">     
    <style>
        .acoes-cell { white-space: nowrap; width: 1%; }
        .pdf-button { display: flex; align-items: center; justify-content: flex-end; margin-top: 8px; }
        .pdf-button a { display: inline-flex; align-items: center; }
        .pdf-button a i { margin-right: 5px; }
    </style> 
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4" id="lista2">
    <h2 class="text-center">Lista de Livros</h2>
    <?php include ROOT_PATH . 'receitas/elementoPagina/barraPesquisa.php'; ?>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="livro">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)">
                    </th>
                    <th>Nome</th>
                    <th>ISBN</th>
                    <th>Editor</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($livro = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" name="itensSelecionados[]" value="<?php echo $livro['idLivro']; ?>" onclick="highlightRow(this)">
                    </td>
                    <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($livro['isbn']); ?></td>
                    <td><?php echo htmlspecialchars($livro['editor']); ?></td>
                    <td class="text-end acoes-cell">
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/verLivro.php?id=<?php echo $livro['idLivro']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <?php if ($_SESSION['cargo'] == "ADM" || $_SESSION['cargo'] == "Editor"): ?>
                            <?php if ($livro['idEditor'] == $idEditorSessao || $_SESSION['cargo'] == "ADM"): ?>
                                <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/editarLivro.php?id=<?php echo $livro['idLivro']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/excluirLivro.php?id=<?php echo $livro['idLivro']; ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="pdf-button">
                            <a href="<?= BASE_URL; ?>receitas/Paginas/livros/PDFLivro.php?id=<?php echo $livro['idLivro']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-print"></i> Gerar PDF
                            </a>
                        </div>
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
            <button type="submit" class="btn btn-danger" id="btnExcluirSelecionados" style="display: none;">
                <i class="fas fa-trash-alt"></i> Excluir Selecionados
            </button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/livros/addLivro.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Livro
            </a>
        </div>
    </form>
</div>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js'; ?>"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

