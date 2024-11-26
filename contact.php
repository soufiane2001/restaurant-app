<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Global Reset */


/* Form Section */
.form-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    width: 90%;
    animation: fadeIn 1s ease;
}

/* Form Styles */
.form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form label {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.form input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form input:focus {
    outline: none;
    border-color: #1d3557;
    box-shadow: 0 0 8px rgba(29, 53, 87, 0.3);
}

.form button {
    background-color: #1d3557;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 1.1rem;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    align-self: flex-end;
}

.form button:hover {
    background-color: #457b9d;
    transform: translateY(-2px);
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 600px) {
    .form-section {
        padding: 20px;
    }

    .form button {
        width: 100%;
        align-self: center;
    }
}

    </style>
</head>
<body>
    <header>
        <h1>Contactez-nous</h1>
        <nav>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <section class="form-section">
        <form class="form" action="process_contact.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Message :</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
