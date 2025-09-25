<?php
// lista_empresas.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../../index.php');
    exit;
}
require_once '../../conexao.php';

// parâmetros de filtro/pesquisa/página
$status     = $_GET['status'] ?? 'Sim';
$search     = trim($_GET['search'] ?? '');
$page       = max(1, (int)($_GET['page'] ?? 1));
$perPage    = 10;
$offset     = ($page - 1) * $perPage;

// monta cláusula WHERE
$where = "WHERE ativo = :status";
if ($search !== '') {
    $where .= " AND (
      cnpj_cpf      LIKE :pesq OR
      nome_fantasia LIKE :pesq OR
      razao_social  LIKE :pesq
    )";
}

// conta total para paginação
$sqlCount   = "SELECT COUNT(*) FROM empresas $where";
$stmtCount  = $conexao->prepare($sqlCount);
$stmtCount->bindValue(':status', $status);
if ($search !== '') {
    $stmtCount->bindValue(':pesq', "%{$search}%");
}
$stmtCount->execute();
$total      = (int)$stmtCount->fetchColumn();
$totalPages = ceil($total / $perPage);

// busca página atual
$sql = "
  SELECT id, cnpj_cpf, nome_fantasia, razao_social, ativo
    FROM empresas
    $where
   ORDER BY nome_fantasia
   LIMIT :lim OFFSET :off
";
$stmt = $conexao->prepare($sql);
$stmt->bindValue(':status', $status);
if ($search !== '') {
    $stmt->bindValue(':pesq', "%{$search}%");
}
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset,  PDO::PARAM_INT);
$stmt->execute();
$empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Empresas Cadastradas</title>
  <style>
    :root {
      --primary: #F28B30;
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
      padding: var(--spacing);
    }
    h1 {
      text-align: center;
      margin-bottom: calc(var(--spacing) * 1.5);
      font-size: 1.75rem;
    }
    .controls {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
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
      font-size: .95rem;
    }
    .tabs a.active {
      background: var(--primary);
      color: #fff;
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
    thead {
      background: var(--primary);
      color: #fff;
    }
    th, td {
      padding: var(--spacing);
      text-align: left;
      font-size: .95rem;
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
      text-align: center;
      margin-top: var(--spacing);
    }
    .pagination a {
      margin: 0 4px;
      padding: 6px 10px;
      text-decoration: none;
      color: var(--primary);
      border: 1px solid var(--primary);
      border-radius: var(--radius);
      transition: all .2s;
      font-size: .95rem;
    }
    .pagination a.current {
      background: var(--primary);
      color: #fff;
    }
  </style>
</head>
<body>

  <h1>Empresas Cadastradas</h1>

  <div class="controls">
    <div class="tabs">
      <a href="?status=Sim<?= $search?"&search=".urlencode($search):'';?>"
         class="<?= $status==='Sim'?'active':'';?>">Ativas</a>
      <a href="?status=Não<?= $search?"&search=".urlencode($search):'';?>"
         class="<?= $status==='Não'?'active':'';?>">Inativas</a>
    </div>

    <form method="get" class="search">
      <input type="hidden" name="status" value="<?=htmlspecialchars($status)?>">
      <input type="text" name="search" placeholder="Buscar..." value="<?=htmlspecialchars($search)?>">
      <button type="submit">🔍</button>
    </form>

    <a href="cadastro_empresa.php" class="btn">+ Nova Empresa</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>CNPJ/CPF</th>
        <th>Nome Fantasia</th>
        <th>Razão Social</th>
        <th>Status</th>
        <th style="width:100px">Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($empresas): foreach ($empresas as $e): ?>
      <tr>
        <td><?=htmlspecialchars($e['cnpj_cpf'])?></td>
        <td><?=htmlspecialchars($e['nome_fantasia'])?></td>
        <td><?=htmlspecialchars($e['razao_social'])?></td>
        <td class="status <?=$e['ativo']?>"><?=$e['ativo']?></td>
        <td class="actions">
          <a href="cadastro_empresa.php?id=<?=$e['id']?>" title="Editar">✏️</a>
          <?php if ($e['ativo']==='Sim'): ?>
            <a href="acao_inativar_empresa.php?id=<?=$e['id']?>&status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$page?>"
               title="Inativar" onclick="return confirm('Inativar esta empresa?')">🚫</a>
          <?php else: ?>
            <a href="acao_ativar_empresa.php?id=<?=$e['id']?>&status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$page?>"
               title="Reativar" onclick="return confirm('Reativar esta empresa?')">✅</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="5" style="text-align:center; padding: var(--spacing)">Nenhuma empresa encontrada.</td></tr>
      <?php endif;?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="?status=<?=$status?>&search=<?=urlencode($search)?>&page=<?=$p?>"
         class="<?=$p===$page?'current':''?>"><?=$p?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

</body>
</html>
