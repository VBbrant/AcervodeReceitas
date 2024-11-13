<?php
session_start();

// Verifica se o ID do livro está na sessão
if (!isset($_SESSION['id_livro'])) {
    echo "ID do livro não encontrado na sessão!";
    exit();
}

require '../dompdf/autoload.inc.php';
require_once("../caminho.php");
include_once ROOT_PATH . "Config/Conexao.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Instancia e configura o Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // Permite URLs externas
$dompdf = new Dompdf($options);

// Obtém o ID do livro da sessão
$id_livro = $_SESSION['id_livro'];

// Consulta o livro
$query_livro = "SELECT nome_livro, ano, autor, arquivo_imagem FROM livro WHERE id_livro = ?";
$stmt = $conn->prepare($query_livro);
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$result_livro = $stmt->get_result();
$livro = $result_livro->fetch_assoc();
$stmt->close();

// Consulta receitas e organiza os dados em arrays para evitar duplicação
$query_receitas = "
    SELECT r.id_receita, r.nome_receita, r.modo_preparo, mr.midia, 
           ri.quantidade, i.nome AS nome_ingrediente, m.nome_medida
    FROM receita_livro rl
    INNER JOIN receita r ON r.id_receita = rl.id_receita
    LEFT JOIN receita_ingrediente ri ON ri.fk_receita = r.id_receita
    LEFT JOIN ingrediente i ON i.id_ingrediente = ri.fk_ingrediente
    LEFT JOIN medida m ON m.id_medida = ri.fk_medida
    LEFT JOIN midia_receita mr ON mr.fk_receita = r.id_receita
    WHERE rl.id_livro = ?
";
$stmt = $conn->prepare($query_receitas);
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$result_receitas = $stmt->get_result();

$receitas = [];
while ($row = $result_receitas->fetch_assoc()) {
    $id_receita = $row['id_receita'];
    
    // Inicializa a receita se não estiver no array
    if (!isset($receitas[$id_receita])) {
        $receitas[$id_receita] = [
            'nome_receita' => $row['nome_receita'],
            'modo_preparo' => $row['modo_preparo'],
            'ingredientes' => [],
            'midias' => []
        ];
    }

    // Adiciona o ingrediente se estiver presente
    if ($row['nome_ingrediente']) {
        $receitas[$id_receita]['ingredientes'][] = $row['quantidade'] . ' ' . $row['nome_medida'] . ' de ' . $row['nome_ingrediente'];
    }

    // Adiciona a mídia se não estiver duplicada
    if ($row['midia'] && !in_array($row['midia'], $receitas[$id_receita]['midias'])) {
        $receitas[$id_receita]['midias'][] = $row['midia'];
    }
}
$stmt->close();

// Inicia a captura do conteúdo HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($livro['nome_livro']); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <style>
        /* CSS conforme seu exemplo */
        body { font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; }
        .capa { background-color: #F5DEB3; text-align: center; padding: 75px; height: 150vh; }
        .capa h1 { font-size: 3em; margin-bottom: 20px; }
        .capa img { max-width: 200px; margin-bottom: 20px; }
        .capa .info { font-size: 1.2em; }
        .receita { margin: 20px auto; max-width: 800px; padding: 15px; border: 2px solid #CCC; border-radius: 8px; background-color: grey; page-break-inside: avoid; }
        .receita h2 { color: #333; font-size: 1.5em; background-color: #f0f0f0; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 10px; }
        .ingredientes { background-color: #FAFAD2; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .modo-preparo { background-color: #FAFAD2; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
        .imagem-receita img { max-width: 100%; max-height: 250px; border-radius: 8px; }
    </style>
</head>
<body>

<!-- Capa do Livro -->
<div class="capa">
    <h1><?php echo htmlspecialchars($livro['nome_livro']); ?></h1>
    <?php if (!empty($livro['arquivo_imagem'])): ?>
        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($livro['arquivo_imagem']); ?>" alt="Imagem do Livro">
    <?php endif; ?>
    <div class="info">
        <p>Autor: <?php echo htmlspecialchars($livro['autor']); ?></p>
        <p>Ano: <?php echo htmlspecialchars($livro['ano']); ?></p>
    </div>
</div>

<?php
// Variável de controle para garantir que a quebra de página ocorra apenas antes da primeira receita
$primeira_receita = true;
?>

<!-- Páginas de Receitas -->
<?php foreach ($receitas as $receita): ?>
    <?php if ($primeira_receita): ?>
        <!-- Quebra de página antes da primeira receita -->
        <div style="page-break-before: always;"></div>
        <?php $primeira_receita = false; ?>
    <?php endif; ?>
    
    <div class="receita">
        <h2><?php echo htmlspecialchars($receita['nome_receita']); ?></h2>
        <div class="ingredientes">
            <h3>Ingredientes:</h3>
            <ul>
                <?php foreach ($receita['ingredientes'] as $ingrediente): ?>
                    <li><?php echo htmlspecialchars($ingrediente); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="modo-preparo">
            <p><strong>Modo de Preparo:</strong> <?php echo htmlspecialchars($receita['modo_preparo']); ?></p>
        </div>
        <?php foreach ($receita['midias'] as $midia): ?>
            <div class="imagem-receita">
                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($midia); ?>" alt="Imagem da Receita">
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

</body>
</html>

<?php
$html = ob_get_clean(); // Armazena o HTML capturado

// Carrega o HTML no DOMPDF e configura o PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("livro_" . $livro['nome_livro'] . ".pdf", ["Attachment" => true]);
exit();
