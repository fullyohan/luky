<?php
session_start();
require_once "../../config/database.php";
require_once "../security/auth-guard.php";

require_auth();

$user_id = (int)($_SESSION['user_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$user_id || !$action) {
    redirectBack();
}




switch ($action) {
    case 'create':
        handleCreatePost($db, $user_id);
        break;
    case 'modify':
        handleModifyPost($db, $user_id);
        break;
    case 'delete':
        handleDeletePost($db, $user_id);
        break;
    case 'like':
        handlePostLike($db, $user_id);
        break;
    default:
        redirectBack();
}


function uploadPostImage() {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        
        $img_name = 'img_' . uniqid('', true) . '.' . $extension;
    
        $path = "/uploads/" . $img_name;

        if (move_uploaded_file($tmp_name, "..".$path)) {
            return $path;
        }
    }
    return null;
}



function handleCreatePost($db, $user_id) {
    $title = mysqli_real_escape_string($db, trim($_POST['title'] ?? ''));
    $price = (float)($_POST['price'] ?? 0);
    $state = mysqli_real_escape_string($db, $_POST['state'] ?? '');
    $description = mysqli_real_escape_string($db, trim($_POST['description'] ?? ''));
   
    $img_path = uploadPostImage();
    $img_escaped = $img_path ? "'" . mysqli_real_escape_string($db, $img_path) . "'" : "NULL";

    $sql = "INSERT INTO posts (user_id, title, description, price, state, img) 
            VALUES ($user_id, '$title', '$description', $price, '$state', $img_escaped)";
            
    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = "Votre annonce a bien été publiée !";
    } else {
        $_SESSION['error'] = "Erreur lors de la publication de l'annonce.";
    }
    
    header("Location: /user/annonces.php");
    exit();
}


function handleModifyPost($db, $user_id){
    $post_id = (int)($_POST['id'] ?? 0);
    $title = mysqli_real_escape_string($db, trim($_POST['title'] ?? ''));
    $price = (float)($_POST['price'] ?? 0);
    $state = mysqli_real_escape_string($db, $_POST['state'] ?? '');
    $description = mysqli_real_escape_string($db, trim($_POST['description'] ?? ''));

    if (!$post_id) redirectBack();
    $image_query = ""; 
    $img_path = uploadPostImage();
    
    if ($img_path) $image_query = ", img = '" . mysqli_real_escape_string($db, $img_path) . "'"; 

    $sql = "UPDATE posts 
            SET title = '$title', 
                price = $price, 
                state = '$state', 
                description = '$description' 
                $image_query 
            WHERE id = $post_id AND user_id = $user_id";

    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = "L'annonce a bien été modifiée !";
    } else {
        $_SESSION['error'] = "Erreur lors de la modification.";
    }

    header("Location: /user/annonces.php");
    exit();
}


function handleDeletePost($db, $user_id) {
    $post_id = (int)($_POST['id'] ?? 0);
    if (!$post_id) redirectBack('/user/annonces.php');
    $sql = "DELETE FROM posts WHERE id = $post_id AND user_id = $user_id";
    if (mysqli_query($db, $sql)) {
        $_SESSION['success'] = "L'annonce a bien été supprimée.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression.";
    }
    header("Location: /user/annonces.php");
    exit();
}


function handlePostLike($db, $user_id){     
    $post_id = (int)($_POST['id'] ?? 0);
    if (!$post_id) redirectBack();
    $stmt = mysqli_query($db, "SELECT 1 FROM favorites WHERE user_id = $user_id AND post_id = $post_id");
    if (mysqli_num_rows($stmt) === 0) mysqli_query($db, "INSERT INTO favorites (user_id, post_id) VALUES ($user_id, $post_id)");
    else mysqli_query($db, "DELETE FROM favorites WHERE user_id = $user_id AND post_id = $post_id");
    redirectBack();
}


function redirectBack($default = '/') {
    $redirect = $_SERVER['HTTP_REFERER'] ?? $default;
    header("Location: " . $redirect);
    exit();
}