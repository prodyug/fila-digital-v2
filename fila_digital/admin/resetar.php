<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

if (!$conn->query("DELETE FROM fila")) {
    die("Erro ao limpar fila: " . $conn->error);
}

if (!$conn->query("ALTER TABLE fila AUTO_INCREMENT = 1")) {
    die("Erro ao resetar AUTO_INCREMENT: " . $conn->error);
}

header("Location: painel.php");
exit();
?>