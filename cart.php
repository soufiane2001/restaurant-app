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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_id'])) {
    $itemId = intval($_POST['delete_item_id']);
    $query = "DELETE FROM cart WHERE user_id = $userId AND item_id = $itemId";
    if ($conn->query($query)) {
        header("Location: cart.php"); // Refresh the cart page
        exit;
    } else {
        echo "Erreur lors de la suppression de l'article : " . $conn->error;
    }
}
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
 
/* Section Cart */
.cart {
    width: 90%;
    max-width: 1000px;
    margin: 50px auto;
    background-color: #ffffff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Table Styles */
.cart table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.cart table th,
.cart table td {
    padding: 15px;
    text-align: center;
    border: 1px solid #dee2e6;
    font-size: 1rem;
}

.cart table th {
    background-color: #457b9d;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

.cart table td {
    background-color: #f8f9fa;
    color: #495057;
}

.cart table tfoot td {
    font-weight: bold;
    font-size: 1.2rem;
    color: #1d3557;
}

/* Delete Button */
.cart .btn-delete {
    background-color: #e63946;
    color: white;
    border: none;
    padding: 8px 15px;
    font-size: 0.9rem;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.cart .btn-delete:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
}

/* Finish Button */
.cart .btn-finish {
    display: inline-block;
    background-color: #e63946;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.cart .btn-finish:hover {
    background-color: #c0392b;
    transform: translateY(-3px);
}

/* Empty Cart Message */
.cart p {
    text-align: center;
    font-size: 1.2rem;
    color: #7f8c8d;
    margin: 30px 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .cart {
        padding: 15px;
    }

    .cart table th,
    .cart table td {
        font-size: 0.95rem;
        padding: 10px;
    }

    .cart .btn-finish {
        font-size: 0.9rem;
        padding: 10px 15px;
    }
}

@media (max-width: 768px) {
    .cart table th,
    .cart table td {
        font-size: 0.85rem;
        padding: 8px;
    }

    .cart .btn-finish {
        font-size: 0.85rem;
        padding: 10px 12px;
    }

    .cart table {
        display: block;
        overflow-x: auto; /* Scroll horizontally if the table overflows */
    }

    .cart table th, .cart table td {
        white-space: nowrap; /* Prevent text wrapping */
    }
}

@media (max-width: 480px) {
    .cart {
        padding: 10px;
    }

    .cart table th,
    .cart table td {
        font-size: 0.8rem;
        padding: 5px;
    }

    .cart .btn-finish {
        font-size: 0.8rem;
        padding: 8px 10px;
    }

    .cart p {
        font-size: 1rem;
    }
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
                            <td>
        <form method="POST" action="cart.php" style="display: inline;">
            <input type="hidden" name="delete_item_id" value="<?php echo $row['item_id']; ?>">
            <button type="submit" class="btn-delete">Supprimer</button>
        </form>
    </td>
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
