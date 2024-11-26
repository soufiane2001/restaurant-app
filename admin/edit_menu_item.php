<?php
session_start();

// Vérifiez si l'utilisateur est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifiez si l'ID de l'article est fourni
if (!isset($_GET['id'])) {
    header("Location: manage_menu.php");
    exit;
}

$itemId = intval($_GET['id']);

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les informations de l'article
$query = "SELECT * FROM menu_items WHERE id = $itemId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
} else {
    $_SESSION['error'] = "Article introuvable.";
    header("Location: manage_menu.php");
    exit;
}

// Mettre à jour les informations de l'article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    // Gestion de l'image si un fichier est téléchargé
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
        $imageName = basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $imageName;
        $imageType = pathinfo($uploadFile, PATHINFO_EXTENSION);

        if (in_array(strtolower($imageType), ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imageQuery = ", image_url = '$uploadFile'";
            } else {
                $_SESSION['error'] = "Erreur lors du téléchargement de l'image.";
                header("Location: edit_menu_item.php?id=$itemId");
                exit;
            }
        } else {
            $_SESSION['error'] = "Format d'image non supporté.";
            header("Location: edit_menu_item.php?id=$itemId");
            exit;
        }
    } else {
        $imageQuery = "";
    }

    // Mise à jour de l'article
    $updateQuery = "UPDATE menu_items 
                    SET name = '$name', description = '$description', price = $price, category = '$category' $imageQuery 
                    WHERE id = $itemId";

    if ($conn->query($updateQuery)) {
        $_SESSION['success'] = "Article mis à jour avec succès.";
        header("Location: manage_menu.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . $conn->error;
    }
}

// Fermez la connexion
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Article</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
    form {
    background: white;
    border-radius: 10px;
    padding: 20px 30px;
    width: 400px;
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
 </style>
</head>
<body>
    <header>
        <h1>Modifier un Article</h1>
        <nav>
            <a href="manage_menu.php">Retour</a>
        </nav>
    </header>
    <section>
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <form action="edit_menu_item.php?id=<?php echo $itemId; ?>" method="POST" enctype="multipart/form-data">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
            <label for="price">Prix :</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($item['price']); ?>" required>
            <label for="category">Catégorie :</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required>
            <label for="image">Image (laisser vide pour conserver l'image actuelle) :</label>
            <input type="file" id="image" name="image">
            <button type="submit">Mettre à jour</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>
