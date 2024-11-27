<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Consultas para obter as estatísticas de receitas
$sqlReceitas = "SELECT 
                    COUNT(*) AS totalReceitas,
                    SUM(CASE WHEN MONTH(data_criacao) = MONTH(CURRENT_DATE) AND YEAR(data_criacao) = YEAR(CURRENT_DATE) THEN 1 ELSE 0 END) AS receitasUltimoMes,
                    SUM(CASE WHEN YEAR(data_criacao) = YEAR(CURRENT_DATE) THEN 1 ELSE 0 END) AS receitasAno
                FROM receita";
$resultReceitas = $conn->query($sqlReceitas);
$receitasData = $resultReceitas->fetch_assoc();

// Consultas para encontrar o cozinheiro com mais receitas no mês e no ano
$sqlCozinheiro = "SELECT 
                    f.nome, 
                    COUNT(r.idReceita) AS totalReceitas, 
                    u.imagem_perfil AS imagem,
                    SUM(CASE WHEN MONTH(r.data_criacao) = MONTH(CURRENT_DATE) THEN 1 ELSE 0 END) AS receitasNoMes,
                    SUM(CASE WHEN YEAR(r.data_criacao) = YEAR(CURRENT_DATE) THEN 1 ELSE 0 END) AS receitasNoAno
                FROM funcionario f
                LEFT JOIN usuario u ON u.idLogin = f.idLogin
                LEFT JOIN receita r ON r.idCozinheiro = f.idFun
                GROUP BY f.idFun
                ORDER BY totalReceitas DESC
                LIMIT 1";
$resultCozinheiro = $conn->query($sqlCozinheiro);
$cozinheiro = $resultCozinheiro->fetch_assoc();

// Consultas para encontrar o editor com mais livros no mês e no ano
$sqlEditor = "SELECT 
                f.nome, 
                COUNT(l.idLivro) AS totalLivros, 
                u.imagem_perfil AS imagem,
                SUM(CASE WHEN MONTH(l.dataEntrega) = MONTH(CURRENT_DATE) THEN 1 ELSE 0 END) AS livrosNoMes,
                SUM(CASE WHEN YEAR(l.dataEntrega) = YEAR(CURRENT_DATE) THEN 1 ELSE 0 END) AS livrosNoAno
            FROM funcionario f
            LEFT JOIN usuario u ON u.idLogin = f.idLogin
            LEFT JOIN livro l ON l.idEditor = f.idFun
            GROUP BY f.idFun
            ORDER BY totalLivros DESC
            LIMIT 1";
$resultEditor = $conn->query($sqlEditor);
$editor = $resultEditor->fetch_assoc();

// Consultas para encontrar o degustador com mais avaliações no mês e no ano
$sqlDegustador = "SELECT 
                    f.nome, 
                    COUNT(a.idDegustacao) AS totalAvaliacoes, 
                    u.imagem_perfil AS imagem,
                    SUM(CASE WHEN MONTH(a.data_degustacao) = MONTH(CURRENT_DATE) THEN 1 ELSE 0 END) AS degustacoesNoMes,
                    SUM(CASE WHEN YEAR(a.data_degustacao) = YEAR(CURRENT_DATE) THEN 1 ELSE 0 END) AS degustacoesNoAno
                FROM funcionario f
                LEFT JOIN usuario u ON u.idLogin = f.idLogin
                LEFT JOIN degustacao a ON a.idDegustador = f.idFun
                GROUP BY f.idFun
                ORDER BY totalAvaliacoes DESC
                LIMIT 1";
$resultDegustador = $conn->query($sqlDegustador);
$degustador = $resultDegustador->fetch_assoc();

// Consultas para obter as 10 receitas com as notas médias mais altas
$sqlTopReceitas = "SELECT 
                    r.nome_rec AS nome, AVG(a.nota_degustacao) AS mediaNotas, f.nome AS nomeCozinheiro
                FROM receita r
                LEFT JOIN degustacao a ON a.idReceita = r.idReceita
                LEFT JOIN funcionario f ON r.idCozinheiro = f.idFun
                GROUP BY r.idReceita
                ORDER BY mediaNotas DESC
                LIMIT 10";
