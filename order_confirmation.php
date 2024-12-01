<?php
session_start();

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$orderId = intval($_GET['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .hero{
    background-image: url('./assets/images/background.png');
    background-repeat: no-repeat;
    background-size: cover;
    height: 80vh;
}
.hero p{
    font-size: 2rem;
}
    </style>
</head>
<body>
    <header>
        <h1>Commande Confirmée</h1>
    </header>
    <section class="hero">
        <p>Merci pour votre commande !</p>
        <p>Votre numéro de commande est : <strong>#<?php echo $orderId; ?></strong></p>
        <p><a href="menu.php">Retour au menu</a></p>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
