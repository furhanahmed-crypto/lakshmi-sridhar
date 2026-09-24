<?php
/**
 * Sticky category filters + name search.
 * Category chips go to real pages. Search stays as ?q= on this page.
 *
 * @var string $shop_page current listing page
 * @var string $shop_root originals.php|prints.php
 * @var string $active_category tag slug or ''
 * @var string $search_q
 */
$shop_root = $shop_root ?? 'originals.php';
$shop_page = $shop_page ?? $shop_root;
$active_category = $active_category ?? '';
$search_q = $search_q ?? '';
?>
<div class="shop-toolbar" data-shop-toolbar>
    <div class="shop-toolbar__inner">
        <nav class="shop-filters" aria-label="Artwork categories">
            <a
                class="shop-filters__chip<?= $active_category === '' ? ' is-active' : '' ?>"
                href="<?= e(shop_list_url($shop_root)) ?>"
            >All</a>
            <?php foreach (shop_categories() as $slug => $cat): ?>
                <?php $label = $cat['filter_label'] ?? $cat['label']; ?>
                <a
                    class="shop-filters__chip<?= $active_category === $slug ? ' is-active' : '' ?>"
                    href="<?= e(shop_list_url($shop_root, $slug)) ?>"
                ><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <form class="shop-search" method="get" action="<?= e(page_url($shop_page)) ?>" data-shop-search-form role="search">
            <label class="visually-hidden" for="shop-search">Search artwork by name</label>
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input
                id="shop-search"
                type="search"
                name="q"
                value="<?= e($search_q) ?>"
                placeholder="Search by name"
                autocomplete="off"
                data-shop-search
            >
        </form>
    </div>
</div>
