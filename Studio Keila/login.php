<?php
session_start(); // Inicia a sessão

// faz a verificação pra ver se está logado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: agendamentospendentes.php"); // redireciona para a pagina se estiver logado
    exit;
}

// credenciais de login
$usuarioCorreto = "admin"; // Nome de usuário correto
$senhaCorreta = "senha123"; // Senha correta

$mensagemErro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // verificação das credenciais pra ver se está correto
    if ($usuario === $usuarioCorreto && $senha === $senhaCorreta) {
        $_SESSION['loggedin'] = true; // Define a seção como logada
        header("Location: agendamentospendentes.php"); // Redireciona para a página admin
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
            background-image: url('fundocurriculo.jpg'); 
            background-size: cover; 
            background-position: center center; 
            background-attachment: fixed; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            font-size: 18px;
            color: white; /
        }

        h1 {
            color: #333;
            font-size: 36px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 12px;
            color: #555;
            font-size: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 92%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 18px;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: black;
            text-decoration: underline;
            font-size: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        .erro {
            color: red;
            text-align: center;
            font-size: 20px;
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
