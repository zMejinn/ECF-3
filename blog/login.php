<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
use Daeme\SRC\Database;

$errorMessage = '';

try {
    $db = Database::getConnection();
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginOrEmail = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = "SELECT * FROM user WHERE (username = :loginOrEmail OR email = :loginOrEmail) AND password = :password LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':loginOrEmail', $loginOrEmail);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Utilisateur trouvé et mot de passe correct
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role']; // Assurez-vous que cette ligne correspond à la structure de votre base de données.

        // Redirigez l'utilisateur en fonction de son rôle
        if ($_SESSION['user_role'] === 'admin') {
            header("Location: admin.php");
            exit;
        } else {
            header("Location: index.php");
            exit;
        }
    } else {
        // Échec de la connexion
        $errorMessage = 'Nom d’utilisateur, e-mail ou mot de passe incorrect.';
    }
}

// Le reste de votre script HTML de connexion ici...


// Assurez-vous que le formulaire de connexion est affiché ici ou redirigez vers celui-ci.

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<div class="container">
    <h2>Login</h2>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="login">Nom d'utilisateur ou E-mail :</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
</div>
</body>
</html>
