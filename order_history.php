<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Historique des Commandes</h1>
        <nav>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <section class="order-history">
        <?php
        session_start();
        $userId = $_SESSION['user_id'];
        $conn = new mysqli('localhost', 'root', '', 'restaurant_app',port:3308);
        $query = "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($order = $result->fetch_assoc()) {
                echo "<div class='order'>
                        <p>Commande #{$order['id']}</p>
                        <p>État : {$order['status']}</p>
                        <p>Total : {$order['total_price']} DH</p>
                        <p>Date : {$order['created_at']}</p>
                      </div>";
            }
        } else {
            echo "<p>Aucune commande trouvée.</p>";
        }
        ?>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
