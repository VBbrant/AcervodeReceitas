<?php
require_once '../../config.php';
require_once ROOT_PATH . 'receitas/conn.php';

session_start();
$idUsuario = $_SESSION['idLogin']; // Usuário logado

// Verifica novos logs que ainda não estão na tabela de notificações
$sql = "
    SELECT ls.idLog, ls.idUsuario, ls.acao, ls.data
    FROM log_sistema ls
    LEFT JOIN notificacoes_temp nt ON ls.idLog = nt.idLog
    WHERE nt.idLog IS NULL
    ORDER BY ls.data DESC
";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $insertQuery = $conn->prepare("
        INSERT INTO notificacoes_temp (idLog, idUsuario, descricao, data)
        VALUES (?, ?, ?, ?)
    ");
    
    while ($row = $result->fetch_assoc()) {
        $descricao = $row['acao'];
        $insertQuery->bind_param(
            "iiss",
            $row['idLog'],
            $idUsuario,
            $descricao,
            $row['data']
        );
        $insertQuery->execute();
    }
}

// Retorna as notificações não vistas
$query = "SELECT descricao, data FROM notificacoes_temp WHERE idUsuario = ? AND visto = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$notificacoes = [];
while ($row = $result->fetch_assoc()) {
    $notificacoes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notificacoes);
?>
