<?php
session_start();

// Conexão com banco
$config = require __DIR__ . '/config/database.php';

$conn = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['db']);
mysqli_set_charset($conn, "utf8");

if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}


// Se já está logado, vai direto pro gestor
if (isset($_SESSION['usuario'])) {
    header("Location: gestor/index.php");
    exit;
}

$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Consulta segura com prepared statement
    $sql = "SELECT id, usuario, senha FROM admins WHERE usuario = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verifica senha (hash)
        if (password_verify($senha, $row['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['usuario_id'] = $row['id'];
            header("Location: gestor/index.php?logado=1");
            exit;
        }
    }
    $erro = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Inpakto Mídia</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { height: 100vh; margin: 0; display: flex; justify-content: center; align-items: center; background: #fff; color: #000; }
        .login-container { background: #fff; padding: 2.5rem 3rem 3rem; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); width: 340px; text-align: center; border: 1.5px solid #000; }
        .logo { margin-bottom: 1.8rem; }
        .logo img { max-width: 150px; display: block; margin: 0 auto; }
        input { width: 100%; padding: 0.75rem 1rem; margin: 0.5rem 0 1.2rem; border: 1.5px solid #000; border-radius: 8px; font-size: 1rem; background: #fff; color: #000; transition: border-color 0.3s; }
        input:focus { border-color: #d72631; outline: none; }
        button { background: #d72631; color: white; padding: 0.85rem 0; width: 100%; border: none; border-radius: 8px; font-size: 1.1rem; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #a51b24; }
        .login-footer { margin-top: 1rem; font-size: 0.9rem; color: #555; }
        .login-footer a { color: #d72631; text-decoration: none; font-weight: 600; }
        .login-footer a:hover { text-decoration: underline; }
        .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="public/assets/img/logo_gestor.png" alt="Logomarca" />
    </div>

    <?php if ($erro): ?>
        <div class="erro">Usuário ou senha incorretos!</div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="usuario" placeholder="Usuário" required />
        <input type="password" name="senha" placeholder="Senha" required />
        <button type="submit">Login</button>
    </form>

</div>
</body>
</html>
