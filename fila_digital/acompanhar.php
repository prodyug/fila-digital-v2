<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Atendimento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="fade">

<div class="container">
    <div class="card">
        <h1>🏥 Acompanhar Atendimento</h1>
        <p>Veja a senha atual e acompanhe a fila.</p>

        <h2>Paciente em Atendimento</h2>
        <div class="senha-atual" id="senhaAtual">Carregando...</div>

        <h2>Fila de Espera</h2>
        <ul class="fila-lista" id="fila"></ul>

        <a href="index.php" class="link-painel link-transition">Voltar</a>
    </div>
</div>

<script>
function atualizarPainel() {
    fetch("dados.php")
    .then(res => res.json())
    .then(data => {
        const senhaDiv = document.getElementById("senhaAtual");

        if (data.senhaAtual) {
            senhaDiv.innerHTML = data.senhaAtual.senha + "<br><small>" + data.senhaAtual.nome + "</small>";
        } else {
            senhaDiv.innerHTML = "Nenhum paciente chamado";
        }

        const fila = document.getElementById("fila");
        fila.innerHTML = "";

        if (data.fila.length > 0) {
            data.fila.forEach(p => {
                fila.innerHTML += `<li class="esperando">${p.senha} - ${p.nome}</li>`;
            });
        } else {
            fila.innerHTML = "<li>Nenhum paciente na fila</li>";
        }
    });
}

setInterval(atualizarPainel, 3000);
atualizarPainel();
</script>

</body>
</html>