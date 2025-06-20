<?php
// bin/usuarios/acao_inativar_usuario.php
session_start();
require_once '../../conexao.php';
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt= $conexao->prepare("UPDATE usuarios SET ativo='Não' WHERE id=?");
    $stmt->execute([$id]);
}
header('Location: usuarios.php?status=Sim&search=' . urlencode($_GET['search'] ?? ''));
exit;
