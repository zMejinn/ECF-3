<?php

namespace Daeme\SRC;

use PDO;

class Post {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les posts
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM posts ORDER BY createdAt DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un post par son ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un nouveau post
    public function addPost($title, $body, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (title, body, userId) VALUES (:title, :body, :userId)");
        $stmt->execute(['title' => $title, 'body' => $body, 'userId' => $userId]);
        return $this->pdo->lastInsertId();
    }

    // Mettre à jour un post existant
    public function updatePost($id, $title, $body) {
        $stmt = $this->pdo->prepare("UPDATE posts SET title = :title, body = :body WHERE id = :id");
        $stmt->execute(['id' => $id, 'title' => $title, 'body' => $body]);
        return $stmt->rowCount();
    }

    // Supprimer un post
    public function deletePost($id) {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    // Obtenir le nombre total de posts
    public function getTotalPostsCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM posts");
        return (int) $stmt->fetchColumn();
    }

    // Obtenir les posts paginés
    public function getPaginatedPosts($limit, $offset) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.name AS authorName
            FROM posts p
            JOIN user u ON p.userId = u.id
            ORDER BY p.createdAt DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createPost($title, $body, $userId, $createdAt) {
        $sql = "INSERT INTO posts (title, body, userId, createdAt) VALUES (:title, :body, :userId, :createdAt)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'title' => $title,
            'body' => $body,
            'userId' => $userId,
            'createdAt' => $createdAt
        ]);
        
        if (!$success) {
            // Log ou affichez l'erreur
            error_log(print_r($stmt->errorInfo(), true));
        }
        
        return $success;
    }
    
    
    
}
