<?php
session_start();
require_once "../components/NavBar.php";
require_once "../components/SideNav.php";
require_once "../components/Footer.php";
require_once '../components/Table.php';
require_once "../components/Button.php";
require_once "../../config/database.php";
require_once "../components/Alert.php";
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
require_once "../security/auth-guard.php";
require_auth();

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($db, $sql);

$annonces = [];
while ($annonce = mysqli_fetch_assoc($result)) {
    $annonces[] = [
        'id' => $annonce['id'],
        'Image' => $annonce['img'],
        'Titre' => $annonce['title'],
        'Prix' => (float) $annonce['price'],
        'Etat' => $annonce['state'] ?? 'Bon état',
        'Date de pub' => (new DateTime($annonce['created_at']))->format('d-m-Y'),
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Leboncoin</title>
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
                <div id="mes-annonces" class="card shadow-sm border-0 p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold m-0 fs-5">Mes annonces en ligne</h4>
                        <span class="badge bg-primary text-white rounded-pill px-3"><?= count($annonces); ?> annonces</span>
                        <?= Alert()?>
                    </div>

                    <div class="table-responsive">
                        <?php
                        Table(
                            dataSource: $annonces,
                            renderActions: function ($item) {
                                echo "
                                <div class='d-flex gap-1'>
                                    <form action='create.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='id' value='{$item['id']}'>
                                        <button type='submit' class='btn btn-sm btn-outline-secondary' title='Modifier'>
                                            <i class='fa-solid fa-pen'></i>
                                        </button>
                                    </form>
                                    <form action='/handlers/post-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='delete'>
                                        <input type='hidden' name='id' value='{$item['id']}'>
                                        <button type='submit' class='btn btn-sm btn-outline-danger' title='Supprimer'>
                                            <i class='fa-solid fa-trash'></i>
                                        </button>
                                    </form>
                                </div>";
                            },
                            renderEmpty: function () {
                                echo "
                                        <div class='text-center py-5'>
                                            <i class='fa-solid fa-plus text-muted mb-3 fs-1' ></i>
                                            <p class='text-secondary m-0 mb-2'>Vous n'avez pas encore publié d'annonces.</p>";

                                Button(
                                    type: 'link',
                                    action: '/user/create.php',
                                    value: 'Publier une annonce',
                                    variant: 'secondary'
                                );
                                echo "</div>";
                            }
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php Footer($route_name, $parent_name) ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>