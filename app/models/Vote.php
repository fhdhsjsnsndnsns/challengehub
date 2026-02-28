<?php
require_once __DIR__ . '/../../config/database.php';

class Vote {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // A-t-il déjà voté ?
    public function hasVoted(int $userId, int $challengeId): bool {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM votes WHERE user_id = ? AND challenge_id = ?'
        );
        $stmt->execute([$userId, $challengeId]);
        return $stmt->rowCount() > 0;
    }

    // Voter
    public function add(int $userId, int $challengeId, int $note): bool {
        if ($this->hasVoted($userId, $challengeId)) return false;
        $stmt = $this->pdo->prepare(
            'INSERT INTO votes (user_id, challenge_id, note) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$userId, $challengeId, $note]);
    }

    // Moyenne des notes
    public function getAverage(int $challengeId): float {
        $stmt = $this->pdo->prepare(
            'SELECT AVG(note) FROM votes WHERE challenge_id = ?'
        );
        $stmt->execute([$challengeId]);
        return round((float) $stmt->fetchColumn(), 1);
    }

    // Nombre de votes
    public function getCount(int $challengeId): int {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM votes WHERE challenge_id = ?'
        );
        $stmt->execute([$challengeId]);
        return (int) $stmt->fetchColumn();
    }
}