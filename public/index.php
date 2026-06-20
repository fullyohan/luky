<?php
session_start();
require_once "../config/database.php";
require_once "components/NavBar.php";
require_once "components/Footer.php";
require_once "components/Card.php";
require_once "components/Alert.php";

$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

$user_id = (int) ($_SESSION['user_id'] ?? 0);
$favorite_ids = [];

if ($user_id) {
    $fav_result = mysqli_query($db, "SELECT post_id FROM favorites WHERE user_id = $user_id");
    while ($fav = mysqli_fetch_assoc($fav_result)) {
        $favorite_ids[] = (int) $fav['post_id'];
    }
}

$sql = "SELECT id, title, price, description, img, state, created_at FROM posts ORDER BY created_at DESC LIMIT 10";
$result = mysqli_query($db, $sql);

$posts = [];
while ($post = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'id' => (int) $post['id'],
        'title' => $post['title'],
        'price' => (float) $post['price'],
        'state' => $post['state'],
        'date' => $post['created_at'],
        'img' => $post['img'],
        'is_fav' => in_array((int) $post['id'], $favorite_ids, true)
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leboncoin - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="shortcut icon" href="fav.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <?php NavBar($route_name, $parent_name); ?>
    <div class="py-5 bg-white rounded border-bottom shadow-xl">
        <div class="container text-center px-4">
            <h2 class="fw-bold mb-4 fs-3 fs-md-2">Des millions de petites annonces au bon endroit</h2>
            <form action="/search" method="GET" class="row g-2 mb-4 justify-content-center">
                <div class="col-12 col-md-6">
                    <input type="text" name="query" class="form-control form-control-lg border-2"
                        placeholder="Que recherchez-vous aujourd'hui ?">
                </div>
                <div class="col-12 col-md-2">
                    <?php
                    Button(
                        type: 'submit',
                        value: "Rechercher",
                        variant: 'primary',
                        icon: 'fa-solid fa-magnifying-glass',
                        size: '100%'
                    );
                    ?>
                </div>
            </form>
            <?= Alert(); ?>
        </div>
    </div>

    <div class="container py-5 px-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
            <h3 class="fw-bold m-0 fs-4">Dernières annonces</h3>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            <?php foreach ($posts as $post): ?>
                <?php Card($post); ?>
            <?php endforeach; ?>
        </div>
    </div>

    <?php Footer($route_name, $parent_name); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>