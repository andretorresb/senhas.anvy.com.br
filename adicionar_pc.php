<?php
require_once 'funcoes.php';
protegerPagina();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empresaNome = trim($_POST['empresa_nome'] ?? '');
    $pc = trim($_POST['pc'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($empresaNome === '' || $pc === '' || $usuario === '' || $senha === '') {
        // Dados incompletos, redireciona para a lista sem alterações
        header('Location: listaempresas.php');
        exit;
    }

    $empresas = carregarEmpresas();

    $senhaEnc = encryptSenha($senha);

    $empresaEncontrada = false;

    foreach ($empresas as &$empresa) {
        if (strcasecmp($empresa['nome'], $empresaNome) === 0) {
            $empresa['pcs'][] = [
                'pc' => $pc,
                'usuario' => $usuario,
                'senha' => $senhaEnc
            ];
            $empresaEncontrada = true;
            break;
        }
    }
    unset($empresa);

    if (!$empresaEncontrada) {
        $empresas[] = [
            'nome' => $empresaNome,
            'pcs' => [
                [
                    'pc' => $pc,
                    'usuario' => $usuario,
                    'senha' => $senhaEnc
                ]
            ]
        ];
    }

    salvarEmpresas($empresas);

    header('Location: listaempresas.php');
    exit;
} else {
    header('Location: listaempresas.php');
    exit;
}
?>
<?php
require_once 'funcoes.php';
protegerPagina();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empresaNome = trim($_POST['empresa_nome'] ?? '');
    $pc = trim($_POST['pc'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($empresaNome === '' || $pc === '' || $usuario === '' || $senha === '') {
        // Dados incompletos, redireciona para a lista sem alterações
        header('Location: listaempresas.php');
        exit;
    }

    $empresas = carregarEmpresas();

    $senhaEnc = encryptSenha($senha);

    $empresaEncontrada = false;

    foreach ($empresas as &$empresa) {
        if (strcasecmp($empresa['nome'], $empresaNome) === 0) {
            $empresa['pcs'][] = [
                'pc' => $pc,
                'usuario' => $usuario,
                'senha' => $senhaEnc
            ];
            $empresaEncontrada = true;
            break;
        }
    }
    unset($empresa);

    if (!$empresaEncontrada) {
        $empresas[] = [
            'nome' => $empresaNome,
            'pcs' => [
                [
                    'pc' => $pc,
                    'usuario' => $usuario,
                    'senha' => $senhaEnc
                ]
            ]
        ];
    }

    salvarEmpresas($empresas);

    header('Location: listaempresas.php');
    exit;
} else {
    header('Location: listaempresas.php');
    exit;
}
?>
