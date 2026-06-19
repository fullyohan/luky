<?php
session_start();
require_once "../../config/database.php";
require_once "../security/auth-guard.php"; 

$action = $_POST['action'] ?? '';

if (!$action) {
    header("Location: /");
    exit();
}

switch ($action) {
    case 'login':
        handleLogin($db);
        break;
    case 'register':
        handleRegister($db);
        break;
   
    case 'logout':
        require_auth();
        handleLogout();
        break;
    case 'update_profile':
        require_auth();
        handleUpdateProfile($db);
        break;
        
    default:
        header("Location: /");
        exit();
}


function handleLogin($db) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: /auth/login.php");
        exit();
    }

    $ref = $_POST['ref'] ?? '/';
    $email_escaped = mysqli_real_escape_string($db, $email);

    $sql = "SELECT id, first_name, email, created_at, status, password FROM users WHERE email = '$email_escaped'";
    $result = mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        if (($user['status'] ?? '') === 'suspended') {
            $_SESSION['error'] = "Votre compte a été suspendu par un administrateur.";
            header("Location: /auth/login.php");
            exit(); 
        }    
        
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['created_at'] = $user['created_at'];
        header("Location: " .($ref === 'http://lebonclone.fr/auth/login.php' ? '/' : $ref));
    } else {
        $_SESSION['error'] = "Adresse mail ou Mot de passe incorrect.";
        header("Location: /auth/login.php");
    }
    exit();
}

function handleRegister($db) {
    $username = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: /auth/register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: /auth/register.php");
        exit();
    }

    if (count($password) < 8){
        $_SESSION['error'] = "Le mot de passe doit depasser 8 caracteres";
        header("Location: /auth/register.php");
        exit();
    }

    $ref = $_POST['ref'] ?? '/';
    $email_escaped = mysqli_real_escape_string($db, $email);
    $username_escaped = mysqli_real_escape_string($db, $username);

    $result_check = mysqli_query($db, "SELECT id FROM users WHERE email = '$email_escaped'");
    
    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['error'] = "Cet email existe déjà.";
        header("Location: /auth/register.php");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO users (last_name, first_name, email, password, created_at) 
                   VALUES ('$username_escaped', '$username_escaped', '$email_escaped', '$password_hash', NOW())";
    
    if (mysqli_query($db, $sql_insert)) {
        $_SESSION['user_id'] = (int)mysqli_insert_id($db);
        $_SESSION['first_name'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['created_at'] = date('Y-m-d H:i:s'); 
        
        header("Location: $ref");
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription.";
        header("Location: /auth/register.php");
    }
    exit();
}


function handleLogout() {
    session_destroy();
    $_SESSION['success'] = "Vous etes deconnectez.";
    header("Location: /");
    exit();
}


function handleUpdateProfile($db) {
    $user_id = (int)($_SESSION['user_id'] ?? 0);
    $username = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email)) {
        $_SESSION['error'] = "Le pseudo et l'email ne peuvent pas être vides.";
        header("Location: /user/");
        exit();
    }

    $email_escaped = mysqli_real_escape_string($db, $email);
    $username_escaped = mysqli_real_escape_string($db, $username);

    $result_check = mysqli_query($db, "SELECT id FROM users WHERE email = '$email_escaped' AND id != $user_id");
    
    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé par un autre compte.";
        header("Location: /user/");
        exit();
    }

    $sql_update = "UPDATE users SET first_name = '$username_escaped', last_name = '$username_escaped', email = '$email_escaped' WHERE id = $user_id";
    mysqli_query($db, $sql_update);
    
    $_SESSION['first_name'] = $username;
    $_SESSION['email'] = $email;

    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($db, "UPDATE users SET password = '$password_hash' WHERE id = $user_id");
    }

    $_SESSION['success'] = "Profil mis à jour avec succès !";
    header("Location: /user/");
    exit();
}

