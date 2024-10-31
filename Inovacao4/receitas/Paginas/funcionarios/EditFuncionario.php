<?php
// Simulando a recuperação dos dados do funcionário (substitua isso por uma consulta ao banco de dados)
$funcionario = [
    'id' => '0001',
    'nome' => 'Nome',
    'rg' => '0000-000',
    'data_admissao' => '2021-10-30',
    'salario' => 'R$ 10000,00',
    'cargo' => 'ADMINISTRADOR',
    'nome_fantasia' => 'Nome'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $id_funcionario = $_POST['id-funcionario'];
    $nome_funcionario = $_POST['nome-funcionario'];
    $rg = $_POST['rg'];
    $data_admissao = $_POST['data-admissao'];
    $salario = $_POST['salario'];
    $cargo = $_POST['cargo'];
    $nome_fantasia = $_POST['nome-fantasia'];

    // Aqui você pode adicionar lógica para atualizar os dados no banco de dados
    // Exemplo:
    // $sql = "UPDATE funcionarios SET nome='$nome_funcionario', rg='$rg', data_admissao='$data_admissao', salario='$salario', cargo='$cargo', nome_fantasia='$nome_fantasia' WHERE id='$id_funcionario'";

    // Exibir mensagem de sucesso (apenas para fins de exemplo)
    echo "<script>alert('Funcionário atualizado com sucesso!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-image: url('https://placehold.co/800x600');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-orange-500 bg-opacity-50">
    <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-3xl font-bold text-center mb-6">Editar Funcionário</h1>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="id-funcionario">Id funcionário</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="id-funcionario" id="id-funcionario" value="<?php echo $funcionario['id']; ?>" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="nome-funcionario">Nome funcionário</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="nome-funcionario" id="nome-funcionario" value="<?php echo $funcionario['nome']; ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="rg">Rg</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="rg" id="rg" value="<?php echo $funcionario['rg']; ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="data-admissao">Data de admissão</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="date" name="data-admissao" id="data-admissao" value="<?php echo $funcionario['data_admissao']; ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="salario">Salário</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="salario" id="salario" value="<?php echo $funcionario['salario']; ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="cargo">Cargo</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded" name="cargo" id="cargo">
                    <option <?php if ($funcionario['cargo'] == 'ADMINISTRADOR') echo 'selected'; ?>>ADMINISTRADOR</option>
                    <!-- Adicione outras opções de cargo aqui -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" for="nome-fantasia">Nome fantasia</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="nome-fantasia" id="nome-fantasia" value="<?php echo $funcionario['nome_fantasia']; ?>">
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Atualizar
