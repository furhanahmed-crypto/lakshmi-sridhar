<?php
/**
 * WhatsApp training schedule CTA strip.
 * @var string|null $variant 'section'|'inline'
 */
$variant = $variant ?? 'section';
?>
<section class="whatsapp-cta <?= $variant === 'inline' ? 'whatsapp-cta--inline' : '' ?>" aria-label="Training schedule">
    <div class="container whatsapp-cta__inner reveal">
        <div class="whatsapp-cta__copy">
            <p class="eyebrow">Training dates</p>
            <h2 class="split-chars">Training dates are announced regularly and can fill up quickly.</h2>
            <p>Message me on WhatsApp and I'll share the latest availability with you.</p>
        </div>
        <a class="btn btn--primary" href="<?= e(whatsapp_class_url()) ?>" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
            Check Next Training Schedule
        </a>
    </div>
</section>
