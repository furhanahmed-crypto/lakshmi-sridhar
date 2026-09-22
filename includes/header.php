<?php
$nav_items = [
    ['label' => 'Home', 'slug' => 'home', 'href' => page_url('index.php')],
    ['label' => 'About Us', 'slug' => 'about', 'href' => page_url('about.php')],
    ['label' => 'Recent Projects', 'slug' => 'projects', 'href' => page_url('recent-projects.php')],
    ['label' => 'Courses Running', 'slug' => 'courses', 'href' => page_url('courses.php')],
    [
        'label' => 'Purchase',
        'slug' => 'purchase',
        'children' => [
            ['label' => 'Originals', 'slug' => 'originals', 'href' => page_url('originals.php')],
            ['label' => 'Prints', 'slug' => 'prints', 'href' => page_url('prints.php')],
        ],
    ],
    ['label' => 'Contact Us', 'slug' => 'contact', 'href' => page_url('contact.php')],
];
$purchase_active = in_array($current_page ?? '', ['originals', 'prints', 'purchase'], true);
?>
<header class="site-header" data-header>
    <div class="site-header__inner">
        <a class="brand" href="<?= page_url('index.php') ?>" aria-label="<?= e(SITE_NAME) ?> — Home">
            <span class="brand__mark" aria-hidden="true">
                <svg viewBox="0 0 48 48" width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="22.5" stroke="currentColor" stroke-width="1"/>
                    <text x="24" y="29" text-anchor="middle" font-family="Cormorant Garamond, Georgia, serif" font-size="18" font-weight="500" fill="currentColor" letter-spacing="1">LS</text>
                </svg>
            </span>
            <span class="brand__text">
                <span class="brand__name"><?= e(SITE_NAME) ?></span>
                <span class="brand__tag"><?= e(SITE_TAGLINE) ?></span>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span class="nav-toggle__label">Menu</span>
            <span class="nav-toggle__icon" aria-hidden="true">
                <i class="fa-solid fa-bars" data-icon-open></i>
                <i class="fa-solid fa-xmark" data-icon-close hidden></i>
            </span>
        </button>

        <nav class="site-nav" id="site-nav" data-nav aria-label="Primary">
            <ul class="site-nav__list">
                <?php foreach ($nav_items as $item): ?>
                    <?php if (!empty($item['children'])): ?>
                        <li class="site-nav__item has-dropdown <?= $purchase_active ? 'is-active' : '' ?>">
                            <button class="site-nav__link site-nav__link--dropdown" type="button" aria-expanded="false" data-dropdown-toggle>
                                <?= e($item['label']) ?>
                                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                            </button>
                            <ul class="site-nav__dropdown" data-dropdown>
                                <?php foreach ($item['children'] as $child): ?>
                                    <li>
                                        <a class="site-nav__dropdown-link <?= is_active($child['slug'], $current_page ?? '') ? 'is-active' : '' ?>" href="<?= e($child['href']) ?>">
                                            <?= e($child['label']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="site-nav__item <?= is_active($item['slug'], $current_page ?? '') ? 'is-active' : '' ?>">
                            <a class="site-nav__link" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <div class="site-nav__cta">
                <a class="btn btn--ghost btn--sm" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    Training Schedule
                </a>
            </div>
        </nav>
    </div>
</header>
