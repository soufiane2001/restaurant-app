<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
/* Global Reset */
.list {
    height: 80vh;
    display: flex;
    padding: 20px;
    align-items: center;
    justify-content: center; /* Center align items horizontally */
}

footer {
    position: relative;
}

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
@media (max-width: 1024px) {
    .list {
        padding: 10px;
        height: auto; /* Adjust height for medium screens */
        flex-direction: column; /* Stack items vertically */
    }

    .form-section {
        width: 80%; /* Increase form width slightly */
    }
}

@media (max-width: 768px) {
    .form-section {
        padding: 25px;
        width: 95%; /* Form takes more space on smaller screens */
    }

    .form button {
        width: 100%;
        align-self: center;
    }
}

@media (max-width: 480px) {
    .list {
        padding: 10px;
        flex-direction: column;
        justify-content: flex-start; /* Adjust alignment */
    }

    .form-section {
        padding: 15px;
        border-radius: 8px;
        width: 100%;
    }

    .form input {
        font-size: 0.9rem;
        padding: 10px;
    }

    .form button {
        padding: 10px;
        font-size: 1rem;
    }
}


    </style>
</head>
<body>
    <header>
        <h1>Connexion</h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="register.php">Inscription</a>
        </nav>
    </header>
    <div class="list">
    <section class="form-section">
        <form class="form" action="process_login.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
    </section>
    </div>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
