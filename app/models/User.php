<?php
declare(strict_types=1);
class User {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }
    public function findById(int $id): ?array { $s=$this->db->prepare('SELECT * FROM users WHERE id=?'); $s->execute([$id]); return $s->fetch()?:null; }
    public function findByEmail(string $email): ?array { $s=$this->db->prepare('SELECT * FROM users WHERE email=?'); $s->execute([$email]); return $s->fetch()?:null; }
    public function register(string $name,string $email,string $password): bool { $h=password_hash($password,PASSWORD_BCRYPT); $s=$this->db->prepare('INSERT INTO users(name,email,password)VALUES(?,?,?)'); return $s->execute([$name,$email,$h]); }
    public function update(int $id,string $name,string $email,?string $avatar=null): bool { if($avatar){$s=$this->db->prepare('UPDATE users SET name=?,email=?,avatar=? WHERE id=?');return $s->execute([$name,$email,$avatar,$id]);} $s=$this->db->prepare('UPDATE users SET name=?,email=? WHERE id=?');return $s->execute([$name,$email,$id]); }
    public function updatePassword(int $id,string $pwd): bool { $h=password_hash($pwd,PASSWORD_BCRYPT);$s=$this->db->prepare('UPDATE users SET password=? WHERE id=?');return $s->execute([$h,$id]); }
    public function delete(int $id): bool { $s=$this->db->prepare('DELETE FROM users WHERE id=?');return $s->execute([$id]); }
    public function emailExists(string $email,?int $excl=null): bool { if($excl){$s=$this->db->prepare('SELECT id FROM users WHERE email=? AND id!=?');$s->execute([$email,$excl]);}else{$s=$this->db->prepare('SELECT id FROM users WHERE email=?');$s->execute([$email]);} return (bool)$s->fetch(); }
    public function getLastInsertId(): int { return (int)$this->db->lastInsertId(); }
}
