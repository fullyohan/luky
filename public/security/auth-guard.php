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
        header("Location: /");
        exit();
    }
}