<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$port = 3308; // Adjust the port as per your MySQL configuration
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port);

// Handle errors with the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's cart items
$userId = $_SESSION['user_id'];
$query = "SELECT cart.*, menu_items.name, menu_items.price 
          FROM cart 
          JOIN menu_items ON cart.item_id = menu_items.id 
          WHERE cart.user_id = $userId";
$result = $conn->query($query);

// Handle finishing the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish_order'])) {
    // Calculate the total price
    $query = "SELECT SUM(menu_items.price * cart.quantity) AS total 
              FROM cart 
              JOIN menu_items ON cart.item_id = menu_items.id 
              WHERE cart.user_id = $userId";
    $resultTotal = $conn->query($query);
    $rowTotal = $resultTotal->fetch_assoc();
    $totalPrice = $rowTotal['total'];

    // Insert into the orders table
    $query = "INSERT INTO orders (user_id, total_price, status) VALUES ($userId, $totalPrice, 'pending')";
    if ($conn->query($query)) {
        // Get the last inserted order ID
        $orderId = $conn->insert_id;

        // Insert the cart items into the order_items table
        $query = "SELECT * FROM cart WHERE user_id = $userId";
        $cartItems = $conn->query($query);
        while ($cartItem = $cartItems->fetch_assoc()) {
            $itemId = $cartItem['item_id'];
            $quantity = $cartItem['quantity'];
            $query = "INSERT INTO order_items (order_id, menu_item_id, quantity) 
                      VALUES ($orderId, $itemId, $quantity)";
            $conn->query($query);
        }

        // Clear the user's cart
        $query = "DELETE FROM cart WHERE user_id = $userId";
        $conn->query($query);

        // Redirect to an order confirmation page
        header("Location: order_confirmation.php?order_id=$orderId");
        exit;
    } else {
        echo "Failed to place the order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
 
.cart{
  
    min-height: 80vh;
}
    </style>
</head>
<body>
    <header>
        <h1>Votre Panier</h1>
        <nav>
            <a href="menu.php">Menu</a>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <section class="cart">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    while ($row = $result->fetch_assoc()):
                        $itemTotal = $row['price'] * $row['quantity'];
                        $total += $itemTotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo number_format($row['price'], 2); ?> DH</td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo number_format($itemTotal, 2); ?> DH</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td><?php echo number_format($total, 2); ?> DH</td>
                    </tr>
                </tfoot>
            </table>
            <form method="POST" action="cart.php">
                <button type="submit" name="finish_order" class="btn-finish">Finir la commande</button>
            </form>
        <?php else: ?>
            <p>Votre panier est vide.</p>
        <?php endif; ?>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
