<?php
require_once __DIR__ . '/includes/config.php';

$current_page = 'contact';
$page_title = 'Contact Us';
$page_description = "Let's talk — enquire about paintings, prints, courses, or commissions with Lakshmi Sridhar.";

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$title = "Let's Talk";
$subtitle = null;
$lede = "Whether you have a question about a painting, want to enquire about a commission, or just want to say hello — I'd love to hear from you.";
$show_media = false;
$eyebrow = 'Contact Us';
?>

<main id="main">
    <?php include __DIR__ . '/sections/page-hero.php'; ?>

    <section class="section" aria-labelledby="contact-heading">
        <div class="container contact-layout">
            <div>
                <h2 id="contact-heading" class="visually-hidden">Contact form</h2>
                <form class="contact-form reveal" action="#" method="post" data-contact-form novalidate>
                    <div class="form-status" data-form-status role="status" aria-live="polite"></div>

                    <div class="form-field">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" autocomplete="name" required>
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" autocomplete="email" required>
                    </div>

                    <div class="form-field">
                        <label for="phone">Phone <span style="text-transform:none;letter-spacing:0;font-weight:400;">(optional)</span></label>
                        <input type="tel" id="phone" name="phone" autocomplete="tel">
                    </div>

                    <div class="form-field">
                        <label for="subject">Subject / What's this about?</label>
                        <select id="subject" name="subject" required>
                            <option value="">Please select</option>
                            <option value="General Enquiry">General Enquiry</option>
                            <option value="Purchase an Original">Purchase an Original</option>
                            <option value="Order a Print">Order a Print</option>
                            <option value="Course Registration">Course Registration</option>
                            <option value="Commission Request">Commission Request</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>

                    <button class="btn btn--primary" type="submit">Send Message</button>
                    <p class="form-note">I try to reply to every message personally, so thank you for your patience if it takes me a day or two — I'm probably just elbow-deep in paint.</p>
                </form>
            </div>

            <aside class="contact-aside reveal">
                <h3>Prefer a quicker chat?</h3>
                <p>Message me directly on WhatsApp.</p>
                <a class="btn btn--primary" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    Chat on WhatsApp
                </a>

                <hr style="border:0;border-top:1px solid var(--border-soft);margin:2rem 0;">

                <h3>Direct contact</h3>
                <ul class="contact-details">
                    <li>
                        <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <a href="tel:<?= e(CONTACT_PHONE_TEL) ?>"><?= e(CONTACT_PHONE) ?></a>
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        <span>Based in <?= e(STUDIO_LOCATION) ?>.</span>
                    </li>
                </ul>

                <hr style="border:0;border-top:1px solid var(--border-soft);margin:2rem 0;">

                <h3>Follow my work</h3>
                <p>Behind-the-scenes process and new pieces as they leave the easel.</p>
                <div class="social-row">
                    <a class="social-link" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a class="social-link" href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </aside>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
