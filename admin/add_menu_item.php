<?php
session_start();

// Vérifiez si l'utilisateur est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    // Gestion du téléchargement d'image
    $uploadDir = 'assets/images/';
    $imageName = basename($_FILES['image']['name']);
    $uploadFile = $uploadDir . $imageName;
    $imageType = pathinfo($uploadFile, PATHINFO_EXTENSION);

    // Vérifiez si le fichier est une image
    if (in_array(strtolower($imageType), ['jpg', 'jpeg', 'png', 'gif'])) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], '../'.$uploadFile)) {
            // Insertion dans la base de données
            $query = "INSERT INTO menu_items (name, description, price, category, image_url) 
                      VALUES ('$name', '$description', $price, '$category', '$uploadFile')";
            if ($conn->query($query)) {
                $_SESSION['success'] = "Article ajouté avec succès.";
                header("Location: manage_menu.php");
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout : " . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Erreur lors du téléchargement de l'image.";
        }
    } else {
        $_SESSION['error'] = "Format d'image non supporté. Utilisez JPG, JPEG, PNG ou GIF.";
    }
}

// Fermez la connexion
$conn->close();
?>
