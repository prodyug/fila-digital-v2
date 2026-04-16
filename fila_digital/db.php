<?php
$host = "sql312.infinityfree.com";
$usuario = "if0_41673976";
$senha = "J2jQXSmeYn";
$banco = "if0_41673976_db_fila";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>