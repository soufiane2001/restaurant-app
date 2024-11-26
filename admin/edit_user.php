<?php
session_start();

// Vérifiez si l'utilisateur est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifiez si l'ID de l'utilisateur est fourni
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$userId = intval($_GET['id']);

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'restaurant_app', $port = 3308);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur
$query = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $_SESSION['error'] = "Utilisateur introuvable.";
    header("Location: manage_users.php");
    exit;
}

// Mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    $updateQuery = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $userId";
    if ($conn->query($updateQuery)) {
        $_SESSION['success'] = "Informations de l'utilisateur mises à jour avec succès.";
        header("Location: manage_users.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour : " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Utilisateur</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
    form {
    background: white;
    border-radius: 10px;
    padding: 20px 30px;
    width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

form input, form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form input:focus, form textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    outline: none;
}

form textarea {
    resize: none;
    height: 100px;
}

form button {
    width: 100%;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}
 </style>
</head>
<body>
    <header>
        <h1>Modifier un Utilisateur</h1>
        <nav>
            <a href="manage_users.php">Retour</a>
        </nav>
    </header>
    <section style="height:65vh">
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <form action="edit_user.php?id=<?php echo $userId; ?>" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>Utilisateur</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Administrateur</option>
            </select>
            <button type="submit">Mettre à jour</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2024 Restaurant Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>
