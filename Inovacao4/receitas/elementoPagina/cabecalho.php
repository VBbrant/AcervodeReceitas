<?php require_once CONFIG_PATH;
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Inicia a sessão se não estiver iniciada
}
$userRole = $_SESSION['cargo'];
?>

<header class="header">
    <nav class="navbar fixed-top">
        <div class="container-fluid header-content">
            <!-- Left side - Menu button and Logo -->
            <div class="d-flex align-items-center">
                <button class="btn menu-button me-3" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?php echo BASE_URL; ?>receitas/Paginas/home.php" class="navbar-brand">
                    <img src="<?php echo BASE_URL; ?>receitas/imagens/logo.png" alt="SaborArte" class="logo-img" height="40">
                </a>
            </div>
            
            <!-- Right side - Search, Notifications, and Profile -->
            <div class="d-flex align-items-center">
                <?php include ROOT_PATH . 'receitas/elementoPagina/pesquisa.php';?>


                <!-- Notificação de Alterações -->
                <div class="notification-container me-3" onclick="toggleNotifications()">
                    <i class="fas fa-bell"></i>
                    <span id="notificationDot" class="notification-dot"></span>
                </div>

                <?php
                $userImage = $_SESSION['imagem_perfil'] ?? null;
                ?>
                <a href="javascript:void(0)" class="btn profile-button" onclick="toggleProfile()" aria-label="Toggle profile">
                    <?php if ($userImage): ?>
                        <img src="<?php echo BASE_URL . 'receitas/imagens/perfil/' . $userImage; ?>" alt="Perfil" class="profile-img">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <!-- Caixa de notificações -->
    <div id="notificationBox" class="notification-box" style="display: none;">
        <div id="notificationContent" class="notification-content">
            <!-- As notificações serão inseridas aqui dinamicamente -->
        </div>
    </div>


    <!-- Sidebar, Profile Dropdown, Overlay -->
    <div class="sidebar" id="sidebar">
        <?php include ROOT_PATH . 'receitas/elementoPagina/barraLateral.php'; ?>
    </div>

    <div class="profile-dropdown" id="profileDropdown">
        <?php include ROOT_PATH . 'receitas/elementoPagina/perfil.php'; ?>
    </div>

    <div class="overlay" id="overlay" onclick="closeAll()"></div>
</header>


<style>
/* Estilos do botão de perfil */
header .profile-button {
    background: none;
    border: none;
    padding: 0.5rem;  /* Ajuste o preenchimento, se necessário */
    color: #333;
    transition: color 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px;
    height: 60px; 
    border-radius: 50%; 
    overflow: hidden; 
}

/* Ajuste da imagem de perfil */
header .profile-button .profile-img {
    width: 100%;       
    height: 100%;      /* A imagem ocupa toda a altura do botão */
    object-fit: cover; /* Ajusta a imagem sem distorcê-la */
    border-radius: 50%; /* A imagem mantém-se redonda */
}

/* Outros ajustes do cabeçalho */
header .navbar {
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 0.5rem 1rem;
    height: 70px;
}

header .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

header .logo-img {
    max-height: 40px;
    width: auto;
}       


</style>

<?php
// Certifique-se de que o ID do usuário está na sessão
$usuarioPerfil = $_SESSION['idLogin'];

// Consulta para obter os dados do usuário
$sqlPerfil = "SELECT u.nome, u.imagem_perfil, f.rg, f.data_nascimento, f.data_admissao, c.nome AS cargo 
        FROM usuario u
        JOIN funcionario f ON u.idLogin = f.idLogin
        JOIN cargo c ON f.idCargo = c.idCargo
        WHERE u.idLogin = ?";
$stmtPerfil = $conn->prepare($sqlPerfil);
$stmtPerfil->bind_param("i", $usuarioPerfil);
$stmtPerfil->execute();
$resultPerfil = $stmtPerfil->get_result();
$usuarioInf = $resultPerfil->fetch_assoc();


$sql_usuario = "SELECT * FROM usuario WHERE idLogin = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuarioPerfil);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario2 = $result_usuario->fetch_assoc();

?>

