<?php
/**
 * Site-wide configuration for Lakshmi Sridhar
 * Bracketed placeholders match the content document ? replace before publishing.
 */

define('SITE_NAME', 'Lakshmi Sridhar');
define('SITE_TAGLINE', 'Art from the Heart');
define('SITE_URL', ''); // e.g. https://lakshmisridhar.com ? leave empty for relative paths
define('STUDIO_LOCATION', 'Celbridge, Co. Kildare, Ireland');
define('CONTACT_PHONE', '+353 89 441 3077');
define('CONTACT_PHONE_TEL', '+353894413077');

// Social & contact ? replace placeholders before publishing
define('INSTAGRAM_HANDLE', 'lakshmi.sridharj');
define('INSTAGRAM_URL', 'https://www.instagram.com/lakshmi.sridharj/');
define('FACEBOOK_URL', 'https://www.facebook.com/'); // [Add Facebook URL if applicable]
define('WHATSAPP_NUMBER', '353894413077'); // digits only for wa.me
define('WHATSAPP_URL', 'https://wa.me/' . WHATSAPP_NUMBER);
define('CONTACT_EMAIL', 'Lakshmi.Sridharj@gmail.com');

define('ASSET_VERSION', '1.7.1');

require_once __DIR__ . '/db.php';

/**
 * Format a numeric price as EUR for display.
 */
function format_money(int|float $amount): string
{
    return mb_chr(0x20AC, 'UTF-8') . number_format((float) $amount, 0, '.', ',');
}

/**
 * Last products() load error message, or null when OK.
 */
function products_error(): ?string
{
    return $GLOBALS['_products_error'] ?? null;
}

/**
 * Load products from MySQL only (source of truth).
 * Returns [] when DB fails or has no rows.
 * Check products_error() for failure vs empty catalogue.
 */
function products(): array
{
    static $products = null;
    if ($products !== null) {
        return $products;
    }

    $GLOBALS['_products_error'] = null;
    $fromDb = products_from_db();

    if ($fromDb === null) {
        $detail = db_last_error();
        $debug = !empty($GLOBALS['_db_config']['debug']);
        $GLOBALS['_products_error'] = 'Error fetching products. Please try again shortly.'
            . ($debug && $detail ? ' ? ' . $detail : '');
        $products = [];
        return $products;
    }

    $products = $fromDb;
    return $products;
}

/**
 * Resolve asset path relative to site root.
 */
function asset(string $path): string
{
    $base = rtrim(SITE_URL, '/');
    return $base . '/assets/' . ltrim($path, '/');
}

/**
 * Resolve page URL.
 */
function page_url(string $slug = ''): string
{
    $base = rtrim(SITE_URL, '/');
    if ($slug === '' || $slug === 'home') {
        return $base . '/index.php';
    }
    return $base . '/' . ltrim($slug, '/');
}

/**
 * Whether the given page is currently active.
 */
function is_active(string $page, string $current): bool
{
    return $page === $current;
}

/**
 * Escape HTML.
 */
function e(?string $value): string
{
    $value = (string) $value;
    if (!mb_check_encoding($value, 'UTF-8')) {
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
