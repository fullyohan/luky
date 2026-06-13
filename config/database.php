<?php
    $host = "localhost";
    $dbname = "luky_db";
    $user = "root";
    $password = "";
    $db = mysqli_connect($host, $user, $password, $dbname);
    if (!$db) 
        die("Erreur : " . mysqli_connect_error());
    mysqli_set_charset($db, "utf8");
    if (isset($_SESSION['user_id'])) {
        $current_session_user_id = (int)$_SESSION['user_id']; 
        $check_status_query = mysqli_query($db, "SELECT status FROM users WHERE id = $current_session_user_id");
        $user = mysqli_fetch_assoc($check_status_query);
        
        if (!$user || $user['status'] === 'suspended') {
            session_destroy();
            session_start();
            $_SESSION['error'] = "Votre session a expiré ou votre compte a été suspendu.";
            header("Location: /auth/login");
            exit();
        }
    }
