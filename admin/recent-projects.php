<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = $_POST['selected'] ?? [];
    if (!is_array($selected)) {
        $selected = [];
    }
    $sorts = $_POST['sort_order'] ?? [];
    $bestsellers = $_POST['bestseller'] ?? [];
    if (!is_array($sorts)) {
        $sorts = [];
    }
    if (!is_array($bestsellers)) {
        $bestsellers = [];
    }

    $items = [];
    foreach ($selected as $productId) {
        $productId = trim((string) $productId);
        if ($productId === '') {
            continue;
        }
        $items[] = [
            'product_id' => $productId,
            'sort_order' => (int) ($sorts[$productId] ?? 0),
            'is_bestseller' => !empty($bestsellers[$productId]),
        ];
    }

    usort($items, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);

    if (recent_project_items_save($items)) {
        $_SESSION['admin_flash'] = count($items)
            ? ('Recent projects updated (' . count($items) . ' selected).')
            : 'Recent projects cleared — the page will show an empty grid until you select products.';
        header('Location: ' . admin_url('recent-projects.php'));
        exit;
    }
    $error = db_last_error() ?: 'Could not save recent projects.';
}

$flash = $_SESSION['admin_flash'] ?? '';
unset($_SESSION['admin_flash']);

$picks = recent_project_items_from_db();
$tableMissing = $picks === null;
$picks = $picks ?? [];
$pickMap = [];
foreach ($picks as $pick) {
    $pickMap[(string) $pick['product_id']] = $pick;
}

// Table exists but nothing saved yet — pre-select the same default grid the site shows
$usingDefaults = !$tableMissing && $pickMap === [];
if ($usingDefaults) {
    foreach (recent_projects_legacy_fallback() as $i => $item) {
        $pid = (string) ($item['id'] ?? '');
        if ($pid === '') {
            continue;
        }
        $pickMap[$pid] = [
            'product_id' => $pid,
            'sort_order' => ($i + 1) * 10,
            'is_bestseller' => !empty($item['is_bestseller']) ? 1 : 0,
        ];
    }
}

