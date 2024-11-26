<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion du Menu</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
 <style>
form {
    background: white;
    border-radius: 10px;
    padding: 20px 30px;
    width: 400px;
    max-width: 90%; /* Ensure it doesn't exceed screen width on smaller devices */
    margin: 20px auto; /* Center the form horizontally */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

form input, form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form input:focus, form textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    outline: none;
}

form textarea {
    resize: none;
    height: 100px;
}

form button {
    width: 100%;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        padding: 15px 20px; /* Reduce padding for smaller devices */
        width: 90%; /* Expand to fit smaller screen sizes */
    }

    form input, form textarea {
        font-size: 0.9rem; /* Adjust font size for smaller screens */
        padding: 8px; /* Reduce padding */
    }

    form button {
        font-size: 0.9rem;
        padding: 10px; /* Adjust button size */
    }
}

@media (max-width: 480px) {
    form {
        padding: 10px 15px;
        border-radius: 8px; /* Slightly smaller border radius */
    }

    form input, form textarea {
        font-size: 0.8rem; /* Adjust font size further for smaller screens */
        padding: 6px;
    }

    form button {
        font-size: 0.8rem;
        padding: 8px 10px;
    }
}

 </style>
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
    <section class="menu-management" style="min-height: 100vh;">
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
                $conn = new mysqli('localhost', 'root', '', 'restaurant_app',port:3308);
                $query = "SELECT * FROM menu_items";
                $result = $conn->query($query);

                while ($item = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$item['id']}</td>
                            <td>{$item['name']}</td>
                            <td>{$item['description']}</td>
                            <td>{$item['price']} DH</td>
                            <td>{$item['category']}</td>
                            <td><img src='../{$item['image_url']}' alt='{$item['name']}' width='50'></td>
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
