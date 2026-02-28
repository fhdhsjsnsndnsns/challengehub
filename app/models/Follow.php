<?php
require_once __DIR__ . '/../../config/database.php';

class Follow {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function follow(int $followerId, int $followingId): bool {
        if ($this->isFollowing($followerId, $followingId)) return false;
        $stmt = $this->pdo->prepare(
            'INSERT INTO follows (follower_id, following_id) VALUES (?, ?)'
        );
        return $stmt->execute([$followerId, $followingId]);
    }

    public function unfollow(int $followerId, int $followingId): bool {
        $stmt = $this->pdo->prepare(
            'DELETE FROM follows WHERE follower_id = ? AND following_id = ?'
        );
        return $stmt->execute([$followerId, $followingId]);
    }

    public function isFollowing(int $followerId, int $followingId): bool {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM follows WHERE follower_id = ? AND following_id = ?'
        );
        $stmt->execute([$followerId, $followingId]);
        return $stmt->rowCount() > 0;
    }

    public function countFollowers(int $userId): int {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM follows WHERE following_id = ?'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function countFollowing(int $userId): int {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM follows WHERE follower_id = ?'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}