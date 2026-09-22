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
             ORDER BY FIELD(size_code, \'S\', \'M\', \'L\'), size_code ASC'
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
 * @param array<string, mixed> $row
 * @return array<string, mixed>
 */
function map_product_row(array $row, PDOStatement $sizeStmt): array
{
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

    return [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'] ?? '',
        'image' => $row['image'],
        'image_alt' => $row['image_alt'] ?? $row['title'],
        'tags' => $tags,
        'status' => $row['status'],
        'featured' => (bool) $row['featured'],
        'default_size' => $row['default_size'] ?: 'S',
        'sort_order' => (int) ($row['sort_order'] ?? 0),
        'sizes' => $sizes,
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
             ORDER BY FIELD(size_code, \'S\', \'M\', \'L\'), size_code ASC'
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
 * Insert or update a product and its S/M/L sizes.
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
    $image = trim((string) ($data['image'] ?? 'images/artwork/image-1.jpeg'));
    if ($image === '') {
        $image = 'images/artwork/image-1.jpeg';
    }
    $imageAlt = trim((string) ($data['image_alt'] ?? $title));
    $status = ($data['status'] ?? 'available') === 'sold' ? 'sold' : 'available';
    $featured = !empty($data['featured']) ? 1 : 0;
    $defaultSize = in_array(($data['default_size'] ?? 'S'), ['S', 'M', 'L'], true)
        ? $data['default_size']
        : 'S';
    $sortOrder = (int) ($data['sort_order'] ?? 0);

    $tags = $data['tags'] ?? [];
    if (!is_array($tags)) {
        $tags = [];
    }
    $tags = array_values(array_intersect($tags, ['original', 'print']));
    $tagsJson = json_encode($tags, JSON_UNESCAPED_UNICODE);

    $sizeMeta = [
        'S' => '13"',
        'M' => '15"',
        'L' => '20"',
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

        foreach ($sizeMeta as $code => $label) {
            $price = isset($sizesIn[$code]['price']) ? (float) $sizesIn[$code]['price'] : 0;
            $compare = isset($sizesIn[$code]['compare_at']) && $sizesIn[$code]['compare_at'] !== ''
                ? (float) $sizesIn[$code]['compare_at']
                : null;
            $upsertSize->execute([$id, $code, $label, $price, $compare]);
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
