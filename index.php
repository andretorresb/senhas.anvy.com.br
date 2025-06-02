<?php 
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $users = file_exists('data/users.json') ? json_decode(file_get_contents('data/users.json'), true) : [];

    foreach ($users as $user) {
        if ($user['usuario'] === $usuario && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = $usuario;
            header('Location: dashboard.php');
            exit;
        }
    }

    $erro = "Usuario ou senha invalidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Senhas login</title>
<style>
  /* Reset e ajustes b�sicos */
  * {
    box-sizing: border-box;
  }

  body {
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 20px;
  }

  .login-container {
    background: white;
    padding: 40px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
  }

  .input-group {
    position: relative;
    margin-bottom: 25px;
  }

  .input-group svg {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    fill: #888;
    width: 20px;
    height: 20px;
  }

  input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px 12px 12px 42px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
  }

  input[type="text"]:focus, input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
  }

  button {
    width: 100%;
    padding: 14px;
    background-color: #007bff;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
  }

  button:hover {
    background-color: #0056b3;
  }

  p {
    text-align: center;
    margin-top: 20px;
  }

  a {
    color: #007bff;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }

  .error {
    color: #d9534f;
    text-align: center;
    margin-bottom: 20px;
  }
</style>
</head>
<body>

<div class="login-container">
  <h2>Login</h2>

  <?php if ($erro): ?>
    <p class="error"><?= htmlspecialchars($erro) ?></p>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <div class="input-group">
      <!-- �cone de usu�rio (SVG) -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
      <input type="text" name="usuario" placeholder="Usuario" required>
    </div>

    <div class="input-group">
      <!-- �cone de cadeado (senha) -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M18 10h-1V7a5 5 0 00-10 0v3H6a2 2 0 00-2 2v7a2 2 0 002 2h12a2 2 0 002-2v-7a2 2 0 00-2-2zm-6 6a2 2 0 110-4 2 2 0 010 4z"/>
      </svg>
      <input type="password" name="senha" placeholder="Senha" required>
    </div>

    <button type="submit">Entrar</button>
  </form>

  <p><a href="register.php">Criar conta</a></p>
</div>

</body>
</html>
