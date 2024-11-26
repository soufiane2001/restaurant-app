<?php
session_start(); // Start the session

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

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $userId = $_SESSION['user_id'];

    // Check if the item is already in the cart for this user
    $query = "SELECT * FROM cart WHERE user_id = $userId AND item_id = $itemId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // If item exists, update the quantity
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $userId AND item_id = $itemId";
        $conn->query($query);
    } else {
        // If item does not exist, insert a new row
        $query = "INSERT INTO cart (user_id, item_id, quantity) VALUES ($userId, $itemId, 1)";
        $conn->query($query);
    }

    // Redirect back to the menu page
    header("Location: menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
      
        footer{
            position: relative;
        }
        .menu-item{
            align-self:flex-start ;
        }
    </style>
</head>
<body>
    <header>
        <h1>Menu</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="cart.php">Panier</a>
            <form method="POST" action="logout.php" style="display: inline;">
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </nav>
        <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
    </header>
    <section class="menu">
        <?php
        $query = "SELECT * FROM menu_items";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='menu-item'>
                    <img src='{$row['image_url']}' alt='{$row['name']}' width='150'>
                    <h3>{$row['name']}</h3>
                    <p>{$row['description']}</p>
                    <p><strong>Prix : {$row['price']} DH</strong></p>
                    <form method='POST' action='menu.php'>
                        <input type='hidden' name='item_id' value='{$row['id']}'>
                        <button type='submit' class='btn-finish'>Ajouter au panier</button>
                    </form>
                </div>";
        }
        ?>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
