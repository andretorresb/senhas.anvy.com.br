<?php
// bin/usuarios/cadastro_usuario.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

$erro  = $_SESSION['erro'] ?? '';
$old   = $_SESSION['old']  ?? [];
unset($_SESSION['erro'], $_SESSION['old']);

$id    = $_GET['id'] ?? null;
$email = $old['email'] ?? '';
$ativo = $old['ativo'] ?? 'Sim';

if ($id && empty($old)) {
    $stmt = $conexao->prepare("SELECT email, ativo FROM usuarios WHERE id=:id");
    $stmt->execute([':id'=>$id]);
    if ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $email = $r['email'];
        $ativo = $r['ativo'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $id?'Editar':'Cadastrar' ?> Usuário</title>
  <style>
    :root{--primary:#F28B30;--light:#F5F7FA;--gray:#7A8A99;--dark:#333;--radius:6px;--spacing:16px;}
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Segoe UI',sans-serif;background:var(--light);color:var(--dark);display:flex;justify-content:center;padding:var(--spacing);}
    .card{background:#fff;padding:calc(var(--spacing)*1.5);border-radius:var(--radius);box-shadow:0 4px 12px rgba(0,0,0,0.05);width:100%;max-width:400px;}
    h1{text-align:center;margin-bottom:var(--spacing);font-size:1.5rem;}
    .error{background:#fdecea;color:#d9534f;padding:8px;border-radius:var(--radius);margin-bottom:var(--spacing);text-align:center;}
    form > * + *{margin-top:var(--spacing);}
    label{display:block;font-weight:600;color:var(--gray);margin-bottom:6px;}
    input, select{width:100%;padding:10px;border:1px solid #ccc;border-radius:var(--radius);font-size:1rem;transition:.2s;}
    input:focus,select:focus{border-color:var(--primary);outline:none;}
    .actions{display:flex;justify-content:space-between;margin-top:calc(var(--spacing)*1.5);}
    .actions a{color:var(--gray);text-decoration:none;}
    .actions a:hover{text-decoration:underline;}
    button{background:var(--primary);color:#fff;border:none;padding:10px 20px;border-radius:var(--radius);cursor:pointer;transition:.2s;}
    button:hover{background:#357ABD;}
  </style>
</head>
<body>

<div class="card">
  <h1><?= $id?'Editar':'Cadastrar' ?> Usuário</h1>
  <?php if ($erro): ?><div class="error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

  <form method="post" action="acao_cadastro_usuario.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label for="email">E-mail <span style="color:red">*</span></label>
    <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($email) ?>" placeholder="usuario@exemplo.com">

    <?php if (!$id): ?>
    <label for="senha">Senha <span style="color:red">*</span></label>
    <input type="password" id="senha" name="senha" required placeholder="Defina uma senha">
    <?php endif; ?>

    <label for="ativo">Ativo</label>
    <select name="ativo" id="ativo">
      <option value="Sim" <?= $ativo==='Sim'?'selected':'' ?>>Sim</option>
      <option value="Não" <?= $ativo==='Não'?'selected':'' ?>>Não</option>
    </select>

    <div class="actions">
      <a href="usuarios.php">« Voltar</a>
      <button type="submit"><?= $id?'Salvar':'Cadastrar' ?></button>
    </div>
  </form>
</div>

</body>
</html>
