<?php
require_once 'funcoes.php';
protegerPagina();

$empresas = carregarEmpresas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeEmpresa = trim($_POST['nome_empresa']);
    $pc = trim($_POST['pc']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Criptografa a senha
    $senhaEnc = encryptSenha($senha);

    // Verifica se a empresa ja existe
    $empresaExiste = false;
    foreach ($empresas as &$empresa) {
        if (strcasecmp($empresa['nome'], $nomeEmpresa) === 0) {
            // Adiciona novo PC na empresa existente
            $empresa['pcs'][] = [
                'pc' => $pc,
                'usuario' => $usuario,
                'senha' => $senhaEnc
            ];
            $empresaExiste = true;
            break;
        }
    }
    unset($empresa);

    if (!$empresaExiste) {
        // Cria empresa nova
        $empresas[] = [
            'nome' => $nomeEmpresa,
            'pcs' => [
                [
                    'pc' => $pc,
                    'usuario' => $usuario,
                    'senha' => $senhaEnc
                ]
            ]
        ];
    }

    salvarEmpresas($empresas);
    header('Location: listaempresas.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar PC a Empresa</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }
    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
      color: #555;
    }
    input[type="text"],
    input[type="password"],
    input[list] {
      width: 100%;
      padding: 10px 12px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    button {
      width: 100%;
      margin-top: 25px;
      padding: 12px;
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    a {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #007bff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Adicionar PC a Empresa</h2>
  <form method="post" action="">
    <label>Empresa:</label>
    <input list="empresas-list" name="nome_empresa" required>
    <datalist id="empresas-list">
      <?php foreach ($empresas as $empresa): ?>
        <option value="<?= htmlspecialchars($empresa['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"></option>
      <?php endforeach; ?>
    </datalist>

    <label>PC:</label>
    <input type="text" name="pc" required>

    <label>Usuario:</label>
    <input type="text" name="usuario" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <button type="submit">Salvar</button>
  </form>

  <a href="listaempresas.php">? Voltar para a lista</a>
</div>

</body>
</html>
