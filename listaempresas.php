<?php 
require_once 'funcoes.php';
protegerPagina();

$empresas = carregarEmpresas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas e PCs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            padding: 20px;
            line-height: 1.6;
        }

        h2, h3 {
            color: #2f3640;
        }

        a {
            text-decoration: none;
            color: #0984e3;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        details {
            margin-bottom: 20px;
            border: 1px solid #dcdde1;
            padding: 15px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
        }

        summary {
            font-weight: bold;
            cursor: pointer;
            position: relative;
            padding: 8px 0;
            padding-right: 140px; /* espa�o maior para os dois bot�es */
            display: block;
            font-size: 16px;
        }

        /* Melhor espa�amento para PCs */
        .pc-details {
            margin: 12px 0;
            margin-left: 25px;
            padding: 12px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            background-color: #f8f9fa;
        }

        .pc-details summary {
            font-weight: normal;
            font-size: 14px;
            color: #495057;
            padding: 5px 0;
            margin-left: 0;
        }

        /* Removendo o ::before e adicionando espa�amento manual */
        .pc-label {
            font-weight: 600;
            color: #2c3e50;
            margin-right: 8px;
        }

        input[readonly] {
            background-color: #ecf0f1;
            border: 1px solid #bdc3c7;
            color: #2d3436;
            padding: 8px 10px;
            border-radius: 4px;
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin: 5px 0 15px 0;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            box-sizing: border-box;
        }

        label {
            font-weight: 600;
            color: #2c3e50;
            display: block;
            margin-bottom: 3px;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 8px;
            transition: all 0.2s ease;
        }

        .edit-btn {
            background-color: #74b9ff;
            color: white;
        }

        .edit-btn:hover {
            background-color: #0984e3;
            transform: translateY(-1px);
        }

        #filtro {
            margin-bottom: 25px;
            padding: 12px;
            width: 100%;
            max-width: 400px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn-excluir {
            background-color: #d63031;
            color: white;
        }

        .btn-excluir:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
        }

        .btn-salvar {
            background-color: #00b894;
            color: white;
        }

        .btn-salvar:hover {
            background-color: #019875;
            transform: translateY(-1px);
        }

        button.toggle-senha {
            padding: 6px 10px;
            background-color: #636e72;
            color: white;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        button.toggle-senha:hover {
            background-color: #2d3436;
        }

        /* Bot�o de excluir empresa */
        .btn-excluir-empresa {
            background-color: #e74c3c;
            color: white;
            position: absolute;
            right: 90px; /* posicionado � esquerda do bot�o +PC */
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 600;
        }
        
        .btn-excluir-empresa:hover {
            background-color: #c0392b;
            transform: translateY(-50%) translateY(-1px);
        }
        .btn-add-pc {
            background-color: #00b894;
            color: white;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 600;
        }
        
        .btn-add-pc:hover {
            background-color: #019875;
            transform: translateY(-50%) translateY(-1px);
        }

        /* Estilos do modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; 
            top: 0;
            width: 100%; 
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
        }

        .modal-content h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .modal-content label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #2c3e50;
        }

        .modal-content input[type="text"],
        .modal-content input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-buttons {
            margin-top: 20px;
            text-align: right;
        }

        .modal-buttons button {
            margin-left: 10px;
        }

        /* Container dos bot�es para melhor organiza��o */
        .button-group {
            margin-top: 15px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Estilos para mensagens de feedback */
        .mensagem {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .mensagem-sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensagem-erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .pc-details {
                margin-left: 10px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .button-group button {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>

<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h2>
<a href="novaempresa.php">Adicionar nova empresa / PC</a> |<a href="dashboard.php">Painel</a> <a href="logout.php">Sair Login</a>

<?php
// Exibir mensagens de sucesso ou erro
if (isset($_SESSION['sucesso'])) {
    echo '<div class="mensagem mensagem-sucesso">' . htmlspecialchars($_SESSION['sucesso'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</div>';
    unset($_SESSION['sucesso']);
}

if (isset($_SESSION['erro'])) {
    echo '<div class="mensagem mensagem-erro">' . htmlspecialchars($_SESSION['erro'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</div>';
    unset($_SESSION['erro']);
}
?>

<h3>Filtrar empresas e PCs</h3>
<input type="text" id="filtro" placeholder="Digite nome, usuario, senha ou PC...">

<?php if (empty($empresas)): ?>
    <p>Nenhuma empresa cadastrada ainda.</p>
<?php else: ?>
    <h3>Empresas cadastradas</h3>
    <div id="lista-empresas">
        <?php foreach ($empresas as $empresaIndex => $empresa): ?>
            <details class="empresa" data-filtro="<?= htmlspecialchars(strtolower($empresa['nome']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" open>
                <summary>
                    <?= htmlspecialchars($empresa['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    <button class="btn-excluir-empresa" 
                        onclick="excluirEmpresa('<?= htmlspecialchars($empresa['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>')">EXCLUIR</button>
                    <button class="btn-add-pc" 
                        data-empresa="<?= htmlspecialchars($empresa['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                        onclick="abrirModal(this)">+ PC</button>
                </summary>

                <?php foreach ($empresa['pcs'] as $pcIndex => $pc): 
                    $senhaDecrypted = decryptSenha($pc['senha']);
                    $filtroStr = strtolower($empresa['nome'] . ' ' . $pc['pc'] . ' ' . $pc['usuario'] . ' ' . $senhaDecrypted);
                ?>
                    <details class="pc-details" data-filtro="<?= htmlspecialchars($filtroStr, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                        <summary>PC <?= htmlspecialchars($pc['pc'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></summary>

                        <form method="post" action="salvar.php" id="form-<?= $empresaIndex ?>-<?= $pcIndex ?>">
                            <label>PC:</label>
                            <input type="text" name="pc" value="<?= htmlspecialchars($pc['pc'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" readonly>

                            <label>Usuario:</label>
                            <input type="text" name="usuario" value="<?= htmlspecialchars($pc['usuario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" readonly>

                            <label>Senha:</label>
                            <input type="password" id="senha-<?= $empresaIndex ?>-<?= $pcIndex ?>" name="senha" value="<?= htmlspecialchars($senhaDecrypted, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" readonly>
                            <button type="button" class="toggle-senha" onclick="toggleSenha('<?= $empresaIndex ?>-<?= $pcIndex ?>')">VER</button>

                            <input type="hidden" name="empresa_index" value="<?= $empresaIndex ?>">
                            <input type="hidden" name="pc_index" value="<?= $pcIndex ?>">

                            <button type="button" class="edit-btn" onclick="ativarEdicao('<?= $empresaIndex ?>-<?= $pcIndex ?>')">Editar</button>
                            <button type="submit" class="btn-salvar" name="editar_empresa" style="display:none;" id="salvar-btn-<?= $empresaIndex ?>-<?= $pcIndex ?>">Salvar</button>

                            <button type="submit" class="btn-excluir" name="excluir_empresa" onclick="return confirm('Excluir este PC?')">Excluir PC</button>
                        </form>
                    </details>
                <?php endforeach; ?>
            </details>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal de adicionar PC -->
<div id="modal-add-pc" class="modal">
    <div class="modal-content">
        <h3>Adicionar novo PC</h3>
        <form method="post" action="adicionar_pc.php" id="form-add-pc">
            <input type="hidden" name="empresa_nome" id="empresa_nome_input" value="">
            
            <label for="novo_pc">PC:</label>
            <input type="text" name="pc" id="novo_pc" required>

            <label for="novo_usuario">Usuario:</label>
            <input type="text" name="usuario" id="novo_usuario" required>

            <label for="nova_senha">Senha:</label>
            <input type="password" name="senha" id="nova_senha" required>

            <div class="modal-buttons">
                <button type="button" onclick="fecharModal()">Cancelar</button>
                <button type="submit" class="btn-salvar">Adicionar</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSenha(index) {
    const campo = document.getElementById('senha-' + index);
    campo.type = (campo.type === 'password') ? 'text' : 'password';
}

function ativarEdicao(index) {
    const form = document.getElementById('form-' + index);
    const inputs = form.querySelectorAll('input[type="text"], input[type="password"]');
    inputs.forEach(input => {
        input.removeAttribute('readonly');
        input.style.backgroundColor = '#fff';
        input.style.color = '#000';
        input.style.borderColor = '#74b9ff';
    });
    document.getElementById('salvar-btn-' + index).style.display = 'inline-block';
}

document.getElementById('filtro').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const empresas = document.querySelectorAll('#lista-empresas .empresa');
    
    empresas.forEach(empresa => {
        const nomeEmpresa = empresa.getAttribute('data-filtro');
        let empresaVisible = nomeEmpresa.includes(filtro);

        const pcs = empresa.querySelectorAll('.pc-details');
        let algumPCvisivel = false;
        
        pcs.forEach(pc => {
            const texto = pc.getAttribute('data-filtro');
            if (texto.includes(filtro)) {
                pc.style.display = '';
                algumPCvisivel = true;
            } else {
                pc.style.display = 'none';
            }
        });

        empresa.style.display = (empresaVisible || algumPCvisivel) ? '' : 'none';
    });
});

// Fun��o para excluir empresa inteira
function excluirEmpresa(nomeEmpresa) {
    if (confirm(`Tem certeza que deseja excluir a empresa "${nomeEmpresa}" e todos os PCs cadastrados nela?`)) {
        // Criar um formul�rio tempor�rio para enviar a requisi��o
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'excluir_empresa.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'empresa_nome';
        input.value = nomeEmpresa;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Fun��o para abrir modal e setar empresa atual
function abrirModal(botao) {
    const empresaNome = botao.getAttribute('data-empresa');
    document.getElementById('empresa_nome_input').value = empresaNome;
    document.getElementById('form-add-pc').reset();
    document.getElementById('modal-add-pc').style.display = 'flex';
}

// Fechar modal
function fecharModal() {
    document.getElementById('modal-add-pc').style.display = 'none';
}

// Fechar modal ao clicar fora da caixa de conte�do
window.onclick = function(event) {
    const modal = document.getElementById('modal-add-pc');
    if (event.target === modal) {
        fecharModal();
    }
};
</script>

</body>
</html>