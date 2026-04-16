<?php
include("db.php");

// senha atual
$sqlAtual = "SELECT * FROM fila WHERE status = 'chamado' ORDER BY id DESC LIMIT 1";
$resultAtual = $conn->query($sqlAtual);
$senhaAtual = $resultAtual->num_rows > 0 ? $resultAtual->fetch_assoc() : null;

// fila
$sqlFila = "SELECT * FROM fila WHERE status = 'esperando' ORDER BY id ASC";
$resultFila = $conn->query($sqlFila);

$fila = [];
while ($row = $resultFila->fetch_assoc()) {
    $fila[] = $row;
}

// histórico
$sqlHistorico = "SELECT * FROM fila WHERE status = 'atendido' ORDER BY id DESC LIMIT 5";
$resultHistorico = $conn->query($sqlHistorico);

$historico = [];
while ($row = $resultHistorico->fetch_assoc()) {
    $historico[] = $row;
}

// resposta JSON
echo json_encode([
    "senhaAtual" => $senhaAtual,
    "fila" => $fila,
    "historico" => $historico
]);