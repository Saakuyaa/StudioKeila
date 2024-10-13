<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redireciona para a página de login
    exit;
}

$servername = "localhost";
$username = "root"; // Usuário padrão do XAMPP
$password = ""; // Senha padrão do XAMPP (deixe vazio)
$dbname = "agendamentosdb"; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Aceitar ou recusar agendamentos
if (isset($_POST['acao'], $_POST['id'])) {
    $id = $_POST['id'];
    if ($_POST['acao'] == 'aceitar') {
        // Aceitar agendamento
        $sqlAceitar = "SELECT * FROM agendamentos_pendentes WHERE id = $id";
        $resultado = $conn->query($sqlAceitar);
        $agendamento = $resultado->fetch_assoc();

        // Inserir na tabela de agendamentos
        $sqlInserir = "INSERT INTO agendamentos (nome, telefone, servico, data, hora) VALUES ('{$agendamento['nome']}', '{$agendamento['telefone']}', '{$agendamento['servico']}', '{$agendamento['data']}', '{$agendamento['hora']}')";
        $conn->query($sqlInserir);

        // Remover da tabela pendente
        $conn->query("DELETE FROM agendamentos_pendentes WHERE id = $id");
    } else if ($_POST['acao'] == 'recusar') {
        // Remover da tabela pendente
        $conn->query("DELETE FROM agendamentos_pendentes WHERE id = $id");
    }

    // Redirecionar após a ação
    header("Location: admin.php");
    exit();
}

// Recuperar todos os agendamentos pendentes
$resultado = $conn->query("SELECT * FROM agendamentos_pendentes");
$agendamentosPendentes = [];
while ($row = $resultado->fetch_assoc()) {
    $agendamentosPendentes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Agendamentos</title>
</head>
<body>
    <h1>Agendamentos Pendentes</h1>

    <?php if (!empty($agendamentosPendentes)): ?>
        <ul>
            <?php foreach ($agendamentosPendentes as $agendamento): ?>
                <li>
                    <?php echo "{$agendamento['nome']} - {$agendamento['telefone']} - {$agendamento['servico']} - {$agendamento['data']} - {$agendamento['hora']}"; ?>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">
                        <button type="submit" name="acao" value="aceitar">Aceitar</button>
                        <button type="submit" name="acao" value="recusar">Recusar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum agendamento pendente.</p>
    <?php endif; ?>

    <a href="logout.php">Sair</a> <!-- Link para logout -->

</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>
