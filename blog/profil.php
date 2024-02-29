<?php
// Au début de votre fichier de profil
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header('Location: login.php');
    exit;
}

// Utilisez les variables de session pour afficher le nom et l'email de l'utilisateur
$username = $_SESSION['username'] ?? 'Invité'; // Utilise 'Invité' comme valeur par défaut si non défini
$email = $_SESSION['email'] ?? 'Non défini'; // Assurez-vous que vous avez défini cette variable lors de la connexion
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'Utilisateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<div class="container">
    <h1>Profil de l'Utilisateur</h1>
    <p>Nom d'utilisateur : <?= htmlspecialchars($username) ?></p>
    <!-- Affichez le bouton uniquement pour les utilisateurs avec le rôle 'admin' -->
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="admin.php" class="btn btn-primary">Page Admin</a>
    <?php endif; ?>
    <!-- Ajoutez d'autres informations de profil ici si nécessaire -->
</div>
</body>
</html>
