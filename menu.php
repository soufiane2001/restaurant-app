<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Menu</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="cart.php">Panier</a>
        </nav>
    </header>
    <section class="menu">
        <?php
        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'restaurant_app');
        $query = "SELECT * FROM menu_items";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='menu-item'>
                    <img src='{$row['image_url']}' alt='{$row['name']}'>
                    <h3>{$row['name']}</h3>
                    <p>{$row['description']}</p>
                    <p><strong>Prix : {$row['price']} DH</strong></p>
                    <button>Ajouter au panier</button>
                </div>";
        }
        ?>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
