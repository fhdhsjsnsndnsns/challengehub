<?php
/**
 * ChallengeHub - Connexion PDO centralisée
 * Singleton : une seule instance de connexion pour toute l'app
 */

declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    // ─── Constructeur privé (pattern Singleton) ────────────────────────────────
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Requêtes préparées natives (anti-injection SQL)
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En prod : logger l'erreur sans l'afficher
            if (DEBUG_MODE) {
                die('Erreur de connexion BDD : ' . $e->getMessage());
            } else {
                die('Erreur interne. Veuillez réessayer plus tard.');
            }
        }
    }

    // ─── Accès à l'instance unique ────────────────────────────────────────────
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // ─── Retourne l'objet PDO ─────────────────────────────────────────────────
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // ─── Empêche le clonage et la désérialisation ─────────────────────────────
    private function __clone() {}
    public function __wakeup(): void
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }
}
