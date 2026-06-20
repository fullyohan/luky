<?php
session_start();
require_once "../config/database.php";
require_once "components/NavBar.php";
require_once "components/Footer.php";
require_once "components/Table.php";
require_once "components/Alert.php";
require_once "security/auth-guard.php";
require_auth();

$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté";
    header("Location: /");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$check_admin = mysqli_query($db, "SELECT status FROM users WHERE id = $user_id");
$current_user = mysqli_fetch_assoc($check_admin);


if (!$current_user || $current_user['status'] !== 'admin') {
    $_SESSION['error'] = "Accès réservé aux administrateurs.";
    header("Location: /");
    exit();
}

$total_users = 0;
$total_admins = 0;
$total_suspended = 0;

$stats_result = mysqli_query($db, "SELECT status, COUNT(*) as count FROM users GROUP BY status");
while ($row = mysqli_fetch_assoc($stats_result)) {
    $total_users += (int)$row['count'];
    if ($row['status'] === 'admin') $total_admins = (int)$row['count'];
    if ($row['status'] === 'suspended') $total_suspended = (int)$row['count'];
}


$allowed_filters = ['all', 'suspended', 'admin'];
$filter = isset($_GET['filter']) && in_array($_GET['filter'], $allowed_filters) ? $_GET['filter'] : 'all';

$sql = "SELECT id, first_name as name, email, status FROM users";

if ($filter === 'suspended') {
    $sql .= " WHERE status = 'suspended'";
} elseif ($filter === 'admin') {
    $sql .= " WHERE status = 'admin'";
}

$sql .= " ORDER BY id DESC";
$users_result = mysqli_query($db, $sql);

$users = [];
while ($user = mysqli_fetch_assoc($users_result)) {
    $users[] = [
        'id'     => '#' . $user['id'], 
        'name'   => $user['name'],
        'email'  => $user['email'],
        'status' => $user['status']
    ];
}

function StatCard(string $title, int $count, string $color = 'dark') { ?>
    <div class="col-12 col-sm-4">
        <div class="card border-0 shadow-sm p-3 bg-white">
            <span class="text-muted small fw-semibold text-uppercase d-block mb-1"><?=$title?></span>
            <h3 class="fw-bold m-0 text-<?= $color ?>"><?= number_format($count) ?></h3>
        </div>
    </div>
<?php } ?>

<?php 
function FilterGroup(string $currentFilter = 'all', string $baseUrl = 'admin.php') {
    $buttons = [
        'all'       => 'Toutes',
        'suspended' => 'Suspendus',
        'admin'     => 'Admins'
    ];
    ?>
    <div class="bg-light p-1 rounded border">
        <?php foreach ($buttons as $value => $label): ?>
            <?php 
                $isActive = ($currentFilter === $value); 
                $class = $isActive 
                    ? 'btn-white shadow-sm fw-semibold text-dark' 
                    : 'text-muted';
            ?>
            <a href="<?= $baseUrl ?>?filter=<?= $value ?>" class="btn btn-sm <?= $class ?> px-3">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Lebonclone</title>
    <link rel="shortcut icon" href="fav.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light d-flex flex-column" style="min-height: 100vh;">
    <?php NavBar($route_name, $parent_name); ?>
    
    <div class="container flex-grow-1 py-5 px-4">
        <?= Alert(); ?>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold fs-3 text-dark mb-1">Tableau de bord de modération</h2>
                <p class="text-muted small mb-0">Gestion globale des utilisateurs en temps réel.</p>
            </div>
        </div>

        <div class="row g-3 mb-5">
            <?php 
                StatCard(title: "Utilisateurs", count: $total_users);
                StatCard(title: "Admins", count: $total_admins, color: "orange");
                StatCard(title: "Suspendus", count: $total_suspended, color: "danger");
            ?>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h4 class="fw-bold m-0 fs-5">Liste d'utilisateurs</h4>
                <?php FilterGroup($filter); ?>
            </div>

            <div class="table-responsive">
                <?php
                    Table(
                        dataSource: $users,
                        renderActions: function ($user) {
                            $clean_id = (int)str_replace('#', '', $user['id']);
                            $statusButtons = "";
                            
                            if ($user['status'] === 'user') {
                                $statusButtons = "
                                    <form action='handlers/admin-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='promote'>
                                        <input type='hidden' name='user_id' value='{$clean_id}'>
                                        <button type='submit' class='btn btn-success btn-sm' title='Promouvoir'>
                                            <i class='fa-solid fa-arrow-up'></i>
                                        </button>
                                    </form>
                                    <form action='handlers/admin-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='suspend'>
                                        <input type='hidden' name='user_id' value='{$clean_id}'>
                                        <button type='submit' class='btn btn-orange btn-sm' title='Suspendre'>
                                            <i class='fa-solid fa-ban'></i>
                                        </button>
                                    </form>";
                            } elseif ($user['status'] === 'admin') {
                                $statusButtons = "
                                    <form action='handlers/admin-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='demote'>
                                        <input type='hidden' name='user_id' value='{$clean_id}'>
                                        <button type='submit' class='btn btn-success btn-sm' title='Downgrade'>
                                            <i class='fa-solid fa-arrow-down'></i>
                                        </button>
                                    </form>
                                    <form action='handlers/admin-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='suspend'>
                                        <input type='hidden' name='user_id' value='{$clean_id}'>
                                        <button type='submit' class='btn btn-orange btn-sm' title='Suspendre'>
                                            <i class='fa-solid fa-ban'></i>
                                        </button>
                                    </form> ";
                            } elseif ($user['status'] === 'suspended') {
                                $statusButtons = "
                                    <form action='handlers/admin-handler.php' method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='unsuspend'>
                                        <input type='hidden' name='user_id' value='{$clean_id}'>
                                        <button type='submit' class='btn btn-orange btn-sm' title='Dessuspendre'>
                                            <i class='fa-solid fa-check'></i>
                                        </button>
                                    </form>";
                            }

                            echo "
                            <div class='btn-group btn-group-sm gap-1'>
                                {$statusButtons}
                                <form action='handlers/admin-handler.php' method='POST' style='display: inline;' 
                                    onsubmit='return confirm(\"Supprimer définitivement cet utilisateur ? (Action irréversible)\");'>
                                    <input type='hidden' name='action' value='delete_user'>
                                    <input type='hidden' name='user_id' value='{$clean_id}'>
                                    <button type='submit' class='btn btn-danger btn-sm' title='Supprimer'>
                                        <i class='fa-solid fa-trash-can'></i>
                                    </button>
                                </form>
                            </div>";
                        },
                    );
                ?>
            </div>
        </div>
    </div>

    <?php Footer($route_name, $parent_name); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>