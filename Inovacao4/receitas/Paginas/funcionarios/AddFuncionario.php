<?php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

$sql_usuarios = "SELECT idLogin, nome FROM usuario";
$result_usuarios = $conn->query($sql_usuarios);
$usuarios = [];
while ($row = $result_usuarios->fetch_assoc()) {
    $usuarios[] = $row;
}

$sql_cargos = "SELECT idCargo, nome FROM cargo";
$result_cargos = $conn->query($sql_cargos);
$cargos = $result_cargos->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>receitas/Style/home3.css">
</head>
<body class="ingrediente">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

    <div class="container my-4">
        <h2 class="text-center">Adicionar Funcionário</h2>
        <form method="POST" action="../../CRUD/processarAdicionar.php">
            <input type="hidden" name="form_type" value="funcionario">

            <!-- Campos de dados do funcionário -->
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Funcionário:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="rg" class="form-label">RG:</label>
                <input type="text" class="form-control" id="rg" name="rg">
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
            </div>
            <div class="mb-3">
                <label for="data_admissao" class="form-label">Data de Admissão:</label>
                <input type="date" class="form-control" id="data_admissao" name="data_admissao">
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salário:</label>
                <input type="number" step="0.01" class="form-control" id="salario" name="salario">
            </div>
            <div class="mb-3">
                <label for="nome_fantasia" class="form-label">Apelido:</label>
                <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia">
            </div>

            <div class="mb-3">
                <label for="cargo" class="form-label">Cargo:</label>
                <select class="form-select" id="idCargo" name="idCargo" required>
                    <option value="">Selecione o Cargo</option>
                    <?php foreach ($cargos as $cargo): ?>
                        <option value="<?= $cargo['idCargo'] ?>"><?= $cargo['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <!-- Selecionar usuário existente -->
            <div class="mb-3">
                <label for="idLogin" class="form-label">Associar a Usuário Existente:</label>
                <select class="form-select" id="idLogin" name="idLogin">
                    <option value="">Nenhum (Gerar link para registro)</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['idLogin']; ?>">
                            <?php echo htmlspecialchars($usuario['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <!-- Botão de Voltar -->
                <button onclick="voltarPagina()" id="backButton" type ="button" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>

                <!-- Botão de Editar -->
                <button type="submit" class="btn btn-primary" style="width: 590px;">Adicionar Funcionário</button>
            </div>
        </form>
    </div>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
