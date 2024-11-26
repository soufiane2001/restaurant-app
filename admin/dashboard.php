<?php
session_start(); // Start the session

// Vérifiez si l'utilisateur est connecté et est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ./admin_login.php");
    exit;
}

// Connexion à la base de données
$port = 3308; // Adaptez le port à votre configuration MySQL
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', port:3308);

// Vérifiez les erreurs de connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les statistiques
// Total des commandes
$resultOrders = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$totalOrders = $resultOrders->fetch_assoc()['total_orders'] ?? 0;

// Total des utilisateurs
$resultUsers = $conn->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $resultUsers->fetch_assoc()['total_users'] ?? 0;

// Revenus totaux
$resultRevenue = $conn->query("SELECT SUM(total_price) as total_revenue FROM orders");
$totalRevenue = $resultRevenue->fetch_assoc()['total_revenue'] ?? 0;

// Formatage des revenus
$totalRevenueFormatted = number_format($totalRevenue, 2, ',', ' ') . " DH";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tableau de Bord</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord</h1>
        <nav>
            <a href="manage_orders.php">Commandes</a>
            <a href="manage_menu.php">Menu</a>
            <a href="manage_users.php">Utilisateurs</a>
            <a href="stats.php">Statistiques</a>
            <a href="./logout.php">Déconnexion</a>
        </nav>
    </header>
    <section class="dashboard">
        <div class="stat">
            <h2>Total des Commandes</h2>
            <p><?php echo $totalOrders; ?></p>
        </div>
        <div class="stat">
            <h2>Utilisateurs</h2>
            <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat">
            <h2>Revenus Totaux</h2>
            <p><?php echo $totalRevenueFormatted; ?></p>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>
