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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Cor de fundo suave */
            color: #333; /* Cor do texto */
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 600px; /* Largura máxima da lista */
            margin: 0 auto; /* Centraliza horizontalmente */
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        li {
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            background-color: #fff; /* Cor de fundo dos itens */
            border-radius: 5px; /* Bordas arredondadas */
            text-align: center; /* Centraliza o texto dos agendamentos */
        }

        form {
            display: inline; /* Alinha os botões em linha */
            margin-left: 10px; /* Espaçamento entre o texto e os botões */
            margin-top: 10px; /* Espaçamento acima dos botões */
        }

        button {
            padding: 5px 10px;
            margin: 0 5px; /* Espaçamento entre os botões */
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[name="acao"][value="aceitar"] {
            background-color: #4CAF50; /* Verde para aceitar */
            color: white;
        }

        button[name="acao"][value="recusar"] {
            background-color: #f44336; /* Vermelho para recusar */
            color: white;
        }

        a {
            display: block; /* Exibe o link para logout em bloco */
            text-align: center; /* Centraliza o link */
            margin-top: 20px; /* Margem acima do link */
            text-decoration: none;
            color: #007BFF; /* Cor do link */
        }

        a:hover {
            text-decoration: underline; /* Efeito ao passar o mouse */
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
                        // Formata a data no formato dia/mês/ano
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
            <p>Nenhum agendamento pendente.</p>
        <?php endif; ?>
    </div>

    <a href="logout.php">Sair</a> <!-- Link para logout -->

</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>
