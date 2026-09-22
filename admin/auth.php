<?php

/**
 * Simple admin session helpers.
 */
require_once dirname(__DIR__) . '/includes/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function admin_password(): string
{
    $cfg = $GLOBALS['_db_config'] ?? null;
    if (!is_array($cfg)) {
        // Ensure db config is loaded
        db();
        $cfg = $GLOBALS['_db_config'] ?? [];
    }
    return (string) ($cfg['admin_password'] ?? 'Lakshmi@1234');
}

/**
 * Absolute URL under /admin/ so redirects work when visiting /admin (no trailing slash).
 * Relative "products.php" from /admin resolves to /products.php in the browser.
 */
function admin_url(string $path = ''): string
{
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/admin/index.php'));
    $dir = dirname($script);
    // SCRIPT_NAME can be "/admin" when the directory URL has no trailing slash
    if (basename($script) === 'admin') {
        $dir = $script;
    }
    $dir = rtrim($dir, '/');
    if ($dir === '' || $dir === '.') {
        $dir = '/admin';
    }
    $path = ltrim($path, '/');
    return $path === '' ? $dir . '/' : $dir . '/' . $path;
}

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_ok']);
}

function admin_require_login(): void
{
    if (!admin_logged_in()) {
        header('Location: ' . admin_url('index.php'));
        exit;
    }
}

function admin_attempt_login(string $password): bool
{
    if (hash_equals(admin_password(), $password)) {
        $_SESSION['admin_ok'] = true;
        return true;
    }
    return false;
}

function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
