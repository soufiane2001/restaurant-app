<?php
session_start();

// Vérifiez si l'utilisateur est administrateur (optionnel)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifiez si l'ID de commande est fourni
if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit;
}

$orderId = intval($_GET['id']);

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les détails de la commande
$query = "SELECT id, status FROM orders WHERE id = $orderId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    echo "Commande introuvable.";
    exit;
}

// Mettre à jour le statut si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $conn->real_escape_string($_POST['status']);

    $updateQuery = "UPDATE orders SET status = '$newStatus' WHERE id = $orderId";
    if ($conn->query($updateQuery)) {
        $_SESSION['success'] = "Le statut de la commande a été mis à jour avec succès.";
        header("Location: manage_orders.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour : " . $conn->error;
    }
}







?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Statut de la Commande</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <header>
        <h1>Modifier le Statut de la Commande</h1>
        <nav>
            <a href="manage_orders.php">Retour</a>
        </nav>
    </header>
    <section class="form">
        <h2>Commande #<?php echo $order['id']; ?></h2>
        <form method="POST" action="">
            <label for="status">Statut :</label>
            <select id="status" name="status" required>
                <option value="En attente" <?php if ($order['status'] == "En attente") echo "selected"; ?>>En attente</option>
                <option value="En cours" <?php if ($order['status'] == "En cours") echo "selected"; ?>>En cours</option>
                <option value="Livrée" <?php if ($order['status'] == "Livrée") echo "selected"; ?>>Livrée</option>
                <option value="Annulée" <?php if ($order['status'] == "Annulée") echo "selected"; ?>>Annulée</option>
            </select>
            <button type="submit">Mettre à jour</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>
