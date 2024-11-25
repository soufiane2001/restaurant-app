<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Restaurant</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        footer {
            position: absolute;
    bottom: 0;
    text-align: center;
    padding: 10px;
    background-color: #343a40;
    color: white;
    width: 98.35%;
}
.hero{
    background-image: url('./assets/images/background.png');
    background-repeat: no-repeat;
    background-size: cover;
    height: 50vh;
}
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue au Restaurant DelishHub</h1>
        <nav>
            <a href="menu.php">Voir le Menu</a>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
            <a href="contact.php">Contactez-nous</a>
        </nav>
    </header>
    <section class="hero">
        <h2>Découvrez nos plats délicieux</h2>
        <button onclick="scrollToMenu()">Voir le Menu</button>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés</p>
    </footer>
    <script src="assets/js/script.js"></script>
</body>
</html>
