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
function products_has_additional_details_column(): bool
{
    static $has = null;
    if ($has !== null) {
        return $has;
    }

    $pdo = db();
    if (!$pdo) {
        $has = false;
        return false;
    }

    try {
        $has = (bool) $pdo->query("SHOW COLUMNS FROM products LIKE 'product_additional_details'")->fetch();
    } catch (Throwable $e) {
        $has = false;
    }

    return $has;
}

function products_has_original_pricing_columns(): bool
{
    static $has = null;
    if ($has !== null) {
        return $has;
    }

    $pdo = db();
    if (!$pdo) {
        $has = false;
        return false;
    }

    try {
        $has = (bool) $pdo->query("SHOW COLUMNS FROM products LIKE 'original_price'")->fetch();
    } catch (Throwable $e) {
        $has = false;
    }

    return $has;
}

function products_from_db(bool $includeAll = false): ?array
{
    $pdo = db();
    if (!$pdo) {
        return null;
    }

    try {
        $detailsCol = products_has_additional_details_column() ? 'product_additional_details, ' : '';
        $originalCols = products_has_original_pricing_columns()
            ? 'original_dimensions, original_price, original_compare_at, '
            : '';
        $sql = "SELECT id, title, description, {$detailsCol}{$originalCols}image, image_alt, tags, status, featured, default_size, sort_order
             FROM products";
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

    $fallbackOriginal = (float) ($ordered['A1']['price'] ?? 0);
    $fallbackCompare = $ordered['A1']['compare_at'] ?? null;
    $originalPrice = array_key_exists('original_price', $row) && $row['original_price'] !== null
        ? (float) $row['original_price']
        : $fallbackOriginal;
    $originalCompare = array_key_exists('original_compare_at', $row) && $row['original_compare_at'] !== null
        ? (float) $row['original_compare_at']
        : ($fallbackCompare !== null ? (float) $fallbackCompare : null);
    $originalDimensions = trim((string) ($row['original_dimensions'] ?? ''));
    if ($originalDimensions === '') {
        $originalDimensions = defined('DEFAULT_ORIGINAL_DIMENSIONS')
            ? DEFAULT_ORIGINAL_DIMENSIONS
            : '10 inch × 13 inch';
    }

    return [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'] ?? '',
        'additional_details' => product_additional_details_decode($row['product_additional_details'] ?? ''),
        'original_dimensions' => $originalDimensions,
        'original_price' => $originalPrice,
        'original_compare_at' => $originalCompare,
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
        $detailsCol = products_has_additional_details_column() ? 'product_additional_details, ' : '';
        $originalCols = products_has_original_pricing_columns()
            ? 'original_dimensions, original_price, original_compare_at, '
            : '';
        $stmt = $pdo->prepare(
            "SELECT id, title, description, {$detailsCol}{$originalCols}image, image_alt, tags, status, featured, default_size, sort_order
             FROM products WHERE id = ? LIMIT 1"
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
    if (!products_has_additional_details_column()) {
        $GLOBALS['_db_last_error'] = 'Run database/product-additional-details.sql first to add product_additional_details.';
        return null;
    }

    $additionalDetails = product_additional_details_encode($data['additional_details'] ?? []);
    $image = trim((string) ($data['image'] ?? 'images/artwork/krishna-petals.jpeg'));
    if ($image === '') {
        $image = 'images/artwork/krishna-petals.jpeg';
    }
    $imageAlt = trim((string) ($data['image_alt'] ?? $title));
    $status = ($data['status'] ?? 'available') === 'sold' ? 'sold' : 'available';
    $featured = !empty($data['featured']) ? 1 : 0;
    $defaultSize = size_store_code((string) ($data['default_size'] ?? 'A1'));
    $sortOrder = (int) ($data['sort_order'] ?? 0);

    $originalDimensions = trim((string) ($data['original_dimensions'] ?? ''));
    if ($originalDimensions === '') {
        $originalDimensions = defined('DEFAULT_ORIGINAL_DIMENSIONS')
            ? DEFAULT_ORIGINAL_DIMENSIONS
            : '10 inch × 13 inch';
    }
    $originalPrice = isset($data['original_price']) && $data['original_price'] !== ''
        ? (float) $data['original_price']
        : 0.0;
    $originalCompare = isset($data['original_compare_at']) && $data['original_compare_at'] !== ''
        ? (float) $data['original_compare_at']
        : null;

    $tags = $data['tags'] ?? [];
    if (!is_array($tags)) {
        $tags = [];
    }
    $allowedTags = array_merge(['original', 'print'], array_keys(shop_categories()));
    $tags = array_values(array_intersect($tags, $allowedTags));
    if ($tags === []) {
        $GLOBALS['_db_last_error'] = 'Select at least Original or Print.';
        return null;
    }
    $tagsJson = json_encode($tags, JSON_UNESCAPED_UNICODE);
    $isOriginal = in_array('original', $tags, true);
    $isPrint = in_array('print', $tags, true);

    if ($isOriginal && products_has_original_pricing_columns() && $originalPrice <= 0) {
        $GLOBALS['_db_last_error'] = 'Original price is required.';
        return null;
    }

    $sizeMeta = [
        'S' => 'A1',
        'M' => 'A2',
        'L' => 'A3',
    ];
    $sizesIn = is_array($data['sizes'] ?? null) ? $data['sizes'] : [];

    if ($isPrint) {
        $hasPrintPrice = false;
        foreach ($sizeMeta as $display) {
            $row = $sizesIn[$display] ?? [];
            if ((float) ($row['price'] ?? 0) > 0) {
                $hasPrintPrice = true;
                break;
            }
        }
        if (!$hasPrintPrice) {
            $GLOBALS['_db_last_error'] = 'Add at least one print size price (A1, A2, or A3).';
            return null;
        }
    }

    if (!products_has_original_pricing_columns()) {
        $GLOBALS['_db_last_error'] = 'Run database/purchase-redesign.sql first to add original price and dimensions.';
        return null;
    }

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
                'INSERT INTO products (id, title, description, product_additional_details, original_dimensions, original_price, original_compare_at, image, image_alt, tags, status, featured, default_size, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ins->execute([
                $id, $title, $description, $additionalDetails,
                $originalDimensions, $originalPrice, $originalCompare,
                $image, $imageAlt, $tagsJson,
                $status, $featured, $defaultSize, $sortOrder,
            ]);
        } else {
            $upd = $pdo->prepare(
                'UPDATE products
                 SET title = ?, description = ?, product_additional_details = ?, original_dimensions = ?, original_price = ?, original_compare_at = ?, image = ?, image_alt = ?, tags = ?, status = ?, featured = ?, default_size = ?, sort_order = ?
                 WHERE id = ?'
            );
            $upd->execute([
                $title, $description, $additionalDetails,
                $originalDimensions, $originalPrice, $originalCompare,
                $image, $imageAlt, $tagsJson,
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
            $price = $isPrint && isset($row['price']) ? (float) $row['price'] : 0.0;
            $compare = $isPrint && isset($row['compare_at']) && $row['compare_at'] !== ''
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

/**
 * Default additional-details copy used for new products and the SQL seed.
 *
 * @return array<int, array{title:string, body:string}>
 */
function product_additional_details_defaults(): array
{
    return [
        [
            'title' => 'Size and quality',
            'body' => 'Originals: see the listed dimensions on each piece (default 10 inch × 13 inch). Prints: choose A1, A2, or A3. Print quality: 300 GSM thick paper with vibrant colours. Item shape: rectangular. Frame material: engineered wood.',
        ],
        [
            'title' => 'Great for gifting',
            'body' => 'These framed posters encourage everyone to live a positive life and achieve more. Their longevity and everyday use give them something to remember you by. A thoughtful gift for a girl, man, boy, student, brother, or friend — and a perfect present for loved ones, colleagues, and friends.',
        ],
        [
            'title' => 'Reusable frames',
            'body' => 'If you want to change the artwork for another poster or photo later, you can. Remove the MDF wood board and put in a new image of your choice.',
        ],
        [
            'title' => 'Use wherever you want',
            'body' => 'These stylish picture frames work as home and office decoration, and also suit hostels, study rooms, classrooms, corridors, shops, and cafés. If you can find a wall to hang them on, they will stay and say something.',
        ],
    ];
}

/**
 * @return array<int, array{title:string, body:string}>
 */
function product_additional_details_decode(mixed $raw): array
{
    if (is_array($raw)) {
        $decoded = $raw;
    } elseif (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode($raw, true);
    } else {
        $decoded = null;
    }

    if (!is_array($decoded)) {
        return [];
    }

    $sections = [];
    foreach ($decoded as $row) {
        if (!is_array($row)) {
            continue;
        }
        $title = trim((string) ($row['title'] ?? ''));
        $body = trim((string) ($row['body'] ?? ''));
        if ($title === '' && $body === '') {
            continue;
        }
        $sections[] = [
            'title' => $title,
            'body' => $body,
        ];
    }

    return $sections;
}

/**
 * @param array<int, array{title?:string, body?:string}> $sections
 */
function product_additional_details_encode(array $sections): string
{
    $clean = [];
    foreach ($sections as $row) {
        $title = trim((string) ($row['title'] ?? ''));
        $body = trim((string) ($row['body'] ?? ''));
        if ($title === '' && $body === '') {
            continue;
        }
        $clean[] = [
            'title' => $title,
            'body' => $body,
        ];
    }

    return json_encode($clean, JSON_UNESCAPED_UNICODE) ?: '[]';
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

function table_exists(string $table): bool
{
    static $cache = [];
    if (array_key_exists($table, $cache)) {
        return $cache[$table];
    }

    $pdo = db();
    if (!$pdo || !preg_match('/^[a-z0-9_]+$/i', $table)) {
        $cache[$table] = false;
        return false;
    }

    try {
        $cache[$table] = (bool) $pdo->query("SHOW TABLES LIKE " . $pdo->quote($table))->fetch();
    } catch (Throwable $e) {
        $cache[$table] = false;
    }

    return $cache[$table];
}

/**
 * Upload an image into assets/images/{subdir}/ keeping the original basename.
 *
 * @param array<string, mixed>|null $file
 */
function admin_upload_image(?array $file, string $subdir = 'artwork'): ?string
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

    if ((int) ($file['size'] ?? 0) > 5 * 1024 * 1024) {
        $GLOBALS['_db_last_error'] = 'Image must be 5MB or smaller.';
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
        $GLOBALS['_db_last_error'] = 'Use a JPG, PNG, or WebP image.';
        return null;
    }

    $subdir = trim(str_replace(['..', '\\'], '', $subdir), '/');
    if ($subdir === '') {
        $subdir = 'artwork';
    }

    $filename = basename(str_replace(["\0", '\\'], '', (string) ($file['name'] ?? '')));
    $filename = trim($filename);
    if ($filename === '' || str_contains($filename, '..')) {
        $GLOBALS['_db_last_error'] = 'Invalid image filename.';
        return null;
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        $GLOBALS['_db_last_error'] = 'Filename must end with .jpg, .jpeg, .png, or .webp.';
        return null;
    }

    $dir = dirname(__DIR__) . '/assets/images/' . $subdir;
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        $GLOBALS['_db_last_error'] = 'Could not access image folder.';
        return null;
    }

    $dest = $dir . '/' . $filename;
    if (is_file($dest)) {
        $stem = pathinfo($filename, PATHINFO_FILENAME);
        $filename = $stem . '-' . date('YmdHis') . '.' . $ext;
        $dest = $dir . '/' . $filename;
    }

    if (!move_uploaded_file($tmp, $dest)) {
        $GLOBALS['_db_last_error'] = 'Could not save uploaded image.';
        return null;
    }

    @chmod($dest, 0644);
    return 'images/' . $subdir . '/' . $filename;
}

/**
 * @return array<int, array<string, mixed>>|null null when table missing / DB error
 */
function student_work_from_db(bool $activeOnly = true): ?array
{
    if (!table_exists('student_work')) {
        return null;
    }

    $pdo = db();
    if (!$pdo) {
        return null;
    }

    try {
        $sql = 'SELECT id, image, image_alt, sort_order, is_active FROM student_work';
        if ($activeOnly) {
            $sql .= ' WHERE is_active = 1';
        }
        $sql .= ' ORDER BY sort_order ASC, id ASC';
        return $pdo->query($sql)->fetchAll() ?: [];
    } catch (Throwable $e) {
        error_log('student_work_from_db failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

/**
 * @param array<string, mixed> $data
 */
function student_work_save(array $data, ?int $id = null): ?int
{
    if (!table_exists('student_work')) {
        $GLOBALS['_db_last_error'] = 'Run database/admin-galleries.sql first.';
        return null;
    }

    $pdo = db();
    if (!$pdo) {
        return null;
    }

    $image = trim((string) ($data['image'] ?? ''));
    $alt = trim((string) ($data['image_alt'] ?? ''));
    $sort = (int) ($data['sort_order'] ?? 0);
    $active = !empty($data['is_active']) ? 1 : 0;

    if ($image === '') {
        $GLOBALS['_db_last_error'] = 'Image is required.';
        return null;
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE student_work SET image = ?, image_alt = ?, sort_order = ?, is_active = ? WHERE id = ?'
            );
            $stmt->execute([$image, $alt !== '' ? $alt : null, $sort, $active, $id]);
            return $id;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO student_work (image, image_alt, sort_order, is_active) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$image, $alt !== '' ? $alt : null, $sort, $active]);
        return (int) $pdo->lastInsertId();
    } catch (Throwable $e) {
        error_log('student_work_save failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

function student_work_delete(int $id): bool
{
    if (!table_exists('student_work') || $id < 1) {
        return false;
    }

    $pdo = db();
    if (!$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM student_work WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    } catch (Throwable $e) {
        error_log('student_work_delete failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return false;
    }
}

/**
 * @return array<int, array<string, mixed>>|null
 */
function recent_project_items_from_db(): ?array
{
    if (!table_exists('recent_project_items')) {
        return null;
    }

    $pdo = db();
    if (!$pdo) {
        return null;
    }

    try {
        $rows = $pdo->query(
            'SELECT r.product_id, r.sort_order, r.is_bestseller
             FROM recent_project_items r
             INNER JOIN products p ON p.id = r.product_id
             ORDER BY r.sort_order ASC, p.title ASC'
        )->fetchAll() ?: [];
        return $rows;
    } catch (Throwable $e) {
        error_log('recent_project_items_from_db failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return null;
    }
}

/**
 * Replace the recent-projects selection.
 *
 * @param array<int, array{product_id:string, sort_order?:int, is_bestseller?:bool}> $items
 */
function recent_project_items_save(array $items): bool
{
    if (!table_exists('recent_project_items')) {
        $GLOBALS['_db_last_error'] = 'Run database/admin-galleries.sql first.';
        return false;
    }

    $pdo = db();
    if (!$pdo) {
        return false;
    }

    $clean = [];
    foreach ($items as $item) {
        $pid = trim((string) ($item['product_id'] ?? ''));
        if ($pid === '' || isset($clean[$pid])) {
            continue;
        }
        $clean[$pid] = [
            'product_id' => $pid,
            'sort_order' => (int) ($item['sort_order'] ?? 0),
            'is_bestseller' => !empty($item['is_bestseller']) ? 1 : 0,
        ];
    }

    try {
        $pdo->beginTransaction();
        $pdo->exec('DELETE FROM recent_project_items');
        if ($clean !== []) {
            $ins = $pdo->prepare(
                'INSERT INTO recent_project_items (product_id, sort_order, is_bestseller) VALUES (?, ?, ?)'
            );
            foreach ($clean as $row) {
                $ins->execute([$row['product_id'], $row['sort_order'], $row['is_bestseller']]);
            }
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('recent_project_items_save failed: ' . $e->getMessage());
        $GLOBALS['_db_last_error'] = $e->getMessage();
        return false;
    }
}

/**
 * Products selected for the Recent Projects page (with bestseller flag).
 *
 * @return array<int, array<string, mixed>>
 */
function recent_projects_products(): array
{
    $picks = recent_project_items_from_db();
    if ($picks === null) {
        // Table missing — legacy fallback
        $available = array_values(array_filter(products(), fn($a) => ($a['status'] ?? 'available') !== 'sold'));
        $featured = array_values(array_filter($available, fn($a) => !empty($a['featured'])));
        $rest = array_values(array_filter($available, fn($a) => empty($a['featured'])));
        $merged = array_slice(array_merge($featured, $rest), 0, 6);
        foreach ($merged as &$item) {
            $item['is_bestseller'] = !empty($item['featured']);
        }
        unset($item);
        return $merged;
    }

    if ($picks === []) {
        return [];
    }

    $byId = [];
    foreach (products_from_db(true) ?? [] as $product) {
        $byId[(string) $product['id']] = $product;
    }

    $out = [];
    foreach ($picks as $pick) {
        $id = (string) ($pick['product_id'] ?? '');
        if ($id === '' || !isset($byId[$id])) {
            continue;
        }
        if (($byId[$id]['status'] ?? 'available') === 'sold') {
            continue;
        }
        $item = $byId[$id];
        $item['is_bestseller'] = !empty($pick['is_bestseller']);
        $item['recent_sort'] = (int) ($pick['sort_order'] ?? 0);
        $out[] = $item;
    }

    return $out;
}
