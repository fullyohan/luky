<?php
function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous devez etre connecte";
        header("Location: /auth/login");
        exit();
    }
}

function require_guest() {
    if (isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous etes deja connecte" ;
        $_POST['ref'] = $_SERVER['HTTP_REFERER'];
        header("Location: /");
        exit();
    }
}