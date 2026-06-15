<?php
session_start();
require_once "../../config/database.php";
require_once "../components/NavBar.php";
require_once "../components/SideNav.php";
require_once "../components/Footer.php";
require_once "../components/Card.php";
require_once "../components/Button.php";
require_once "../security/auth-guard.php";
require_auth();
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);


$favoris = [];
$user_id = $_SESSION['user_id'];
$fav_result = mysqli_query($db, "SELECT post_id, created_at FROM favorites WHERE user_id = $user_id ORDER BY created_at DESC");

while ($fav_row = mysqli_fetch_assoc($fav_result)) {
    $post_id = $fav_row['post_id'];
    $post_info_result = mysqli_query($db, "SELECT * FROM posts WHERE id = '$post_id'");
    $post_info = mysqli_fetch_assoc($post_info_result);

    if ($post_info) {
        $favoris[] = [
            'id' => $post_info['id'],
            'title' => $post_info['title'],
            'price' => (float) $post_info['price'],
            'state' => $post_info['state'],
            'date' => $post_info['created_at'],
            'img' => $post_info['img'],
            'is_fav' => true
        ];
    }
}
;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - Leboncoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    
    <?php NavBar($route_name, $parent_name) ?>
    <div class="container flex-grow-1 py-5 px-4">
        <div class="row g-4">
            <?php SideNav($route_name) ?>

            <div class="col-12 col-lg-9">
                <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius: 12px;">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0 fs-5">Mes annonces favorites</h4>
                        <span class="badge bg-primary text-white rounded-pill px-3">
                            <?= count($favoris); ?> sauvegardée<?= count($favoris) > 1 ? 's' : '' ?>
                        </span>
                    </div>

                    <?php if (empty($favoris)): ?>
                        <div class="text-center py-5">
                            <i class="fa-regular fa-heart text-muted mb-3 fs-1"></i>
                            <p class="text-secondary m-0 mb-3">Vous n'avez pas encore d'annonces en favoris.</p>
                            <?php
                            Button(
                                type: 'link',
                                action: '../index.php',
                                value: 'Parcourir les annonces',
                                variant: 'secondary'
                            );
                            ?>
                        </div>

                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                            <?php
                            foreach ($favoris as $item) {
                                Card($item);
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <?php Footer($route_name, $parent_name) ?>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>