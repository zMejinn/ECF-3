<?php
require_once 'src/database.php'; // Ajustez ce chemin si nécessaire
require_once __DIR__ . '/vendor/autoload.php';

use Daeme\SRC\Post;
use Daeme\SRC\Database;

$pdo = Database::getConnection();
$postModel = new Post($pdo);

// create-post.php
$title = $_POST['title'] ?? '';
$body = $_POST['content'] ?? ''; // Correspond au nom du champ dans le formulaire HTML
$userId = 1; // Vous devez définir l'ID de l'utilisateur ici. Assurez-vous de remplacer cela par l'ID de l'utilisateur actuel connecté.
$createdAt = date('Y-m-d H:i:s'); // La date et l'heure actuelles

if (!empty($title) && !empty($body)) {
    $result = $postModel->createPost($title, $body, $userId, $createdAt);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Impossible de créer le post.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Titre ou contenu manquant.']);
}
