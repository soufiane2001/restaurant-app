<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Localisation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Styles */
      
        .map-container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
        }

        footer {
            background: #1d3557;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Localisation</h1>
        <nav>
            <a href="menu.php">Menu</a>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <main>
        <section>
            <h2 style="text-align: center; color: #1d3557; margin-top: 20px;">Notre Emplacement</h2>
            <p style="text-align: center; color: #555; font-size: 1.1rem; margin-bottom: 20px;">
                Voici l'emplacement de notre restaurant. Venez nous rendre visite !
            </p>
            <div class="map-container">
                <!-- Embed Google Maps iframe -->
                <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3296.6687844567564!2d-5.565835684771855!3d33.89424998065416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd9ff4f18ab2aa55%3A0x92951e4a3020027c!2sMeknes%2C%20Morocco!5e0!3m2!1sen!2sus!4v1671220374408!5m2!1sen!2sus" 
    width="600" 
    height="450" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
</iframe>


            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
