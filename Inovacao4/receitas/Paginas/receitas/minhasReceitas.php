<?php session_start();
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

$sql = "SELECT 
    r.idReceita, r.idCozinheiro, 
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
$result = $conn->query($sql);

$result = $conn->query($sql);

$idEditorSessao = $_SESSION['idFun'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Medidas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'receitas/Style/lista.css';?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/receita2.css">
    <style>
        .container{ margin-top: 100px !important;}
    </style>  
</head>
<body class="receita">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4" id="lista2">
<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="receita">
        <table class="table table-striped">
            <thead>
                <th class="checkbox-cell">
                    <input type="checkbox" id="selectAllReceitas" class="custom-checkbox" onclick="toggleAllCheckboxes(this)" style="display: none;">
                    <label for="selectAllReceitas" class="custom-label">
                        <i class="far fa-square unchecked-icon"></i>
                        <i class="fas fa-check-square checked-icon"></i>
                    </label>
                </th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Avaliação</th>
                <th class="text-end">Ações</th>
            </thead>
            <tbody>
                <?php while ($receita = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" id="checkboxReceita<?php echo $receita['idReceita']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $receita['idReceita']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkboxReceita<?php echo $receita['idReceita']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td>
                        <?php 
                            $titulo = htmlspecialchars($receita['titulo']);
                            echo strlen($titulo) > 20 ? substr($titulo, 0, 20) . '...' : $titulo;
                        ?>
                    </td>
                    <td>
                        <?php 
                            $descricao = htmlspecialchars($receita['descricao']);
                            echo strlen($descricao) > 60 ? substr($descricao, 0, 60) . '...' : $descricao;
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($receita['avaliacao']); ?></td>

                    <td class="text-end" >
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/verReceitaIndividual.php?id=<?php echo $receita['idReceita']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <?php if ($_SESSION['cargo'] === 'ADM' || $_SESSION['cargo'] === 'Cozinheiro') : ?>
                            <?php if (
                                ($_SESSION['idLogin'] === $receita['idCozinheiro'] && $_SESSION['cargo'] === 'Cozinheiro') || 
                                $_SESSION['cargo'] === 'ADM'
                            ) : ?>
                                <a href="<?= BASE_URL; ?>receitas/Paginas/receitas/editarReceita.php?id=<?= $receita['idReceita']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL; ?>receitas/Paginas/receitas/excluirReceita.php?id=<?= $receita['idReceita']; ?>" 
                                class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta medida?');">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

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
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Receita
            </a>
        </div>
    </form>
</div>



<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>
<script>
    function gerarPDF() {
        const idLivro = "<?php echo $idLivro; ?>"; // Obtém o ID do livro
        window.open("<?= BASE_URL;?>receitas/Paginas/livros/PDFLivro.php?id=" + <?php $livro['idLivro']; ?>, "_blank"); // Abre o PDF em uma nova aba
        }
</script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

