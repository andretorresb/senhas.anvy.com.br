<?php
require_once 'funcoes.php';
protegerPagina();

$empresas = carregarEmpresas();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['nova_empresa'])) {
        // Cadastro de nova empresa ou adicionar PC numa empresa existente
        $nome = limparTexto($_POST['nome_empresa'] ?? '');
        $pc = limparTexto($_POST['pc'] ?? '');
        $usuario = limparTexto($_POST['usuario'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if ($nome !== '' && $pc !== '' && $usuario !== '') {
            $senha_enc = $senha !== '' ? encryptSenha($senha) : '';

            // Verifica se empresa existe
            $empresaExiste = false;
            foreach ($empresas as &$empresa) {
                if (strcasecmp($empresa['nome'], $nome) === 0) {
                    // Adiciona novo PC nessa empresa
                    $empresa['pcs'][] = [
                        'pc' => $pc,
                        'usuario' => $usuario,
                        'senha' => $senha_enc
                    ];
                    $empresaExiste = true;
                    break;
                }
            }
            unset($empresa);

            if (!$empresaExiste) {
                // Cria nova empresa com um PC
                $empresas[] = [
                    'nome' => $nome,
                    'pcs' => [
                        [
                            'pc' => $pc,
                            'usuario' => $usuario,
                            'senha' => $senha_enc
                        ]
                    ]
                ];
            }

            salvarEmpresas($empresas);
            $mensagem = "Empresa/PC cadastrada com sucesso.";
        } else {
            $mensagem = "Por favor, preencha todos os campos obrigatórios.";
        }

    } elseif (isset($_POST['editar_empresa'])) {
        // Editar PC dentro de empresa
        // Espera empresa_index e pc_index
        $empresa_index = intval($_POST['empresa_index'] ?? -1);
        $pc_index = intval($_POST['pc_index'] ?? -1);

        if (isset($empresas[$empresa_index]) && isset($empresas[$empresa_index]['pcs'][$pc_index])) {
            $pc = limparTexto($_POST['pc'] ?? '');
            $usuario = limparTexto($_POST['usuario'] ?? '');
            $senha = $_POST['senha'] ?? '';

            $senha_enc = $senha !== '' ? encryptSenha($senha) : $empresas[$empresa_index]['pcs'][$pc_index]['senha'];

            $empresas[$empresa_index]['pcs'][$pc_index]['pc'] = $pc;
            $empresas[$empresa_index]['pcs'][$pc_index]['usuario'] = $usuario;
            $empresas[$empresa_index]['pcs'][$pc_index]['senha'] = $senha_enc;

            salvarEmpresas($empresas);
            $mensagem = "Dados do PC atualizados.";
        } else {
            $mensagem = "PC ou empresa não encontrado.";
        }

    } elseif (isset($_POST['excluir_empresa'])) {
        // Excluir um PC dentro da empresa
        $empresa_index = intval($_POST['empresa_index'] ?? -1);
        $pc_index = intval($_POST['pc_index'] ?? -1);

        if (isset($empresas[$empresa_index]) && isset($empresas[$empresa_index]['pcs'][$pc_index])) {
            // Remove o PC
            array_splice($empresas[$empresa_index]['pcs'], $pc_index, 1);

            // Se empresa ficar sem PCs, remove a empresa também
            if (count($empresas[$empresa_index]['pcs']) === 0) {
                array_splice($empresas, $empresa_index, 1);
            }

            salvarEmpresas($empresas);
            $mensagem = "PC excluído com sucesso.";
        } else {
            $mensagem = "PC ou empresa não encontrado.";
        }
    }
}

$_SESSION['mensagem'] = $mensagem;

if (isset($_POST['nova_empresa'])) {
    header('Location: novaempresa.php');
} else {
    header('Location: listaempresas.php');
}
exit;
