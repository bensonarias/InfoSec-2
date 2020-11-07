<?php


session_start();
unset($_SESSION['UserLogin']);
unset($_SESSION['Access']);
unset($_SESSION['ID']);

$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
echo header("Location: login.php");
?>