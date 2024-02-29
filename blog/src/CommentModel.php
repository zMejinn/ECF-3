<?php

namespace Daeme\SRC;

use PDO;
use PDOException;

class CommentModel {
    private PDO $db; // Instance de PDO

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère les commentaires associés à un identifiant de poste.
     *
     * @param int $postId L'identifiant du poste
     * @return array Les commentaires du poste
     */
    public function getCommentsByPostId(int $postId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM comments WHERE postId = :postId ORDER BY createdAt");
            $stmt->execute(['postId' => $postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gérer l'exception ou la transmettre plus loin
            throw $e;
        }
    }

    /**
     * Ajoute un commentaire à un poste.
     *
     * @param string $name Le nom de la personne qui commente
     * @param string $email L'email de la personne qui commente
     * @param string $body Le corps du commentaire
     * @param int $postId L'identifiant du poste auquel le commentaire est associé
     * @return string L'identifiant du commentaire ajouté
     */
    public function addComment(string $name, string $email, string $body, int $postId): string {
        try {
            $stmt = $this->db->prepare("INSERT INTO comments (name, email, body, postId) VALUES (:name, :email, :body, :postId)");
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'body' => $body,
                'postId' => $postId
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Gérer l'exception ou la transmettre plus loin
            throw $e;
        }
    }

    // Ajoutez d'autres méthodes en relation avec les commentaires si nécessaire
}
