<?php
require_once __DIR__ . '/../../config/database.php';

class Comment {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Ajouter un commentaire
    public function add(int $userId, int $challengeId, string $contenu): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO comments (user_id, challenge_id, contenu) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$userId, $challengeId, $contenu]);
    }

    // Récupérer tous les commentaires d'un défi
    public function getByChallengeId(int $challengeId): array {
        $stmt = $this->pdo->prepare(
            'SELECT c.*, u.nom, u.prenom FROM comments c
             JOIN users u ON c.user_id = u.id
             WHERE c.challenge_id = ?
             ORDER BY c.created_at DESC'
        );
        $stmt->execute([$challengeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un commentaire
    public function delete(int $id, int $userId): bool {
        $stmt = $this->pdo->prepare(
            'DELETE FROM comments WHERE id = ? AND user_id = ?'
        );
        return $stmt->execute([$id, $userId]);
    }
}