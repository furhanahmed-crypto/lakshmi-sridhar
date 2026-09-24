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
 * @param bool $includeAll when true, include sold items (admin)
 * @return array<int, array<string, mixed>>|null null when DB unavailable
 */
function products_from_db(bool $includeAll = false): ?array
{
    $pdo = db();
    if (!$pdo) {
        return null;
    }

    try {
        $sql = 'SELECT id, title, description, image, image_alt, tags, status, featured, default_size, sort_order
             FROM products';
        if (!$includeAll) {
            $sql .= ' WHERE status = \'available\'';
        }
        $sql .= ' ORDER BY sort_order ASC, title ASC';

        $rows = $pdo->query($sql)->fetchAll();

        if (!$rows) {
            return [];
        }

        $sizeStmt = $pdo->prepare(
            'SELECT size_code, label, price, compare_at
             FROM product_sizes
             WHERE product_id = ?
             ORDER BY FIELD(size_code, \'S\', \'A1\', \'M\', \'A2\', \'L\', \'A3\'), size_code ASC'
        );

        $products = [];
        foreach ($rows as $row) {
            $products[] = map_product_row($row, $sizeStmt);
        }

        return $products;
    } catch (Throwable $e) {
        error_log('products_from_db failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

/**
 * Store codes stay S/M/L (CHAR(1) in MySQL). Public / admin UI uses A1/A2/A3.
 */
function size_display_code(string $code): string
{
    return match (strtoupper($code)) {
        'S', 'A1' => 'A1',
        'M', 'A2' => 'A2',
        'L', 'A3' => 'A3',
        default => 'A1',
    };
}

function size_store_code(string $code): string
{
    return match (strtoupper($code)) {
        'A1', 'S' => 'S',
        'A2', 'M' => 'M',
        'A3', 'L' => 'L',
        default => 'S',
    };
}

/**
 * @param array<string, mixed> $row
 * @return array<string, mixed>
 */
function map_product_row(array $row, PDOStatement $sizeStmt): array
{
    $sizeStmt->execute([$row['id']]);
    $sizeRows = $sizeStmt->fetchAll();
    $sizes = [];
    foreach ($sizeRows as $s) {
        $display = size_display_code((string) $s['size_code']);
        $sizes[$display] = [
            'label' => $display,
            'price' => (float) $s['price'],
            'compare_at' => $s['compare_at'] !== null ? (float) $s['compare_at'] : null,
        ];
    }

    $ordered = [];
    foreach (['A1', 'A2', 'A3'] as $code) {
        if (isset($sizes[$code])) {
            $ordered[$code] = $sizes[$code];
        }
    }

    $tags = $row['tags'];
    if (is_string($tags)) {
        $decoded = json_decode($tags, true);
        $tags = is_array($decoded) ? $decoded : [];
    } elseif (!is_array($tags)) {
        $tags = [];
    }

    return [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'] ?? '',
        'image' => $row['image'],
        'image_alt' => $row['image_alt'] ?? $row['title'],
        'tags' => $tags,
        'status' => $row['status'],
        'featured' => (bool) $row['featured'],
        'default_size' => size_display_code((string) ($row['default_size'] ?: 'S')),
        'sort_order' => (int) ($row['sort_order'] ?? 0),
        'sizes' => $ordered,
    ];
}

function product_by_id(string $id): ?array
{
    $pdo = db();
    if (!$pdo || $id === '') {
        return null;
    }

    try {
        $stmt = $pdo->prepare(
            'SELECT id, title, description, image, image_alt, tags, status, featured, default_size, sort_order
             FROM products WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $sizeStmt = $pdo->prepare(
            'SELECT size_code, label, price, compare_at
             FROM product_sizes WHERE product_id = ?
             ORDER BY FIELD(size_code, \'S\', \'A1\', \'M\', \'A2\', \'L\', \'A3\'), size_code ASC'
        );

        return map_product_row($row, $sizeStmt);
    } catch (Throwable $e) {
        error_log('product_by_id failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

function product_slug(string $title): string
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');
    return $slug !== '' ? $slug : ('product-' . time());
}

/**
 * Save an uploaded product image to assets/images/artwork/
 * using the user's original filename (basename only).
 * Returns relative path under assets/ (e.g. images/artwork/my-photo.jpg).
 *
 * @param array<string, mixed>|null $file typically $_FILES['image']
 */
function product_upload_image(?array $file, string $productIdHint = 'product'): ?string
{
    if (!$file || !isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        $GLOBALS['_db_last_error'] = 'Image upload failed (code ' . (int) $file['error'] . ').';
        return null;
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        $GLOBALS['_db_last_error'] = 'Invalid uploaded file.';
        return null;
    }

    $maxBytes = 5 * 1024 * 1024; // 5MB
    if ((int) ($file['size'] ?? 0) > $maxBytes) {
        $GLOBALS['_db_last_error'] = 'Image must be 5MB or smaller.';
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    $allowedMimes = [
        'image/jpeg' => true,
        'image/png' => true,
        'image/webp' => true,
    ];
    if (!isset($allowedMimes[$mime])) {
        $GLOBALS['_db_last_error'] = 'Use a JPG, PNG, or WebP image.';
        return null;
    }

    // Keep the exact client filename (no path rewriting / renaming).
    $original = (string) ($file['name'] ?? '');
    $filename = basename(str_replace(["\0", '\\'], '', $original));
    $filename = trim($filename);

    if ($filename === '' || $filename === '.' || $filename === '..') {
        $GLOBALS['_db_last_error'] = 'Invalid image filename.';
        return null;
    }

    // Block path tricks while preserving the visible name.
    if (str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
        $GLOBALS['_db_last_error'] = 'Invalid image filename.';
        return null;
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowedExt = ['jpg' => true, 'jpeg' => true, 'png' => true, 'webp' => true];
    if (!isset($allowedExt[$ext])) {
        $GLOBALS['_db_last_error'] = 'Filename must end with .jpg, .jpeg, .png, or .webp.';
        return null;
    }

    $dir = dirname(__DIR__) . '/assets/images/artwork';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        $GLOBALS['_db_last_error'] = 'Could not access artwork folder.';
        return null;
    }

    $dest = $dir . '/' . $filename;

    if (!move_uploaded_file($tmp, $dest)) {
        $GLOBALS['_db_last_error'] = 'Could not save uploaded image.';
        return null;
    }

    @chmod($dest, 0644);
    return 'images/artwork/' . $filename;
}

/**
 * Insert or update a product and its A1/A2/A3 sizes (stored as S/M/L).
 *
 * @param array<string, mixed> $data
 * @return string|null product id on success
 */
function product_save(array $data, bool $isNew = false): ?string
{
    $pdo = db();
    if (!$pdo) {
        return null;
    }

    $id = trim((string) ($data['id'] ?? ''));
    $title = trim((string) ($data['title'] ?? ''));
    if ($title === '') {
        $GLOBALS['_db_last_error'] = 'Title is required.';
        return null;
    }

    if ($id === '') {
        $id = product_slug($title);
    }

    $description = trim((string) ($data['description'] ?? ''));
    $image = trim((string) ($data['image'] ?? 'images/artwork/krishna-petals.jpeg'));
    if ($image === '') {
        $image = 'images/artwork/krishna-petals.jpeg';
    }
    $imageAlt = trim((string) ($data['image_alt'] ?? $title));
    $status = ($data['status'] ?? 'available') === 'sold' ? 'sold' : 'available';
    $featured = !empty($data['featured']) ? 1 : 0;
    $defaultSize = size_store_code((string) ($data['default_size'] ?? 'A1'));
    $sortOrder = (int) ($data['sort_order'] ?? 0);

    $tags = $data['tags'] ?? [];
    if (!is_array($tags)) {
        $tags = [];
    }
    $allowedTags = array_merge(['original', 'print'], array_keys(shop_categories()));
    $tags = array_values(array_intersect($tags, $allowedTags));
    $tagsJson = json_encode($tags, JSON_UNESCAPED_UNICODE);

    $sizeMeta = [
        'S' => 'A1',
        'M' => 'A2',
        'L' => 'A3',
    ];
    $sizesIn = is_array($data['sizes'] ?? null) ? $data['sizes'] : [];

    try {
        $pdo->beginTransaction();

        if ($isNew) {
            // Ensure unique id
            $check = $pdo->prepare('SELECT id FROM products WHERE id = ? LIMIT 1');
            $base = $id;
            $n = 2;
            while (true) {
                $check->execute([$id]);
                if (!$check->fetch()) {
                    break;
                }
                $id = $base . '-' . $n;
                $n++;
            }

            $ins = $pdo->prepare(
                'INSERT INTO products (id, title, description, image, image_alt, tags, status, featured, default_size, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ins->execute([
                $id, $title, $description, $image, $imageAlt, $tagsJson,
                $status, $featured, $defaultSize, $sortOrder,
            ]);
        } else {
            $upd = $pdo->prepare(
                'UPDATE products
                 SET title = ?, description = ?, image = ?, image_alt = ?, tags = ?, status = ?, featured = ?, default_size = ?, sort_order = ?
                 WHERE id = ?'
            );
            $upd->execute([
                $title, $description, $image, $imageAlt, $tagsJson,
                $status, $featured, $defaultSize, $sortOrder, $id,
            ]);
            if ($upd->rowCount() === 0) {
                // Still ok if no field changed; verify exists
                $exists = $pdo->prepare('SELECT id FROM products WHERE id = ?');
                $exists->execute([$id]);
                if (!$exists->fetch()) {
                    throw new RuntimeException('Product not found.');
                }
            }
        }

        $upsertSize = $pdo->prepare(
            'INSERT INTO product_sizes (product_id, size_code, label, price, compare_at)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE label = VALUES(label), price = VALUES(price), compare_at = VALUES(compare_at)'
        );

        foreach ($sizeMeta as $storeCode => $display) {
            $row = $sizesIn[$display] ?? $sizesIn[$storeCode] ?? [];
            $price = isset($row['price']) ? (float) $row['price'] : 0;
            $compare = isset($row['compare_at']) && $row['compare_at'] !== ''
                ? (float) $row['compare_at']
                : null;
            $upsertSize->execute([$id, $storeCode, $display, $price, $compare]);
        }

        $pdo->commit();
        return $id;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('product_save failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

function product_details_ensure_table(): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }

    try {
        $old = $pdo->query("SHOW TABLES LIKE 'product_detail_sections'")->fetchColumn();
        $new = $pdo->query("SHOW TABLES LIKE 'product_common_details'")->fetchColumn();
        if ($old && !$new) {
            $pdo->exec('RENAME TABLE product_detail_sections TO product_common_details');
        }

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS product_common_details (
                id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
                heading VARCHAR(160) NOT NULL,
                body TEXT NOT NULL,
                sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        return true;
    } catch (Throwable $e) {
        error_log('product_details_ensure_table failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return false;
    }
}

/**
 * @return array<int, array{id:int, title:string, body:string}>
 */
function product_details_from_db(): array
{
    if (!product_details_ensure_table()) {
        return [];
    }

    $pdo = db();
    if (!$pdo) {
        return [];
    }

    try {
        $rows = $pdo->query(
            'SELECT id, heading, body FROM product_common_details ORDER BY sort_order ASC, id ASC'
        )->fetchAll();

        $sections = [];
        foreach ($rows as $row) {
            $title = trim((string) $row['heading']);
            $body = trim((string) $row['body']);
            if ($title === '' && $body === '') {
                continue;
            }
            $sections[] = [
                'id' => (int) $row['id'],
                'title' => $title,
                'body' => $body,
            ];
        }

        return $sections;
    } catch (Throwable $e) {
        error_log('product_details_from_db failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return [];
    }
}

/**
 * @param array<int, array{id?:int|string, title?:string, body?:string}> $sections
 */
function product_details_save(array $sections): bool
{
    if (!product_details_ensure_table()) {
        return false;
    }

    $pdo = db();
    if (!$pdo) {
        return false;
    }

    try {
        $pdo->beginTransaction();
        $upd = $pdo->prepare(
            'UPDATE product_common_details SET heading = ?, body = ?, sort_order = ? WHERE id = ?'
        );
        $ins = $pdo->prepare(
            'INSERT INTO product_common_details (heading, body, sort_order) VALUES (?, ?, ?)'
        );

        foreach (array_values($sections) as $i => $section) {
            $id = (int) ($section['id'] ?? 0);
            $title = trim((string) ($section['title'] ?? ''));
            $body = trim((string) ($section['body'] ?? ''));
            $order = $i + 1;

            if ($id > 0) {
                $upd->execute([$title, $body, $order, $id]);
            } elseif ($title !== '' || $body !== '') {
                $ins->execute([$title, $body, $order]);
            }
        }

        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('product_details_save failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return false;
    }
}

function product_delete(string $id): bool
{
    $pdo = db();
    if (!$pdo || $id === '') {
        return false;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    } catch (Throwable $e) {
        error_log('product_delete failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return false;
    }
}
