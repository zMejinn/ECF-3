<!-- src/View/post/index.php -->
<?php require __DIR__ . '/header.php'; ?>
<?php
require_once __DIR__ . '/vendor/autoload.php'; // Assurez-vous que le chemin est correct

use Daeme\SRC\Post;
use Daeme\SRC\Database;

// Étape 1 : Obtenez une connexion à la base de données
$pdo = Database::getConnection();

// Étape 2 : Créez une instance de votre modèle Post
$postModel = new Post($pdo);

// Étape 3 : Récupérez tous les posts
$posts = $postModel->getAll();

// Maintenant, $posts contient vos posts sous forme de tableau
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$limit = 12;
$offset = ($page - 1) * $limit;

$posts = $postModel->getPaginatedPosts($limit, $offset);
$totalPosts = $postModel->getTotalPostsCount();
$totalPages = ceil($totalPosts / $limit);
?>

<div class="container mt-5">
    <h1>Posts Récents</h1>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($post['body'], 0, 100)) ?>...</p>
                        <a href="/BLOG/show.php?page=show&id=<?= htmlspecialchars($post['id']) ?>" class="btn btn-primary">Lire plus</a>

                    </div>
                    <div class="card-footer text-muted">
                        Publié le <?= htmlspecialchars(date("d/m/Y", strtotime($post['createdAt']))) ?>
                        par <?= htmlspecialchars($post['authorName']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <!-- Bouton Précédent -->
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Précédent</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Bouton Suivant -->
                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">Suivant &raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
