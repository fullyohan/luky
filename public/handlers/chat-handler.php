<?php
session_start();
require_once "../../config/database.php";
require_once "../security/auth-guard.php";

require_auth();

if (empty($_POST)) {
    header("Location: /");
    exit();
}

$sender_id = (int)($_SESSION['user_id'] ?? 0);
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'start':
        $post_id = (int)($_POST['post_id'] ?? 0);
        $content = trim($_POST['message'] ?? '');

        if ($post_id <= 0 || $content === '') {
            header("Location: /");
            exit();
        }

        $sql_post_info = "SELECT user_id FROM posts WHERE id = $post_id";
        $result_post_info = mysqli_query($db, $sql_post_info);
        $post_info = mysqli_fetch_assoc($result_post_info);
        $seller_id = (int)($post_info['user_id'] ?? 0);

        if ($seller_id === $sender_id) {
            $_SESSION['error'] = "Vous ne pouvez pas envoyer de message sur votre propre annonce.";
            header("Location: /");
            exit();
        }

      
        $sql_check = "SELECT id FROM chat_rooms WHERE post_id = $post_id AND buyer_id = $sender_id";
        $result_check = mysqli_query($db, $sql_check);
        $chat = mysqli_fetch_assoc($result_check);

        if ($chat) {
            $chat_id = (int)$chat['id'];
        } else {
            $sql_create_chat = "INSERT INTO chat_rooms (post_id, buyer_id, seller_id, created_at) 
                                VALUES ($post_id, $sender_id, $seller_id, NOW())";
            if (mysqli_query($db, $sql_create_chat)) {
                $chat_id = (int)mysqli_insert_id($db);
            } else {
                header("Location: /");
                exit();
            }
        }

        $content_clean = mysqli_real_escape_string($db, $content);
        $sql_msg = "INSERT INTO messages (chat_room_id, sender_id, content, created_at) 
                    VALUES ($chat_id, $sender_id, '$content_clean', NOW())";
        mysqli_query($db, $sql_msg);

        header("Location: /user/messages/chat.php?id=" . $chat_id);
        exit();

    case 'send':
        $chat_room_id = (int)($_POST['chat_room_id'] ?? 0);
        $content = trim($_POST['message'] ?? '');

        if ($chat_room_id <= 0 || $content === '') {
            header("Location: /user/messages/");
            exit();
        }
        $sql_verify_access = "SELECT id FROM chat_rooms WHERE id = $chat_room_id AND (buyer_id = $sender_id OR seller_id = $sender_id)";
        $result_verify = mysqli_query($db, $sql_verify_access);
        
        if (mysqli_num_rows($result_verify) === 0) {
            header("Location: /user/messages/");
            exit();
        }

        $content_clean = mysqli_real_escape_string($db, $content);
        $sql_msg = "INSERT INTO messages (chat_room_id, sender_id, content, created_at) 
                    VALUES ($chat_room_id, $sender_id, '$content_clean', NOW())";
        mysqli_query($db, $sql_msg);
        header("Location: /user/messages/chat.php?id=" . $chat_room_id);
        exit();

    default:
        header("Location: /");
        exit();
}