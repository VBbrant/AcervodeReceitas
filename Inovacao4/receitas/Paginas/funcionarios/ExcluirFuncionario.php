<?php
// Simulação de funcionários (substitua isso por uma consulta ao banco de dados)
$funcionarios = [
    ['id' => '01', 'nome' => 'Marcos Braz', 'cargo' => 'Cozinheiro'],
    ['id' => '02', 'nome' => 'Filled State', 'cargo' => 'Filled State'],
];

$funcionariosSelecionados = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os IDs dos funcionários a serem removidos
    $funcionariosSelecionados = $_POST['funcionarios_selecionados'] ?? [];
    
    // Aqui você pode adicionar lógica para remover os funcionários do banco de dados
    // Exemplo:
    // $idsParaRemover = implode(',', $funcionariosSelecionados);
    // $sql = "DELETE FROM funcionarios WHERE id IN ($idsParaRemover)";

    // Exibir mensagem de sucesso (apenas para fins de exemplo)
    echo "<script>alert('Funcionários removidos com sucesso!');</script>";
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
            background-repeat: no-repeat;
            filter: sepia(1);
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-800 bg-opacity-50">
    <div class="text-center text-white">
        <h1 class="text-5xl font-bold mb-8">Excluir Funcionário</h1>
        <div class="flex justify-center space-x-8">
            <div>
                <label class="block mb-2">Selecione funcionários</label>
                <form method="POST" action="">
                    <select name="funcionario" class="block w-64 p-2 mb-4 text-black rounded">
                        <?php foreach ($funcionarios as $funcionario): ?>
                            <option value="<?php echo $funcionario['id']; ?>"><?php echo $funcionario['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="adicionar" class="px-4 py-2 bg-black text-white rounded">Adicionar</button>
                </form>
            </div>
            <div>
                <label class="block mb-2">Funcionários selecionados</label>
                <form method="POST" action="">
                    <table class="w-full text-black bg-white rounded">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Id</th>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Cargo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($funcionarios as $funcionario): ?>
                                <tr>
                                    <td class="border px-4 py-2">
                                        <input type="checkbox" name="funcionarios_selecionados[]" value="<?php echo $funcionario['id']; ?>">
                                        <?php echo $funcionario['id']; ?>
                                    </td>
                                    <td class="border px-4 py-2"><?php echo $funcionario['nome']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $funcionario['cargo']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="remover" class="mt-4 px-4 py-2 bg-black text-white rounded">Remover funcionários</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
