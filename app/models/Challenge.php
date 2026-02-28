<?php
require_once __DIR__ . '/../../config/database.php';

class Challenge {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Créer un défi
    public function create(string $titre, string $desc, string $cat, string $date, int $userId, ?string $image = null): bool {
    $stmt = $this->pdo->prepare(
        'INSERT INTO challenges (titre, description, categorie, date_limite, user_id, image)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    return $stmt->execute([$titre, $desc, $cat, $date, $userId, $image]);
}

    // Lister tous les défis
    public function getAll(string $search = '', string $categorie = ''): array {
    $sql = 'SELECT c.*, u.nom AS auteur,u.photo AS user_photo,
            (SELECT COUNT(*) FROM submissions s WHERE s.challenge_id = c.id) AS nb_participants,
            (SELECT ROUND(AVG(v.note), 1) FROM votes v WHERE v.challenge_id = c.id) AS moyenne_votes,
            (SELECT COUNT(*) FROM votes v WHERE v.challenge_id = c.id) AS nb_votes,
            (SELECT COUNT(*) FROM comments cm WHERE cm.challenge_id = c.id) AS nb_comments
            FROM challenges c
            JOIN users u ON c.user_id = u.id
            WHERE 1=1';
    $params = [];

    if ($search) {
        $sql .= ' AND (c.titre LIKE ? OR c.description LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($categorie) {
        $sql .= ' AND c.categorie = ?';
        $params[] = $categorie;
    }

    $sql .= ' ORDER BY moyenne_votes DESC, nb_participants DESC, c.created_at DESC';
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Un seul défi par id
    public function getById(int $id): array|false {
    $stmt = $this->pdo->prepare(
        'SELECT c.*, u.nom AS auteur, u.photo AS user_photo 
         FROM challenges c
         JOIN users u ON c.user_id = u.id 
         WHERE c.id = ?'
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    // Modifier
    public function update(int $id, string $titre, string $desc, string $cat, string $date, int $userId): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE challenges SET titre=?, description=?, categorie=?, date_limite=?
             WHERE id=? AND user_id=?'
        );
        return $stmt->execute([$titre, $desc, $cat, $date, $id, $userId]);
    }

    // Supprimer
    public function delete(int $id, int $userId): bool {
        $stmt = $this->pdo->prepare('DELETE FROM challenges WHERE id = ? AND user_id = ?');
        return $stmt->execute([$id, $userId]);
    }
}