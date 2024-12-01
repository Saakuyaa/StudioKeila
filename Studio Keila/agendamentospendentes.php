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
    header("Location: agendamentospendentes.php");
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
    <style>
       body {
    font-family: Arial, sans-serif;
    background-image: url('fundocurriculo.jpg');
    background-size: cover;
    background-position: center center;
    background-attachment: fixed;
    color: #333;
    margin: 0;
    padding: 20px;
}

h1 {
    margin-top: 25px;
    text-align: center;
    font-size: 36px; 
    color: black; 
}

.container {
    max-width: 600px;
    margin: 0 auto;
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 20px 0;
}

li {
    padding: 15px; 
    border: 1px solid #ccc;
    margin-bottom: 15px; 
    background-color: rgba(255, 255, 255, 0.8); 
    border-radius: 8px; 
    text-align: center;
    font-size: 18px; 
    line-height: 1.6; 
    transition: all 0.3s ease; 
}

li:hover {
    background-color: rgba(255, 255, 255, 1); 
    transform: scale(1.02); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}

form {
    display: inline;
    margin-left: 10px;
    margin-top: 10px;
}

button {
    padding: 8px 15px; 
    margin: 0 10px;
    border: none;
    border-radius: 5px; 
    cursor: pointer;
    font-size: 16px; 
    transition: all 0.3s ease; 
}

button[name="acao"][value="aceitar"] {
    background-color: #4CAF50;
    color: white;
}

button[name="acao"][value="recusar"] {
    background-color: #f44336;
    color: white;
}

button:hover {
    transform: scale(1.05); 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
}

a {
    display: block;
    text-align: center;
    margin-top: 30px;
    text-decoration: none;
    color: #007BFF;
    font-size: 23px; 
    transition: color 0.3s ease; 
}

a:hover {
    text-decoration: underline;
    color: #0056b3; 
}
.nenhum-agendamento {
    font-size: 24px; 
    text-align: center;
    padding: 15px;
    color: #333; 
}
    </style>
</head>
<body>
    <h1>Agendamentos Pendentes</h1>

    <div class="container">
        <?php if (!empty($agendamentosPendentes)): ?>
            <ul>
                <?php foreach ($agendamentosPendentes as $agendamento): ?>
                    <li>
                        <?php 
                        // formata a data e hora do mês
                        $dataFormatada = date('d/m/Y', strtotime($agendamento['data']));
                        echo "{$agendamento['nome']} - {$agendamento['telefone']} - {$agendamento['servico']} - {$dataFormatada} - {$agendamento['hora']}"; 
                        ?>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $agendamento['id']; ?>">
                            <button type="submit" name="acao" value="aceitar">Aceitar</button>
                            <button type="submit" name="acao" value="recusar">Recusar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="nenhum-agendamento">Nenhum agendamento pendente.</p>
        <?php endif; ?>
    </div>

    <a href="logout.php">Sair</a> <!-- Link para logout -->

</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>
