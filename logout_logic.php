<?php
require 'config/constants.php';

// Unset all session variables
$_SESSION = array();

// If session uses cookies, remove the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_unset();
session_destroy();

// Regenerate session ID for extra security
session_regenerate_id(true);

header('Location: ' . ROOT_URL);
exit;