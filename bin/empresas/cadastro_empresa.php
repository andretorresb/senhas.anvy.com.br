<?php
// cadastro_empresa.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

// recupera mensagem de erro e old
$erroMsg = $_SESSION['erro'] ?? '';
$old     = $_SESSION['old'] ?? [];
unset($_SESSION['erro'], $_SESSION['old']);

$id        = $_GET['id'] ?? null;

// se edição e não veio old, carrega do banco
if ($id && empty($old)) {
    $sql = "SELECT cnpj_cpf, nome_fantasia, razao_social 
              FROM empresas 
             WHERE id = :id 
             LIMIT 1";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $old['cnpj_cpf']      = $row['cnpj_cpf'];
        $old['nome_fantasia'] = $row['nome_fantasia'];
        $old['razao_social']  = $row['razao_social'];
    }
}

$cnpj_cpf = $old['cnpj_cpf']      ?? '';
$fantasia = $old['nome_fantasia'] ?? '';
$razao    = $old['razao_social']  ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $id ? 'Editar' : 'Cadastrar' ?> Empresa</title>
  <style>
    :root {
      --primary: #4A90E2;
      --light:   #F5F7FA;
      --gray:    #7A8A99;
      --dark:    #333;
      --radius:  6px;
      --spacing: 16px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--light);
      color: var(--dark);
      display: flex;
      justify-content: center;
      padding: var(--spacing);
    }
    .card {
      background: #fff;
      padding: calc(var(--spacing) * 1.5);
      border-radius: var(--radius);
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      width: 100%;
      max-width: 450px;
    }
    h1 {
      font-size: 1.5rem;
      margin-bottom: var(--spacing);
      text-align: center;
    }
    .error {
      background: #fdecea;
      color: #d9534f;
      padding: 10px 14px;
      border-radius: var(--radius);
      margin-bottom: var(--spacing);
      text-align: center;
    }
    form > * + * {
      margin-top: var(--spacing);
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--gray);
    }
    input[type="text"], textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: var(--radius);
      font-size: 1rem;
      transition: border-color .2s;
    }
    input[type="text"]:focus, textarea:focus {
      border-color: var(--primary);
      outline: none;
    }
    textarea {
      resize: vertical;
      height: 100px;
    }
    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: calc(var(--spacing) * 1.5);
    }
    .actions a {
      color: var(--gray);
      text-decoration: none;
      font-size: .95rem;
    }
    .actions a:hover {
      text-decoration: underline;
    }
    button {
      background: var(--primary);
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: var(--radius);
      font-size: 1rem;
      cursor: pointer;
      transition: background .2s;
    }
    button:hover {
      background: #357ABD;
    }
  </style>
</head>
<body>

  <div class="card">
    <h1><?= $id ? 'Editar' : 'Cadastrar' ?> Empresa</h1>

    <?php if ($erroMsg): ?>
      <div class="error"><?= htmlspecialchars($erroMsg) ?></div>
    <?php endif; ?>

    <form method="post" action="acao_cadastro_empresa.php">
      <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

      <div>
        <label for="cnpj_cpf">CNPJ ou CPF <span style="color:red">*</span></label>
        <input
          type="text"
          id="cnpj_cpf"
          name="cnpj_cpf"
          required
          maxlength="14"
          pattern="\d{11,14}"
          oninput="this.value = this.value.replace(/\D/g, '')"
          value="<?= htmlspecialchars($cnpj_cpf) ?>"
          placeholder="Somente números (11 a 14 dígitos)"
        >
      </div>

      <div>
        <label for="fantasia">Nome Fantasia</label>
        <input
          type="text"
          id="fantasia"
          name="nome_fantasia"
          value="<?= htmlspecialchars($fantasia) ?>"
          placeholder="Opcional"
        >
      </div>

      <div>
        <label for="razao">Razão Social</label>
        <textarea
          id="razao"
          name="razao_social"
          placeholder="Opcional"
        ><?= htmlspecialchars($razao) ?></textarea>
      </div>

      <div class="actions">
        <a href="empresas.php">« Voltar à lista</a>
        <button type="submit"><?= $id ? 'Salvar' : 'Cadastrar' ?></button>
      </div>
    </form>
  </div>

</body>
</html>
