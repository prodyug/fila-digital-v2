<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Vida+</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="fade">

    <div class="container">
        <div class="card">
            <h1>🏥 Clínica Vida+</h1>
            <p>Bem-vindo. Informe seu nome para gerar sua senha de atendimento.</p>

            <form action="pegar_senha.php" method="POST" id="formSenha">
                <input type="text" name="nome" placeholder="Nome do paciente" required>
                <button type="submit" id="btnSenha">Gerar Senha</button>
            </form>

            <a href="acompanhar.php" class="link-painel link-transition">Acompanhar atendimento</a>
    </div>

    <script>
    document.getElementById("formSenha").addEventListener("submit", function() {
        const btn = document.getElementById("btnSenha");
        btn.classList.add("loading");
        btn.innerText = "Gerando...";
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
    </script>

</body>
</html>