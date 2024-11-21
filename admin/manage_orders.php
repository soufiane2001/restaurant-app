<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Commandes</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h1>Gestion des Commandes</h1>
        <nav>
            <a href="dashboard.php">Tableau de Bord</a>
        </nav>
    </header>
    <section>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Client</th>
                    <th>Articles</th>
                    <th>Total</th>
                    <th>État</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'restaurant_app',port:3308);
                $query = "SELECT o.id, u.name, o.total_price, o.status FROM orders o 
                          JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
                $result = $conn->query($query);

                while ($order = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$order['id']}</td>
                            <td>{$order['name']}</td>
                             <td><a href='order_details.php?id={$order['id']}'>Détails ici</a></td>
                            <td>{$order['total_price']} DH</td>
                            <td>{$order['status']}</td>
                            <td>
                                <a href='update_order_status.php?id={$order['id']}'>Modifier</a>
                            </td>
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
