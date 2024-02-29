<?php
require_once __DIR__ . '/vendor/autoload.php'; // Ajustez le chemin
include 'header.php';

use Daeme\SRC\Post;
use Daeme\SRC\Database;

$pdo = Database::getConnection();
$postModel = new Post($pdo);

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post = $postModel->getById($postId);

if (!$post) {
    echo "Post not found";
    exit;
}

$postId = $_GET['id']; // Validez et nettoyez cette entrée

$stmt = $pdo->prepare("SELECT * FROM comments WHERE postId = :postId ORDER BY createdAt DESC");
$stmt->execute(['postId' => $postId]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container mt-5">
        <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>
        <p class="mb-4"><?= nl2br(htmlspecialchars($post['body'])) ?></p>

        <?php if ($comments): ?>
        <div class="comments-section">
            <h3>Commentaires</h3>
            <?php foreach ($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($comment['name']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($comment['createdAt']) ?></h6>
                    <p class="card-text"><?= nl2br(htmlspecialchars($comment['body'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p>Aucun commentaire pour ce post.</p>
        <?php endif; ?>
        
    </div>
<!-- Modal pour la création d'un nouveau post -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPostModalLabel">Nouveau Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createPostForm">
          <!-- Titre du post -->
          <div class="mb-3">
            <label for="post-title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="post-title" name="title" required>
          </div>
          <!-- Contenu du post -->
          <div class="mb-3">
            <label for="post-content" class="form-label">Contenu</label>
            <textarea class="form-control" id="post-content" name="content" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary">Créer Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>




