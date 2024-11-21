<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'restaurant_app';
$conn = new mysqli($host, $user, $password, $database,port:3308);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Ne pas échapper ici, car ce sera haché

    // Vérification si l'utilisateur existe
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Récupérer les données utilisateur
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Démarrer une session et enregistrer les données utilisateur
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Rediriger l'utilisateur en fonction de son rôle
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: menu.php");
            }
            exit;
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect. <a href='login.php'>Réessayer</a>";
        }
    } else {
        // Email non trouvé
        echo "Aucun utilisateur trouvé avec cet email. <a href='register.php'>Créer un compte</a>";
    }
}

// Fermer la connexion
$conn->close();
?>
