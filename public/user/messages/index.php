<?php 
session_start();
require_once "../../components/NavBar.php";
require_once "../../components/Footer.php";
require_once "../../../config/database.php";
require_once "../../security/auth-guard.php";
require_auth();
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

$user_id = (int)$_SESSION['user_id'];
$chat_rooms = [];

$sql = "SELECT id, post_id, seller_id, buyer_id 
        FROM chat_rooms 
        WHERE seller_id = $user_id OR buyer_id = $user_id
        ORDER BY id DESC";
$result = mysqli_query($db, $sql);

if ($result) {
    while ($chat_room = mysqli_fetch_assoc($result)) {
        $chat_room_id = (int)$chat_room['id'];
        $post_id = (int)$chat_room['post_id'];
       
        $other_member_id = ((int)$chat_room['seller_id'] === $user_id) ? (int)$chat_room['buyer_id'] : (int)$chat_room['seller_id'];

        $sql_user = "SELECT first_name FROM users WHERE id = $other_member_id";
        $result_user = mysqli_query($db, $sql_user);
        $user_data = mysqli_fetch_assoc($result_user);
        $recipient_name = $user_data['first_name'] ?? 'Anonyme';
      
        $sql_last_message = "SELECT created_at, content FROM messages 
                             WHERE chat_room_id = $chat_room_id 
                             ORDER BY created_at DESC LIMIT 1";
        $result_last_message = mysqli_query($db, $sql_last_message);
        $last_message = mysqli_fetch_assoc($result_last_message);

        if (isset($last_message['content'])) {
            $message_content = str_replace(["\r", "\n"], ' ', $last_message['content']);
        } else {
            $message_content = 'Aucun message pour le moment...';
        }
        
        $message_hour = isset($last_message['created_at']) ? (new DateTime($last_message['created_at']))->format('H:i') : '--:--';

        $sql_post = "SELECT title FROM posts WHERE id = $post_id";
        $result_post = mysqli_query($db, $sql_post);
        $post = mysqli_fetch_assoc($result_post);
        $post_title = $post['title'] ?? 'Annonce supprimée';

        $chat_rooms[] = [ 
            "chat_room_id" => $chat_room_id,
            "post_title" => $post_title,
            "user" => $recipient_name, 
            "content" => $message_content,
            "hour" => $message_hour
        ];
    }
}
?>

<?php function MessageListItem($chat_room) { ?>
    <a href="chat.php?id=<?= $chat_room['chat_room_id'] ?>" class="list-group-item list-group-item-action p-3 conversation-item d-flex align-items-center gap-3 border-0 border-bottom">
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex justify-content-between align-items-baseline">
                <span class="fw-bold text-dark"><?= htmlspecialchars($chat_room['user'], ENT_QUOTES, 'UTF-8') ?></span>
                <small class="text-orange fw-bold" style="color: #f56a2a;"><?= $chat_room['hour'] ?></small>
            </div>
            <div class="fw-semibold text-truncate small text-secondary my-1"><?= htmlspecialchars($chat_room['content'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="text-muted text-truncate small" style="font-size: 0.8rem;">
                <i class="fa-solid fa-tags me-1" style="font-size: 0.75rem;"></i> <?= htmlspecialchars($chat_room['post_title'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
    </a>    
<?php } ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Messages - Luky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../style.css">
    <link rel="shortcut icon" href="../../fav.png" type="image/x-icon">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    
    <?php NavBar($route_name, $parent_name); ?>
    
    <div class="container flex-grow-1 py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-bold m-0 text-dark">Mes messages</h4>
            <span class="fw-bold" style="color: #f56a2a;"><?= count($chat_rooms) ?> discussion<?= count($chat_rooms) > 1 ? 's' : '' ?></span>
        </div>
        
        <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
            <div class="list-group list-group-flush">
                <?php if (empty($chat_rooms)): ?>
                    <div class="p-5 text-center text-muted">
                        <i class="fa-regular fa-envelope-open fa-2x mb-3"></i>
                        <p class="mb-0">Vous n'avez pas encore de messages.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($chat_rooms as $chat_room) : ?>
                        <?= MessageListItem($chat_room); ?>  
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php Footer($route_name, $parent_name); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>