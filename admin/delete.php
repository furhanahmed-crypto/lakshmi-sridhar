<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$id = trim((string) ($_POST['id'] ?? ''));
if ($id !== '' && product_delete($id)) {
    $_SESSION['admin_flash'] = 'Product deleted.';
} else {
    $_SESSION['admin_flash'] = db_last_error() ?: 'Could not delete product.';
}

header('Location: products.php');
exit;
