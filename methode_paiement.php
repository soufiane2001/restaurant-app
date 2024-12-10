<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Methode de Paiement</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
      

        .payment-section {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .payment-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1d3557;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .payment-section p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .payment-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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
        <h1>Methode de Paiement</h1>
        <nav>
            <a href="menu.php">Menu</a>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <main>
        <section class="payment-section">
            <h2>Cash à la Livraison</h2>
            <p>
                Notre méthode de paiement est simple et sécurisée. Vous pouvez payer votre commande 
                en espèces directement au livreur lorsque votre commande vous est livrée. C'est une option pratique, 
                surtout si vous préférez ne pas utiliser de cartes de crédit ou d'autres moyens de paiement en ligne.
            </p>
            <p>
                Le livreur vous remettra votre commande et collectera le montant en espèces. Assurez-vous d'avoir l'appoint 
                pour faciliter la transaction.
            </p>
            <img src="assets/images/cod.png" alt="Livreur apportant une commande">
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
