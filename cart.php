<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
        <?php
        session_start();
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $itemTotal = $item['quantity'] * $item['price'];
                $total += $itemTotal;
                echo "<tr>
                        <td>{$item['name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['price']} DH</td>
                        <td>{$itemTotal} DH</td>
                        <td><a href='remove_from_cart.php?id={$item['id']}'>Supprimer</a></td>
                      </tr>";
            }
            echo "</tbody>
                  </table>
                  <div class='cart-total'>
                      <p>Total : <strong>{$total} DH</strong></p>
                      <button onclick='checkout()'>Passer la commande</button>
                  </div>";
        } else {
            echo "<p>Votre panier est vide.</p>";
        }
        ?>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
    <script>
        function checkout() {
            window.location.href = "order_status.php";
        }
    </script>
</body>
</html>