$resultTopReceitas = $conn->query($sqlTopReceitas);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Parâmetros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloParametros.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4">
    <h2 class="text-center mb-4">Parâmetros do Sistema</h2>

    <div class="row">
        <!-- Estatísticas de Receitas -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Estatísticas de Receitas</div>
                <div class="card-body">
                    <h5 class="card-title">Total de Receitas</h5>
                    <p class="card-text"><?php echo $receitasData['totalReceitas']; ?> receitas</p>
                    <h5 class="card-title">Receitas no Último Mês</h5>
                    <p class="card-text"><?php echo $receitasData['receitasUltimoMes']; ?> receitas</p>
                    <h5 class="card-title">Receitas no Ano</h5>
                    <p class="card-text"><?php echo $receitasData['receitasAno']; ?> receitas</p>
                </div>
            </div>
        </div>

        <!-- Cozinheiro com Mais Receitas -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Cozinheiro com Mais Receitas</div>
                <div class="card-body">
                    <?php if ($cozinheiro): ?>
                        <img src="<?php echo BASE_URL ."receitas/imagens/perfil/".$cozinheiro['imagem'] ? BASE_URL ."receitas/imagens/perfil/".$cozinheiro['imagem'] : BASE_URL . 'receitas/images/default_user.png'; ?>" alt="Cozinheiro" class="img-fluid rounded-circle mb-3" width="100">
                        <h5 class="card-title"><?php echo htmlspecialchars($cozinheiro['nome']); ?></h5>
                        <p class="card-text">Total de Receitas: <?php echo $cozinheiro['totalReceitas']; ?> receitas</p>
                        <p class="card-text">Receitas no Mês: <?php echo $cozinheiro['receitasNoMes']; ?> receitas</p>
                        <p class="card-text">Receitas no Ano: <?php echo $cozinheiro['receitasNoAno']; ?> receitas</p>
                    <?php else: ?>
                        <p class="card-text">Nenhum cozinheiro encontrado para este mês.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Editor com Mais Livros -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Editor com Mais Livros</div>
                <div class="card-body">
                    <?php if ($editor): ?>
                        <img src="<?php echo BASE_URL ."receitas/imagens/perfil/".$editor['imagem'] ? BASE_URL ."receitas/imagens/perfil/".$editor['imagem'] : BASE_URL . 'receitas/images/default_user.png'; ?>" alt="Editor" class="img-fluid rounded-circle mb-3" width="100">
                        <h5 class="card-title"><?php echo htmlspecialchars($editor['nome']); ?></h5>
                        <p class="card-text">Total de Livros: <?php echo $editor['totalLivros']; ?> livros</p>
                        <p class="card-text">Livros no Mês: <?php echo $editor['livrosNoMes']; ?> livros</p>
                        <p class="card-text">Livros no Ano: <?php echo $editor['livrosNoAno']; ?> livros</p>
                    <?php else: ?>
                        <p class="card-text">Nenhum editor encontrado para este mês.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Degustador com Mais Avaliações -->
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Degustador com Mais Avaliações</div>
                <div class="card-body">
                    <?php if ($degustador): ?>
                        <img src="<?php echo BASE_URL ."receitas/imagens/perfil/".$degustador['imagem'] ? BASE_URL ."receitas/imagens/perfil/".$degustador['imagem'] : BASE_URL . 'receitas/images/default_user.png'; ?>" alt="Degustador" class="img-fluid rounded-circle mb-3" width="100">
                        <h5 class="card-title"><?php echo htmlspecialchars($degustador['nome']); ?></h5>
                        <p class="card-text">Total de Avaliações: <?php echo $degustador['totalAvaliacoes']; ?> degustações</p>
                        <p class="card-text">Avaliações no Mês: <?php echo $degustador['degustacoesNoMes']; ?> degustações</p>
                        <p class="card-text">Avaliações no Ano: <?php echo $degustador['degustacoesNoAno']; ?> degustações</p>
                    <?php else: ?>
                        <p class="card-text">Nenhum degustador encontrado para este mês.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div id="lista2">
        <h3 class="text-center my-4">Top 10 Receitas com as Notas Médias Mais Altas</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nome da Receita</th>
                        <th>Cozinheiro</th>
                        <th>Média das Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultTopReceitas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['nomeCozinheiro']); ?></td>
                            <td><?php echo number_format($row['mediaNotas'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>


