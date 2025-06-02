<?php

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $senha2 = $_POST['senha2'];

    if ($senha !== $senha2) {
        $erro = "As senhas nao coincidem.";
    } else {
        $users = file_exists('data/users.json') ? json_decode(file_get_contents('data/users.json'), true) : [];

        foreach ($users as $user) {
            if ($user['usuario'] === $usuario) {
                $erro = "Usuario ja existe.";
                break;
            }
        }

        if (!$erro) {
            $users[] = [
                'usuario' => $usuario,
                'senha' => password_hash($senha, PASSWORD_DEFAULT)
            ];
            file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));
            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #2c3e50;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #219150;
        }

        a {
            text-decoration: none;
            color: #2980b9;
        }

        a:hover {
            text-decoration: underline;
        }

        .erro {
            color: red;
            margin-top: 15px;
        }

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Criar cadastro</h2>

<form method="post">
    <label>Usuario:</label>
    <input type="text" name="usuario" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <label>Confirmar senha:</label>
    <input type="password" name="senha2" required>

    <button type="submit">Cadastrar</button>
</form>

<p><a href="index.php">Voltar ao login</a></p>

<?php if ($erro): ?>
    <p class="erro"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

</body>
</html>
