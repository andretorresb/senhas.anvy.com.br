<?php
// acao_login.php
session_start();
require_once 'conexao.php';

unset($_SESSION['erro'], $_SESSION['old_email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']   ?? '');
    $senha =        $_POST['senha'] ?? '';

    $senhaHash = md5($senha);

    $sql = "SELECT id, email
              FROM usuarios
             WHERE email = :email
               AND senha = :senha
               AND ativo = 'Sim'
             LIMIT 1";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':email', $email,    PDO::PARAM_STR);
    $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
    $stmt->execute();

    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // login ok
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_id']    = $user['id'];
        header('Location: bin/dashboard.php');
        exit;
    }

    // login falhou: guarda erro e e-mail antigo
    $_SESSION['erro']      = 'E-mail ou senha inválidos, ou conta não ativada.';
    $_SESSION['old_email'] = $email;
    header('Location: index.php');
    exit;
}

// acesso direto sem POST
header('Location: index.php');
exit;
