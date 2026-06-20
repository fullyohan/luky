<?php
$route_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_FILENAME);
$parent_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
?>

<?php function FooterLink($link, $name, $active)
{ ?>
    <li>
        <a href="<?= $link?>"class=" <?= $active ? 'fw-bold text-light' : 'text-light opacity-1' ?> text-decoration-none">
            <?= $name?>
        </a>
    </li>
<?php } ?>
<?php function Footer(string $route_name, string $parent_name) { ?>
    <footer class="bg-primary text-light py-5 border-top mt-auto">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-4 text-center text-md-start">
                <div class="col">
                    <a class="navbar-brand text-white fw-bold fs-3 me-xl-4 d-flex align-items-center gap-2" href="/">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-1.2 -0.0 283.7 312.8" width="25px" height="40px" style="fill: currentColor;">
                            <g id="__id157_sa31t3ayx1">
                                <path d="M281.069,266.327l-21.158-169.274c-1.79-14.33-14.033-25.137-28.476-25.137h-28.329v-9.505c0-34.413-27.998-62.411-62.411-62.411s-62.411,27.998-62.411,62.411v9.505h-28.331c-14.441,0-26.682,10.805-28.476,25.137L.319,266.327c-1.47,11.755,2.182,23.59,10.024,32.473,7.84,8.883,19.135,13.978,30.983,13.978h198.735c11.851,0,23.143-5.095,30.983-13.978,7.842-8.881,11.494-20.718,10.024-32.473ZM252.964,290.187c-3.906,2.703-8.613,4.008-13.363,4.008H41.327c-6.522,0-12.735-2.804-17.052-7.692-4.315-4.889-6.325-11.401-5.517-17.871l21.158-169.272c.633-5.052,4.948-8.861,10.038-8.861h16.156c6.724,0,12.175,5.451,12.175,12.175v44.471h18.583v-44.471c0-6.724,5.451-12.175,12.175-12.175h56.517v-18.583h-68.692v-8.728c0-24.524,20.069-45.018,44.589-44.598,23.816.408,43.066,19.909,43.066,43.821v84.735h18.583v-56.646h28.329c5.091,0,9.405,3.809,10.038,8.858l21.158,169.274c1.033,8.279-2.547,16.629-9.667,21.555Z"/>
                            </g>
                        </svg>
                        <span class="m-0">luky market</span>
                    </a>
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