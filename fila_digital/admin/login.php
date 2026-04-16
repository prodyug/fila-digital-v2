<?php
session_start();

if (isset($_SESSION["admin"])) {
    header("Location: painel.php");
    exit();
}

include("../db.php");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST["user"] ?? "");
    $pass = $_POST["pass"] ?? "";

    if (empty($user) || empty($pass)) {
        $erro = "Preencha usuário e senha.";
    } else {
        $stmt = $conn->prepare("SELECT id, usuario, senha FROM admins WHERE usuario = ? LIMIT 1");

        if (!$stmt) {
            die("Erro no prepare: " . $conn->error);
        }

        $stmt->bind_param("s", $user);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $admin = $resultado->fetch_assoc();

            if (password_verify($pass, $admin["senha"])) {
                $_SESSION["admin"] = true;
                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_user"] = $admin["usuario"];

                header("Location: painel.php");
                exit();
            } else {
                $erro = "Login inválido.";
            }
        } else {
            $erro = "Login inválido.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Login do Administrador</h1>

            <?php if (!empty($erro)): ?>
                <p style="color:red;"><?php echo htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="user" placeholder="Usuário" required>
                <input type="password" name="pass" placeholder="Senha" required>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>