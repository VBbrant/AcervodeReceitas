<?php 
include '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';

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



$viewMode = $_GET['view'] ?? 'grid'; // Padrão: exibição em grid (grade)
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Receitas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/receita2.css">
    <?php if ($viewMode === 'lista'): ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/lista.css"> 
    <?php endif; ?>
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php';?>

    <main class="container my-4">
        <h1 class="receitas-title">RECEITAS</h1>

        <div class="text-end mb-3">
            <a href="?view=<?php echo $viewMode === 'grid' ? 'lista' : 'grid'; ?>" class="btn btn-secondary">
                <i class="fas fa-list"></i> <?php echo $viewMode === 'grid' ? 'Modo Lista' : 'Modo Grade'; ?>
            </a>
        </div>

        <?php if ($viewMode === 'grid'): ?>
            <!-- Exibição em modo grade (grid) -->
            <div class="filters-container mb-3">
                <select id="categoryFilter" class="filter-select">
                    <option value="">Definir Categoria</option>
                    <option value="carnes">Carnes</option>
                    <option value="massas">Massas</option>
                    <option value="sobremesas">Sobremesas</option>
                </select>
                
                <select id="ratingFilter" class="filter-select">
                    <option value="">Definir Avaliação</option>
                    <option value="3">3+ estrelas</option>
                    <option value="5">5+ estrelas</option>
                    <option value="7">7+ estrelas</option>
                    <option value="9">9+ estrelas</option>
                </select>
            </div>

            <div class="recipes-grid" id="recipesGrid"></div> <!-- Div para inserir receitas dinamicamente -->

        <?php else: ?>
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
                    <button type="button" class="btn btn-warning" id="btnExcluirMassa" onclick="ativarExclusaoMassa()">
                        <i class="fas fa-trash-alt"></i> Excluir em Massa
                    </button>
                    <button type="submit" class="btn btn-danger" style="display: none;" id="btnExcluirSelecionados">
                        <i class="fas fa-trash-alt"></i> Excluir Selecionados
                    </button>
                    <a href="<?php echo BASE_URL; ?>receitas/Paginas/receitas/addReceita.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Adicionar Receita
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <script src="<?php echo BASE_URL; ?>receitas/Scripts/carregarReceitas.js"></script>
<?php include ROOT_PATH . "receitas/elementoPagina/rodape.php"; ?>
