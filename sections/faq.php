<?php
/**
 * FAQ accordion section.
 * @var array $faqs
 * @var string $heading
 */
$heading = $heading ?? 'FAQs';
?>
<section class="section faq-section" aria-labelledby="faq-heading">
    <div class="container narrow">
        <div class="section-intro reveal">
            <p class="eyebrow">Questions</p>
            <h2 id="faq-heading" class="section-title"><?= e($heading) ?></h2>
        </div>

        <div class="faq-list" data-faq>
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item reveal" data-faq-item>
                    <button
                        class="faq-item__question"
                        type="button"
                        aria-expanded="false"
                        aria-controls="faq-panel-<?= (int) $index ?>"
                        id="faq-btn-<?= (int) $index ?>"
                        data-faq-toggle
                    >
                        <span><?= e($faq['q']) ?></span>
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    </button>
                    <div
                        class="faq-item__answer"
                        id="faq-panel-<?= (int) $index ?>"
                        role="region"
                        aria-labelledby="faq-btn-<?= (int) $index ?>"
                        hidden
                        data-faq-panel
                    >
                        <p><?= e($faq['a']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
