<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

$conn->query("UPDATE fila SET status = 'atendido' WHERE status = 'chamado'");

$sql = "SELECT * FROM fila WHERE status = 'esperando' ORDER BY id ASC LIMIT 1";
$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

if ($result->num_rows > 0) {
    $linha = $result->fetch_assoc();
    $id = $linha["id"];

    $stmt = $conn->prepare("UPDATE fila SET status = 'chamado' WHERE id = ?");
    if (!$stmt) {
        die("Erro no prepare: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: painel.php");
exit();
?>