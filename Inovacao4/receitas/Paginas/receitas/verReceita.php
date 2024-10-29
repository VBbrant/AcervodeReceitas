<?php
require_once "../../../config.php";
session_start();

$receita = isset($_SESSION['receita']) ? $_SESSION['receita'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>receitas/Style/receitas.css">
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>receitas/Style/estiloCabecalho.css">
    <title><?php echo $receita ? htmlspecialchars($receita['nome_rec']) : "Receita"; ?></title>
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

    <div class="container py-5">
        <?php if ($error): ?>
            <p class="alert alert-danger"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($receita): ?>
            <h1 class="mb-4"><?php echo htmlspecialchars($receita['nome_rec']); ?></h1>
            <p><strong>Data de Criação:</strong> <?php echo htmlspecialchars($receita['data_criacao']); ?></p>
            <p><strong>Porções:</strong> <?php echo htmlspecialchars($receita['num_porcao']); ?></p>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($receita['descricao']); ?></p>
            
            <?php if ($receita['link_imagem']): ?>
                <img src="<?php echo htmlspecialchars($receita['link_imagem']); ?>" alt="<?php echo htmlspecialchars($receita['nome_rec']); ?>" class="img-fluid rounded mb-4">
            <?php endif; ?>

            <h3>Modo de Preparo</h3>
            <p><?php echo nl2br(htmlspecialchars($receita['modo_preparo'])); ?></p>
        <?php else: ?>
            <p class="alert alert-danger">Receita não encontrada.</p>
        <?php endif; ?>
    </div>

    <?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
</body>
</html>

<?php
// Limpa os dados da sessão após exibir
unset($_SESSION['receita']);
?>
