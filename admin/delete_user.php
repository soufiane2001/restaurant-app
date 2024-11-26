<?php
session_start();

// Vérifiez si l'utilisateur est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifiez si l'ID de l'utilisateur est fourni
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur manquant.";
    header("Location: manage_users.php");
    exit;
}

$userId = intval($_GET['id']);

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifiez si l'utilisateur existe
$query = "SELECT id FROM users WHERE id = $userId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Supprimer l'utilisateur
    $deleteQuery = "DELETE FROM users WHERE id = $userId";
    if ($conn->query($deleteQuery)) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression : " . $conn->error;
    }
} else {
    $_SESSION['error'] = "Utilisateur introuvable.";
}

// Redirigez vers la page de gestion des utilisateurs
header("Location: manage_users.php");
exit;
?>
