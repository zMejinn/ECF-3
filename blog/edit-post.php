<?php

require_once __DIR__ . '/vendor/autoload.php';
use Daeme\SRC\Post;
use Daeme\SRC\Database;

$pdo = Database::getConnection();
$postModel = new Post($pdo);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $updatedDate = $_POST['updatedDate'] ?? null; // Récupération de la nouvelle date de mise à jour

    if ($id && $title && $body && $updatedDate) {
        $result = $postModel->updatePost($id, $title, $body, $updatedDate); // Mise à jour avec la date
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Post updated successfully', 'updatedDate' => $updatedDate]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update post']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing data']);
    }
    exit;
}


