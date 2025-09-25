<?php
session_start();
if (!empty($_SESSION['user_email'])) {
    header('Location: bin/menu.php');
    exit;
}
$erro      = $_SESSION['erro']      ?? '';
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['erro'], $_SESSION['old_email']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ello - Gerenciador de Senhas</title>
<link rel="shortcut icon" href="imagens/logo.webp" type="image/x-icon">
<style>
  * {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;
  }

  body {
    background: #f1f1f1ff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .login-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    width: 95%;
    max-width: 400px;
  }

  .logomarca{
    width: 70%;
    display: flex;
    margin-inline: auto;
    margin-bottom: 30px;
  }

  .input-group {
    position: relative;
    margin-bottom: 20px;
  }

  .input-group img {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    fill: #888;
    width: 20px;
    height: 20px;
  }

  input[type="email"], input[type="password"] {
    width: 100%;
    padding: 8px 8px 8px 40px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
  }

  input[type="email"]:focus, input[type="password"]:focus {
    border-color: #F28B30;
    outline: none;
  }

  button {
    width: 100%;
    background-color: #F28B30;
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  button:hover {
    background-color: #F28B30;
  }
  button img{
    height: 30px;
    animation: chamarAtencao 1s ease-in-out infinite;
  }
  @keyframes chamarAtencao {
  0%, 100% {
    transform: translateX(0);
  }
  50% {
    transform: translateX(10px);
  }
}
 .error {
  top: 0;
  display: flex;
  position: absolute;
  margin: 10px auto;
  color: #fff;
  background-color: #d9534f ;
  padding: 5px 10px;
  border-radius: 3px;
  text-align: center;
  margin-bottom: 20px;
  opacity: 1;          
  animation: esconder 4s ease forwards;
}

@keyframes esconder {
  0%, 75% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

</style>
</head>
<body>
  <?php if ($erro): ?>
    <p class="error"><?= htmlspecialchars($erro) ?></p>
  <?php endif; ?>

  <div class="login-container">
    <img src="imagens/logomarca.webp" alt="Logomarca" class="logomarca">

    <form method="post" autocomplete="off" action="acao_login.php">
      <div class="input-group">
        <img src="icones/person_500dp_B7B7B7_FILL0_wght400_GRAD0_opsz48.svg" >
        <input
          type="email"
          name="email"
          placeholder="E-mail"
          required
          value="<?= htmlspecialchars($old_email) ?>"
        >
      </div>

      <div class="input-group">
        <img src="icones/lock_500dp_B7B7B7_FILL0_wght400_GRAD0_opsz48.svg">
        <input type="password" name="senha" placeholder="Senha" required>
      </div>

      <button type="submit">
        <img src="icones/arrow_right_alt_500dp_FFFFFF_FILL0_wght400_GRAD0_opsz48.svg">
      </button>
    </form>
  </div>
</body>
</html>