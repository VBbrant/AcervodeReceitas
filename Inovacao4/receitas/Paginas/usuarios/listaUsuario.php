<?php
require_once '../../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

// Consulta para pegar os dados dos usuários
$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaborArte - Lista de Usuários</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/lista.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>receitas/Style/estiloCabecalho.css">       
</head>
<body>
<?php include ROOT_PATH . 'receitas/elementoPagina/cabecalho.php'; ?>  

<div class="container my-4">
    <h2 class="text-center">Lista de Usuários</h2>
    <form method="POST" action="<?php echo BASE_URL; ?>receitas/CRUD/processarExcluirEmMassa.php" id="formExcluirMassa" onsubmit="return confirmarExclusaoEmMassa()">
        <input type="hidden" name="type" value="usuario">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <!-- Checkbox para selecionar todos -->
                        <input type="checkbox" id="selectAllUsuarios" class="custom-checkbox" onclick="toggleAllCheckboxes(this)" style="display: none;">
                        <label for="selectAllUsuarios" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Imagem de Perfil</th>
                    <th class="text-end acoes-cell">Ações</th>
                </tr>
            </thead>
            <tbody class="selected-row">
                <?php while ($usuario = $result->fetch_assoc()): ?>
                <tr>
                    <td class="checkbox-cell">
                        <!-- Checkbox customizado para cada linha -->
                        <input type="checkbox" id="checkboxUsuario<?php echo $usuario['idLogin']; ?>" class="custom-checkbox" name="itensSelecionados[]" value="<?php echo $usuario['idLogin']; ?>" style="display: none;" onclick="highlightRow(this)">
                        <label for="checkboxUsuario<?php echo $usuario['idLogin']; ?>" class="custom-label">
                            <i class="far fa-square unchecked-icon"></i>
                            <i class="fas fa-check-square checked-icon"></i>
                        </label>
                    </td>
                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td>
                        <!-- Exibe a imagem de perfil se disponível -->
                        <?php if ($usuario['imagem_perfil']): ?>
                            <img src="<?php echo BASE_URL . 'receitas/imagens/perfil/' . $usuario['imagem_perfil']; ?>" alt="Imagem de Perfil" class="rounded-circle" style="width: 40px; height: 40px;">
                        <?php else: ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>
                    </td>                    
                    <td class="acoes-cell" id="botao">
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/verUsuario.php?id=<?php echo $usuario['idLogin']; ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/excluirUsuario.php?id=<?php echo $usuario['idLogin']; ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                            <i class="fas fa-trash-alt"></i> Excluir
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="text-end">
            <button type="button" class="btn btn-warning" id="btnExcluirMassa" onclick="ativarExclusaoMassa()">
                <i class="fas fa-trash-alt"></i> Excluir em Massa
            </button>
            <button type="submit" class="btn btn-danger" style="display: none;" id="btnExcluirSelecionados">
                <i class="fas fa-trash-alt"></i> Excluir Selecionados
            </button>
            <a href="<?php echo BASE_URL; ?>receitas/Paginas/usuarios/addUsuario.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Usuário
            </a>
        </div>
    </form>
</div>

<script src="<?php echo BASE_URL . 'receitas/Scripts/listas.js';?>"></script>
<?php include ROOT_PATH . 'receitas/elementoPagina/rodape.php'; ?>
