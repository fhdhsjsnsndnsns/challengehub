<?php
/**
 * ChallengeHub - Routeur Central
 * Point d'entrée unique de l'application
 */

declare(strict_types=1);

// ─── Chargement de la configuration ───────────────────────────────────────────
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

// ─── Autoloader simple (sans Composer) ────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/app/controllers/' . $class . '.php',
        __DIR__ . '/app/models/'      . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ─── Session sécurisée ────────────────────────────────────────────────────────
session_start([
    'cookie_httponly' => true,
    'cookie_secure'   => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
]);

// ─── Lecture de la route depuis l'URL ─────────────────────────────────────────
// Format attendu : ?controller=user&action=login
// Exemple       : ?controller=challenge&action=show&id=5
$controllerName = $_GET['controller'] ?? 'home';
$action         = $_GET['action']     ?? 'index';
$id             = isset($_GET['id']) ? (int) $_GET['id'] : null;

// ─── Table de routage ─────────────────────────────────────────────────────────
$routes = [
    'home'       => 'HomeController',
    'user'       => 'UserController',
    'challenge'  => 'ChallengeController',
    'submission' => 'SubmissionController',
    'comment'    => 'CommentController',
    'vote'       => 'VoteController',
];

// ─── Résolution du controller ─────────────────────────────────────────────────
if (!array_key_exists($controllerName, $routes)) {
    http_response_code(404);
    require_once __DIR__ . '/app/views/layouts/404.php';
    exit;
}

$controllerClass = $routes[$controllerName];
$controllerFile  = __DIR__ . '/app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    require_once __DIR__ . '/app/views/layouts/404.php';
    exit;
}

require_once $controllerFile;

// ─── Instanciation et dispatch ────────────────────────────────────────────────
$controller = new $controllerClass();

// Vérifie que la méthode (action) existe dans le controller
if (!method_exists($controller, $action)) {
    http_response_code(404);
    require_once __DIR__ . '/app/views/layouts/404.php';
    exit;
}

// Appel de l'action avec l'id si présent
if ($id !== null) {
    $controller->$action($id);
} else {
    $controller->$action();
}
