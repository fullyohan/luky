<?php 
    $route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
    $parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
    require_once 'Button.php';
?>

<?php function NavLink($icon,$name,$active,$link) {?>
    <li class="nav-item text-center">
        <a href="<?=$link?>" class="nav-link d-flex flex-column flex-lg-row align-items-center justify-content-center gap-1 <?= $active ? 'fw-bold active' : '' ?>">
            <i class="<?= $icon ?>"></i> <span class="d-lg-inline"><?= $name ?></span>
        </a>
    </li>
<?php }?>

<?php function NavBar(string $route_name, string $parent_name,string $search_query = '' ) { ?>
    <nav class="navbar bg-primary navbar-dark navbar-expand-lg navbar-light border-bottom sticky-top py-2">
        <div class="container py-1">

            <a class="navbar-brand text-white fw-bold fs-3 me-xl-4" href="/">
                luky market
            </a>

            <form class="d-none d-lg-flex flex-grow-1 mx-4" action="/search" method="GET" style="max-width: 500px">
                <div class="input-group">
                    <input type="search" name="query" class="form-control bg-light border-end-0 py-2" placeholder="Rechercher sur luky market..." aria-label="Rechercher" value="<?=$search_query ?>">
                    <button class="btn bg-white border-start-0 px-3" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <form class="d-flex d-lg-none my-3" action="/search" method="GET">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control bg-white" placeholder="Rechercher..." <?=$search_query ?>>
                        <button class="btn bg-white" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-lg-center gap-2 gap-lg-3">

                    <?= 
                        
                        NavLink(
                            icon:'fa-solid fa-plus-square',
                            name:'Déposer une annonce',
                            active:$route_name === 'create',
                            link:'/user/create.php'
                        ),

                        NavLink(
                            icon:'fa-solid fa-heart',
                            name:'Mes favoris',
                            active:$route_name === 'fav',
                            link:'/user/fav.php'
                        );

                        NavLink(
                            icon:'fa-solid fa-comment-dots',
                            name:'Messages',
                            active:$parent_name === '/user/messages',
                            link:'/user/messages'
                        );
                        $is_loged = isset($_SESSION['user_id']);
                        Button(
                            type:'link',
                            variant:'secondary',
                            icon:'fa-solid fa-user me-1',
                            value:$is_loged ? 'Mon compte':'Se connecter',
                            action:$is_loged ? '/user':'/auth/login.php',
                            paddingX:3
                        );
                    ?>

                </ul>

            </div>
        </div>
    </nav>
<?php } ?>