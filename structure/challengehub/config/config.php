<?php
/**
 * ChallengeHub - Configuration globale
 * À adapter selon votre environnement (WAMPServer / XAMPP / EasyPHP)
 */

declare(strict_types=1);

// ─── Base de données ───────────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'challengehub');
define('DB_USER',    'root');
define('DB_PASS',    '');          // Mot de passe WAMPServer (vide par défaut)
define('DB_CHARSET', 'utf8mb4');

// ─── Application ──────────────────────────────────────────────────────────────
define('APP_NAME',    'ChallengeHub');
define('APP_URL',     'http://localhost/challengehub');  // Adapter si besoin
define('APP_VERSION', '1.0.0');

// ─── Chemins ──────────────────────────────────────────────────────────────────
define('ROOT_PATH',    __DIR__ . '/..');
define('APP_PATH',     ROOT_PATH . '/app');
define('PUBLIC_PATH',  ROOT_PATH . '/public');
define('UPLOAD_PATH',  PUBLIC_PATH . '/images/uploads');
define('UPLOAD_URL',   APP_URL . '/public/images/uploads');

// ─── Upload d'images ──────────────────────────────────────────────────────────
define('MAX_FILE_SIZE',    5 * 1024 * 1024); // 5 Mo
define('ALLOWED_TYPES',    ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// ─── Sécurité ─────────────────────────────────────────────────────────────────
define('HASH_ALGO',   PASSWORD_BCRYPT);
define('TOKEN_TTL',   3600); // Durée de vie du token CSRF en secondes

// ─── Pagination ───────────────────────────────────────────────────────────────
define('ITEMS_PER_PAGE', 10);

// ─── Mode debug (mettre à false en production) ────────────────────────────────
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}
