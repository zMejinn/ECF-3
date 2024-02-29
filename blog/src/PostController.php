<?php
// src/Controller/PostController.php

namespace Daeme\SRC;

use daeme\SRC\Post; // Assurez-vous que la classe Post existe et est correctement définie
use PDO;

class PostController {
    private $postModel;

    public function __construct(PDO $pdo) {
        $this->postModel = new Post($pdo);
    }

    public function index() {
        $totalPosts = $this->postModel->getTotalPostsCount();
        $postsPerPage = 12;
        $totalPages = ceil($totalPosts / $postsPerPage);
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $postsPerPage;
        $posts = $this->postModel->getPaginatedPosts($postsPerPage, $offset);

        // Affichage des posts avec pagination
        require __DIR__ . '/../index.php'; // Assurez-vous que le chemin vers le fichier est correct
    }

    // Méthode pour afficher un post spécifique et ses commentaires
    public function show($id) {
        $post = $this->postModel->getById($id);
        if ($post) {
            // Récupérer les commentaires associés au post ici
            require __DIR__ . '/../show.php'; // Assurez-vous que le chemin vers le fichier est correct
        } else {
            header('Location: /?error=postnotfound');
            exit;
        }
    }

    // Méthode pour vérifier l'accès à l'espace d'administration
    public function adminAccess() {
        // Ici, vous devriez vérifier si l'utilisateur est connecté et a le rôle 'ADMIN'
        // Si ce n'est pas le cas, redirigez vers la page de connexion
        // Cette logique dépend de votre système d'authentification
    }

    // Méthode pour ajouter un nouveau post
    public function create($title, $body, $userId) {
        // Assurez-vous que l'utilisateur a les droits nécessaires pour créer un post
        $postId = $this->postModel->addPost($title, $body, $userId);
        if ($postId) {
            header('Location: /../show.php?id=' . $postId);
            exit;
        } else {
            header('Location: /../create?error=failed');
            exit;
        }
    }

    // Méthode pour modifier un post existant
    public function update($id, $title, $body) {
        // Assurez-vous que l'utilisateur a les droits nécessaires pour modifier le post
        $result = $this->postModel->updatePost($id, $title, $body);
        if ($result) {
            header('Location: /../show.phpid=' . $id);
            exit;
        } else {
            header('Location: /../edit?id=' . $id . '&error=failed');
            exit;
        }
    }

    // Méthode pour supprimer un post
    public function delete($id) {
        // Assurez-vous que l'utilisateur a les droits nécessaires pour supprimer le post
        $result = $this->postModel->deletePost($id);
        if ($result) {
            header('Location: /admin');
            exit;
        } else {
            header('Location: /../show.php?id=' . $id . '&error=deletefailed');
            exit;
        }
    }
}
