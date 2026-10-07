<?php
require_once __DIR__ . '/includes/config.php';

$id = trim((string) ($_GET['id'] ?? ''));
$type = strtolower(trim((string) ($_GET['type'] ?? 'original')));
$context = $type === 'print' ? 'print' : 'original';

if ($id === '') {
    header('Location: ' . page_url('purchase/originals/'), true, 301);
    exit;
}

header('Location: ' . product_url($id, $context), true, 301);
exit;
