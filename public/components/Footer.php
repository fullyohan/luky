<?php
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
?>

<?php function FooterLink($link, $name, $active)
{ ?>
    <li>
        <a 
            href="<?= $link?>"
            class="<?= $active ? 'fw-bold text-light' : 'text-light opacity-1' ?> text-decoration-none"
        >
            <?= $name?>
        </a>
    </li>
<?php } ?>
<?php function Footer(string $route_name, string $parent_name)
{ ?>
    <footer class="bg-primary text-light py-5 border-top mt-auto">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-4 text-center text-md-start">
                <div class="col">
                    <h5 class="text-light fw-bold mb-3">luky market</h5>
                    <p class="small mb-0">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aperiam nam doloremque facilis saepe
                        libero deserunt inventore, blanditiis odit quo aspernatur, aut ipsum incidunt deleniti vero quam
                        laborum corrupti quaerat sapiente voluptatem cumque dolores, eius minima? Veritatis ab explicabo
                        numquam vel.
                    </p>
                </div>

                <div class="col">
                    <h5 class="fw-bold mb-3">Plan du site</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <?php 
                            FooterLink(
                                link:'/',
                                active: $parent_name === '\\',
                                name:'Accueil'
                            ) ;
                            FooterLink(
                                link:'/user/create.php',
                                active: $route_name === 'create',
                                name:'Déposer une annonce'
                            ) ;
                            FooterLink(
                                link:'/user/fav.php',
                                active: $route_name === 'fav',
                                name:'Mes
                                    favoris'
                            ) ;
                            FooterLink(
                                link:'/user/messages/',
                                active: $parent_name === '/user/messages',
                                name:'Messages'
                            ) 
                        ?>
                       
                    </ul>
                </div>
            </div>

            <hr class="border-secondary my-4 opacity-25">
            <div class="text-center">
                <p class="mb-1 text-light small fw-bold">© <?php echo date('Y'); ?> Luky Market</p>
            </div>
        </div>
    </footer>
<?php } ?>