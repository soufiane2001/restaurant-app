<?php
session_start(); // Démarrer la session

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $port = 3308; // Adaptez ce port à votre configuration MySQL
    $conn = new mysqli('localhost', 'root', '', 'restaurant_app',port:3308);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Supprimer les espaces inutiles
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
    
        // Vérification des informations de connexion
        if ($email == "admin@admin.com" && $password == "12345") {
            echo "Connexion réussie !";
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = "admin chef";
            $_SESSION['role'] = "admin";
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Mot de passe incorrect".$_POST['email'].$_POST['password'];
        }
    } else {
        $_SESSION['error'] ="Veuillez remplir les champs.";
    }
    
     
   
} 
?>
