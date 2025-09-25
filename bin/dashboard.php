<?php
// bin/dashboard.php
session_start();
if (empty($_SESSION['user_email'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../conexao.php';

$search = trim($_GET['search'] ?? '');

// Busca apenas ativos
$sql = "
  SELECT 
    e.id               AS empresa_id,
    COALESCE(e.nome_fantasia, e.cnpj_cpf) AS empresa_label,
    c.id               AS pc_id,
    c.nome             AS pc_nome,
    c.descricao        AS pc_desc
  FROM empresas e
  LEFT JOIN computadores c 
    ON c.empresa_id = e.id AND c.ativo = 'Sim'
  WHERE e.ativo = 'Sim'
    AND (
      e.nome_fantasia LIKE ? 
      OR e.cnpj_cpf     LIKE ? 
      OR e.razao_social LIKE ?
      OR c.nome         LIKE ?
    )
  ORDER BY 
    COALESCE(e.nome_fantasia,e.cnpj_cpf) ASC,
    c.nome ASC
";
$stmt = $conexao->prepare($sql);
$term = "%{$search}%";
for ($i = 1; $i <= 4; $i++) {
    $stmt->bindValue($i, $term, PDO::PARAM_STR);
}
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// agrupa por empresa
$empresas = [];
foreach ($rows as $r) {
    $eid = $r['empresa_id'];
    if (!isset($empresas[$eid])) {
        $empresas[$eid] = [
            'label' => $r['empresa_label'],
            'pcs'   => []
        ];
    }
    if ($r['pc_id']) {
        $empresas[$eid]['pcs'][] = [
            'nome' => $r['pc_nome'],
            'desc' => $r['pc_desc']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Empresas & Computadores</title>
  <style>
    :root {
      --primary: #F28B30;
      --light:   #F5F7FA;
      --gray:    #7A8A99;
      --dark:    #333;
      --radius:  6px;
      --spacing: 16px;
    }
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
      font-family:'Segoe UI',sans-serif;
      background:var(--light);
      color:var(--dark);
      padding:var(--spacing);
    }
    header {
      display:flex;
      flex-wrap: wrap;
      align-items:center;
      justify-content: space-between;
      margin-bottom:var(--spacing);
    }
    header h1 {
      font-size:1.5rem;
    }
    .search {
      display:flex;
      gap:8px;
      margin-top:8px;
    }
    .search input {
      padding:6px 10px;
      border:1px solid #ccc;
      border-radius:var(--radius);
      flex:1;
    }
    .search button {
      background:var(--primary);
      color:#fff;
      border:none;
      padding:6px 12px;
      border-radius:var(--radius);
      cursor:pointer;
    }
    details.company {
      background:#fff;
      border:1px solid #ddd;
      border-radius:var(--radius);
      margin-bottom:var(--spacing);
      box-shadow:0 2px 6px rgba(0,0,0,0.05);
      overflow:hidden;
    }
    details.company summary {
      cursor:pointer;
      font-weight:600;
      padding:var(--spacing);
      display:flex;
      align-items:center;
      gap:8px;
    }
    /* removida a pintura de fundo no open */
    /* details.company[open] summary { background:var(--primary); color:#fff; } */
    .pc-list {
      padding:0 var(--spacing) var(--spacing) var(--spacing);
    }
    details.pc {
      background:#f8f9fa;
      border:1px solid #eee;
      border-radius:var(--radius);
      margin-bottom:8px;
      overflow:hidden;
    }
    details.pc summary {
      cursor:pointer;
      padding:10px;
      display:flex;
      align-items:center;
      gap:6px;
      font-weight:500;
    }
    .pc-desc {
      padding:0 10px 10px 30px;
      color:var(--dark);
      font-size:.95rem;
      line-height:1.4;
    }
    .icon { font-size:1.2rem; }
  </style>
</head>
<body>

<header>
  <h1>🏢 Empresas & 🖥 Computadores</h1>
  <form method="get" class="search">
    <input type="text" name="search" placeholder="Pesquisar empresa ou PC..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">🔍</button>
  </form>
</header>

<?php if (empty($empresas)): ?>
  <p>Nenhuma empresa ou computador encontrado.</p>
<?php else: ?>
  <?php foreach ($empresas as $empresa): ?>
    <details class="company">
      <summary>
        <span class="icon">🏢</span>
        <?= htmlspecialchars($empresa['label']) ?>
      </summary>
      <div class="pc-list">
        <?php if (empty($empresa['pcs'])): ?>
          <p style="color:var(--gray); padding: var(--spacing)">Sem computadores cadastrados.</p>
        <?php else: ?>
          <?php foreach ($empresa['pcs'] as $pc): ?>
            <details class="pc">
              <summary><span class="icon">💻</span><?= htmlspecialchars($pc['nome']) ?></summary>
              <div class="pc-desc"><?= nl2br(htmlspecialchars($pc['desc'])) ?></div>
            </details>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </details>
  <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
