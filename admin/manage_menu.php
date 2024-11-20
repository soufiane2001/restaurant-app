<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion du Menu</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h1>Gestion du Menu</h1>
        <nav>
            <a href="dashboard.php">Tableau de Bord</a>
            <a href="manage_orders.php">Commandes</a>
            <a href="../index.php">Déconnexion</a>
        </nav>
    </header>
    <section class="menu-management">
        <h2>Ajouter un Article</h2>
        <form action="add_menu_item.php" method="POST" enctype="multipart/form-data">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Prix :</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="category">Catégorie :</label>
            <input type="text" id="category" name="category" required>
            <label for="image">Image :</label>
            <input type="file" id="image" name="image" required>
            <button type="submit">Ajouter</button>
        </form>
        <h2>Liste des Articles</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'restaurant_app');
                $query = "SELECT * FROM menu_items";
                $result = $conn->query($query);

                while ($item = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$item['id']}</td>
                            <td>{$item['name']}</td>
                            <td>{$item['description']}</td>
                            <td>{$item['price']} DH</td>
                            <td>{$item['category']}</td>
                            <td><img src='../uploads/{$item['image_url']}' alt='{$item['name']}' width='50'></td>
                            <td>
                                <a href='edit_menu_item.php?id={$item['id']}'>Modifier</a> | 
                                <a href='delete_menu_item.php?id={$item['id']}'>Supprimer</a>
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
