<?php
    session_start();
    require_once "../components/NavBar.php";
    require_once "../components/SideNav.php";
    require_once "../components/Footer.php";
    require_once "../components/Alert.php";
    require_once "../security/auth-guard.php";
    require_auth();
    $route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
    $parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
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
                <div id="profil" class="card shadow-sm border-0 p-4">
                    <h4 class="fw-bold mb-4 fs-5">Mes informations personnelles</h4>
                    <?= Alert() ?>
                    <form action="/handlers/auth-handler.php" method="POST">
                        <input name="action" hidden value="update_profile">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="account-pseudo" class="form-label fw-semibold text-secondary small">Nom ou
                                    Pseudo</label>
                                <input type="text" id="account-pseudo" name="pseudo" class="form-control"
                                    value="<?= $_SESSION['first_name'] ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="account-email" class="form-label fw-semibold text-secondary small">Adresse
                                    email</label>
                                <input type="email" id="account-email" name="email" class="form-control"
                                    value="<?= $_SESSION['email'] ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="account-password"
                                    class="form-label fw-semibold text-secondary small">Nouveau mot de passe (laisser
                                    vide si inchangé)</label>
                                <input type="password" id="account-password" name="password" class="form-control"
                                    placeholder="••••••••">
                            </div>
                            <div class="col-12 text-end mt-4">
                                <?php 
                                    Button(
                                        type: 'submit',
                                        value: "Enregistrer les modifications",
                                        variant: 'primary',
                                    );
                                ?>
                            </div>
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