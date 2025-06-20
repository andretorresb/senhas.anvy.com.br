<?php
// bin/usuarios/acao_ativar_usuario.php
session_start();
require_once '../../conexao.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt= $conexao->prepare("UPDATE usuarios SET ativo='Sim' WHERE id=?");
    $stmt->execute([$id]);
}
header('Location: usuarios.php?status=Não&search=' . urlencode($_GET['search'] ?? ''));
exit;
