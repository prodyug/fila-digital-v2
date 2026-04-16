<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"] ?? "");

    if (empty($nome)) {
        die("Nome inválido.");
    }

    $sql = "SELECT senha FROM fila ORDER BY id DESC LIMIT 1";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        $ultimaSenha = $linha["senha"];

        $numero = (int) substr($ultimaSenha, 1);
        $novoNumero = $numero + 1;
    } else {
        $novoNumero = 1;
    }

    $novaSenha = "A" . str_pad($novoNumero, 3, "0", STR_PAD_LEFT);

    $stmt = $conn->prepare("INSERT INTO fila (nome, senha, status) VALUES (?, ?, 'esperando')");
    $stmt->bind_param("ss", $nome, $novaSenha);
    $stmt->execute();

    $nomeSeguro = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');

    echo "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Senha Gerada</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <div class='container'>
            <div class='card'>
                <h1>Sua senha</h1>
                <div class='senha'>$novaSenha</div>
                <p>Nome: $nomeSeguro</p>
                <a href='index.php' class='link-painel'>Voltar</a>
            </div>
        </div>
    </body>
    </html>
    ";
}
?>