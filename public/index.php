<?php
session_start();

// Autoloader simple (bonus pro)
function autoload($class) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
}
spl_autoload_register('autoload');

// ROUTEUR SIMPLE
$action = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'User';

switch ($controller) {
    case 'User':
        $ctrl = new UserController();
        if ($action === 'register') {
            $ctrl->register();
        }
        break;
    default:
        echo "Page non trouvée";
}
?>
