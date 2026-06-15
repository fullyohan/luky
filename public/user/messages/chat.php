<?php 
session_start();
require_once "../../components/NavBar.php";
require_once "../../../config/database.php";
require_once "../../security/auth-guard.php";
require_auth();
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
if (!isset($_GET['id'])) {
    header('Location: /user/messages/');
    exit;
}

$current_user_id = (int) $_SESSION['user_id'];
$chat_room_id = (int) $_GET['id'];

$sql = "
    SELECT
        cr.id,
        cr.buyer_id,
        cr.seller_id,
        p.title,
        p.price,
        p.img,
        u.first_name
    FROM chat_rooms cr
    JOIN posts p
        ON p.id = cr.post_id
    JOIN users u
        ON u.id = CASE
            WHEN $current_user_id = cr.seller_id THEN cr.buyer_id
            ELSE cr.seller_id
        END
    WHERE cr.id = $chat_room_id
      AND ($current_user_id IN (cr.seller_id, cr.buyer_id))
";

$result = mysqli_query($db, $sql);
$chat = mysqli_fetch_assoc($result);

if (!$chat) {
    header('Location: /');
    exit;
}

$recipient_name = $chat['first_name'];
$avatar_initial = strtoupper($recipient_name[0] ?? 'A');

$sql_messages = "
    SELECT content, created_at, sender_id
    FROM messages
    WHERE chat_room_id = $chat_room_id
    ORDER BY created_at
";

$result_messages = mysqli_query($db, $sql_messages);

$messages = [];

while ($row = mysqli_fetch_assoc($result_messages)) {
    $messages[] = [
        'type'    => ((int)$row['sender_id'] === $current_user_id) ? 'out' : 'in',
        'content' => $row['content'],
        'hour'    => date('H:i', strtotime($row['created_at']))
    ];
}
function chatBubble($message) { 
    $justify = ($message['type'] === 'in') ? 'justify-content-start' : 'justify-content-end';
    $style = ($message['type'] === 'in') ? 'background-color:#e9ecef;color:black;' : 'background-color:#0d6efd;color:white;';
    $body = htmlspecialchars($message['content']);
    echo " 
    <div class='d-flex mb-3 $justify'>
        <div class='p-2 px-3 mw-75' style='border-radius: 10px;$style'>
            <p class='mb-1' style='font-size: 0.95rem;'>$body</p>
            <small class='text-muted d-block text-end' style='font-size: 0.7rem;'>{$message['hour']}</small>
        </div>
    </div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion avec <?= htmlspecialchars($recipient_name, ENT_QUOTES, 'UTF-8') ?> - Leboncoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="vh-100 bg-light p-0 m-0 overflow-hidden d-flex flex-column">
    
    <?php NavBar($route_name, $parent_name); ?>
    
    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
        <div class="p-3 border-bottom bg-white shadow-sm">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div class="d-flex align-items-center gap-3">
                    <a href="/user/messages/"
                    class="btn btn-light btn-sm rounded-circle text-secondary d-flex align-items-center justify-content-center"
                    style="width:32px;height:32px;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>

                    <div class="bg-primary text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        style="width:40px;height:40px;">
                        <?= $avatar_initial ?>
                    </div>

                    <h6 class="m-0 fw-bold text-dark">
                        <?= htmlspecialchars($recipient_name) ?>
                    </h6>
                </div>

                <div class="bg-light p-2 rounded border d-flex align-items-center gap-2 w-100 w-md-auto">
                    <img
                        src="<?= htmlspecialchars($chat['img']) ?>"
                        alt="Produit"
                        class="rounded"
                        style="width:60px;height:60px;object-fit:cover;"
                    >

                    <div class="flex-grow-1">
                        <div class="fw-bold text-truncate text-dark" style="font-size:0.8rem;">
                            <?= htmlspecialchars($chat['title']) ?>
                        </div>

                        <span class="fw-bold text-danger" style="font-size:0.85rem;">
                            <?= number_format($chat['price'], 0, ',', ' ') ?> €
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <div id="chat-box" class="flex-grow-1 overflow-y-auto p-3" style="background-color: #f8f9fa;">
            <?php if (empty($messages)): ?>
                <div class="text-center text-muted my-5">
                    <i class="fa-regular fa-comments fa-2x mb-2"></i>
                    <p class="small">Aucun message ici. Lancez la discussion !</p>
                </div>
            <?php else: ?>
                <?php foreach($messages as $message): ?>
                    <?php chatBubble($message); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="p-3 border-top bg-white">
            <form action="/handlers/chat-handler.php" method="POST" class="d-flex align-items-center gap-2 mx-auto">
                <input type="hidden" name="chat_room_id" value="<?= $chat_room_id ?>">
                <input type="hidden" name="action" value="send">
                <div class="flex-grow-1">
                    <input type="text" name="message" class="form-control bg-light border-0 py-2 rounded-pill px-3" placeholder="Écrivez votre message ici..." required autocomplete="off">
                </div>
                <button type="submit" class="btn p-0 rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0 bg-primary" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-paper-plane" style="font-size: 0.95rem;"></i>
                </button>
            </form>
        </div>

    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>