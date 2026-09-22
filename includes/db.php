<?php
/**
 * PDO connection for Hostinger MySQL.
 *
 * Copy db.local.php.example → db.local.php and fill in credentials.
 * On Hostinger, host is usually "localhost".
 */

function db(): ?PDO
{
    static $pdo = false; // false = not tried yet; null = failed; PDO = ok

    if ($pdo !== false) {
        return $pdo instanceof PDO ? $pdo : null;
    }

    $configFile = __DIR__ . '/db.local.php';
    if (!is_file($configFile)) {
        $pdo = null;
        return null;
    }

    /** @var array{host?:string,port?:int,name?:string,user?:string,pass?:string,charset?:string,debug?:bool} $cfg */
    $cfg = require $configFile;
    $GLOBALS['_db_config'] = $cfg;

    $host = $cfg['host'] ?? 'localhost';
    $port = (int) ($cfg['port'] ?? 3306);
    $name = $cfg['name'] ?? '';
    $user = $cfg['user'] ?? '';
    $pass = $cfg['pass'] ?? '';
    $charset = $cfg['charset'] ?? 'utf8mb4';

    if ($name === '' || $user === '') {
        $GLOBALS['_db_last_error'] = 'Database name or user is missing in db.local.php';
        $pdo = null;
        return null;
    }

    try {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $name, $charset);
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (Throwable $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        $pdo = null;
        return null;
    }
}

/**
 * Last PDO connection error (for debugging).
 */
function db_last_error(): ?string
{
    return $GLOBALS['_db_last_error'] ?? null;
}

/**
 * Fetch products from MySQL shaped like productsData.php entries.
 *
 * @return array<int, array<string, mixed>>|null null when DB unavailable
 */
function products_from_db(): ?array
{
    $pdo = db();
    if (!$pdo) {
        return null;
    }

    try {
        $rows = $pdo->query(
            'SELECT id, title, description, image, image_alt, tags, status, featured, default_size
             FROM products
             WHERE status = \'available\'
             ORDER BY sort_order ASC, title ASC'
        )->fetchAll();

        if (!$rows) {
            return [];
        }

        $sizeStmt = $pdo->prepare(
            'SELECT size_code, label, price, compare_at
             FROM product_sizes
             WHERE product_id = ?
             ORDER BY FIELD(size_code, \'S\', \'M\', \'L\'), size_code ASC'
        );

        $products = [];
        foreach ($rows as $row) {
            $sizeStmt->execute([$row['id']]);
            $sizeRows = $sizeStmt->fetchAll();
            $sizes = [];
            foreach ($sizeRows as $s) {
                $sizes[$s['size_code']] = [
                    'label' => $s['label'],
                    'price' => (float) $s['price'],
                    'compare_at' => $s['compare_at'] !== null ? (float) $s['compare_at'] : null,
                ];
            }

            $tags = $row['tags'];
            if (is_string($tags)) {
                $decoded = json_decode($tags, true);
                $tags = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($tags)) {
                $tags = [];
            }

            $products[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'] ?? '',
                'image' => $row['image'],
                'image_alt' => $row['image_alt'] ?? $row['title'],
                'tags' => $tags,
                'status' => $row['status'],
                'featured' => (bool) $row['featured'],
                'default_size' => $row['default_size'] ?: 'S',
                'sizes' => $sizes,
            ];
        }

        return $products;
    } catch (Throwable $e) {
        error_log('products_from_db failed: ' . $e->getMessage());
        return null;
    }
}
