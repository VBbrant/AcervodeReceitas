<?php
    require_once '../../../config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incluir Funcionário</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloFuncionario1.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">

</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>
    <input type="hidden" name="form_type" value="funcionario">
    <div class="container" id="Formulario">
        <div class="bg-white p-5 rounded shadow w-100" style="max-width: 600px;">
            <h1 class="text-center mb-4">Incluir Funcionário</h1>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nome-funcionario" class="form-label">Nome funcionário</label>
                    <input type="text" name="nome-funcionario" id="nome-funcionario" class="form-control" placeholder="Nome">
                </div>

                <div class="mb-3">
                    <label for="rg" class="form-label">Rg</label>
                    <input type="text" name="rg" id="rg" class="form-control" placeholder="RG">
                </div>

                <div class="mb-3">
                    <label for="data-admissao" class="form-label">Data de admissão</label>
                    <input type="date" name="data-admissao" id="data-admissao" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="salario" class="form-label">Salário</label>
                    <input type="text" name="salario" id="salario" class="form-control" placeholder="Salário">
                </div>

                <div class="mb-3">
                    <label for="cargo" class="form-label">Cargo</label>
                    <select name="cargo" id="cargo" class="form-select">
                        <option>ADMINISTRADOR</option>
                        <option>COZINHEIRO</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nome-fantasia" class="form-label">Nome fantasia</label>
                    <input type="text" name="nome-fantasia" id="nome-fantasia" class="form-control" placeholder="Nome fantasia">
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-dark">Incluir</button>
                </div>
            </form>
        </div>
    </div>

<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>

