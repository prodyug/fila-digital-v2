<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo TV - Clínica Vida+</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f2233, #16324a, #1d4f73);
            color: white;
            min-height: 100vh;
            overflow: hidden;
        }

        .topo {
            padding: 28px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .topo h1 {
            font-size: 42px;
            font-weight: 800;
        }

        .relogio {
            font-size: 24px;
            font-weight: 600;
            opacity: 0.9;
        }

        .layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            padding: 24px;
            height: calc(100vh - 100px);
        }

        .principal,
        .lateral {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 28px;
            padding: 28px;
            backdrop-filter: blur(8px);
        }

        .subtitulo {
            font-size: 24px;
            opacity: 0.9;
            margin-bottom: 18px;
        }

        .senha-box {
            height: calc(100% - 42px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: transform 0.25s ease;
        }

        .senha-numero {
            font-size: 150px;
            font-weight: 900;
            color: #7ff0a8;
            line-height: 1;
            letter-spacing: 4px;
            text-shadow: 0 0 30px rgba(127, 240, 168, 0.18);
        }

        .senha-nome {
            margin-top: 20px;
            font-size: 36px;
            font-weight: 600;
            color: #e8f6ff;
            max-width: 90%;
            word-break: break-word;
        }

        .sem-chamada {
            font-size: 48px;
            font-weight: 700;
            color: #dcecff;
        }

        .lateral h2 {
            font-size: 28px;
            margin-bottom: 18px;
        }

        .fila-lista {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .fila-lista li {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            padding: 16px 18px;
            font-size: 24px;
            font-weight: 600;
        }

        .fila-lista span {
            color: #9ed8ff;
        }

        .rodape {
            position: absolute;
            bottom: 16px;
            left: 24px;
            right: 24px;
            text-align: center;
            font-size: 18px;
            opacity: 0.7;
        }

        @media (max-width: 1100px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .senha-numero {
                font-size: 110px;
            }

            .senha-nome {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

    <div class="topo">
        <h1>🏥 Clínica Vida+ • Painel de Chamadas</h1>
        <div class="relogio" id="relogio"></div>
    </div>

    <div class="layout">
        <div class="principal">
            <div class="subtitulo">Paciente em atendimento</div>
            <div class="senha-box" id="senhaBox">
                <div class="sem-chamada" id="senhaAtual">Nenhum paciente chamado</div>
            </div>
        </div>

        <div class="lateral">
            <h2>Fila de espera</h2>
            <ul class="fila-lista" id="fila"></ul>
        </div>
    </div>

    <div class="rodape">
        Escaneie o QR Code na recepção para acompanhar sua senha pelo celular.
    </div>

    <audio id="somChamada" src="ding.mp3" preload="auto"></audio>

    <script>
        let ultimaSenha = null;

        function atualizarRelogio() {
            const agora = new Date();
            const texto = agora.toLocaleString("pt-BR");
            document.getElementById("relogio").textContent = texto;
        }

        function tocarSom() {
            const audio = document.getElementById("somChamada");
            audio.currentTime = 0;
            audio.play().catch(() => {});
        }

        function falarSenha(texto) {
            speechSynthesis.cancel();
            const msg = new SpeechSynthesisUtterance(texto);
            msg.lang = "pt-BR";
            msg.rate = 1;
            msg.pitch = 1;
            speechSynthesis.speak(msg);
        }

        function animarChamada() {
            const box = document.getElementById("senhaBox");
            box.style.transform = "scale(1.04)";
            setTimeout(() => {
                box.style.transform = "scale(1)";
            }, 250);
        }

        function atualizarTV() {
            fetch("dados.php")
                .then(res => {
                    if (!res.ok) {
                        throw new Error("Erro ao buscar dados");
                    }
                    return res.json();
                })
                .then(data => {
                    const senhaAtual = document.getElementById("senhaAtual");
                    const fila = document.getElementById("fila");

                    if (data.senhaAtual) {
                        senhaAtual.innerHTML = `
                            <div class="senha-numero">${data.senhaAtual.senha}</div>
                            <div class="senha-nome">${data.senhaAtual.nome}</div>
                        `;

                        if (ultimaSenha !== data.senhaAtual.senha) {
                            tocarSom();
                            falarSenha(`Senha ${data.senhaAtual.senha}, dirija-se ao atendimento`);
                            animarChamada();
                            ultimaSenha = data.senhaAtual.senha;
                        }
                    } else {
                        senhaAtual.innerHTML = `<div class="sem-chamada">Nenhum paciente chamado</div>`;
                    }

                    fila.innerHTML = "";

                    if (data.fila.length > 0) {
                        data.fila.slice(0, 8).forEach(p => {
                            fila.innerHTML += `<li><span>${p.senha}</span> — ${p.nome}</li>`;
                        });
                    } else {
                        fila.innerHTML = `<li>Nenhum paciente na fila</li>`;
                    }
                })
                .catch(() => {
                    document.getElementById("senhaAtual").innerHTML =
                        `<div class="sem-chamada">Erro ao carregar painel</div>`;
                });
        }

        setInterval(atualizarTV, 3000);
        setInterval(atualizarRelogio, 1000);

        atualizarTV();
        atualizarRelogio();
    </script>

</body>
</html>