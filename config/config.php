<?php
declare(strict_types=1);

define('DB_HOST',    'localhost');
define('DB_NAME',    'challengehub');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME',   'ChallengeHub');
define('APP_URL',    'http://localhost/challengehub_full');

define('ROOT_PATH',   __DIR__ . '/..');
define('APP_PATH',    ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', ROOT_PATH . '/public/images/uploads');
define('UPLOAD_URL',  APP_URL . '/public/images/uploads');

define('MAX_FILE_SIZE',  5 * 1024 * 1024);
define('ALLOWED_TYPES',  ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ITEMS_PER_PAGE', 9);
define('DEBUG_MODE',     true);

if (DEBUG_MODE) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
