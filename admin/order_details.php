<?php
session_start();

// Vérifiez si l'ID de commande est fourni
if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit;
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$orderId = intval($_GET['id']);

// Récupérer les détails de la commande
$query = "SELECT o.id, u.name AS client_name, o.total_price, o.status, o.created_at 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          WHERE o.id = $orderId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();

    // Récupérer les articles associés à la commande
    $itemsQuery = "SELECT m.name AS item_name, oi.quantity 
                   FROM order_items oi 
                   JOIN menu_items m ON oi.menu_item_id = m.id 
                   WHERE oi.order_id = $orderId";
    $itemsResult = $conn->query($itemsQuery);
} else {
    echo "Commande introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h1>Détails de la Commande</h1>
        <nav>
            <a href="manage_orders.php">Retour</a>
        </nav>
    </header>
    <section>
        <h2>Commande #<?php echo $order['id']; ?></h2>
        <p><strong>Client :</strong> <?php echo $order['client_name']; ?></p>
        <p><strong>Date :</strong> <?php echo $order['created_at']; ?></p>
        <p><strong>Total :</strong> <?php echo $order['total_price']; ?> DH</p>
        <p><strong>État :</strong> <?php echo $order['status']; ?></p>

        <h3>Articles :</h3>
        <table>
            <thead>
                <tr>
                    <th>Nom de l'Article</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($item = $itemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$item['item_name']}</td>
                            <td>{$item['quantity']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>
