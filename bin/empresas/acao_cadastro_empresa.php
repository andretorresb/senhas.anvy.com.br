<?php
// acao_cadastro_empresa.php
session_start();
require_once '../../conexao.php';

// limpa quaisquer erros/old anteriores
unset($_SESSION['erro'], $_SESSION['old']);

// 1) recebe dados
$id          = $_POST['id']             ?? null;
$cnpj_cpf    = trim($_POST['cnpj_cpf']  ?? '');
$nomeF       = trim($_POST['nome_fantasia'] ?? '');
$razaoSocial = trim($_POST['razao_social']  ?? '');

// funções de validação
function validaCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

function validaCNPJ($cnpj) {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    if (strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)) return false;
    $tabelas = [5,4,3,2,9,8,7,6,5,4,3,2];
    for ($t = 12; $t < 14; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cnpj[$c] * $tabelas[$c + ($t - 12)];
        }
        $d = ($d % 11) < 2 ? 0 : 11 - ($d % 11);
        if ($cnpj[$c] != $d) return false;
    }
    return true;
}

// 2) validações básicas
$only = preg_replace('/\D/', '', $cnpj_cpf);
if ($only === '') {
    $_SESSION['erro'] = 'O campo CNPJ/CPF é obrigatório.';
} elseif (strlen($only) === 11) {
    if (!validaCPF($only)) {
        $_SESSION['erro'] = 'CPF inválido.';
    }
} elseif (strlen($only) === 14) {
    if (!validaCNPJ($only)) {
        $_SESSION['erro'] = 'CNPJ inválido.';
    }
} else {
    $_SESSION['erro'] = 'Informe 11 dígitos para CPF ou 14 para CNPJ.';
}

if (!empty($_SESSION['erro'])) {
    $_SESSION['old'] = [
        'cnpj_cpf'      => $cnpj_cpf,
        'nome_fantasia'=> $nomeF,
        'razao_social' => $razaoSocial
    ];
    header('Location: cadastro_empresa.php' . ($id ? "?id={$id}" : ''));
    exit;
}

// 3) verifica unicidade (compara só dígitos)
$sql = "SELECT COUNT(*) FROM empresas
        WHERE REPLACE(cnpj_cpf, '\\D', '') = :only"
     . ($id ? " AND id <> :id" : '');
$stmt = $conexao->prepare($sql);
$stmt->bindValue(':only', $only);
if ($id) {
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
}
$stmt->execute();
if ($stmt->fetchColumn() > 0) {
    $_SESSION['erro'] = 'Este CNPJ/CPF já está cadastrado.';
    $_SESSION['old']  = [
        'cnpj_cpf'      => $cnpj_cpf,
        'nome_fantasia'=> $nomeF,
        'razao_social' => $razaoSocial
    ];
    header('Location: cadastro_empresa.php' . ($id ? "?id={$id}" : ''));
    exit;
}

// 4) insere ou atualiza
if ($id) {
    $sql = "UPDATE empresas
               SET cnpj_cpf      = :cnpj_cpf,
                   nome_fantasia = :fantasia,
                   razao_social  = :razao
             WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
} else {
    // garante ativo = 'Sim' nos novos cadastros
    $sql = "INSERT INTO empresas
              (cnpj_cpf, nome_fantasia, razao_social, ativo)
            VALUES
              (:cnpj_cpf, :fantasia, :razao, 'Sim')";
    $stmt = $conexao->prepare($sql);
}

$stmt->bindValue(':cnpj_cpf', preg_replace('/\D/', '', $cnpj_cpf));
$stmt->bindValue(':fantasia', $nomeF);
$stmt->bindValue(':razao',    $razaoSocial);
$stmt->execute();

// 5) redireciona para a lista
header('Location: empresas.php');
exit;
