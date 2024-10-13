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
</head>
<body>
    <h1>Login</h1>
    <?php if ($mensagemErro): ?>
        <p style="color: red;"><?php echo $mensagemErro; ?></p>
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
</body>
</html>
