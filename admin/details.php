<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$error = '';
$flash = $_SESSION['admin_flash'] ?? '';
unset($_SESSION['admin_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted = $_POST['sections'] ?? [];
    $sections = [];
    if (is_array($posted)) {
        foreach ($posted as $row) {
            if (!is_array($row)) {
                continue;
            }
            $sections[] = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => trim((string) ($row['title'] ?? '')),
                'body' => trim((string) ($row['body'] ?? '')),
            ];
        }
    }

    if (product_details_save($sections)) {
        $_SESSION['admin_flash'] = 'Product common details updated. This text appears on every product page.';
        header('Location: ' . admin_url('details.php'));
        exit;
    }

    $error = db_last_error() ?: 'Could not save product details.';
    $items = $sections;
} else {
    $items = product_spec_sections();
}

if (!$items) {
    $items = [
        ['id' => 0, 'title' => '', 'body' => ''],
        ['id' => 0, 'title' => '', 'body' => ''],
        ['id' => 0, 'title' => '', 'body' => ''],
        ['id' => 0, 'title' => '', 'body' => ''],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product common details — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?= ASSET_VERSION ?>">
</head>
<body class="admin">
    <?php $admin_nav = 'details'; include __DIR__ . '/header.php'; ?>
    <div class="admin-shell">
        <div class="admin-top">
            <div>
                <h1>Product common details</h1>
                <p>Shared copy shown on every product page — edit once, it updates everywhere.</p>
            </div>
        </div>

        <?php if ($flash): ?>
            <p class="flash"><?= e($flash) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="flash flash--error"><?= e($error) ?></p>
        <?php endif; ?>

        <form class="panel" method="post">
            <div class="form-grid">
                <?php foreach ($items as $i => $section): ?>
                    <div class="field">
                        <input type="hidden" name="sections[<?= (int) $i ?>][id]" value="<?= e((string) ($section['id'] ?? 0)) ?>">
                        <label for="heading-<?= (int) $i ?>">Section <?= (int) $i + 1 ?> heading</label>
                        <input id="heading-<?= (int) $i ?>" name="sections[<?= (int) $i ?>][title]" value="<?= e($section['title'] ?? '') ?>">
                        <label for="body-<?= (int) $i ?>" style="margin-top:0.85rem;">Section <?= (int) $i + 1 ?> text</label>
                        <textarea id="body-<?= (int) $i ?>" name="sections[<?= (int) $i ?>][body]" rows="5"><?= e($section['body'] ?? '') ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-footer">
                <button class="btn btn--primary" type="submit">Save common details</button>
                <a class="btn btn--ghost" href="products.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
