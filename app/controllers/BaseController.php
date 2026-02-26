<?php
declare(strict_types=1);

abstract class BaseController
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewFile)) throw new \RuntimeException("Vue introuvable : $view");
        $content = $viewFile;
        require_once APP_PATH . '/views/layouts/main.php';
    }

    protected function redirect(string $controller, string $action = 'index', ?int $id = null, array $extra = []): void
    {
        $url = APP_URL . '/index.php?controller=' . $controller . '&action=' . $action;
        if ($id !== null) $url .= '&id=' . $id;
        foreach ($extra as $k => $v) $url .= '&' . urlencode($k) . '=' . urlencode((string)$v);
        header('Location: ' . $url);
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->flash('error', 'Vous devez être connecté.');
            $this->redirect('user', 'login');
        }
    }

    protected function isLoggedIn(): bool { return !empty($_SESSION['user_id']); }
    protected function getCurrentUserId(): ?int { return $_SESSION['user_id'] ?? null; }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('Token CSRF invalide.');
        }
    }

    protected function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    protected function uploadImage(array $file, string $prefix = ''): ?string
    {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
        if ($file['size'] > MAX_FILE_SIZE) return null;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ALLOWED_TYPES, true)) return null;
        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid() . '.' . strtolower($ext);
        $dest     = UPLOAD_PATH . '/' . $filename;
        if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
        return $filename;
    }

    protected function jsonResponse(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
