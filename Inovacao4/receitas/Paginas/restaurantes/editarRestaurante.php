<?php
// editarRestaurante.php
require_once "../../../config.php";
require_once ROOT_PATH . "receitas/conn.php";

// Recuperar o id do restaurante
$idRestaurante = $_GET['id'] ?? null;

if ($idRestaurante) {
    // Recuperar os dados do restaurante
    $sql = "SELECT nome, telefone, endereco FROM restaurante WHERE idRestaurante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idRestaurante);
    $stmt->execute();
    $stmt->bind_result($nome, $telefone, $endereco);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Editar Restaurante</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/AddReceita3.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloEditar.css">
</head>
<body>
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    
    <div class="container my-4">
        <h2 class="text-center">Editar Restaurante</h2>
        <form method="POST" action="../../CRUD/processarEditar.php" id="formulario1">
            <input type="hidden" name="form_type" value="restaurante">
            <input type="hidden" name="idRestaurante" value="<?php echo $idRestaurante; ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Restaurante:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="telefone" 
                    name="telefone" 
                    placeholder="(Ex: (61) 98765-4321)" 
                    value="<?php echo $telefone; ?>"
                    required
                    oninput="formatarTelefone(this)"
                    aria-describedby="telefoneHelp">
                <small id="telefoneHelp" class="form-text text-muted">Formato: (61) 98765-4321</small>
                <div id="erroTelefone" class="text-danger" style="display: none;">Formato inválido. Exemplo: (61) 98765-4321</div>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $endereco; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Atualizar Restaurante</button>
        </form>
    </div>

<script>

function formatarTelefone(input) {
   let telefone = input.value.replace(/\D/g, ''); // Remove tudo que não for número
   let formattedPhone = '';

   // Formatação para o padrão (XX) XXXXX-XXXX
   if (telefone.length <= 2) {
       formattedPhone = `(${telefone}`;
   } else if (telefone.length <= 7) {
       formattedPhone = `(${telefone.substring(0, 2)}) ${telefone.substring(2)}`;
   } else {
       formattedPhone = `(${telefone.substring(0, 2)}) ${telefone.substring(2, 7)}-${telefone.substring(7, 11)}`;
   }

   // Aplica a formatação no campo de entrada
   input.value = formattedPhone;

   // Valida o formato
   const regex = /^\(\d{2}\)\s\d{5}-\d{4}$/;
   const erroDiv = document.getElementById('erroTelefone');
   if (!regex.test(input.value)) {
       erroDiv.style.display = 'block'; // Exibe o erro
   } else {
       erroDiv.style.display = 'none'; // Esconde o erro
   }
}
</script>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

