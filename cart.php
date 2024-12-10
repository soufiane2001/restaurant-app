<?php
session_start();

$userId = $_SESSION['user_id'];
// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
$port = 3308; // Adjust the port as per your MySQL configuration
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include FPDF library
require('./fpdf/fpdf.php');

// Handle finishing the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish_order'])) {
    $userId = $_SESSION['user_id'];

    // Fetch the user's cart items
    $query = "SELECT cart.*, menu_items.name, menu_items.price 
              FROM cart 
              JOIN menu_items ON cart.item_id = menu_items.id 
              WHERE cart.user_id = $userId";
    $result = $conn->query($query);

    // Calculate the total price
    $totalPrice = 0;
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $itemTotal = $row['price'] * $row['quantity'];
        $totalPrice += $itemTotal;
        $items[] = $row;
    }

    // Insert into orders table
    $query = "INSERT INTO orders (user_id, total_price, status) VALUES ($userId, $totalPrice, 'pending')";
   
    if ($conn->query($query)) {
        $orderId = $conn->insert_id;

        // Insert into order_items table
        foreach ($items as $item) {
            $itemId = $item['item_id'];
            $quantity = $item['quantity'];
            $query = "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES ($orderId, $itemId, $quantity)";
            $conn->query($query);
        }

        // Clear the cart
        $query = "DELETE FROM cart WHERE user_id = $userId";
        $conn->query($query);

        // Generate PDF invoice
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Title
        $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
        $pdf->Ln(10);

        // Order details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Commande #: $orderId", 0, 1);
        $pdf->Cell(0, 10, "Utilisateur: " . $_SESSION['user_name'], 0, 1);
        $pdf->Cell(0, 10, "Date: " . date('d-m-Y H:i:s'), 0, 1);
        $pdf->Ln(10);

        // Items table
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Article', 1);
        $pdf->Cell(30, 10, 'Prix', 1);
        $pdf->Cell(30, 10, 'Quantite', 1);
        $pdf->Cell(40, 10, 'Total', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $pdf->Cell(80, 10, $item['name'], 1);
            $pdf->Cell(30, 10, number_format($item['price'], 2) . ' DH', 1);
            $pdf->Cell(30, 10, $item['quantity'], 1);
            $pdf->Cell(40, 10, number_format($itemTotal, 2) . ' DH', 1);
            $pdf->Ln();
        }

        // Total price
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(140, 10, 'Total', 1);
        $pdf->Cell(40, 10, number_format($totalPrice, 2) . ' DH', 1);

        // Output the PDF to the browser
        $fileName = "Facture_Commande_$orderId.pdf";
        $pdf->Output('D', $fileName); // Force download  
         header("Location: order_confirmation.php?order_id=$orderId");
        exit;
     
    } else {
        echo "Erreur lors de la commande : " . $conn->error;
    }
}

// Fetch the user's cart items for display
$query = "SELECT cart.*, menu_items.name, menu_items.price 
          FROM cart 
          JOIN menu_items ON cart.item_id = menu_items.id 
          WHERE cart.user_id = $userId";
$result = $conn->query($query); 

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
<div id="black-overlay">
        <h1>Access Denied</h1>
        <p>kaml lkhlass</p>
        
    </div>
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
