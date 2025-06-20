<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    .menu {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-top: 30px;
    }
    .menu a {
      display: block;
      padding: 20px;
      text-align: center;
      background: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .menu a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  
  <div class="menu">
    <a href="novaempresa.php">Cadastrar Empresa</a>
    <a href="listaempresas.php">Ver Empresas</a>
    <a href="logout.php">Sair</a>
  </div>
</body>
</html>