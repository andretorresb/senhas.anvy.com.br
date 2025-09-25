<?php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ello - Gerenciador de Senhas</title>
  <link rel="shortcut icon" href="../imagens/logo.webp" type="image/x-icon">
  <style>
     * {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;
  }
  body{
    background-color: #f7f7f7;
  }
  header{
    width: 100%;
    height: 50px;
    background-color: #F28B30;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
    display:flex;
  }
  header img{
    padding: 5px;
    display: flex;
    height: 100%;
    cursor:pointer;
  }
  header span{
    height: 100%;
    align-content: center;
    font-size: large;
    color:#fff;
    padding: 0 20px;
  }
  .menu {
      position: fixed;
      top: 50px;
      left: -80%;            /* escondido à esquerda */
      width: 80%;
      height: calc(100vh - 50px);
      background: #fff;
      box-shadow: 2px 0 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      padding: 20px;
      transition: left 0.3s ease;
      z-index: 1000;
    }
    .menu.open {
      left: 0;                /* fica visível */
    }
    .menu a {
      text-decoration: none;
      color: #333;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }
    .menu a:last-child {
      border: none;
    }
    /* iframe ocupa toda área abaixo do header */
    iframe#contentFrame {
      position: absolute;
      top: 50px;
      left: 0;
      width: 100%;
      height: calc(100vh - 50px);
      border: none;
    }
   </style>
</head>
<body>
  <header>
    <img id="btnMenu" src="../icones/dehaze_500dp_FFFFFF_FILL0_wght400_GRAD0_opsz48.svg" alt="Menu">
    <span>Ello - Gerenciador de Senhas</span>
  </header>

  <div class="menu" id="menu">
    <a href="dashboard.php"     target="contentFrame">Dashboard</a>
    <a href="empresas/empresas.php"       target="contentFrame">Cadastro de Empresas</a>
    <a href="computadores/computadores.php"       target="contentFrame">Cadastro de Computadores</a>
    <a href="usuarios/usuarios.php"       target="contentFrame">Cadastro de Usuários</a>
    <a href="logout.php"           >Sair</a>
  </div>

  <iframe id="contentFrame" name="contentFrame" src="dashboard.php"></iframe>

  <script>
    const btnMenu = document.getElementById('btnMenu');
    const menu    = document.getElementById('menu');
    const links   = menu.querySelectorAll('a');

    // toggle do menu
    btnMenu.addEventListener('click', () => {
      menu.classList.toggle('open');
    });

    // ao clicar em qualquer link, carrega no iframe e fecha menu
    links.forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.remove('open');
      });
    });
  </script>
</body>
</html>