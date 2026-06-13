<?php function SideNavLink($name,$active,$link,$icon) { ?>
    <a class="nav-link <?= $active ? 'active' : 'text-secondary' ?>" href="<?= $link ?>">
        <i class="<?= $icon ?>"></i> <?= $name ?>
    </a>
<?php }?>

<?php function SideNav(string $route_name)
{ ?>
    <div class="col-12 col-lg-3">
        <div class="card shadow-sm border-0 p-3 mb-3">
            <div class="d-flex align-items-center gap-3 p-2 border-bottom mb-3">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4 ratio ratio-1x1"
                    style="max-width: 50px;">
                    <span class="d-flex align-items-center justify-content-center"><?= strtoupper($_SESSION['first_name'][0]) ?></span>
                </div>
                <div>
                    <h6 class="m-0 fw-bold text-dark"><?= $_SESSION['first_name'] ?></h6>
                    <small class="text-muted">Membre depuis <?=  (new DateTime($_SESSION['created_at']))->format('Y') ?> </small>
                </div>
            </div>
            <div class="nav flex-column nav-pills gap-1">

                <?php 
                    SideNavLink(
                        name:'Modifier mon profil',
                        link:'/user',
                        active: $route_name === 'index',
                        icon:'fa-solid fa-user-gear me-2'
                    );
                    SideNavLink(
                        name:'Mes favoris',
                        link:'/user/fav.php',
                        active: $route_name === 'fav',
                        icon:'fa-solid fa-heart me-2'
                    );
                    SideNavLink(
                        name:'Mes annonces',
                        link:'/user/annonces.php',
                        active: $route_name === 'annonces',
                        icon:'fa-solid fa-box me-2'
                    )
                ?>
                
                <form action="/handlers/auth-handler.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="nav-link text-danger border-top mt-3 pt-2 w-100 text-start" style="background: none; border: none; padding-left: 0;">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>