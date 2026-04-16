<?php
session_start();

// limpa tudo
$_SESSION = [];

// destrói cookie da sessão (extra segurança)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// destrói sessão
session_destroy();

header("Location: login.php");
exit();
?>