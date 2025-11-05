<?php
require 'db.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($nome) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $email]);
            $mensagem = "<p style='color: green;'>Usuário '$nome' cadastrado com sucesso!</p>";
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Erro de chave duplicada (email)
                $mensagem = "<p style='color: red;'>Erro: O e-mail '$email' já está cadastrado.</p>";
            } else {
                $mensagem = "<p style='color: red;'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
            }
        }
    } else {
        $mensagem = "<p style='color: orange;'>Por favor, preencha todos os campos corretamente.</p>";
    }
}

// Buscar todos os usuários para exibir na lista
$sql_select = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$stmt_select = $pdo->query($sql_select);
$usuarios = $stmt_select->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários (PHP/MySQL com Docker)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Cadastro de Usuários</h1>

    <?= $mensagem ?>

    <form method="POST" action="index.php">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Cadastrar">
    </form>

    <hr>

    <h2>Usuários Cadastrados</h2>
    <?php if (count($usuarios) > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum usuário cadastrado ainda.</p>
    <?php endif; ?>

</body>
</html>