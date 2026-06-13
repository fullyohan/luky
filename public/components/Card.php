<?php function Card(array $annonce)
{ ?>
    <style>
        .float {
            transition: transform 0.3s ease-in-out,
                box-shadow 0.3s ease-in-out;
        }
        .float:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
    <div class="col">
        <div class="card h-100 shadow-sm position-relative float">
            <a href="/details.php?id=<?= $annonce['id'] ?>" class="text-decoration-none text-dark h-100 d-flex flex-column">
                <div class="w-100 bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                    <img src="<?= htmlspecialchars($annonce['img']); ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($annonce['title']); ?>"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
                    <div>
                        <h5 class="card-title fs-6 fw-bold mb-1 text-truncate">
                            <?= htmlspecialchars($annonce['title']); ?>
                        </h5>
                        <p class="text-danger fw-bold fs-5 mb-2"><?= number_format($annonce['price'], 0, ',', ' '); ?> €</p>
                        <span class="badge bg-primary mb-3"><?= htmlspecialchars($annonce['state']); ?></span>
                    </div>

                    <div class="border-top pt-2 mt-2">
                        <small class="text-muted d-block">
                            <?php
                            $date_key = $annonce['date'] ?? $annonce['created_at'];
                            $created_at = new DateTime($date_key);

                            if ($created_at->format('Y-m-d') === (new DateTime('today'))->format('Y-m-d')) {
                                $date = "Aujourd'hui, " . $created_at->format('H:i');
                            } elseif ($created_at->format('Y-m-d') === (new DateTime('yesterday'))->format('Y-m-d')) {
                                $date = "Hier, " . $created_at->format('H:i');
                            } else {
                                $date = $created_at->format('d/m/Y, H:i');
                            }
                            echo $date;
                            ?>
                        </small>
                    </div>
                </div>
            </a>
            <?php if (isset($_SESSION['user_id'])) : ?>
                <form action="/handlers/post-handler.php" method="POST" class="position-absolute top-0 end-0 m-2"
                    style="z-index: 10;">
                    <input type="hidden" name="id" value="<?= $annonce['id'] ?>">
                    <input type="hidden" name="action" value="like">
                    <button type="submit" 
                        class="btn btn-light btn-sm rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center"
                        style="width: 35px; height: 35px;" title="Ajouter aux favoris">
                        <i class="fa-solid fa-heart <?= $annonce['is_fav'] ? 'text-danger' : 'text-secondary'; ?>"></i>
                    </button>
                </form>
            <?php endif; ?>
            
        </div>
    </div>
<?php } ?>