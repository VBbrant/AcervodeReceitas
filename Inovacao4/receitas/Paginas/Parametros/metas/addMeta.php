<?php
// addMetas.php
require_once "../../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Obter lista de funcionários (cozinheiros) para seleção
$sql_cozinheiros = "SELECT idFun, nome FROM funcionario where idCargo = 7";
$result_cozinheiros = $conn->query($sql_cozinheiros);
$cozinheiros = [];
while ($row = $result_cozinheiros->fetch_assoc()) {
    $cozinheiros[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Adicionar Meta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita3.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body class="ingrediente">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4" id="formulario1">
        <h2 class="text-center">Adicionar Meta</h2>
        <form method="POST" action="../../../CRUD/processarAdicionar.php" id="formulario1">
            <input type="hidden" name="form_type" value="meta">

            <!-- Selecionar Cozinheiro -->
            <div class="mb-3">
                <label for="idCozinheiro" class="form-label">Selecionar Cozinheiro:</label>
                <select class="form-select" id="idCozinheiro" name="idCozinheiro" required>
                    <option value="">Selecione um cozinheiro</option>
                    <?php foreach ($cozinheiros as $cozinheiro): ?>
                        <option value="<?php echo $cozinheiro['idFun']; ?>">
                            <?php echo htmlspecialchars($cozinheiro['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Meta de Receitas -->
            <div class="mb-3">
                <label for="metaReceitas" class="form-label">Meta de Receitas:</label>
                <input type="number" class="form-control" id="metaReceitas" name="metaReceitas" required min="1" placeholder="Digite o número de receitas">
            </div>

            <!-- Data de Início -->
            <div class="mb-3">
                <label for="dataInicio" class="form-label">Data de Início:</label>
                <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
            </div>

            <!-- Data Final -->
            <div class="mb-3">
                <label for="dataFinal" class="form-label">Data Final:</label>
                <input type="date" class="form-control" id="dataFinal" name="dataFinal" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Adicionar Meta</button>
        </form>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