$products = products_from_db(true) ?? [];
usort($products, function ($a, $b) use ($pickMap) {
    $aOn = isset($pickMap[(string) $a['id']]);
    $bOn = isset($pickMap[(string) $b['id']]);
    if ($aOn !== $bOn) {
        return $aOn ? -1 : 1;
    }
    if ($aOn && $bOn) {
        return ((int) $pickMap[(string) $a['id']]['sort_order']) <=> ((int) $pickMap[(string) $b['id']]['sort_order']);
    }
    return ((int) ($a['sort_order'] ?? 0)) <=> ((int) ($b['sort_order'] ?? 0));
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent projects — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(admin_asset('admin.css')) ?>">
</head>
<body class="admin">
    <?php $admin_nav = 'recent-projects'; include __DIR__ . '/header.php'; ?>

    <div class="admin-shell edit-shell">
        <div class="admin-top edit-top">
            <div>
                <p class="edit-kicker">Website page</p>
                <h1>Recent projects</h1>
                <p>Choose which catalogue products appear on Recent Projects, set their order, and mark Best Sellers.</p>
            </div>
            <?php include __DIR__ . '/page-actions.php'; ?>
        </div>

        <?php if ($flash): ?>
            <p class="flash"><?= e($flash) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="flash flash--error"><?= e($error) ?></p>
        <?php endif; ?>
        <?php if ($tableMissing): ?>
            <p class="flash flash--error">Run <code>database/admin-galleries.sql</code> in phpMyAdmin first.</p>
        <?php elseif (!empty($usingDefaults)): ?>
            <p class="flash">Showing the previous default Recent Projects selection. Click <strong>Save recent projects</strong> to store it, or change the ticks / Best Seller flags first.</p>
        <?php endif; ?>

        <?php if (!$tableMissing): ?>
        <form class="edit-form" method="post" data-recent-form>
            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">1</span>
                    <div>
                        <h2>Select products</h2>
                        <p>Tick a product to show it on Recent Projects. Use sort order for the grid sequence. Best Seller adds the badge on that page only.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="recent-toolbar">
                        <p><span data-selected-count><?= count($pickMap) ?></span> selected</p>
                        <div class="recent-toolbar__actions">
                            <button type="button" class="btn btn--ghost btn--sm" data-select-all>Select all available</button>
                            <button type="button" class="btn btn--ghost btn--sm" data-clear-all>Clear all</button>
                        </div>
                    </div>

                    <?php if (!$products): ?>
                        <p class="empty-inline">No products in the catalogue yet. <a href="<?= e(admin_url('edit.php')) ?>">Add a product</a> first.</p>
                    <?php else: ?>
                        <div class="recent-pick-list">
                            <?php foreach ($products as $item): ?>
                                <?php
                                $pid = (string) $item['id'];
                                $checked = isset($pickMap[$pid]);
                                $sort = $checked ? (int) $pickMap[$pid]['sort_order'] : (int) ($item['sort_order'] ?? 0);
                                $best = $checked ? !empty($pickMap[$pid]['is_bestseller']) : false;
                                $sold = ($item['status'] ?? '') === 'sold';
                                ?>
                                <article class="recent-pick<?= $checked ? ' is-selected' : '' ?><?= $sold ? ' is-sold' : '' ?>">
                                    <label class="recent-pick__check">
                                        <input
                                            type="checkbox"
                                            name="selected[]"
                                            value="<?= e($pid) ?>"
                                            data-recent-check
                                            <?= $checked ? 'checked' : '' ?>
                                            <?= $sold ? 'disabled' : '' ?>
                                        >
                                        <span class="recent-pick__thumb">
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="<?= e(asset((string) $item['image'])) ?>" alt="">
                                            <?php endif; ?>
                                        </span>
                                        <span class="recent-pick__meta">
                                            <strong><?= e((string) $item['title']) ?></strong>
                                            <small>
                                                <?= e((string) ($item['status'] ?? 'available')) ?>
                                                <?php if (!empty($item['featured'])): ?> · featured on home<?php endif; ?>
                                                <?php if ($sold): ?> · sold (cannot show)<?php endif; ?>
                                            </small>
                                        </span>
                                    </label>
                                    <div class="recent-pick__controls">
                                        <div class="field field--mini">
                                            <label for="sort-<?= e($pid) ?>">Sort</label>
                                            <input id="sort-<?= e($pid) ?>" type="number" name="sort_order[<?= e($pid) ?>]" value="<?= e((string) $sort) ?>" <?= $sold ? 'disabled' : '' ?>>
                                        </div>
                                        <label class="edit-check">
                                            <input type="checkbox" name="bestseller[<?= e($pid) ?>]" value="1" <?= $best ? 'checked' : '' ?> <?= $sold ? 'disabled' : '' ?>>
                                            <span>Best Seller</span>
                                        </label>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <div class="edit-actions">
                <button class="btn btn--primary" type="submit">Save recent projects</button>
                <a class="btn btn--ghost" href="<?= e(page_url('recent-projects.php')) ?>" target="_blank" rel="noopener">Preview page</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script>
    (function () {
      var form = document.querySelector("[data-recent-form]");
      if (!form) return;
      var countEl = form.querySelector("[data-selected-count]");

      function refresh() {
        var checks = form.querySelectorAll("[data-recent-check]:not(:disabled)");
        var n = 0;
        checks.forEach(function (input) {
          var row = input.closest(".recent-pick");
          if (row) row.classList.toggle("is-selected", input.checked);
          if (input.checked) n += 1;
        });
        if (countEl) countEl.textContent = String(n);
      }

      form.addEventListener("change", function (e) {
        if (e.target && e.target.matches("[data-recent-check]")) refresh();
      });

      var selectAll = form.querySelector("[data-select-all]");
      var clearAll = form.querySelector("[data-clear-all]");
      if (selectAll) {
        selectAll.addEventListener("click", function () {
          form.querySelectorAll("[data-recent-check]:not(:disabled)").forEach(function (input) {
            input.checked = true;
          });
          refresh();
        });
      }
      if (clearAll) {
        clearAll.addEventListener("click", function () {
          form.querySelectorAll("[data-recent-check]").forEach(function (input) {
            input.checked = false;
          });
          refresh();
        });
      }
      refresh();
    })();
    </script>
</body>
</html>
