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
           <a class="navbar-brand text-white fw-bold fs-3 me-xl-4 d-flex align-items-center gap-2" href="/">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="-1.2 -0.0 283.7 312.8" width="25px" height="40px" style="fill: currentColor;">
                    <g id="__id157_sa31t3ayx1">
                        <path d="M281.069,266.327l-21.158-169.274c-1.79-14.33-14.033-25.137-28.476-25.137h-28.329v-9.505c0-34.413-27.998-62.411-62.411-62.411s-62.411,27.998-62.411,62.411v9.505h-28.331c-14.441,0-26.682,10.805-28.476,25.137L.319,266.327c-1.47,11.755,2.182,23.59,10.024,32.473,7.84,8.883,19.135,13.978,30.983,13.978h198.735c11.851,0,23.143-5.095,30.983-13.978,7.842-8.881,11.494-20.718,10.024-32.473ZM252.964,290.187c-3.906,2.703-8.613,4.008-13.363,4.008H41.327c-6.522,0-12.735-2.804-17.052-7.692-4.315-4.889-6.325-11.401-5.517-17.871l21.158-169.272c.633-5.052,4.948-8.861,10.038-8.861h16.156c6.724,0,12.175,5.451,12.175,12.175v44.471h18.583v-44.471c0-6.724,5.451-12.175,12.175-12.175h56.517v-18.583h-68.692v-8.728c0-24.524,20.069-45.018,44.589-44.598,23.816.408,43.066,19.909,43.066,43.821v84.735h18.583v-56.646h28.329c5.091,0,9.405,3.809,10.038,8.858l21.158,169.274c1.033,8.279-2.547,16.629-9.667,21.555Z"/>
                    </g>
                </svg>
                
                <span class="m-0">luky market</span>
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
                        );

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