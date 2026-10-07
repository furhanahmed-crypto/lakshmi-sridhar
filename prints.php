<?php
require_once __DIR__ . '/includes/config.php';
$q = trim((string) ($_GET['q'] ?? ''));
$cat = shop_filter_tag((string) ($_GET['category'] ?? ''));
header('Location: ' . shop_list_url('purchase/prints/', $cat, $q), true, 301);
exit;
