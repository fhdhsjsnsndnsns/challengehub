<?php
declare(strict_types=1);
class Submission {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }
    public function findByChallenge(int $cid,string $sort='recent'): array {
        $ob=$sort==='popular'?'(SELECT COUNT(*) FROM votes v WHERE v.submission_id=s.id) DESC':'s.created_at DESC';
        $s=$this->db->prepare("SELECT s.*,u.name AS author_name,u.avatar AS author_avatar,(SELECT COUNT(*) FROM votes v WHERE v.submission_id=s.id) AS vote_count,(SELECT COUNT(*) FROM comments cm WHERE cm.submission_id=s.id) AS comment_count FROM submissions s JOIN users u ON s.user_id=u.id WHERE s.challenge_id=? ORDER BY $ob");
        $s->execute([$cid]);return $s->fetchAll();
    }
    public function findById(int $id): ?array { $s=$this->db->prepare('SELECT s.*,u.name AS author_name,u.avatar AS author_avatar,c.title AS challenge_title,c.id AS challenge_id,(SELECT COUNT(*) FROM votes v WHERE v.submission_id=s.id) AS vote_count FROM submissions s JOIN users u ON s.user_id=u.id JOIN challenges c ON s.challenge_id=c.id WHERE s.id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public function findByUser(int $uid): array { $s=$this->db->prepare('SELECT s.*,c.title AS challenge_title,(SELECT COUNT(*) FROM votes v WHERE v.submission_id=s.id) AS vote_count FROM submissions s JOIN challenges c ON s.challenge_id=c.id WHERE s.user_id=? ORDER BY s.created_at DESC');$s->execute([$uid]);return $s->fetchAll(); }
    public function create(int $uid,int $cid,string $desc,?string $media): int { $s=$this->db->prepare('INSERT INTO submissions(user_id,challenge_id,description,media)VALUES(?,?,?,?)');$s->execute([$uid,$cid,$desc,$media]);return (int)$this->db->lastInsertId(); }
    public function update(int $id,string $desc,?string $media=null): bool { if($media){$s=$this->db->prepare('UPDATE submissions SET description=?,media=? WHERE id=?');return $s->execute([$desc,$media,$id]);}$s=$this->db->prepare('UPDATE submissions SET description=? WHERE id=?');return $s->execute([$desc,$id]); }
    public function delete(int $id): bool { $s=$this->db->prepare('DELETE FROM submissions WHERE id=?');return $s->execute([$id]); }
    public function belongsToUser(int $id,int $uid): bool { $s=$this->db->prepare('SELECT id FROM submissions WHERE id=? AND user_id=?');$s->execute([$id,$uid]);return (bool)$s->fetch(); }
    public function getTopRanked(int $limit=10): array { $s=$this->db->prepare('SELECT s.*,u.name AS author_name,c.title AS challenge_title,COUNT(v.id) AS vote_count FROM submissions s JOIN users u ON s.user_id=u.id JOIN challenges c ON s.challenge_id=c.id LEFT JOIN votes v ON v.submission_id=s.id GROUP BY s.id ORDER BY vote_count DESC LIMIT ?');$s->execute([$limit]);return $s->fetchAll(); }
    public function userAlreadySubmitted(int $uid,int $cid): bool { $s=$this->db->prepare('SELECT id FROM submissions WHERE user_id=? AND challenge_id=?');$s->execute([$uid,$cid]);return (bool)$s->fetch(); }
}
