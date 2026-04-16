<?php
include("db.php");

$usuario = "admin";
$senha = password_hash("1234", PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (usuario, senha) VALUES (?, ?)");

if (!$stmt) {
    die("Erro no prepare: " . $conn->error);
}

$stmt->bind_param("ss", $usuario, $senha);

if ($stmt->execute()) {
    echo "Admin criado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}
?>