<?php
// lista_computadores.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

// parâmetros
$status  = $_GET['status'] ?? 'Sim';
$search  = trim($_GET['search'] ?? '');
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset  = ($page - 1) * $perPage;

// cláusula WHERE
$where = "WHERE c.ativo = :status";
if ($search !== '') {
    $where .= " AND (c.nome LIKE :ps OR c.descricao LIKE :ps)";
}

// total para paginação
$sqlCount = "SELECT COUNT(*) FROM computadores c $where";
$stmtC    = $conexao->prepare($sqlCount);
$stmtC->bindValue(':status', $status);
if ($search) {
    $stmtC->bindValue(':ps', "%{$search}%");
}
$stmtC->execute();
$total      = $stmtC->fetchColumn();
$totalPages = ceil($total / $perPage);

// consulta principal
$sql = "
  SELECT 
    c.id, 
    c.nome, 
    SUBSTRING(c.descricao,1,60) AS resumo,
    c.ativo, 
    e.nome_fantasia
  FROM computadores c
  JOIN empresas e ON e.id = c.empresa_id
  $where
  ORDER BY c.nome
  LIMIT :lim OFFSET :off
";
$stmt = $conexao->prepare($sql);
$stmt->bindValue(':status', $status);
if ($search) $stmt->bindValue(':ps', "%$search%");
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset,  PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Computadores</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root {
      --primary: #F28B30;
      --light: #F5F7FA;
      --gray: #7A8A99;
      --dark: #333;
      --radius: 4px;
      --spacing: 12px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--light);
      color: var(--dark);
      padding: var(--spacing);
    }
    h1 {
      margin-bottom: calc(var(--spacing) * 1.5);
      font-size: 1.75rem;
      text-align: center;
    }
    .controls {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: var(--spacing);
      margin-bottom: var(--spacing);
    }
    .tabs {
      display: flex;
      gap: var(--spacing);
    }
    .tabs a {
      padding: 6px 14px;
      border-radius: var(--radius);
      text-decoration: none;
      color: var(--gray);
      border: 1px solid transparent;
      transition: all .2s;
    }
    .tabs a.active {
      color: #fff;
      background: var(--primary);
      border-color: var(--primary);
    }
    .search {
      flex-grow: 1;
      max-width: 300px;
      display: flex;
    }
    .search input {
      flex: 1;
      padding: 6px 10px;
      border: 1px solid #ccc;
      border-radius: var(--radius) 0 0 var(--radius);
      font-size: .95rem;
    }
    .search button {
      padding: 6px 12px;
      border: 1px solid var(--primary);
      background: var(--primary);
      color: #fff;
      border-radius: 0 var(--radius) var(--radius) 0;
      cursor: pointer;
      transition: background .2s;
    }
    .search button:hover {
      background: #357ABD;
    }
    .btn {
      padding: 8px 16px;
      background: var(--primary);
      color: #fff;
      border-radius: var(--radius);
      text-decoration: none;
      font-size: .95rem;
      transition: background .2s;
    }
    .btn:hover {
      background: #357ABD;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    th, td {
      padding: var(--spacing);
      text-align: left;
    }
    thead {
      background: var(--primary);
      color: #fff;
      font-weight: normal;
    }
    tbody tr {
      border-bottom: 1px solid #eee;
    }
    tbody tr:last-child {
      border-bottom: none;
    }
    td.status {
      font-weight: bold;
      color: var(--gray);
    }
    td.status.Sim {
      color: green;
    }
    td.status.Não {
      color: red;
    }
    td.actions a {
      margin-right: 8px;
      text-decoration: none;
      font-size: 1rem;
    }
    .pagination {
      margin-top: var(--spacing);
      text-align: center;
    }
    .pagination a {
      margin: 0 4px;
      padding: 6px 10px;
      text-decoration: none;
      color: var(--primary);
      border: 1px solid var(--primary);
      border-radius: var(--radius);
      transition: all .2s;
    }
    .pagination a.current {
      background: var(--primary);
      color: #fff;
    }
  </style>
</head>
<body>

  <h1>Computadores</h1>

  <div class="controls">
    <div class="tabs">
      <a href="?status=Sim<?= $search?"&search=".urlencode($search):'';?>"
         class="<?= $status==='Sim'?'active':''; ?>">Ativos</a>
      <a href="?status=Não<?= $search?"&search=".urlencode($search):'';?>"
         class="<?= $status==='Não'?'active':''; ?>">Inativos</a>
    </div>
    <form method="get" class="search">
      <input type="hidden" name="status" value="<?=htmlspecialchars($status)?>">
      <input type="text" name="search" placeholder="Buscar..." value="<?=htmlspecialchars($search)?>">
      <button type="submit">🔍</button>
    </form>
    <a href="cadastro_computador.php" class="btn">+ Novo Computador</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Empresa</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Status</th>
        <th style="width:100px">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($rows): foreach ($rows as $c): ?>
      <tr>
        <td><?=htmlspecialchars($c['nome_fantasia'])?></td>
        <td><?=htmlspecialchars($c['nome'])?></td>
        <td><?=htmlspecialchars($c['resumo'])?>…</td>
        <td class="status <?= $c['ativo'] ?>"><?= $c['ativo'] ?></td>
        <td class="actions">
          <a href="cadastro_computador.php?id=<?=$c['id']?>" title="Editar">✏️</a>
          <?php if($c['ativo']==='Sim'): ?>
            <a href="acao_inativar_computador.php?id=<?=$c['id']?>&status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$page?>"
               title="Inativar" onclick="return confirm('Inativar este computador?')">🚫</a>
          <?php else: ?>
            <a href="acao_ativar_computador.php?id=<?=$c['id']?>&status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$page?>"
               title="Reativar" onclick="return confirm('Reativar este computador?')">✅</a>
          <?php endif;?>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="5" style="text-align:center; padding: var(--spacing)">Nenhum computador encontrado.</td></tr>
      <?php endif;?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="?status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$p?>"
         class="<?= $p===$page?'current':'' ?>">
        <?=$p?>
      </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

</body>
</html>
