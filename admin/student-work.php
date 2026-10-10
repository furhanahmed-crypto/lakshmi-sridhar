<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$flash = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save_all');

    if ($action === 'add') {
        $uploaded = admin_upload_image($_FILES['image'] ?? null, 'artwork/student-work');
        if ($uploaded === null) {
            $error = db_last_error() ?: 'Could not upload image.';
        } else {
            $alt = trim((string) ($_POST['image_alt'] ?? ''));
            $sort = (int) ($_POST['sort_order'] ?? 0);
            $id = student_work_save([
                'image' => $uploaded,
                'image_alt' => $alt,
                'sort_order' => $sort,
                'is_active' => 1,
            ]);
            if ($id) {
                $_SESSION['admin_flash'] = 'Student work image added.';
                header('Location: ' . admin_url('student-work.php'));
                exit;
            }
            $error = db_last_error() ?: 'Could not save image.';
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0 && student_work_delete($id)) {
            $_SESSION['admin_flash'] = 'Image removed.';
            header('Location: ' . admin_url('student-work.php'));
            exit;
        }
        $error = db_last_error() ?: 'Could not delete image.';
    } else {
        $rows = $_POST['items'] ?? [];
        if (!is_array($rows)) {
            $rows = [];
        }
        $ok = true;
        foreach ($rows as $id => $row) {
            if (!is_array($row)) {
                continue;
            }
            $id = (int) $id;
            if ($id < 1) {
                continue;
            }
            $saved = student_work_save([
                'image' => trim((string) ($row['image'] ?? '')),
                'image_alt' => trim((string) ($row['image_alt'] ?? '')),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => !empty($row['is_active']),
            ], $id);
            if (!$saved) {
                $ok = false;
                break;
            }
        }
        if ($ok) {
            $_SESSION['admin_flash'] = 'Student work gallery updated.';
            header('Location: ' . admin_url('student-work.php'));
            exit;
        }
        $error = db_last_error() ?: 'Could not update gallery.';
    }
}

$flash = $_SESSION['admin_flash'] ?? '';
unset($_SESSION['admin_flash']);
$items = student_work_from_db(false);
$tableMissing = $items === null;
$items = $items ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student work — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(admin_asset('admin.css')) ?>">
</head>
<body class="admin">
    <?php $admin_nav = 'student-work'; include __DIR__ . '/header.php'; ?>

    <div class="admin-shell edit-shell">
        <div class="admin-top edit-top">
            <div>
                <p class="edit-kicker">Courses page</p>
                <h1>Student work</h1>
                <p>Images shown under “From the classroom” on Courses Running. Reorder, hide, replace alt text, or add new pieces.</p>
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
        <?php endif; ?>

        <?php if (!$tableMissing): ?>
        <form class="edit-form" method="post" enctype="multipart/form-data">
            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">1</span>
                    <div>
                        <h2>Add an image</h2>
                        <p>Uploads go into the student-work folder and appear on the Courses masonry when active.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <input type="hidden" name="action" value="add">
                    <div class="edit-row edit-row--3">
                        <div class="field">
                            <label for="new_image">Image file</label>
                            <input id="new_image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required>
                            <p class="field-hint">JPG, PNG, or WebP · max 5MB</p>
                        </div>
                        <div class="field">
                            <label for="new_alt">Alt text</label>
                            <input id="new_alt" name="image_alt" placeholder="Short description of the artwork">
                        </div>
                        <div class="field">
                            <label for="new_sort">Sort order</label>
                            <input id="new_sort" name="sort_order" type="number" value="<?= e((string) ((count($items) + 1) * 10)) ?>">
                            <p class="field-hint">Lower numbers appear first.</p>
                        </div>
                    </div>
                    <div class="edit-inline-actions">
                        <button class="btn btn--primary btn--sm" type="submit">Add image</button>
                    </div>
                </div>
            </section>
        </form>

        <form class="edit-form" method="post">
            <section class="edit-card">
                <header class="edit-card__header">
                    <span class="edit-card__step">2</span>
                    <div>
                        <h2>Manage gallery</h2>
                        <p><?= count($items) ?> image<?= count($items) === 1 ? '' : 's' ?> in the database. Inactive images stay saved but hidden on the site.</p>
                    </div>
                </header>
                <div class="edit-card__body">
                    <input type="hidden" name="action" value="save_all">
                    <?php if (!$items): ?>
                        <p class="empty-inline">No student work images yet. Add one above.</p>
                    <?php else: ?>
                        <div class="gallery-manage">
                            <?php foreach ($items as $row): ?>
                                <?php $id = (int) $row['id']; ?>
                                <article class="gallery-manage__item">
                                    <div class="gallery-manage__thumb">
                                        <img src="<?= e(asset((string) $row['image'])) ?>" alt="">
                                    </div>
                                    <div class="gallery-manage__fields">
                                        <input type="hidden" name="items[<?= $id ?>][image]" value="<?= e((string) $row['image']) ?>">
                                        <div class="field">
                                            <label for="alt-<?= $id ?>">Alt text</label>
                                            <input id="alt-<?= $id ?>" name="items[<?= $id ?>][image_alt]" value="<?= e((string) ($row['image_alt'] ?? '')) ?>">
                                        </div>
                                        <div class="edit-row edit-row--2">
                                            <div class="field">
                                                <label for="sort-<?= $id ?>">Sort</label>
                                                <input id="sort-<?= $id ?>" name="items[<?= $id ?>][sort_order]" type="number" value="<?= e((string) (int) ($row['sort_order'] ?? 0)) ?>">
                                            </div>
                                            <label class="edit-check gallery-manage__active">
                                                <input type="checkbox" name="items[<?= $id ?>][is_active]" value="1" <?= !empty($row['is_active']) ? 'checked' : '' ?>>
                                                <span>Show on site</span>
                                            </label>
                                        </div>
                                        <button
                                            class="btn btn--danger btn--sm"
                                            type="submit"
                                            form="delete-<?= $id ?>"
                                            onclick="return confirm('Remove this image from the student work gallery?');"
                                        >Remove</button>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <div class="edit-actions">
                <button class="btn btn--primary" type="submit">Save gallery</button>
                <a class="btn btn--ghost" href="<?= e(page_url('courses.php')) ?>#student-work" target="_blank" rel="noopener">Preview on site</a>
            </div>
        </form>

        <?php foreach ($items as $row): ?>
            <?php $id = (int) $row['id']; ?>
            <form id="delete-<?= $id ?>" method="post" hidden>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $id ?>">
            </form>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
