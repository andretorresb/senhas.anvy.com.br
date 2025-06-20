<?php
// cadastro_computador.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

// busca lista de empresas (para o select)
$stmtE    = $conexao->query("SELECT id, nome_fantasia FROM empresas WHERE ativo='Sim' ORDER BY nome_fantasia");
$empresas = $stmtE->fetchAll(PDO::FETCH_ASSOC);

// recupera erros e old
$erro      = $_SESSION['erro'] ?? '';
$old       = $_SESSION['old'] ?? [];
unset($_SESSION['erro'], $_SESSION['old']);

$id        = $_GET['id'] ?? null;
$empresaId = $old['empresa_id'] ?? '';
$nome      = $old['nome']       ?? '';
$descricao = $old['descricao']  ?? '';

// se for edição e não veio old, carrega do banco
if ($id && empty($old)) {
    $sql = "SELECT empresa_id, nome, descricao 
              FROM computadores 
             WHERE id = :id 
             LIMIT 1";
    $st = $conexao->prepare($sql);
    $st->bindValue(':id', $id, PDO::PARAM_INT);
    $st->execute();
    if ($r = $st->fetch(PDO::FETCH_ASSOC)) {
        $empresaId = $r['empresa_id'];
        $nome      = $r['nome'];
        $descricao = $r['descricao'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $id ? 'Editar' : 'Cadastrar' ?> Computador</title>
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
      max-width: 500px;
    }
    h1 {
      font-size: 1.5rem;
      margin-bottom: var(--spacing);
      text-align: center;
    }
    .error {
      background: #fdecea;
      color: #d9534f;
      padding: 8px 12px;
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
    select, input[type="text"], textarea {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: var(--radius);
      font-size: 1rem;
      transition: border-color .2s;
    }
    select:focus, input:focus, textarea:focus {
      border-color: var(--primary);
      outline: none;
    }
    textarea {
      resize: vertical;
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
    <h1><?= $id ? 'Editar' : 'Cadastrar' ?> Computador</h1>

    <?php if ($erro): ?>
      <div class="error"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post" action="acao_cadastro_computador.php">
      <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

      <div>
        <label for="empresa">Empresa <span style="color:red">*</span></label>
        <select name="empresa_id" id="empresa" required>
          <option value="">Selecione…</option>
          <?php foreach ($empresas as $e): ?>
            <option value="<?= $e['id'] ?>" <?= ($e['id'] == $empresaId ? 'selected' : '') ?>>
              [<?= $e['id'] ?>] <?= htmlspecialchars($e['nome_fantasia']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="nome">Nome do Computador <span style="color:red">*</span></label>
        <input type="text" name="nome" id="nome" required
               value="<?= htmlspecialchars($nome) ?>" placeholder="Digite o nome">
      </div>

      <div>
        <label for="desc">Descrição</label>
        <textarea name="descricao" id="desc" rows="5"
                  placeholder="Informações adicionais…"><?= htmlspecialchars($descricao) ?></textarea>
      </div>

      <div class="actions">
        <a href="computadores.php">« Voltar à lista</a>
        <button type="submit"><?= $id ? 'Salvar' : 'Cadastrar' ?></button>
      </div>
    </form>
  </div>

</body>
</html>
