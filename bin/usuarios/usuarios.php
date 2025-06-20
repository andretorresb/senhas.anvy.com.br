<?php
// bin/usuarios/lista_usuarios.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

$search  = trim($_GET['search'] ?? '');
$status  = $_GET['status'] ?? 'Sim';

$sql = "
  SELECT id, email, ativo
    FROM usuarios
   WHERE ativo = ?
     AND email LIKE ?
   ORDER BY email ASC
";
$stmt = $conexao->prepare($sql);
$stmt->execute([$status, "%{$search}%"]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Usuários</title>
  <style>
    :root {
      --primary:#4A90E2;--light:#F5F7FA;--gray:#7A8A99;
      --dark:#333;--radius:6px;--spacing:16px;
    }
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Segoe UI',sans-serif;background:var(--light);color:var(--dark);padding:var(--spacing);}
    header{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:var(--spacing);}
    header h1{font-size:1.5rem;}
    .search{display:flex;gap:8px;margin-top:8px;}
    .search input{flex:1;padding:6px 10px;border:1px solid #ccc;border-radius:var(--radius);}
    .search button{background:var(--primary);color:#fff;border:none;padding:6px 12px;border-radius:var(--radius);cursor:pointer;}
    .tabs{display:flex;gap:8px;}
    .tabs a{padding:6px 12px;border-radius:var(--radius);text-decoration:none;color:var(--gray);border:1px solid transparent;transition:.2s;}
    .tabs a.active{background:var(--primary);color:#fff;border-color:var(--primary);}
    table{width:100%;border-collapse:collapse;background:#fff;border-radius:var(--radius);overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);}
    th,td{padding:var(--spacing);text-align:left;font-size:.95rem;}
    thead{background:var(--primary);color:#fff;}
    tbody tr{border-bottom:1px solid #eee;}
    tbody tr:last-child{border-bottom:none;}
    td.actions a{margin-right:8px;text-decoration:none;font-size:1rem;}
    .btn{padding:8px 16px;background:var(--primary);color:#fff;border-radius:var(--radius);text-decoration:none;transition:.2s;}
    .btn:hover{background:#357ABD;}
  </style>
</head>
<body>

<header>
  <h1>Usuários</h1>
  <form method="get" class="search">
    <input name="search" placeholder="Buscar e-mail..." value="<?= htmlspecialchars($search) ?>">
    <button>🔍</button>
  </form>
  <div class="tabs">
    <a href="?status=Sim<?= $search?'&search='.urlencode($search):''?>" class="<?= $status==='Sim'?'active':'' ?>">Ativos</a>
    <a href="?status=Não<?= $search?'&search='.urlencode($search):''?>" class="<?= $status==='Não'?'active':'' ?>">Inativos</a>
    <a href="cadastro_usuario.php" class="btn">+ Novo</a>
  </div>
</header>

<table>
  <thead>
    <tr><th>E-mail</th><th>Status</th><th style="width:100px">Ações</th></tr>
  </thead>
  <tbody>
    <?php if ($users): foreach ($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['ativo'] ?></td>
        <td class="actions">
          <a href="cadastro_usuario.php?id=<?= $u['id'] ?>" title="Editar">✏️</a>
          <?php if ($u['ativo']==='Sim'): ?>
            <a href="acao_inativar_usuario.php?id=<?= $u['id'] ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>"
               title="Inativar" onclick="return confirm('Inativar usuário?')">🚫</a>
          <?php else: ?>
            <a href="acao_ativar_usuario.php?id=<?= $u['id'] ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>"
               title="Reativar" onclick="return confirm('Reativar usuário?')">✅</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="3" style="text-align:center;color:var(--gray)">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>
