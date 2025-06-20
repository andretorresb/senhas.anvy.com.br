<?php
// conexao.php

$host = 'anvy.com.br';
$db   = 'anvycomb_gravador_de_senhas';
$user = 'anvycomb_gravador_de_senhas';
$pass = 'lc47%qXr2z@0';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conexao = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Em caso de erro, exibe mensagem e termina o script
    echo 'Falha ao conectar: ' . $e->getMessage();
    exit;
}
