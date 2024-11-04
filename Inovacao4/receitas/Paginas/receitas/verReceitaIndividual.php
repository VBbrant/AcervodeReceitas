<?php
require_once '../../../config.php';
include ROOT_PATH . 'receitas/conn.php';
include 'processarVerIndividual.php';
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Receita</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/receitaLista2.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php';?>
    <div class="conteudo-receita">
        <div class="container mt-5">
            <!-- Imagem e título da receita -->
            <div class="text-center">
            <img src="<?php echo !empty($receita['link_imagem']) ? $receita['link_imagem'] : BASE_URL . $receita['arquivo_imagem']; ?>" alt="Imagem da Receita" class="img-fluid rounded">
                <h1 class="mt-4"><?php echo htmlspecialchars($receita['nome_rec']); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($receita['descricao']); ?></p>
                <a href="EditarReceita.php?id=<?php echo $idReceita; ?>" class="btn btn-dark">Editar</a>
            </div>

            <!-- Avaliação -->
            <div class="avaliacao-container">
                <div class="rating">
                    <span class="score">
                    <?php 
                    $notaMedia = $receita['media_nota'] ?? 0;
                    echo "Nota média: " . number_format($notaMedia, 1, ',', '');
                    ?>

                    </span>
                    <span class="stars">
                        <?php

                            $num_estrelas = round($notaMedia / 2, 1);
                            $estrelas_completas = floor($num_estrelas);
                            $meia_estrela = ($num_estrelas - $estrelas_completas) >= 0.5;

                            for ($i = 0; $i < $estrelas_completas; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }


                            if ($meia_estrela) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            }

                            // Renderiza estrelas vazias para completar 5
                            for ($i = $estrelas_completas + $meia_estrela; $i < 5; $i++) {
                                echo '<i class="far fa-star"></i>';
                            }
                        ?>
                    </span>
                </div>
                <div class="chef-info">
                    <i class="fas fa-user"></i>
                    <span>Chefe <?php echo htmlspecialchars($receita['nome_cozinheiro']); ?></span>
                </div>
            </div>


            <!-- Exibição dos Ingredientes -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h3>Ingredientes</h3>
                <ul>
                    <?php foreach ($ingredientes as $ingrediente): ?>
                        <li>
                            <?= htmlspecialchars($ingrediente['ingrediente']) ?> - 
                            <?= htmlspecialchars($ingrediente['quantidade']) ?> <?= htmlspecialchars($ingrediente['sistema']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>


            <!-- Modo de Preparo -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h3>Modo de Preparo</h3>
                <ol>
                    <?php
                    $passos = explode("\n", $receita['modo_preparo']);
                    foreach ($passos as $passo) {
                        echo "<li>" . htmlspecialchars($passo) . "</li>";
                    }
                    ?>
                </ol>
            </div>

            <!-- Exibição da Categoria e Porções -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h4>Categoria: <?= htmlspecialchars($receita['categoria']) ?></h4>
                <p>Porções: <?= htmlspecialchars($receita['num_porcao']) ?> porções</p>
            </div>

            <!-- Exibição das Avaliações -->
            <div class="mt-5 p-4 rounded bg-light shadow">
                <h3>Avaliações</h3>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-3"><i class="fas fa-user"></i> <?= htmlspecialchars($avaliacao['degustador']) ?></span>
                        <h4 class="me-3"><?= htmlspecialchars($avaliacao['nota_degustacao']) ?> <i class="fas fa-star text-warning"></i></h4>
                        <p><?= htmlspecialchars($avaliacao['comentario_texto']) ?></p>
                        <small class="text-muted"><?= htmlspecialchars(date('d/m/Y', strtotime($avaliacao['data_degustacao']))) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>                
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
    

