<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    $userLoggedIn = false;
} else {
    $userLoggedIn = true;
    $userId = $_SESSION['user_id'];
}

// Connect to the database
$port = 3308; // Adjust the port as per your MySQL configuration
$conn = new mysqli('localhost', 'root', '', 'restaurant_app',port:3308);

// Handle errors with the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle testimonial submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userLoggedIn) {
    $testimonial = $conn->real_escape_string($_POST['testimonial']);
    $rating = intval($_POST['rating']);

    $query = "INSERT INTO testimonials (user_id, testimonial, rating) VALUES ($userId, '$testimonial', $rating)";
    if ($conn->query($query)) {
        echo "<p style='color: green; text-align: center;'>Merci pour ton avis!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Failed to submit your testimonial: " . $conn->error . "</p>";
    }
}

// Fetch testimonials
$query = "SELECT users.name, testimonials.testimonial, testimonials.rating, testimonials.created_at 
          FROM testimonials 
          JOIN users ON testimonials.user_id = users.id 
          ORDER BY testimonials.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Avis Clients</h1>
        <nav>
            <a href="menu.php">Menu</a>
            <a href="index.php">Accueil</a>
        </nav>
    </header>
    <main>
        <section class="testimonials">
            <h2>Ce que disent nos clients</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="testimonial">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['testimonial']); ?></p>
                        <p>Note : <?php echo str_repeat('⭐', $row['rating']); ?></p>
                        <p><em><?php echo $row['created_at']; ?></em></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun avis pour le moment.</p>
            <?php endif; ?>
        </section>
        <section class="add-testimonial">
            <h2>Ajoutez votre avis</h2>
            <?php if ($userLoggedIn): ?>
                <form method="POST" action="avis.php">
                    <textarea name="testimonial" placeholder="Partagez votre expérience" required></textarea>
                    <label for="rating">Note :</label>
                    <select name="rating" id="rating" required>
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Très bon</option>
                        <option value="3">3 - Bon</option>
                        <option value="2">2 - Moyen</option>
                        <option value="1">1 - Mauvais</option>
                    </select>
                    <button type="submit">Envoyer</button>
                </form>
            <?php else: ?>
                <p style="text-align: center; color: red;">Veuillez <a href="login.php">vous connecter</a> pour ajouter votre avis.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Restaurant App. Tous droits réservés.</p>
    </footer>
</body>
</html>
