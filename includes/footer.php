<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand reveal">
            <a class="brand brand--footer" href="<?= page_url('index.php') ?>">
                <span class="brand__mark" aria-hidden="true">
                    <svg viewBox="0 0 48 48" width="36" height="36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="22.5" stroke="currentColor" stroke-width="1"/>
                        <text x="24" y="29" text-anchor="middle" font-family="Cormorant Garamond, Georgia, serif" font-size="18" font-weight="500" fill="currentColor">LS</text>
                    </svg>
                </span>
                <span class="brand__text">
                    <span class="brand__name"><?= e(SITE_NAME) ?></span>
                    <span class="brand__tag">Studio in Ireland</span>
                </span>
            </a>
            <p class="site-footer__note">
                Original paintings, fine art prints, and hands-on training — created with love in Ireland.
            </p>
        </div>

        <div class="site-footer__nav reveal">
            <h3 class="eyebrow">Explore</h3>
            <ul>
                <li><a href="<?= page_url('about.php') ?>">About Us</a></li>
                <li><a href="<?= page_url('recent-projects.php') ?>">Recent Projects</a></li>
                <li><a href="<?= page_url('courses.php') ?>">Courses Running</a></li>
                <li><a href="<?= page_url('originals.php') ?>">Originals</a></li>
                <li><a href="<?= page_url('prints.php') ?>">Prints</a></li>
                <li><a href="<?= page_url('contact.php') ?>">Contact Us</a></li>
            </ul>
        </div>

        <div class="site-footer__connect reveal">
            <h3 class="eyebrow">Stay close</h3>
            <p>Never miss a new painting, print release, or training date.</p>
            <div class="social-row">
                <a class="social-link" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                </a>
                <a class="social-link" href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                </a>
                <a class="social-link" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                </a>
            </div>
            <a class="btn btn--primary btn--sm" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                Check Next Training Schedule
            </a>
        </div>
    </div>

    <div class="site-footer__base">
        <div class="container site-footer__base-inner">
            <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
            <p class="site-footer__location">
                <?= e(STUDIO_LOCATION) ?>
                <span class="dot" aria-hidden="true">·</span>
                <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>
                <span class="dot" aria-hidden="true">·</span>
                <a href="tel:<?= e(CONTACT_PHONE_TEL) ?>"><?= e(CONTACT_PHONE) ?></a>
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="<?= asset('js/main.js') ?>?v=<?= ASSET_VERSION ?>"></script>
<script src="<?= asset('js/animations.js') ?>?v=<?= ASSET_VERSION ?>"></script>
</body>
</html>
