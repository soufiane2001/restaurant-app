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

// Récupérer les revenus par mois
$query = "SELECT MONTH(created_at) AS month, SUM(total_price) AS revenue 
          FROM orders 
          GROUP BY MONTH(created_at)";
$result = $conn->query($query);

$revenues = [];
$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];

while ($row = $result->fetch_assoc()) {
    $revenues[$months[$row['month']]] = $row['revenue'];
}

// Remplir les données manquantes avec 0
$chartData = [];
foreach ($months as $key => $month) {
    $chartData[$month] = $revenues[$month] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Statistiques</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Statistiques</h1>
        <nav>
            <a href="dashboard.php">Tableau de Bord</a>
            <a href="../index.php">Déconnexion</a>
        </nav>
    </header>
    <section>
        <canvas id="statsChart" width="400" height="200"></canvas>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
    <script>
        // Données récupérées dynamiquement depuis PHP
        const labels = <?php echo json_encode(array_keys($chartData)); ?>;
        const data = <?php echo json_encode(array_values($chartData)); ?>;

        // Configuration du graphique
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenus (DH)',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
