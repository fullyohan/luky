<?php
session_start();
require_once "../../config/database.php";
require_once "../security/auth-guard.php";
require_auth();



$user_id = (int)$_SESSION['user_id'] ?? 0;
$action = $_POST['action'] ?? '';
$target_user_id = (int)($_POST['user_id'] ?? 0);

if ($user_id <= 0 || !$action || $target_user_id <= 0) {
    header("Location: /admin");
    exit();
}

$sql_check = "SELECT status FROM users WHERE id = $user_id";
$result_check = mysqli_query($db, $sql_check);
$current_user = mysqli_fetch_assoc($result_check);

if (!$current_user || $current_user['status'] !== 'admin') {
    $_SESSION['error'] = "Action non autorisée. Vous devez être administrateur.";
    header("Location: /");
    exit();
}


if ($target_user_id === $user_id && in_array($action, ['demote', 'suspend', 'delete_user'])) {
    $_SESSION['error'] = "Vous ne pouvez pas modifier ou supprimer votre propre compte administrateur.";
    header("Location: /admin");
    exit();
}



switch ($action) {
    case 'promote':
        $sql = "UPDATE users SET status = 'admin' WHERE id = $target_user_id";
        executeModeration($db, $sql, "L'utilisateur a été promu administrateur.");
        break;

    case 'demote':
        $sql = "UPDATE users SET status = 'user' WHERE id = $target_user_id";
        executeModeration($db, $sql, "L'administrateur a été rétrogradé au rang d'utilisateur.");
        break;

    case 'suspend':
        $sql = "UPDATE users SET status = 'suspended' WHERE id = $target_user_id";
        executeModeration($db, $sql, "Le compte de l'utilisateur a été suspendu.");
        break;

    case 'unsuspend':
        $sql = "UPDATE users SET status = 'user' WHERE id = $target_user_id";
        executeModeration($db, $sql, "Le compte de l'utilisateur a été réactivé.");
        break;

    case 'delete_user':
        $sql = "DELETE FROM users WHERE id = $target_user_id";
        executeModeration($db, $sql, "L'utilisateur a été définitivement supprimé.");
        break;

    default:
        header("Location: /admin");
        exit();
}

function executeModeration($db, $sql, $success_message) {
    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = $success_message;
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de l'opération.";
    }
    header("Location: /admin");
    exit();
}