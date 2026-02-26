<?php
declare(strict_types=1);
class Comment {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }
    public function findBySubmission(int $sid): array { $s=$this->db->prepare('SELECT cm.*,u.name AS author_name,u.avatar AS author_avatar FROM comments cm JOIN users u ON cm.user_id=u.id WHERE cm.submission_id=? ORDER BY cm.created_at ASC');$s->execute([$sid]);return $s->fetchAll(); }
    public function create(int $uid,int $sid,string $content): bool { $s=$this->db->prepare('INSERT INTO comments(user_id,submission_id,content)VALUES(?,?,?)');return $s->execute([$uid,$sid,$content]); }
    public function delete(int $id): bool { $s=$this->db->prepare('DELETE FROM comments WHERE id=?');return $s->execute([$id]); }
    public function belongsToUser(int $id,int $uid): bool { $s=$this->db->prepare('SELECT id FROM comments WHERE id=? AND user_id=?');$s->execute([$id,$uid]);return (bool)$s->fetch(); }
}
