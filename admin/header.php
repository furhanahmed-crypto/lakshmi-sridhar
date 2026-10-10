<?php
/**
 * Shared admin header: logo + nav.
 * @var string $admin_nav products|edit|student-work|recent-projects
 */
$admin_nav = $admin_nav ?? '';
?>
<header class="admin-header">
    <div class="admin-header__inner">
        <a class="admin-brand" href="<?= e(admin_url('products.php')) ?>" aria-label="<?= e(SITE_NAME) ?> — Admin">
            <img src="<?= asset('images/logo.png') ?>" alt="<?= e(SITE_NAME) ?>" width="200" height="58" decoding="async">
        </a>
        <nav class="admin-nav" aria-label="Admin">
            <a class="<?= $admin_nav === 'products' ? 'is-active' : '' ?>" href="<?= e(admin_url('products.php')) ?>">Products</a>
            <a class="<?= $admin_nav === 'edit' ? 'is-active' : '' ?>" href="<?= e(admin_url('edit.php')) ?>">Add product</a>
            <a class="<?= $admin_nav === 'student-work' ? 'is-active' : '' ?>" href="<?= e(admin_url('student-work.php')) ?>">Student work</a>
            <a class="<?= $admin_nav === 'recent-projects' ? 'is-active' : '' ?>" href="<?= e(admin_url('recent-projects.php')) ?>">Recent projects</a>
            <a class="admin-nav__logout" href="<?= e(admin_url('logout.php')) ?>">Log out</a>
        </nav>
    </div>
</header>
