<?php
session_start();

// Vérifiez si l'utilisateur est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifiez si l'ID de l'article est fourni
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID article manquant.";
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

// Récupérer l'article pour vérifier son existence et obtenir l'image associée
$query = "SELECT image_url FROM menu_items WHERE id = $itemId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
    $imagePath = $item['image_url'];

    // Supprimer l'article
    $deleteQuery = "DELETE FROM menu_items WHERE id = $itemId";
    if ($conn->query($deleteQuery)) {
        // Supprimer l'image associée si elle existe
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }
        $_SESSION['success'] = "Article supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression : " . $conn->error;
    }
} else {
    $_SESSION['error'] = "Article introuvable.";
}

// Redirigez vers la page de gestion des menus
header("Location: manage_menu.php");
exit;
?>
