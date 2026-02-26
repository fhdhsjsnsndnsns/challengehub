<?php
declare(strict_types=1);

class Vote
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function hasVoted(int $userId, int $submissionId): bool
    {
        $s = $this->db->prepare('SELECT id FROM votes WHERE user_id=? AND submission_id=?');
        $s->execute([$userId, $submissionId]);
        return (bool) $s->fetch();
    }

    public function cast(int $userId, int $submissionId): bool
    {
        if ($this->hasVoted($userId, $submissionId)) return false;
        $s = $this->db->prepare('INSERT INTO votes (user_id, submission_id) VALUES (?,?)');
        return $s->execute([$userId, $submissionId]);
    }

    public function remove(int $userId, int $submissionId): bool
    {
        $s = $this->db->prepare('DELETE FROM votes WHERE user_id=? AND submission_id=?');
        return $s->execute([$userId, $submissionId]);
    }

    public function toggle(int $userId, int $submissionId): array
    {
        if ($this->hasVoted($userId, $submissionId)) {
            $this->remove($userId, $submissionId);
            $action = 'removed';
        } else {
            $this->cast($userId, $submissionId);
            $action = 'added';
        }
        return ['action' => $action, 'count' => $this->countBySubmission($submissionId)];
    }

    public function countBySubmission(int $submissionId): int
    {
        $s = $this->db->prepare('SELECT COUNT(*) FROM votes WHERE submission_id=?');
        $s->execute([$submissionId]);
        return (int) $s->fetchColumn();
    }
}
