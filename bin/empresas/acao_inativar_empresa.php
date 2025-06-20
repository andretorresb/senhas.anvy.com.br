<?php
// acao_inativar_empresa.php
session_start();
require_once '../../conexao.php';

$id     = (int)($_GET['id'] ?? 0);
$status = $_GET['status'] ?? 'Sim';
$search = $_GET['search'] ?? '';
$page   = (int)($_GET['page'] ?? 1);

if ($id > 0) {
    $sql = "UPDATE empresas SET ativo = 'Não' WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

// redireciona de volta mantendo filtros/página
header("Location: empresas.php?status={$status}&search=".urlencode($search)."&page={$page}");
exit;
