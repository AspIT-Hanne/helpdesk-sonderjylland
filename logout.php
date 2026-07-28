<?php

    // Start sessionen for at kunne tilgå den
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Tøm hele $_SESSION-arrayet for data
    $_SESSION = array();

    // Slet sessions-cookiet i browseren (hvis det bruges)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Stop sessionen på serveren
    session_destroy();

    // Omdiriger brugeren til login-siden og stop scriptet
    header('Location: login.php?reason=logout');
    exit;

?>