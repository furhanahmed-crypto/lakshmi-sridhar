<?php
/**
 * Notice when product catalogue cannot load or is empty.
 * Call after products() on listing pages.
 */
$error = products_error();
$items = $items ?? [];
?>
<?php if ($error): ?>
    <p class="products-notice products-notice--error" role="alert"><?= e($error) ?></p>
<?php elseif ($items === []): ?>
    <p class="products-notice" role="status">No products available right now.</p>
<?php endif; ?>