<!-- Modal HTML -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center">
                <?php if ($userImage): ?>
                    <img style="width: 120px; height: 120px ; border-radius: 50%;margin-top: 15px; " src="<?php echo BASE_URL . 'receitas/imagens/perfil/' . $userImage; ?>" alt="Perfil" class="profile-img">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <form id="profileForm" action="<?= BASE_URL ;?>receitas/CRUD/processarConta.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="perfil">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nomeUsuario" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nomeUsuario" value="<?php echo htmlspecialchars($usuarioInf['nome']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" value="<?php echo htmlspecialchars($usuarioInf['cargo']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="rg" class="form-label">RG</label>
                        <input type="text" class="form-control" id="rg" value="<?php echo htmlspecialchars($usuarioInf['rg']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="dataAdmissao" class="form-label">Data de Admissão</label>
                        <input type="date" class="form-control" id="dataAdmissao" value="<?php echo $usuarioInf['data_admissao']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="dataNascimento" value="<?php echo $usuarioInf['data_nascimento']; ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="imagemPerfil" class="form-label">Imagem de Perfil</label>
                        <input type="file" class="form-control" id="imagemPerfil" name="imagemPerfil" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary"  onclick="abrirModalConfiguracoes()" data-bs-dismiss="modal">Configurações</button>
                    <button type="button" class="btn btn-black" data-bs-dismiss="modal">Voltar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal de Configurações -->
<div class="modal fade" id="modalConfiguracoes" tabindex="-1" aria-labelledby="modalConfiguracoesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfiguracoesLabel">Opções de Segurança</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?= $usuario2['email']?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" disabled>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="mudarSenha()">Editar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Alteração de Senha -->
<div class="modal fade" id="modalAlterarSenha" tabindex="-1" aria-labelledby="modalAlterarSenhaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlterarSenhaLabel">Alterar Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAlterarSenha" method="POST" action="<?= BASE_URL;?>receitas/CRUD/processarConta.php">
                    <input type="hidden" name="form_type" value="mudarSenha">

                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                    </div>
                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                    </div>
                    <div class="mb-3">
                        <label for="repita_nova_senha" class="form-label">Repita Nova Senha</label>
                        <input type="password" class="form-control" id="repita_nova_senha" name="repita_nova_senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript e Estilos Específicos para o Modal -->
<style>
    /* Estilo específico para o modal de perfil */
    #profileModal .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    #profileModal .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    #profileModal .modal-title {
        color: #495057;
    }
    #profileModal .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    #profileModal .form-label {
        font-weight: 500;
    }
    #profileModal .btn {
        margin-right: 0.5rem;
    }
</style>

<script>

    document.getElementById('profileModal').addEventListener('show.bs.modal', function () {
    });

    function abrirModalConfiguracoes() {
        new bootstrap.Modal(document.getElementById('modalConfiguracoes')).show();
    }


    function mudarSenha() {
    new bootstrap.Modal(document.getElementById('modalAlterarSenha')).show();
    }

    let lastCheckTime = new Date().toISOString(); // Armazena o último horário de verificação
    let notificationData = {
        additions: 0,
        edits: 0,
        deletions: 0,
        bulkDeletions: 0
    };

    function checkNotifications() {
    fetch('<?=BASE_URL;?>receitas/CRUD/sincronizarNotificacoes.php')
        .then(response => response.json())
        .then(data => {
            const notificationDot = document.getElementById('notificationDot');
            const notificationContent = document.getElementById('notificationContent');
            
            if (data.length > 0) {
                notificationDot.style.display = 'block';
                
                let notificacoesHtml = '<ul>';
                data.forEach(notificacao => {
                    notificacoesHtml += `<li>${notificacao.descricao} (${new Date(notificacao.data).toLocaleString()})</li>`;
                });
                notificacoesHtml += '</ul>';
                notificationContent.innerHTML = notificacoesHtml;
            } else {
                notificationDot.style.display = 'none';
                notificationContent.innerHTML = '';
            }
        })
        .catch(error => console.error('Erro ao buscar notificações:', error));
    }

    function marcarNotificacoesComoVistas() {
    fetch('<?=BASE_URL;?>receitas/CRUD/marcarNotificacoesComoVistas.php', { method: 'POST' })
        .then(() => {
            document.getElementById('notificationDot').style.display = 'none';
        });
    }




    function toggleNotifications() {
        const notificationBox = document.getElementById('notificationBox');
        if (notificationBox.style.display === 'none' || notificationBox.style.display === '') {
            notificationBox.style.display = 'block';
            marcarNotificacoesComoVistas();
        } else {
            notificationBox.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
    checkNotifications();
    setInterval(checkNotifications, 15000); // Atualiza a cada 15 segundos
});


</script>
<style>


</style>
