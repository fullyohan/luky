CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    status VARCHAR(50) DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2),
    img VARCHAR(255),
    state VARCHAR(30),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE favorites (
    user_id INT,
    post_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE chat_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    buyer_id INT,
    seller_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_room_id INT,
    sender_id INT,
    content TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;



INSERT INTO users (id, first_name,email, password, phone, status) VALUES
(1, 'Lordwinds','lordwinds@lukymarket.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G', 'user'),
(2, 'Ulrich','ulrich@lukymarket.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(3, 'Karounga','karounga@lukymarket.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(4, 'Yohan','yohan@lukymarket.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','user'),
(5, 'Admin','admin@lukymarket.fr', '$2y$10$mC/fC7vE6f0zHwunD8hMxeV7WcclnKk9C8r9I3yI1fF.wYjUaXy2G','admin');

INSERT INTO posts (id, user_id, title, description, price, img, state) VALUES
(1, 1, 'Console Xbox Series S', 'Xbox Series S 512 Go en parfait état, vendue avec sa manette d’origine et tous les câbles nécessaires.', 250.00, '/uploads/image1.png', 'Excellent état'),
(2, 2, 'Écran PC Gaming 24"', 'Écran Full HD 144Hz, idéal pour le gaming. Temps de réponse de 1ms. Pas de rayure.', 120.00, '/uploads/image2.png', 'Bon état'),
(3, 3, 'Manette PS5 DualSense', 'Manette officielle PlayStation 5 couleur blanche. Très peu servie, aucun drift sur les joysticks.', 50.00, '/uploads/image3.png', 'Comme neuf'),
(4, 4, 'Clavier Mécanique RGB', 'Clavier gamer avec switchs rouges. Rétroéclairage entièrement personnalisable via logiciel.', 75.00, '/uploads/image4.png', 'Bon état');


INSERT INTO favorites (user_id, post_id) VALUES
(2, 1),
(4, 1),
(1, 3);

INSERT INTO chat_rooms (id, post_id, buyer_id, seller_id) VALUES
(1, 1, 2, 1), 
(2, 3, 1, 3),
(3, 1, 4, 1); 

INSERT INTO messages (chat_room_id, sender_id, content) VALUES
(1, 2, 'Bonjour Lordwinds, ta Xbox Series S est toujours disponible ?'),
(1, 1, 'Salut Ulrich ! Oui, elle est toujours disponible. Tu es d''où ?'),
(1, 2, 'Super, je suis disponible sur Paris pour une remise en main propre cette semaine.'),
(2, 1, 'Bonjour Karounga, je suis très intéressé par ta manette PS5, un échange est possible ?'),
(2, 3, 'Bonjour Lordwinds. Désolé, je préfère uniquement une vente en espèces.')
(3, 4, 'Salut Lordwinds ! Ton annonce pour la Xbox m\'intéresse grave. Elle est toujours dispo ?'),
(3, 1, 'Salut Yohan ! Oui toujours dispo. J\'ai déjà deux personnes dessus mais le premier qui vient la cherche.'),
(3, 4, 'Ça marche, je peux passer la prendre ce soir si ça te va ? Dis-moi ton prix final.');



