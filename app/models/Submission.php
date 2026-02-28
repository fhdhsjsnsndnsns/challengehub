<?php
require_once __DIR__ . '/../../config/database.php';

class Submission {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Rejoindre un défi
    public function join(int $userId, int $challengeId): bool {
        if ($this->hasJoined($userId, $challengeId)) return false;
        $stmt = $this->pdo->prepare(
            'INSERT INTO submissions (user_id, challenge_id, description) VALUES (?, ?, "")'
        );
        return $stmt->execute([$userId, $challengeId]);
    }

    // A-t-il déjà rejoint ?
    public function hasJoined(int $userId, int $challengeId): bool {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM submissions WHERE user_id = ? AND challenge_id = ?'
        );
        $stmt->execute([$userId, $challengeId]);
        return $stmt->rowCount() > 0;
    }

    // Nombre de participants
    public function countByChallengeId(int $challengeId): int {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM submissions WHERE challenge_id = ?'
        );
        $stmt->execute([$challengeId]);
        return (int) $stmt->fetchColumn();
    }
}