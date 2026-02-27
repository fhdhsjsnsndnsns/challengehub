<?php
/**
 * ChallengeHub - Controller de base
 * Tous les controllers héritent de cette classe
 */

declare(strict_types=1);

abstract class BaseController
{
    protected PDO $db;

    public function __construct()
    {
        // Récupère la connexion PDO centralisée
        $this->db = Database::getInstance()->getConnection();
    }

    // ─── Rendu d'une vue ──────────────────────────────────────────────────────
    /**
     * Charge un fichier de vue en lui injectant des données.
     *
     * @param string $view   Chemin relatif depuis /app/views/ (ex: 'challenge/list')
     * @param array  $data   Variables à rendre disponibles dans la vue
     */
    protected function render(string $view, array $data = []): void
    {
        // Rend les clés du tableau accessibles comme variables dans la vue
        extract($data);

        $viewFile = APP_PATH . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vue introuvable : {$view}");
        }

        // Inclusion du layout principal avec la vue à l'intérieur
        $content = $viewFile; // Le layout utilisera cette variable
        require_once APP_PATH . '/views/layouts/main.php';
    }

    // ─── Redirection ──────────────────────────────────────────────────────────
    protected function redirect(string $controller, string $action = 'index', ?int $id = null): void
    {
        $url = APP_URL . '/index.php?controller=' . $controller . '&action=' . $action;
        if ($id !== null) {
            $url .= '&id=' . $id;
        }
        header('Location: ' . $url);
        exit;
    }

    // ─── Vérification de session ──────────────────────────────────────────────
    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('user', 'login');
        }
    }

    protected function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    protected function getCurrentUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    // ─── Protection CSRF ──────────────────────────────────────────────────────
    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token']) || 
            (time() - ($_SESSION['csrf_token_time'] ?? 0)) > TOKEN_TTL) {
            $_SESSION['csrf_token']      = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('Token CSRF invalide. Requête rejetée.');
        }
    }

    // ─── Nettoyage XSS ────────────────────────────────────────────────────────
    protected function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // ─── Réponse JSON (pour futures fonctionnalités AJAX) ─────────────────────
    protected function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
