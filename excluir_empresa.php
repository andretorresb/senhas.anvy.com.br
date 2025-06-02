<?php
require_once 'funcoes.php';
protegerPagina();

// Verificar se o método é POST e se o nome da empresa foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['empresa_nome'])) {
    $empresaNome = trim($_POST['empresa_nome']);
    
    if (empty($empresaNome)) {
        $_SESSION['erro'] = 'Nome da empresa não pode estar vazio.';
        header('Location: listaempresa.php');
        exit;
    }
    
    try {
        // Carregar empresas existentes
        $empresas = carregarEmpresas();
        $empresaEncontrada = false;
        
        // Filtrar empresas removendo a empresa especificada
        $empresasFiltradas = [];
        foreach ($empresas as $empresa) {
            if ($empresa['nome'] !== $empresaNome) {
                $empresasFiltradas[] = $empresa;
            } else {
                $empresaEncontrada = true;
            }
        }
        
        if (!$empresaEncontrada) {
            $_SESSION['erro'] = 'Empresa não encontrada.';
            header('Location: listaempresa.php');
            exit;
        }
        
        // Salvar empresas filtradas de volta no arquivo
        if (salvarEmpresas($empresasFiltradas)) {
            $_SESSION['sucesso'] = "Empresa '{$empresaNome}' e todos os PCs foram excluídos com sucesso.";
        } else {
            $_SESSION['erro'] = 'Erro ao salvar as alterações no arquivo.';
        }
        
    } catch (Exception $e) {
        $_SESSION['erro'] = 'Erro ao excluir empresa: ' . $e->getMessage();
    }
    
} else {
    $_SESSION['erro'] = 'Requisição inválida.';
}

// Redirecionar de volta para a lista de empresas
header('Location: listaempresas.php');
exit;
?>