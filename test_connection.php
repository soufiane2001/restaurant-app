<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'restaurant_app';

// Tentative de connexion
$conn = new mysqli($host, $user, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
echo "Connexion réussie à la base de données !";
?>
