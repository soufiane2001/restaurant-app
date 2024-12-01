<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
         .order-history {
            max-width: 800px;
            margin: 30px auto;
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .order {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f4f4f4;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .order:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .order p {
            margin: 5px 0;
            line-height: 1.5;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <header>
        <h1>Historique des Commandes</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="cart.php">Panier</a>
            <a href="order_history.php">vos commandes</a>
            <form method="POST" action="logout.php" style="display: inline;">
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
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
