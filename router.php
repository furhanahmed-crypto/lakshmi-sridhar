<?php
/**
 * Local PHP built-in server router (Apache uses .htaccess instead).
 *
 * Start with:
 *   php -S localhost:8081 router.php
 *
 * Makes /admin redirect to /admin/ so CSS and relative links resolve correctly.
 */

$uri = urldecode((string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'));

if ($uri === '/admin') {
    $query = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
    header('Location: /admin/' . ($query ? ('?' . $query) : ''), true, 301);
    exit;
}

$file = __DIR__ . $uri;

if ($uri !== '/' && is_file($file)) {
    return false; // serve static / existing PHP files as-is
}

if (is_dir($file)) {
    $index = rtrim($file, '/\\') . DIRECTORY_SEPARATOR . 'index.php';
    if (is_file($index)) {
        require $index;
        return true;
    }
}

return false;
}
