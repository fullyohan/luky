<?php
    session_start();
    require_once "../components/NavBar.php";
    require_once "../components/Footer.php";
    require_once "../components/TextInput.php";
    require_once "../components/Button.php";
    require_once "../security/auth-guard.php";
    require_once "../components/Alert.php";
    require_guest();
    $route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
    $parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Luky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="fav.png" type="image/x-icon">
</head>
<body class="bg-light d-flex flex-column" style="min-height: 100vh;">
   <?php Navbar($route_name, $parent_name) ?>
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5 px-4">
        <div class="card shadow-sm border-0 p-4 w-100" style="max-width: 450px; border-radius: 12px;">
            <div class="text-center mb-4">
                <h2 class="fw-bold fs-3 text-dark mb-2">Bonjour !</h2>
                <p class="text-muted small">Connectez-vous pour gérer vos annonces et messages.</p>
            </div>
            <form action="/handlers/auth-handler.php" method="POST">
                <?php 
                    TextInput(
                        type: "email", 
                        name: "email", 
                        label: "Adresse email", 
                        placeholder: "exemple@mail.com", 
                        isRequired: true
                    ); 
                    TextInput(
                        type: "password", 
                        name: "password", 
                        label: "Mot de passe", 
                        placeholder: "Votre mot de passe", 
                        isRequired: true
                    ); 
                    Button(
                        type:'submit',
                        variant:'primary',
                        value:'Se connecter',
                        size:'100%'
                    );
                ?>
                <div class="mt-4">
                    <?= Alert(); ?>
                </div>
                <input hidden name="ref" value="<?= $_SERVER['HTTP_REFERER'] ?? '' ?>">
                <input hidden name="action" value="login">
            </form>
            <div class="text-center mt-3 border-top pt-3">
                <p class="text-muted small mb-0">
                    Aucun compte ? 
                    <a href="/auth/register.php" class="text-primary fw-bold text-decoration-none">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
    <?php Footer($route_name, $parent_name) ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>