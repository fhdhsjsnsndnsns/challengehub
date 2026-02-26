<?php
declare(strict_types=1);
class Challenge {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }
    public function findAll(int $page=1,?string $cat=null,?string $search=null,string $sort='recent'): array {
        $offset=($page-1)*ITEMS_PER_PAGE; $where=['1=1']; $params=[];
        if($cat){$where[]='c.category=?';$params[]=$cat;}
        if($search){$where[]='(c.title LIKE ? OR c.description LIKE ?)';$params[]="%$search%";$params[]="%$search%";}
        $ob=match($sort){'popular'=>'(SELECT COUNT(*) FROM submissions s WHERE s.challenge_id=c.id) DESC','deadline'=>'c.deadline ASC',default=>'c.created_at DESC'};
        $sql="SELECT c.*,u.name AS author_name,(SELECT COUNT(*) FROM submissions s WHERE s.challenge_id=c.id) AS submission_count FROM challenges c JOIN users u ON c.user_id=u.id WHERE ".implode(' AND ',$where)." ORDER BY $ob LIMIT ? OFFSET ?";
        $params[]=ITEMS_PER_PAGE;$params[]=$offset;
        $s=$this->db->prepare($sql);$s->execute($params);return $s->fetchAll();
    }
    public function countAll(?string $cat=null,?string $search=null): int {
        $where=['1=1'];$params=[];
        if($cat){$where[]='category=?';$params[]=$cat;}
        if($search){$where[]='(title LIKE ? OR description LIKE ?)';$params[]="%$search%";$params[]="%$search%";}
        $s=$this->db->prepare('SELECT COUNT(*) FROM challenges WHERE '.implode(' AND ',$where));$s->execute($params);return (int)$s->fetchColumn();
    }
    public function findById(int $id): ?array { $s=$this->db->prepare('SELECT c.*,u.name AS author_name FROM challenges c JOIN users u ON c.user_id=u.id WHERE c.id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public function create(int $uid,string $title,string $desc,string $cat,string $dl,?string $img): int { $s=$this->db->prepare('INSERT INTO challenges(user_id,title,description,category,deadline,image)VALUES(?,?,?,?,?,?)');$s->execute([$uid,$title,$desc,$cat,$dl,$img]);return (int)$this->db->lastInsertId(); }
    public function update(int $id,string $title,string $desc,string $cat,string $dl,?string $img=null): bool { if($img){$s=$this->db->prepare('UPDATE challenges SET title=?,description=?,category=?,deadline=?,image=? WHERE id=?');return $s->execute([$title,$desc,$cat,$dl,$img,$id]);} $s=$this->db->prepare('UPDATE challenges SET title=?,description=?,category=?,deadline=? WHERE id=?');return $s->execute([$title,$desc,$cat,$dl,$id]); }
    public function delete(int $id): bool { $s=$this->db->prepare('DELETE FROM challenges WHERE id=?');return $s->execute([$id]); }
    public function getCategories(): array { $s=$this->db->query('SELECT DISTINCT category FROM challenges ORDER BY category');return $s->fetchAll(PDO::FETCH_COLUMN); }
    public function belongsToUser(int $id,int $uid): bool { $s=$this->db->prepare('SELECT id FROM challenges WHERE id=? AND user_id=?');$s->execute([$id,$uid]);return (bool)$s->fetch(); }
public function getLatest(int $limit = 5): array
{
    $sql = "SELECT c.*, u.name AS author_name,
                   (SELECT COUNT(*) FROM submissions s WHERE s.challenge_id = c.id) AS submission_count
            FROM challenges c
            JOIN users u ON c.user_id = u.id
            ORDER BY c.created_at DESC
            LIMIT :limit";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}}
