<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$id = trim((string) ($_GET['id'] ?? ''));
$isNew = $id === '';
$product = $isNew ? null : product_by_id($id);
$error = '';

if (!$isNew && !$product) {
    $_SESSION['admin_flash'] = 'Product not found.';
    header('Location: products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tags = [];
    if (!empty($_POST['tag_original'])) {
        $tags[] = 'original';
    }
    if (!empty($_POST['tag_print'])) {
        $tags[] = 'print';
    }

    $currentImage = (!$isNew && $product) ? (string) ($product['image'] ?? '') : 'images/artwork/image-1.jpeg';
    $uploaded = product_upload_image($_FILES['image'] ?? null, $isNew ? product_slug((string) ($_POST['title'] ?? 'product')) : $id);
    if ($uploaded === null && !empty($_FILES['image']['name']) && (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $error = db_last_error() ?: 'Image upload failed.';
    } else {
        $payload = [
            'id' => $isNew ? '' : $id,
            'title' => trim((string) ($_POST['title'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'image' => $uploaded ?: $currentImage,
            'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
            'status' => ($_POST['status'] ?? 'available') === 'sold' ? 'sold' : 'available',
            'featured' => !empty($_POST['featured']),
            'default_size' => $_POST['default_size'] ?? 'S',
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'tags' => $tags,
            'sizes' => [
                'S' => [
                    'price' => $_POST['price_s'] ?? 0,
                    'compare_at' => $_POST['compare_s'] ?? '',
                ],
                'M' => [
                    'price' => $_POST['price_m'] ?? 0,
                    'compare_at' => $_POST['compare_m'] ?? '',
                ],
                'L' => [
                    'price' => $_POST['price_l'] ?? 0,
                    'compare_at' => $_POST['compare_l'] ?? '',
                ],
            ],
        ];

        $savedId = product_save($payload, $isNew);
        if ($savedId) {
            $_SESSION['admin_flash'] = $isNew ? 'Product added.' : 'Product updated.';
            header('Location: products.php');
            exit;
        }

        $error = db_last_error() ?: 'Could not save product.';
    }

    $product = array_merge($product ?? [], [
        'title' => trim((string) ($_POST['title'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'image' => $uploaded ?: $currentImage,
        'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
        'status' => ($_POST['status'] ?? 'available') === 'sold' ? 'sold' : 'available',
        'featured' => !empty($_POST['featured']),
        'default_size' => $_POST['default_size'] ?? 'S',
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        'tags' => $tags,
        'sizes' => [
            'S' => ['label' => '13"', 'price' => (float) ($_POST['price_s'] ?? 0), 'compare_at' => ($_POST['compare_s'] ?? '') !== '' ? (float) $_POST['compare_s'] : null],
            'M' => ['label' => '15"', 'price' => (float) ($_POST['price_m'] ?? 0), 'compare_at' => ($_POST['compare_m'] ?? '') !== '' ? (float) $_POST['compare_m'] : null],
            'L' => ['label' => '20"', 'price' => (float) ($_POST['price_l'] ?? 0), 'compare_at' => ($_POST['compare_l'] ?? '') !== '' ? (float) $_POST['compare_l'] : null],
        ],
    ]);
}

$product = $product ?? [
    'title' => '',
    'description' => '',
    'image_alt' => '',
    'status' => 'available',
    'featured' => false,
    'default_size' => 'S',
    'sort_order' => 0,
    'tags' => ['original', 'print'],
    'sizes' => [
        'S' => ['label' => '13"', 'price' => 89, 'compare_at' => 129],
        'M' => ['label' => '15"', 'price' => 119, 'compare_at' => 159],
        'L' => ['label' => '20"', 'price' => 149, 'compare_at' => 199],
    ],
];

$tags = $product['tags'] ?? [];
$s = $product['sizes']['S'] ?? ['price' => 0, 'compare_at' => null];
$m = $product['sizes']['M'] ?? ['price' => 0, 'compare_at' => null];
$l = $product['sizes']['L'] ?? ['price' => 0, 'compare_at' => null];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isNew ? 'Add product' : 'Edit product' ?> — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?= ASSET_VERSION ?>">
</head>
<body class="admin">
    <div class="admin-shell">
        <div class="admin-top">
            <div>
                <h1><?= $isNew ? 'Add product' : 'Edit product' ?></h1>
                <p><?= $isNew ? 'Create a new catalogue item' : e($product['title'] ?? '') ?></p>
            </div>
            <div class="admin-actions">
                <a class="btn btn--ghost" href="products.php">Back to list</a>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="flash flash--error"><?= e($error) ?></p>
        <?php endif; ?>

        <form class="panel" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" name="title" required value="<?= e($product['title'] ?? '') ?>">
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?= e($product['description'] ?? '') ?></textarea>
                </div>

                <div class="field">
                    <label for="image">Product image</label>
                    <?php if (!empty($product['image'])): ?>
                        <div class="image-preview">
                            <img src="<?= asset($product['image']) ?>" alt="<?= e($product['image_alt'] ?? $product['title'] ?? '') ?>">
                        </div>
                    <?php endif; ?>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                    <p class="field-hint"><?= $isNew ? 'Saved as-is into assets/images/artwork/ using your filename · JPG, PNG, or WebP · max 5MB' : 'Leave empty to keep current image. New upload keeps your exact filename in artwork/ · JPG, PNG, or WebP · max 5MB' ?></p>
                </div>

                <div class="form-grid form-grid--2" style="padding:0;gap:1rem;">
                    <div class="field">
                        <label for="image_alt">Image alt text</label>
                        <input id="image_alt" name="image_alt" value="<?= e($product['image_alt'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label for="sort_order">Sort order</label>
                        <input id="sort_order" name="sort_order" type="number" value="<?= e((string) ($product['sort_order'] ?? 0)) ?>">
                    </div>
                </div>

                <div class="form-grid form-grid--2" style="padding:0;gap:1rem;">
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="available" <?= ($product['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="sold" <?= ($product['status'] ?? '') === 'sold' ? 'selected' : '' ?>>Sold</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="default_size">Default size</label>
                        <select id="default_size" name="default_size">
                            <?php foreach (['S', 'M', 'L'] as $code): ?>
                                <option value="<?= $code ?>" <?= ($product['default_size'] ?? 'S') === $code ? 'selected' : '' ?>><?= $code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Visibility</label>
                    <div class="check-row">
                        <label><input type="checkbox" name="featured" value="1" <?= !empty($product['featured']) ? 'checked' : '' ?>> Featured on home</label>
                        <label><input type="checkbox" name="tag_original" value="1" <?= in_array('original', $tags, true) ? 'checked' : '' ?>> Original</label>
                        <label><input type="checkbox" name="tag_print" value="1" <?= in_array('print', $tags, true) ? 'checked' : '' ?>> Print</label>
                    </div>
                </div>

                <div class="field" style="margin-bottom:0;">
                    <label>Size prices</label>
                    <div class="sizes-grid">
                        <?php
                        $sizeFields = [
                            'S' => [$s, 'price_s', 'compare_s'],
                            'M' => [$m, 'price_m', 'compare_m'],
                            'L' => [$l, 'price_l', 'compare_l'],
                        ];
                        foreach ($sizeFields as $code => [$size, $priceName, $compareName]):
                        ?>
                            <div class="size-card">
                                <h3><?= e($code) ?> · <?= e($size['label'] ?? $code) ?></h3>
                                <div class="field">
                                    <label for="<?= e($priceName) ?>">Price (€)</label>
                                    <input id="<?= e($priceName) ?>" name="<?= e($priceName) ?>" type="number" min="0" step="1" required value="<?= e((string) (int) ($size['price'] ?? 0)) ?>">
                                </div>
                                <div class="field" style="margin-bottom:0;">
                                    <label for="<?= e($compareName) ?>">Compare at (€)</label>
                                    <input id="<?= e($compareName) ?>" name="<?= e($compareName) ?>" type="number" min="0" step="1" value="<?= e($size['compare_at'] !== null && $size['compare_at'] !== '' ? (string) (int) $size['compare_at'] : '') ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button class="btn btn--primary" type="submit"><?= $isNew ? 'Add product' : 'Save changes' ?></button>
                <a class="btn btn--ghost" href="products.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
