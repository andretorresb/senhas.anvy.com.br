<?php
// acao_cadastro_computador.php
session_start();
require_once '../../conexao.php';

unset($_SESSION['erro'], $_SESSION['old']);

$id         = $_POST['id']          ?? null;
$empresa_id = (int)($_POST['empresa_id'] ?? 0);
$nome       = trim($_POST['nome']   ?? '');
$descricao  = trim($_POST['descricao'] ?? '');

// validações
if ($empresa_id <= 0) {
    $_SESSION['erro'] = 'Selecione a empresa.';
} elseif ($nome === '') {
    $_SESSION['erro'] = 'Informe o nome do computador.';
}

if (!empty($_SESSION['erro'])) {
    $_SESSION['old'] = compact('empresa_id','nome','descricao');
    header('Location: cadastro_computador.php' . ($id?"?id={$id}":''));
    exit;
}

// insert ou update
if ($id) {
    $sql = "UPDATE computadores
               SET empresa_id = :emp,
                   nome       = :nome,
                   descricao  = :desc
             WHERE id = :id";
    $st = $conexao->prepare($sql);
    $st->bindValue(':id',  (int)$id, PDO::PARAM_INT);
} else {
    $sql = "INSERT INTO computadores (empresa_id,nome,descricao,ativo)
            VALUES (:emp,:nome,:desc,'Sim')";
    $st = $conexao->prepare($sql);
}

$st->bindValue(':emp',  $empresa_id, PDO::PARAM_INT);
$st->bindValue(':nome', $nome);
$st->bindValue(':desc', $descricao);
$st->execute();

header('Location: computadores.php');
exit;
