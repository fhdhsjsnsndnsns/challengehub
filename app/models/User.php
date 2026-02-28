<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function create(string $nom, string $prenom, string $email, string $password): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $this->pdo->prepare(
        'INSERT INTO users (nom, prenom, email, motdepasse) VALUES (?, ?, ?, ?)'
    );
    return $stmt->execute([$nom, $prenom, $email, $hash]);
}

    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Modifier le profil
public function update(int $id, string $nom, string $prenom, string $email): bool {
    $stmt = $this->pdo->prepare(
        'UPDATE users SET nom=?, prenom=?, email=? WHERE id=?'
    );
    return $stmt->execute([$nom, $prenom, $email, $id]);
}

// Modifier le mot de passe
public function updatePassword(int $id, string $newPassword): bool {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $this->pdo->prepare(
        'UPDATE users SET motdepasse=? WHERE id=?'
    );
    return $stmt->execute([$hash, $id]);
}

// Supprimer le compte
public function delete(int $id): bool {
    $stmt = $this->pdo->prepare('DELETE FROM users WHERE id=?');
    return $stmt->execute([$id]);
}
public function getDefisParCategorie(int $userId): array {
    $stmt = $this->pdo->prepare(
        'SELECT c.*, 
         (SELECT COUNT(*) FROM submissions s WHERE s.challenge_id = c.id) AS nb_participants,
         (SELECT ROUND(AVG(v.note),1) FROM votes v WHERE v.challenge_id = c.id) AS moyenne_votes,
         (SELECT COUNT(*) FROM comments cm WHERE cm.challenge_id = c.id) AS nb_comments
         FROM challenges c
         WHERE c.user_id = ?
         ORDER BY c.categorie, c.created_at DESC'
    );
    $stmt->execute([$userId]);
    $defis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Grouper par catégorie
    $grouped = [];
    foreach ($defis as $defi) {
        $grouped[$defi['categorie']][] = $defi;
    }
    return $grouped;
}

public function countDefis(int $userId): int {
    $stmt = $this->pdo->prepare(
        'SELECT COUNT(*) FROM challenges WHERE user_id = ?'
    );
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}
public function updatePhoto(int $id, ?string $filename): bool {
    $stmt = $this->pdo->prepare(
        'UPDATE users SET photo = ? WHERE id = ?'
    );
    return $stmt->execute([$filename, $id]);
}
}