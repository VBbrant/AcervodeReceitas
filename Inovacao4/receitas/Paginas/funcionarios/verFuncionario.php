<?php session_start();
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

if ($_SESSION['cargo'] !== 'ADM') {
    echo "<script>
        alert('Você não tem permissão para acessar essa página.');
        window.history.back();
    </script>";
    exit;
}

$idFuncionario = $_GET['id'] ?? null;
if (!$idFuncionario) {
    echo "ID do funcionario não fornecido.";
    exit;
}

// Consulta para obter os detalhes do funcionário
$sql = "SELECT f.*, c.nome AS cargo_nome, c.idCargo 
        FROM funcionario f
        LEFT JOIN cargo c ON c.idCargo = f.idCargo 
        WHERE f.idFun = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idFuncionario);
$stmt->execute();
$result = $stmt->get_result();
$funcionario = $result->fetch_assoc();

// Consulta para obter todos os cargos
$sql_cargos = "SELECT idCargo, nome FROM cargo";
$result_cargos = $conn->query($sql_cargos);
$cargos = $result_cargos->fetch_all(MYSQLI_ASSOC);

$stmt->close();

$sql_usuarios = "SELECT idLogin, nome FROM usuario";
$result_usuarios = $conn->query($sql_usuarios);
$usuarios = [];
while ($row = $result_usuarios->fetch_assoc()) {
    $usuarios[] = $row;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Ver Ingredientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloBackground.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
</head>
<body class="ingrediente">
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>

<div class="container my-4" id="lista">
    <h2 class="text-center">Detalhes do funcionario</h2>
    <form disabled>
            <!-- Campos de dados do funcionário -->
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Funcionário:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($funcionario['nome']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="rg" class="form-label">RG:</label>
                <input type="text" class="form-control" id="rg" name="rg" value="<?= htmlspecialchars($funcionario['rg']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= $funcionario['data_nascimento'] ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="data_admissao" class="form-label">Data de Admissão:</label>
                <input type="date" class="form-control" id="data_admissao" name="data_admissao" value="<?= $funcionario['data_admissao'] ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salário:</label>
                <input type="number" step="0.01" class="form-control" id="salario" name="salario" value="<?= $funcionario['salario'] ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="nome_fantasia" class="form-label">Apelido:</label>
                <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" value="<?= htmlspecialchars($funcionario['nome_fantasia']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($funcionario['email'])?>" disabled>
            </div>

            <!-- Campo de seleção para o cargo -->
            <div class="mb-3">
                <label for="idCargo" class="form-label">Cargo:</label>
                <select class="form-select" id="idCargo" name="idCargo" disabled>
                    <option value="">Selecione o Cargo</option>
                    <?php foreach ($cargos as $cargo): ?>
                        <option value="<?= $cargo['idCargo'] ?>" <?= $funcionario['idCargo'] == $cargo['idCargo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cargo['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campo de seleção para o restaurante -->
            <div class="mb-3">
                <label for="idRestaurante" class="form-label">Restaurante:</label>
                <select class="form-select" id="idRestaurante" name="idRestaurante" disabled>
                    <option value="">Selecione o Restaurante</option>
                    <?php 
                    $sql_restaurantes = "SELECT idRestaurante, nome FROM restaurante";
                    $result_restaurantes = $conn->query($sql_restaurantes);
                    while ($restaurante = $result_restaurantes->fetch_assoc()): ?>
                        <option value="<?= $restaurante['idRestaurante'] ?>" <?= $funcionario['idRestaurante'] == $restaurante['idRestaurante'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($restaurante['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Campo de seleção para o usuário -->
            <div class="mb-3">
                <label for="idLogin" class="form-label">Associar a Usuário Existente:</label>
                <select class="form-select" id="idLogin" name="idLogin" disabled>
                    <option value="<?= $funcionario['idLogin'] == $usuario['idLogin'] ? 'selected' : '' ?>"><?= htmlspecialchars($usuario['nome']) ?></option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['idLogin'] ?>" <?= $funcionario['idLogin'] == $usuario['idLogin'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario['nome']) ?>
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
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/funcionarios/editarFuncionario.php?id=<?php echo $idFuncionario; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </form>
</div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
