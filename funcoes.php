<?php
session_start();

define('ENCRYPT_METHOD', 'AES-128-CBC');
define('SECRET_KEY', 'minha_chave_secreta_123'); 
define('SECRET_IV', '1234567890123456');

function encryptSenha($senha) {
    return openssl_encrypt($senha, ENCRYPT_METHOD, SECRET_KEY, 0, SECRET_IV);
}

function decryptSenha($senha_enc) {
    return openssl_decrypt($senha_enc, ENCRYPT_METHOD, SECRET_KEY, 0, SECRET_IV);
}

function limparTexto($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function carregarEmpresas() {
    if (file_exists('data/empresas.json')) {
        $json_raw = file_get_contents('data/empresas.json');
        $json_utf8 = mb_convert_encoding($json_raw, 'UTF-8', 'UTF-8');
        $empresas = json_decode($json_utf8, true);
        if (!is_array($empresas)) $empresas = [];
        return $empresas;
    }
    return [];
}

function salvarEmpresas($empresas) {
    file_put_contents('data/empresas.json', json_encode($empresas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function carregarPCs() {
    $arquivo = 'data/pcs.json';
    if (file_exists($arquivo)) {
        $json_raw = file_get_contents($arquivo);
        $json_utf8 = mb_convert_encoding($json_raw, 'UTF-8', 'UTF-8');
        $pcs = json_decode($json_utf8, true);
        if (!is_array($pcs)) $pcs = [];
        return $pcs;
    }
    return [];
}

function salvarPCs($pcs) {
    file_put_contents('data/pcs.json', json_encode($pcs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function usuarioLogado() {
    return isset($_SESSION['usuario']);
}

function protegerPagina() {
    if (!usuarioLogado()) {
        header('Location: index.php');
        exit;
    }
}
?>
