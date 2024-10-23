<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Receita</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Style/estilo.css">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/elementoPagina/cabecalho.php';
; ?>

    <div class="container mt-4">
        <h2 class="section-title">Detalhes da Receita</h2>
        <div class="recipe-details-box">
            <?php
            include '../../CRUD/processarAll.php';

            if (isset($nome)) {
                echo "
                <h3>{$nome}</h3>
                <img src='../../imagens/{$imagem}' alt='Imagem da Receita'>
                <p><strong>Data de Criação:</strong> {$dataCriacao}</p>
                <p><strong>Porções:</strong> {$numPorcao}</p>
                <p><strong>Descrição:</strong> {$descricao}</p>
                <h4>Modo de Preparo</h4>
                <p>{$modoPreparo}</p>
                ";
            } else {
                echo "<p>Receita não encontrada.</p>";
            }
            ?>
        </div>
    </div>

    <footer class="mt-4 text-center">
        <p>&copy; 2024 SaborArte. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
