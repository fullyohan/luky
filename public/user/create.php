<?php
session_start();
require_once "../components/NavBar.php";
require_once "../components/Footer.php";
require_once "../components/TextInput.php";
require_once "../components/Button.php";
require_once "../../config/database.php";
require_once "../security/auth-guard.php";
require_auth();
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez etre connecte";
    header("Location: /");
    exit();
}

$user_id = $_SESSION['user_id'];


$post = null; 

if (isset($_POST['id'])) {
    $post_id = (int)$_POST['id'];
    $sql = "SELECT * FROM posts WHERE user_id = $user_id AND id = $post_id";
    $result = mysqli_query($db, $sql);
    $post = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post ? 'Modifier mon annonce' : 'Déposer une annonce' ?> - Luky</title>
    <link rel="shortcut icon" href="../fav.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="../style.css">
</head>

<body class="bg-light d-flex flex-column" style="min-height: 100vh;">

    <?php NavBar($route_name, $parent_name) ?>

    <div class="container flex-grow-1 py-5 px-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0 p-4" style="border-radius: 12px;">

                    <div class="border-bottom pb-3 mb-4">
                        <h2 class="fw-bold fs-3 text-dark mb-1"><?= $post ? 'Modifier votre annonce' : 'Déposer une annonce' ?></h2>
                        <p class="text-muted small mb-0">Remplissez le formulaire ci-dessous pour mettre votre objet en vente.</p>
                    </div>

                    <form action="/handlers/post-handler.php" method="POST" enctype="multipart/form-data">
                        <input hidden value="<?= $post ? 'modify' : 'create' ?>" name="action">
                        <?php if ($post): ?>
                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        <?php endif; ?>

                        <?php
                        TextInput(
                            type: 'text',
                            name: "title",
                            label: "Titre de l'annonce",
                            placeholder: "Ex: Vélo de course, iPhone 13...",
                            isRequired: true,
                            subtitle: "Choisissez un titre clair et descriptif.",
                            value: $post['title'] ?? '' 
                        );
                        ?>

                        <div class="mb-4">
                            <label for="price" class="form-label fw-semibold text-secondary small">Prix (€)</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="price" id="price" min="0" step="1"
                                    class="form-control border-2" placeholder="0" required value="<?= $post['price'] ?? '' ?>">
                                <span class="input-group-text border-2 bg-light fw-bold">€</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="state" class="form-label fw-semibold text-secondary small">Etat du produit</label>
                            <?php $states = ['Neuf', 'Très bon état', 'Bon état', 'Correct'];?>
                            <select class="form-select border-2" name="state" id="state" required>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?= $state ?>" <?= ($post['state'] ?? '') === $state ? 'selected' : '' ?>>
                                        <?= $state ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-secondary small">Description de l'annonce</label>
                            <textarea name="description" id="description" rows="5" class="form-control border-2"
                                placeholder="Détaillez votre produit : état, caractéristiques, dimensions, raisons de la vente..." 
                                required><?= htmlspecialchars($post['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold text-secondary small">Photo du produit</label>
                            <input type="file" name="image" id="image" class="form-control border-2" accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text text-muted xsmall">Formats acceptés : JPG, JPEG ou PNG.</div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 border-top pt-4 mt-4">
                            <?php
                            Button(
                                type: 'submit',
                                value: $post ? "Enregistrer les modifications" : "Publier l'annonce",
                                variant: 'primary'
                            );
                            ?>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <?php Footer($route_name, $parent_name) ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>