<?php
    // session_start();

    unset($_SESSION["username"]);
    unset($_SESSION['email']);
    unset($_SESSION['lehrerusername']);
    unset($_SESSION['date']);
    unset($_SESSION["role"]);
    unset($_SESSION["lehrerzugriff"]);
    unset($_SESSION["lehrernamen"]);
    unset($_SESSION["lehrerusername"]);

    // header("Location: login.php");

    // Initialisierung der Session.
    // Wenn Sie session_name("irgendwas") verwenden, vergessen Sie es
    // jetzt nicht!
    session_start();

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"],
            $params["domain"], $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    echo "<script>window.location.href = 'login.php'</script>;"
?>
