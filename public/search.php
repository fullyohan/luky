<?php
session_start();
require_once "../config/database.php";
require_once "components/NavBar.php";
require_once "components/Footer.php";
require_once "components/Card.php";

$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);


$user_id = (int)($_SESSION['user_id'] ?? 0);
$favorite_ids = [];

if ($user_id) {
    $fav_result = mysqli_query($db, "SELECT post_id FROM favorites WHERE user_id = $user_id");
    while ($fav = mysqli_fetch_assoc($fav_result)) {
        $favorite_ids[] = (int)$fav['post_id']; 
    }
}

$search_query = $_GET['query'] ?? '';
$search_escaped = mysqli_real_escape_string($db, $search_query);


$limit = 10; 
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}


$total_query = "SELECT COUNT(*) as total FROM posts WHERE title LIKE '%$search_escaped%'";
$total_result = mysqli_query($db, $total_query);
$total = mysqli_fetch_assoc($total_result);
$total_posts = (int)$total['total'];

$total_pages = (int)ceil($total_posts / $limit);
if ($total_pages < 1) {
    $total_pages = 1;
}

if ($current_page > $total_pages) {
    $current_page = $total_pages;
}

$offset = ($current_page - 1) * $limit;

$sql = "SELECT id, title, price, description, img, state, created_at 
        FROM posts 
        WHERE title LIKE '%$search_escaped%'
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
        
$result = mysqli_query($db, $sql);

$posts = [];
while ($post = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'id'     => (int)$post['id'],
        'title'  => $post['title'],
        'price'  => (float)$post['price'],
        'state'  => $post['state'], 
        'date'   => $post['created_at'],
        'img'    => $post['img'], 
        'is_fav' => in_array((int)$post['id'], $favorite_ids, true)
    ];
}

$query_param = !empty($search_query) ? "&query=" . urlencode($search_query) : "";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - Leboncoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <?php NavBar($route_name, $parent_name, $search_query); ?>
    
    <div class="container py-5 px-4">
        <div class="mb-4">
            <h3 class="fw-bold m-0 fs-4">
                <?php if (!empty($search_query)): ?>
                    Résultats pour "<?= htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8') ?>" 
                    <span class="text-muted fs-6 fw-normal">(<?= $total_posts ?> annonce<?= $total_posts > 1 ? 's' : '' ?> trouvée<?= $total_posts > 1 ? 's' : '' ?>)</span>
                <?php else: ?>
                    Toutes les annonces récentes
                <?php endif; ?>
            </h3>
        </div>

        <?php if (empty($posts)): ?>
            <div class="text-center py-5 bg-white rounded border shadow-sm my-4">
                <i class="fa-solid fa-magnifying-glass-blur text-muted fs-1 mb-3"></i>
                <h4 class="fw-bold text-secondary">Oups, aucune annonce ne correspond à votre recherche</h4>
                <p class="text-muted">Vérifiez l'orthographe ou essayez d'autres mots-clés.</p>
                <a href="index.php" class="btn btn-primary mt-2">Retour à l'accueil</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($posts as $post): ?>
                    <?php Card($post); ?>
                <?php endforeach; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-5">
                    <ul class="pagination justify-content-center">        
                        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page - 1 . $query_param ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($current_page === $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i . $query_param ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page + 1 . $query_param ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            <?php endif; ?>

        <?php endif; ?>
    </div>
                
    <?php Footer($route_name, $parent_name); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>