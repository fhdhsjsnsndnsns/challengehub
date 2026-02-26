<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

spl_autoload_register(function (string $class): void {
    foreach (['/app/controllers/', '/app/models/'] as $dir) {
        $file = __DIR__ . $dir . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

session_start([
    'cookie_httponly' => true,
    'cookie_secure'   => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
]);

$controller = $_GET['controller'] ?? 'home';
$action     = $_GET['action']     ?? 'index';
$id         = isset($_GET['id'])  ? (int) $_GET['id'] : null;

$routes = [
    'home'       => 'HomeController',
    'user'       => 'UserController',
    'challenge'  => 'ChallengeController',
    'submission' => 'SubmissionController',
    'comment'    => 'CommentController',
    'vote'       => 'VoteController',
];

if (!array_key_exists($controller, $routes)) {
    http_response_code(404);
    require_once __DIR__ . '/app/views/layouts/404.php';
    exit;
}

$class = $routes[$controller];
require_once __DIR__ . '/app/controllers/' . $class . '.php';
$ctrl = new $class();

if (!method_exists($ctrl, $action)) {
    http_response_code(404);
    require_once __DIR__ . '/app/views/layouts/404.php';
    exit;
}

$id !== null ? $ctrl->$action($id) : $ctrl->$action();
