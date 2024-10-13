<?php
session_start(); // Inicia a sessão

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

// Mensagem inicial
$mensagem = '';

// Verificar se a data é um domingo e inserir dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $servico = $_POST['servico'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    // Verificar se a data é um domingo
    $diaDaSemana = date('w', strtotime($data)); // 0 = domingo

    if ($diaDaSemana == 0) {
        $_SESSION['mensagem'] = "Agendamento não permitido para domingos!";
    } else {
        // Verificar se a hora está dentro do intervalo permitido
        $horaFormatada = date('H:i', strtotime($hora));
        if ($horaFormatada < '08:00' || $horaFormatada > '18:00') {
            $_SESSION['mensagem'] = "O horário deve estar entre 08:00 e 18:00.";
        } else {
            // Verificar se já existe um agendamento pendente para essa data e hora
            $sqlVerificacao = "SELECT * FROM agendamentos_pendentes WHERE data = '$data' AND hora = '$hora'";
            $resultadoVerificacao = $conn->query($sqlVerificacao);

            if ($resultadoVerificacao->num_rows > 0) {
                $_SESSION['mensagem'] = "Esse horário já está agendado.";
            } else {
                // Inserir no banco de dados na tabela pendente
                $sql = "INSERT INTO agendamentos_pendentes (nome, telefone, servico, data, hora) VALUES ('$nome', '$telefone', '$servico', '$data', '$hora')";

                if ($conn->query($sql) === TRUE) {
                    $_SESSION['mensagem'] = "Agendamento enviado para aprovação!";
                } else {
                    $_SESSION['mensagem'] = "Erro: " . $conn->error;
                }
            }
        }
    }

    // Redirecionar após o envio do formulário para evitar duplicação
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Mostrar mensagem se existir
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Online</title>
    <link rel="stylesheet" href="agendamentos.css">
</head>
<body>
    <h1>Agende seu horário com o Studio Keila</h1>

    <form id="agendamento-form" method="post" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" pattern="[0-9]{11}" placeholder="Ex: 11987654321" required>

        <label for="servico">Serviço:</label>
        <select id="servico" name="servico" required>
            <option value="">Selecione um serviço</option>
            <option value="Manicure">Manicure</option>
            <option value="Pedicure">Pedicure</option>
            <option value="Progressiva">Progressiva</option>
            <option value="Tintura de cabelo">Tintura de cabelo</option>
        </select>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data" required>

        <label for="hora">Hora:</label>
        <select id="hora" name="hora" required>
            <option value="">Selecione a hora</option>
            <?php
            // Gera opções de horários permitidos
            for ($h = 8; $h <= 18; $h++) {
                for ($m = 0; $m < 60; $m += 30) {
                    $horaDisponivel = sprintf("%02d:%02d", $h, $m);
                    echo "<option value=\"$horaDisponivel\">$horaDisponivel</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Agendar</button>
    </form>

    <div id="mensagem">
        <?php if (!empty($mensagem)) echo $mensagem; ?>
    </div>
</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>
