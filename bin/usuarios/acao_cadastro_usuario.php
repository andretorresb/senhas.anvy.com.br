<?php
// bin/usuarios/acao_cadastro_usuario.php
session_start();
require_once '../../conexao.php';
unset($_SESSION['erro'], $_SESSION['old']);

$id    = $_POST['id']    ?? null;
$email = trim($_POST['email']  ?? '');
$ativo = $_POST['ativo'] ?? 'Sim';

// validações
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = 'E-mail inválido.';
}
if (!$id && empty($_POST['senha'])) {
    $_SESSION['erro'] = 'Senha é obrigatória.';
}

// preserva old
if (!empty($_SESSION['erro'])) {
    $_SESSION['old'] = compact('email','ativo');
    header('Location: cadastro_usuario.php' . ($id?"?id={$id}":''));
    exit;
}

// verifica unicidade
if ($id) {
    $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email AND id <> :id";
    $stmt= $conexao->prepare($sql);
    $stmt->execute([':email'=>$email,':id'=>$id]);
} else {
    $stmt= $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
}
if ($stmt->fetchColumn()>0) {
    $_SESSION['erro']='E-mail já cadastrado.';
    $_SESSION['old']=compact('email','ativo');
    header('Location: cadastro_usuario.php' . ($id?"?id={$id}":''));
    exit;
}

// INSERT ou UPDATE
if ($id) {
    $sql = "UPDATE usuarios SET email=:email, ativo=:ativo WHERE id=:id";
    $stmt= $conexao->prepare($sql);
    $stmt->execute([':email'=>$email,':ativo'=>$ativo,':id'=>$id]);
} else {
    $senhaHash = md5($_POST['senha']);
    $sql = "INSERT INTO usuarios (email,senha,ativo) VALUES (:email,:senha,:ativo)";
    $stmt= $conexao->prepare($sql);
    $stmt->execute([':email'=>$email,':senha'=>$senhaHash,':ativo'=>$ativo]);
}

header('Location: usuarios.php');
exit;
