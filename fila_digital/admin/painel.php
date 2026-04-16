<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Clínica Vida+</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="fade">

<div class="container">
    <div class="card">

        <h1>🏥 Painel de Atendimento</h1>
        <p class="horario"><?php echo date("d/m/Y - H:i"); ?></p>
        <p>Acompanhe a senha atual e os pacientes aguardando.</p>

        <h2>Paciente em Atendimento</h2>
        <div class="senha-atual" id="senhaAtual">Carregando...</div>

        <form action="chamar.php" method="POST">
            <button type="submit">Chamar Paciente</button>
        </form>

        <form action="resetar.php" method="POST" style="margin-top:10px;">
            <button type="submit" class="botao-reset">Resetar Fila</button>
        </form>

        <h2>Pacientes Aguardando</h2>
        <ul class="fila-lista" id="fila"></ul>

        <h2>Histórico de Atendimentos</h2>
        <ul class="fila-lista" id="historico"></ul>

        <a href="../index.php" class="link-painel link-transition">Voltar para recepção</a>
        <br>
        <a href="logout.php" class="link-painel">Sair</a>

    </div>
</div>

<audio id="somChamada" src="../ding.mp3" preload="auto"></audio>

<script>
let ultimaSenha = null;

function tocarSom() {
    const audio = document.getElementById("somChamada");
    audio.currentTime = 0;
    audio.play().catch(() => {});
}

function falarSenha(texto) {
    const msg = new SpeechSynthesisUtterance(texto);
    msg.lang = "pt-BR";
    msg.rate = 1;
    msg.pitch = 1;
    speechSynthesis.speak(msg);
}

function animarSenha() {
    const el = document.getElementById("senhaAtual");
    el.style.transform = "scale(1.1)";
    el.style.transition = "0.2s";

    setTimeout(() => {
        el.style.transform = "scale(1)";
    }, 200);
}

document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", () => {
        const btn = form.querySelector("button");
        if (btn) {
            btn.classList.add("loading");
            btn.innerText = "Processando...";
        }
    });
});

document.querySelectorAll(".link-transition").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        document.body.style.opacity = "0";
        setTimeout(() => {
            window.location.href = this.href;
        }, 200);
    });
});

function atualizarPainel() {
    fetch("../dados.php")
    .then(res => {
        if (!res.ok) {
            throw new Error("Erro ao buscar dados");
        }
        return res.json();
    })
    .then(data => {
        const senhaDiv = document.getElementById("senhaAtual");

        if (data.senhaAtual) {
            senhaDiv.innerHTML = data.senhaAtual.senha +
                "<br><small>" + data.senhaAtual.nome + "</small>";

            if (ultimaSenha !== data.senhaAtual.senha) {
                tocarSom();
                animarSenha();
                falarSenha(`Senha ${data.senhaAtual.senha}, dirija-se ao atendimento`);
                ultimaSenha = data.senhaAtual.senha;
            }
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

        const historico = document.getElementById("historico");
        historico.innerHTML = "";

        if (data.historico.length > 0) {
            data.historico.forEach(p => {
                historico.innerHTML += `<li class="atendido">${p.senha} - ${p.nome}</li>`;
            });
        } else {
            historico.innerHTML = "<li>Nenhum atendimento finalizado</li>";
        }
    })
    .catch(err => {
        console.error("Erro:", err);
        document.getElementById("senhaAtual").innerHTML = "Erro ao carregar dados";
    });
}

setInterval(atualizarPainel, 3000);
atualizarPainel();
</script>

</body>
</html>