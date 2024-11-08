<?php
session_start(); // Inicia a sessão

// Verifica se já está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php"); // Redireciona para a página admin se já estiver logado
    exit;
}

// Defina suas credenciais de login (você pode usar um banco de dados para isso)
$usuarioCorreto = "admin"; // Nome de usuário correto
$senhaCorreta = "senha123"; // Senha correta

$mensagemErro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se as credenciais estão corretas
    if ($usuario === $usuarioCorreto && $senha === $senhaCorreta) {
        $_SESSION['loggedin'] = true; // Define a sessão como logada
        header("Location: admin.php"); // Redireciona para a página admin
        exit;
    } else {
        $mensagemErro = "Usuário ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column; /* Adiciona esta linha para empilhar verticalmente */
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px; /* Define uma largura fixa para o formulário */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .erro {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if ($mensagemErro): ?>
        <p class="erro"><?php echo $mensagemErro; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
    <br>
    <a href="inicial.html">Voltar à página inicial</a>
</body>
</html>
