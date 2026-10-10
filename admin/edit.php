<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$id = trim((string) ($_GET['id'] ?? ''));
$isNew = $id === '';
$product = $isNew ? null : product_by_id($id);
$error = '';

if (!$isNew && !$product) {
    $_SESSION['admin_flash'] = 'Product not found.';
    header('Location: ' . admin_url('products.php'));
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
    $categoryTag = trim((string) ($_POST['category'] ?? ''));
    if ($categoryTag !== '' && isset(shop_categories()[$categoryTag])) {
        $tags[] = $categoryTag;
    }

    $additionalDetails = [];
    $postedDetails = $_POST['additional_details'] ?? [];
    if (is_array($postedDetails)) {
        foreach ($postedDetails as $row) {
            if (!is_array($row)) {
                continue;
            }
            $additionalDetails[] = [
                'title' => trim((string) ($row['title'] ?? '')),
                'body' => trim((string) ($row['body'] ?? '')),
            ];
        }
    }

    $currentImage = (!$isNew && $product) ? (string) ($product['image'] ?? '') : 'images/artwork/krishna-petals.jpeg';
    $uploaded = product_upload_image($_FILES['image'] ?? null, $isNew ? product_slug((string) ($_POST['title'] ?? 'product')) : $id);
    if ($uploaded === null && !empty($_FILES['image']['name']) && (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $error = db_last_error() ?: 'Image upload failed.';
    } else {
        $payload = [
            'id' => $isNew ? '' : $id,
            'title' => trim((string) ($_POST['title'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'additional_details' => $additionalDetails,
            'original_dimensions' => trim((string) ($_POST['original_dimensions'] ?? DEFAULT_ORIGINAL_DIMENSIONS)),
            'original_price' => $_POST['original_price'] ?? 0,
            'original_compare_at' => $_POST['original_compare_at'] ?? '',
            'image' => $uploaded ?: $currentImage,
            'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
            'status' => ($_POST['status'] ?? 'available') === 'sold' ? 'sold' : 'available',
            'featured' => !empty($_POST['featured']),
            'default_size' => $_POST['default_size'] ?? 'A1',
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'tags' => $tags,
            'sizes' => [
                'A1' => [
                    'price' => $_POST['price_a1'] ?? 0,
                    'compare_at' => $_POST['compare_a1'] ?? '',
                ],
                'A2' => [
                    'price' => $_POST['price_a2'] ?? 0,
                    'compare_at' => $_POST['compare_a2'] ?? '',
                ],
                'A3' => [
                    'price' => $_POST['price_a3'] ?? 0,
                    'compare_at' => $_POST['compare_a3'] ?? '',
                ],
            ],
        ];

        $savedId = product_save($payload, $isNew);
        if ($savedId) {
            $_SESSION['admin_flash'] = $isNew ? 'Product added.' : 'Product updated.';
            header('Location: ' . admin_url('products.php'));
            exit;
        }

        $error = db_last_error() ?: 'Could not save product.';
    }

    $product = array_merge($product ?? [], [
        'title' => trim((string) ($_POST['title'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'additional_details' => $additionalDetails ?? [],
        'original_dimensions' => trim((string) ($_POST['original_dimensions'] ?? DEFAULT_ORIGINAL_DIMENSIONS)),
        'original_price' => (float) ($_POST['original_price'] ?? 0),
        'original_compare_at' => ($_POST['original_compare_at'] ?? '') !== '' ? (float) $_POST['original_compare_at'] : null,
        'image' => $uploaded ?: $currentImage,
        'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
        'status' => ($_POST['status'] ?? 'available') === 'sold' ? 'sold' : 'available',
        'featured' => !empty($_POST['featured']),
        'default_size' => $_POST['default_size'] ?? 'A1',
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        'tags' => $tags,
        'sizes' => [
            'A1' => ['label' => 'A1', 'price' => (float) ($_POST['price_a1'] ?? 0), 'compare_at' => ($_POST['compare_a1'] ?? '') !== '' ? (float) $_POST['compare_a1'] : null],
            'A2' => ['label' => 'A2', 'price' => (float) ($_POST['price_a2'] ?? 0), 'compare_at' => ($_POST['compare_a2'] ?? '') !== '' ? (float) $_POST['compare_a2'] : null],
            'A3' => ['label' => 'A3', 'price' => (float) ($_POST['price_a3'] ?? 0), 'compare_at' => ($_POST['compare_a3'] ?? '') !== '' ? (float) $_POST['compare_a3'] : null],
        ],
    ]);
}

$product = $product ?? [
    'title' => '',
    'description' => '',
    'additional_details' => product_additional_details_defaults(),
    'original_dimensions' => DEFAULT_ORIGINAL_DIMENSIONS,
    'original_price' => 89,
    'original_compare_at' => 129,
    'image_alt' => '',
    'status' => 'available',
    'featured' => false,
    'default_size' => 'A1',
    'sort_order' => 0,
    'tags' => ['original', 'print'],
    'sizes' => [
        'A1' => ['label' => 'A1', 'price' => 45, 'compare_at' => 65],
        'A2' => ['label' => 'A2', 'price' => 60, 'compare_at' => 80],
        'A3' => ['label' => 'A3', 'price' => 75, 'compare_at' => 100],
    ],
];

$defaults = product_additional_details_defaults();
$additionalDetails = array_values($product['additional_details'] ?? []);
if ($additionalDetails === []) {
    $additionalDetails = $defaults;
}
while (count($additionalDetails) < 4) {
    $additionalDetails[] = $defaults[count($additionalDetails)] ?? ['title' => '', 'body' => ''];
}

$tags = $product['tags'] ?? [];
$isOriginal = in_array('original', $tags, true);
$isPrint = in_array('print', $tags, true);
$a1 = $product['sizes']['A1'] ?? $product['sizes']['S'] ?? ['price' => 0, 'compare_at' => null];
$a2 = $product['sizes']['A2'] ?? $product['sizes']['M'] ?? ['price' => 0, 'compare_at' => null];
$a3 = $product['sizes']['A3'] ?? $product['sizes']['L'] ?? ['price' => 0, 'compare_at' => null];
$sizeFields = [
    'A1' => [$a1, 'price_a1', 'compare_a1'],
    'A2' => [$a2, 'price_a2', 'compare_a2'],
    'A3' => [$a3, 'price_a3', 'compare_a3'],
];
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
    <link rel="stylesheet" href="<?= e(admin_asset('admin.css')) ?>">
</head>
<body class="admin">
    <?php $admin_nav = 'edit'; include __DIR__ . '/header.php'; ?>

    <div class="admin-shell edit-shell">
        <div class="admin-top edit-top">
            <div>
                <p class="edit-kicker"><?= $isNew ? 'Catalogue' : 'Editing' ?></p>
                <h1><?= $isNew ? 'Add product' : 'Edit product' ?></h1>
                <p><?= $isNew ? 'Fill the sections in order. Only Original and/or Print pricing appears after you choose how to sell it.' : e($product['title'] ?? '') ?></p>
            </div>
            <div class="admin-actions">
                <a class="btn btn--ghost btn--sm" href="<?= e(admin_url('products.php')) ?>">Back to products</a>
                <?php include __DIR__ . '/page-actions.php'; ?>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="flash flash--error"><?= e($error) ?></p>
        <?php endif; ?>

        <form class="edit-form" method="post" enctype="multipart/form-data" data-product-form>
            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">1</span>
                    <div>
                        <h2>Product details</h2>
                        <p>Name and short description shown on the shop cards and product page.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="field">
                        <label for="title">Title</label>
                        <input id="title" name="title" required value="<?= e($product['title'] ?? '') ?>" placeholder="e.g. The Reader">
                    </div>
                    <div class="field field--flush">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="One or two sentences about the piece"><?= e($product['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </section>

            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">2</span>
                    <div>
                        <h2>How it is sold</h2>
                        <p>Choose Original, Print, or both. Pricing sections below match this choice.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="sell-as" role="group" aria-label="Sell as">
                        <label class="sell-as__option">
                            <input type="checkbox" name="tag_original" value="1" data-type-toggle="original" <?= $isOriginal ? 'checked' : '' ?>>
                            <span class="sell-as__box">
                                <strong>Original</strong>
                                <small>One physical size + one price on /purchase/originals</small>
                            </span>
                        </label>
                        <label class="sell-as__option">
                            <input type="checkbox" name="tag_print" value="1" data-type-toggle="print" <?= $isPrint ? 'checked' : '' ?>>
                            <span class="sell-as__box">
                                <strong>Print</strong>
                                <small>A1 / A2 / A3 prices on /purchase/prints</small>
                            </span>
                        </label>
                    </div>

                    <div class="edit-row edit-row--3">
                        <div class="field">
                            <label for="category">Collection</label>
                            <select id="category" name="category">
                                <option value="">None</option>
                                <?php foreach (shop_categories() as $slug => $cat): ?>
                                    <option value="<?= e($slug) ?>" <?= in_array($slug, $tags, true) ? 'selected' : '' ?>><?= e($cat['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="status">Availability</label>
                            <select id="status" name="status">
                                <option value="available" <?= ($product['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="sold" <?= ($product['status'] ?? '') === 'sold' ? 'selected' : '' ?>>Sold</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="sort_order">Sort order</label>
                            <input id="sort_order" name="sort_order" type="number" value="<?= e((string) ($product['sort_order'] ?? 0)) ?>">
                            <p class="field-hint">Lower numbers appear first.</p>
                        </div>
                    </div>

                    <label class="edit-check">
                        <input type="checkbox" name="featured" value="1" <?= !empty($product['featured']) ? 'checked' : '' ?>>
                        <span>Featured on the home page</span>
                    </label>
                    <p class="field-hint">Best Seller badges for Recent Projects are managed under Admin → Recent projects.</p>
                </div>
            </section>

            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">3</span>
                    <div>
                        <h2>Artwork image</h2>
                        <p>Preview updates as soon as you pick a file. Click Save to upload it.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="image-layout">
                        <div class="image-preview<?= empty($product['image']) ? ' is-empty' : '' ?>" data-image-preview>
                            <img
                                src="<?= !empty($product['image']) ? e(asset($product['image'])) : '' ?>"
                                alt="<?= e($product['image_alt'] ?? $product['title'] ?? '') ?>"
                                data-image-preview-img
                                <?= empty($product['image']) ? 'hidden' : '' ?>
                            >
                            <span class="image-preview__placeholder" data-image-preview-placeholder<?= !empty($product['image']) ? ' hidden' : '' ?>>No image</span>
                        </div>
                        <div class="image-layout__fields">
                            <div class="field">
                                <label for="image">Upload image</label>
                                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-image-input>
                                <p class="field-hint" data-image-preview-status><?= $isNew ? 'JPG, PNG, or WebP · max 5MB' : 'Leave empty to keep the current image · JPG, PNG, or WebP · max 5MB' ?></p>
                            </div>
                            <div class="field field--flush">
                                <label for="image_alt">Alt text</label>
                                <input id="image_alt" name="image_alt" value="<?= e($product['image_alt'] ?? '') ?>" placeholder="Short description for accessibility / SEO">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="edit-card" data-panel="original" <?= $isOriginal ? '' : 'hidden' ?>>
                <header class="edit-card__header">
                    <span class="edit-card__step">4</span>
                    <div>
                        <h2>Original pricing</h2>
                        <p>Used only for originals — one dimension and one price.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="edit-row edit-row--3">
                        <div class="field">
                            <label for="original_dimensions">Dimensions</label>
                            <input id="original_dimensions" name="original_dimensions" value="<?= e($product['original_dimensions'] ?? DEFAULT_ORIGINAL_DIMENSIONS) ?>" placeholder="<?= e(DEFAULT_ORIGINAL_DIMENSIONS) ?>">
                        </div>
                        <div class="field">
                            <label for="original_price">Price (€)</label>
                            <input id="original_price" name="original_price" type="number" min="0" step="1" value="<?= e((string) (int) ($product['original_price'] ?? 0)) ?>">
                        </div>
                        <div class="field">
                            <label for="original_compare_at">Compare-at price (€)</label>
                            <input id="original_compare_at" name="original_compare_at" type="number" min="0" step="1" value="<?= e(($product['original_compare_at'] ?? '') !== '' && $product['original_compare_at'] !== null ? (string) (int) $product['original_compare_at'] : '') ?>" placeholder="Optional">
                            <p class="field-hint">Shown struck-through when higher than price.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="edit-card" data-panel="print" <?= $isPrint ? '' : 'hidden' ?>>
                <header class="edit-card__header">
                    <span class="edit-card__step">5</span>
                    <div>
                        <h2>Print pricing</h2>
                        <p>Used only for prints — set A1, A2, and A3 separately.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="edit-row edit-row--3 edit-row--default-size">
                        <div class="field">
                            <label for="default_size">Default size on cards</label>
                            <select id="default_size" name="default_size">
                                <?php foreach (['A1', 'A2', 'A3'] as $code): ?>
                                    <option value="<?= $code ?>" <?= ($product['default_size'] ?? 'A1') === $code ? 'selected' : '' ?>><?= $code ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="print-sizes">
                        <?php foreach ($sizeFields as $code => [$size, $priceName, $compareName]): ?>
                            <article class="print-size">
                                <h3><?= e($code) ?></h3>
                                <div class="field">
                                    <label for="<?= e($priceName) ?>">Price (€)</label>
                                    <input id="<?= e($priceName) ?>" name="<?= e($priceName) ?>" type="number" min="0" step="1" value="<?= e((string) (int) ($size['price'] ?? 0)) ?>">
                                </div>
                                <div class="field field--flush">
                                    <label for="<?= e($compareName) ?>">Compare-at (€)</label>
                                    <input id="<?= e($compareName) ?>" name="<?= e($compareName) ?>" type="number" min="0" step="1" value="<?= e($size['compare_at'] !== null && $size['compare_at'] !== '' ? (string) (int) $size['compare_at'] : '') ?>" placeholder="Optional">
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">6</span>
                    <div>
                        <h2>Extra product details</h2>
                        <p>Optional info blocks under the buy button on the product page.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <div class="detail-grid">
                        <?php foreach ($additionalDetails as $i => $section): ?>
                            <article class="detail-block">
                                <p class="detail-block__label">Section <?= (int) $i + 1 ?></p>
                                <div class="field">
                                    <label for="detail-title-<?= (int) $i ?>">Heading</label>
                                    <input id="detail-title-<?= (int) $i ?>" name="additional_details[<?= (int) $i ?>][title]" value="<?= e($section['title'] ?? '') ?>">
                                </div>
                                <div class="field field--flush">
                                    <label for="detail-body-<?= (int) $i ?>">Text</label>
                                    <textarea id="detail-body-<?= (int) $i ?>" name="additional_details[<?= (int) $i ?>][body]" rows="3"><?= e($section['body'] ?? '') ?></textarea>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <div class="edit-actions">
                <button class="btn btn--primary" type="submit"><?= $isNew ? 'Add product' : 'Save changes' ?></button>
                <a class="btn btn--ghost" href="<?= e(admin_url('products.php')) ?>">Cancel</a>
            </div>
        </form>
    </div>

    <script>
    (function () {
      var form = document.querySelector("[data-product-form]");
      if (form) {
        function syncPanels() {
          var originalOn = !!form.querySelector('[data-type-toggle="original"]:checked');
          var printOn = !!form.querySelector('[data-type-toggle="print"]:checked');
          var originalPanel = form.querySelector('[data-panel="original"]');
          var printPanel = form.querySelector('[data-panel="print"]');
          if (originalPanel) originalPanel.hidden = !originalOn;
          if (printPanel) printPanel.hidden = !printOn;
        }
        form.querySelectorAll("[data-type-toggle]").forEach(function (el) {
          el.addEventListener("change", syncPanels);
        });
        syncPanels();
      }

      var input = document.querySelector("[data-image-input]");
      var wrap = document.querySelector("[data-image-preview]");
      var img = document.querySelector("[data-image-preview-img]");
      var placeholder = document.querySelector("[data-image-preview-placeholder]");
      var status = document.querySelector("[data-image-preview-status]");
      if (!input || !wrap || !img) return;

      var originalSrc = img.getAttribute("src") || "";
      var originalHint = status ? status.textContent : "";
      var objectUrl = null;

      function revokePreviewUrl() {
        if (objectUrl) {
          URL.revokeObjectURL(objectUrl);
          objectUrl = null;
        }
      }

      function showPreview(src, fileName) {
        img.hidden = false;
        img.src = src;
        wrap.classList.remove("is-empty");
        wrap.classList.add("is-new");
        if (placeholder) placeholder.hidden = true;
        if (status) status.textContent = "New image selected: " + fileName + " — save to apply.";
      }

      function clearPreview() {
        revokePreviewUrl();
        wrap.classList.remove("is-new");
        if (originalSrc) {
          img.src = originalSrc;
          img.hidden = false;
          wrap.classList.remove("is-empty");
          if (placeholder) placeholder.hidden = true;
        } else {
          img.removeAttribute("src");
          img.hidden = true;
          wrap.classList.add("is-empty");
          if (placeholder) placeholder.hidden = false;
        }
        if (status) status.textContent = originalHint;
      }

      input.addEventListener("change", function () {
        var file = input.files && input.files[0];
        if (!file) {
          clearPreview();
          return;
        }
        if (!file.type || file.type.indexOf("image/") !== 0) {
          clearPreview();
          if (status) status.textContent = "Please choose a JPG, PNG, or WebP image.";
          input.value = "";
          return;
        }
        revokePreviewUrl();
        objectUrl = URL.createObjectURL(file);
        showPreview(objectUrl, file.name);
      });
    })();
    </script>
</body>
</html>
