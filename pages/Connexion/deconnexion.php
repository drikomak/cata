<?php
session_start();  // Démarrage de la session

// Destruction des variables de session
$_SESSION = array();

// Destruction du cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruction de la session elle-même
session_destroy();

// Redirection vers la page de connexion
header("Location: connexion.php");
exit;
?>
