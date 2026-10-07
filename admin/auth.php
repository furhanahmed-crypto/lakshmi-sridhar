<?php

/**
 * Simple admin session helpers.
 */
require_once dirname(__DIR__) . '/includes/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * /admin (no trailing slash) makes relative URLs like admin.css resolve to /admin.css.
 * Always send browsers to /admin/ first (Apache .htaccess does this too; PHP -S needs this).
 */
function admin_redirect_bare_path(): void
{
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
    $path = (string) (parse_url($uri, PHP_URL_PATH) ?: '');
    if ($path !== '/admin') {
        return;
    }
    $query = parse_url($uri, PHP_URL_QUERY);
    $target = '/admin/' . ($query ? ('?' . $query) : '');
    header('Location: ' . $target, true, 301);
    exit;
}

admin_redirect_bare_path();

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
 * Absolute URL under /admin/ so links/CSS work from /admin and /admin/index.php.
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

/** Versioned stylesheet/script URL under /admin/ (never relative). */
function admin_asset(string $file): string
{
    return admin_url($file) . '?v=' . rawurlencode((string) ASSET_VERSION);
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
