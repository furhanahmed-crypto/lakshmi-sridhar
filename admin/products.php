<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$products = products_from_db(true);
$dbError = $products === null ? (db_last_error() ?: 'Could not load products.') : null;
$products = $products ?? [];
$flash = $_SESSION['admin_flash'] ?? '';
unset($_SESSION['admin_flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css?v=<?= ASSET_VERSION ?>">
</head>
<body class="admin">
    <div class="admin-shell">
        <div class="admin-top">
            <div>
                <h1>Products</h1>
                <p><?= count($products) ?> item<?= count($products) === 1 ? '' : 's' ?> in the catalogue</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn--primary" href="edit.php">Add product</a>
                <a class="btn btn--ghost" href="<?= page_url('index.php') ?>" target="_blank" rel="noopener">View site</a>
                <a class="btn btn--ghost" href="logout.php">Log out</a>
            </div>
        </div>

        <?php if ($flash): ?>
            <p class="flash"><?= e($flash) ?></p>
        <?php endif; ?>

        <?php if ($dbError): ?>
            <p class="flash flash--error"><?= e($dbError) ?></p>
        <?php endif; ?>

        <div class="panel">
            <?php if (!$dbError && !$products): ?>
                <p class="empty">No products yet. <a href="edit.php">Add the first one</a>.</p>
            <?php elseif ($products): ?>
                <div class="table-wrap">
                    <table class="products">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>S</th>
                                <th>M</th>
                                <th>L</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $item): ?>
                                <?php
                                $s = $item['sizes']['S'] ?? ['price' => 0, 'compare_at' => null];
                                $m = $item['sizes']['M'] ?? ['price' => 0, 'compare_at' => null];
                                $l = $item['sizes']['L'] ?? ['price' => 0, 'compare_at' => null];
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($item['image'])): ?>
                                            <img class="thumb" src="<?= asset($item['image']) ?>" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <h2 class="product-title"><?= e($item['title']) ?></h2>
                                        <p class="product-desc"><?= e($item['description']) ?></p>
                                        <div class="tags">
                                            <?php foreach ($item['tags'] as $tag): ?>
                                                <span class="tag"><?= e($tag) ?></span>
                                            <?php endforeach; ?>
                                            <?php if (($item['status'] ?? '') === 'sold'): ?>
                                                <span class="tag">sold</span>
                                            <?php endif; ?>
                                            <?php if (!empty($item['featured'])): ?>
                                                <span class="tag">featured</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="price-stack">
                                            <?php if (!empty($s['compare_at'])): ?><span class="was"><?= e(format_money($s['compare_at'])) ?></span><?php endif; ?>
                                            <span><?= e(format_money($s['price'] ?? 0)) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="price-stack">
                                            <?php if (!empty($m['compare_at'])): ?><span class="was"><?= e(format_money($m['compare_at'])) ?></span><?php endif; ?>
                                            <span><?= e(format_money($m['price'] ?? 0)) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="price-stack">
                                            <?php if (!empty($l['compare_at'])): ?><span class="was"><?= e(format_money($l['compare_at'])) ?></span><?php endif; ?>
                                            <span><?= e(format_money($l['price'] ?? 0)) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            <a class="btn btn--ghost btn--sm" href="edit.php?id=<?= e(urlencode($item['id'])) ?>">Edit</a>
                                            <form method="post" action="delete.php" onsubmit="return confirm('Delete this product?');">
                                                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                <button class="btn btn--danger btn--sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
