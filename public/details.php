<?php
session_start();
require_once "../config/database.php";
require_once "components/NavBar.php";
require_once "components/Footer.php";

$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

if (isset($_GET['id'])) {
    $post_id = (int) ($_GET['id']);
   
    $sql = "SELECT posts.*, users.first_name as seller_name FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $post_id";
    $result_post = mysqli_query($db, $sql);
    $post = mysqli_fetch_assoc($result_post);

    if (!$post) {
        header("Location: index.php");
        exit();
    }

    $created_at = new DateTime($post['created_at']);
    $date_formatted = "Publiée le " . $created_at->format('d/m/Y à H:i');
    $user_id = (int) ($_SESSION['user_id'] ?? 0);
    $is_fav = false;
    if ($user_id > 0) {
        $fav_check = mysqli_query($db, "SELECT 1 FROM favorites WHERE user_id = $user_id AND post_id = $post_id");
        $is_fav = (mysqli_num_rows($fav_check) > 0);
    }
    $seller_initial = strtoupper($post['seller_name'][0] ?? 'U');
} else {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - Leboncoin</title>
    <link rel="shortcut icon" href="fav.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <?php NavBar($route_name, $parent_name); ?>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-12 col-lg-8">     
                <div class="rounded-3 overflow-hidden bg-light border text-center mb-3">
                    <img src="<?= htmlspecialchars($post['img']) ?>" class="img-fluid w-100"
                        style="max-height: 450px; object-fit: contain;" alt="<?= htmlspecialchars($post['title']) ?>">
                </div>

                <h1 class="fw-bold fs-3 text-dark px-1 mb-2"><?= htmlspecialchars($post['title']) ?></h1>
                <div class="px-1 mb-4">
                    <span class="badge bg-light text-dark border py-2 px-3">
                        <?= htmlspecialchars($post['state'] ?? 'Bon état') ?>
                    </span>
                </div>

                <div class="card border-0 p-4 shadow-sm mb-4">
                    <h5 class="fw-bold fs-6 text-dark mb-3">Description</h5>
                    <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.6;">
                        <?= htmlspecialchars($post['description']) ?>
                    </p>
                    <hr>
                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> <?= $date_formatted ?></small>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 p-4 shadow-sm position-sticky" style="top: 20px;">
                    
                    <div class="pb-3 border-bottom text-center text-lg-start">
                        <span class="text-muted small text-uppercase fw-semibold">Prix</span>
                        <h2 class="text-danger fw-bold m-0 fs-2 mt-1">
                            <?= number_format($post['price'], 0, ',', ' ') ?> €
                        </h2>
                    </div>

                    <div class="py-4 d-flex flex-column gap-2">
                        <?php if ($user_id !== $post['user_id']): ?>
                            <button type="button" class="btn btn-primary fw-bold btn-lg w-100 py-2.5 fs-6"
                                data-bs-toggle="modal" data-bs-target="#contactSellerModal">
                                <i class="fa-solid fa-comment-dots me-2"></i> Contacter le vendeur
                            </button>

                            <form action="/handlers/post-handler.php" method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="action" value="like">
                                <input type="hidden" name="ref" value="/post-detail.php?id=<?= $post['id'] ?>">
                                <?php if ($is_fav): ?>
                                    <button type="submit" class="btn btn-danger fw-bold w-100 py-2 fs-6">
                                        <i class="fa-solid fa-heart me-2"></i> Retirer des favoris
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-outline-secondary fw-bold w-100 py-2 fs-6">
                                        <i class="fa-regular fa-heart text-danger me-2"></i> Enregistrer l'annonce
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <form action='/user/create' method='POST' style='display: inline;'>
                                <input type='hidden' name='id' value='<?= $post['id'] ?>'>
                                <button type='submit' class='btn btn-primary fw-bold w-100 py-2 fs-6' title='Modifier'>
                                    <i class="fa-solid fa-pen-to-square me-2"></i> Modifier l'annonce
                                </button>
                            </form>

                            <form action="/handlers/post-handler.php" method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-outline-danger fw-bold w-100 py-2 fs-6">
                                    <i class="fa-solid fa-trash-can me-2"></i> Supprimer l'annonce
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="pt-3 border-top d-flex align-items-center gap-3">
                        <div class="bg-primary text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-6 shadow-sm"
                            style="width: 40px; height: 40px; min-width: 40px;">
                            <?= $seller_initial ?>
                        </div>
                        <div>
                            <h6 class="m-0 fw-bold text-dark">
                                <?= htmlspecialchars($post['seller_name'] ?? 'Anonyme') ?>
                            </h6>
                            <small class="text-muted text-xs">Propriétaire de l'annonce</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if ($user_id !== $post['user_id']): ?>
        <div class="modal fade" id="contactSellerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="contactSellerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold fs-6 text-dark" id="contactSellerModalLabel">
                            <i class="fa-regular fa-comments text-primary me-2"></i>
                            Nouvelle discussion avec <?= htmlspecialchars($post['seller_name'] ?? 'le vendeur', ENT_QUOTES, 'UTF-8') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="/handlers/chat-handler.php" method="POST">
                        <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                        <input type="hidden" name="action" value="start">
                        <div class="modal-body p-4">
                            <div class="p-3 bg-light rounded mb-3 border">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">À propos de l'annonce</small>
                                <span class="fw-bold text-dark d-block text-truncate"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="fw-bold text-danger"><?= number_format($post['price'], 0, ',', ' ') ?> €</span>
                            </div>

                            <div class="mb-3">
                                <label for="first_message" class="form-label fw-bold small text-secondary">Votre premier message</label>
                                <textarea class="form-control" id="first_message" name="message" rows="4"
                                    placeholder="Bonjour, votre article est-il toujours disponible ?" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-top-0">
                            <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="fa-solid fa-paper-plane me-2"></i> Envoyer et ouvrir le chat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php Footer($route_name, $parent_name); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>