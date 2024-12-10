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

        .confirm-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Historique des Commandes</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="cart.php">Panier</a>
            <a href="order_history.php">Vos Commandes</a>
            <form method="POST" action="logout.php" style="display: inline;">
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </nav>
    </header>
    <section class="order-history">
        <?php
        session_start();
        $userId = $_SESSION['user_id'];
        $conn = new mysqli('localhost', 'root', '', 'restaurant_app', 3308);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle confirmation of delivery
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order_id'])) {
            $orderId = intval($_POST['confirm_order_id']);
            $updateQuery = "UPDATE orders SET status = 'confirmed' WHERE id = $orderId AND user_id = $userId";
            $conn->query($updateQuery);
        }

        // Fetch user orders
        $query = "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($order = $result->fetch_assoc()) {
                echo "<div class='order'>
                        <p>Commande #{$order['id']}</p>
                        <p>État : {$order['status']}</p>
                        <p>Total : {$order['total_price']} DH</p>
                        <p>Date : {$order['created_at']}</p>";
                
                // Show confirmation button if order is delivered
                if ($order['status'] === 'Livrée') {
                    echo "<form method='POST' action='order_history.php'>
                            <input type='hidden' name='confirm_order_id' value='{$order['id']}'>
                            <button type='submit' class='confirm-btn'>Confirmer la réception</button>
                          </form>";
                }

                echo "</div>";
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
