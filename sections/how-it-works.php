<?php
/**
 * How it works steps.
 * @var array $steps [['title' => ..., 'text' => ...], ...]
 * @var string $heading
 */
$heading = $heading ?? 'How it Works';
?>
<section class="section how-section" aria-labelledby="how-heading">
    <div class="container">
        <div class="section-intro reveal">
            <p class="eyebrow">From studio to you</p>
            <h2 id="how-heading" class="section-title"><?= e($heading) ?></h2>
        </div>

        <ol class="how-steps">
            <?php foreach ($steps as $i => $step): ?>
                <li class="how-step reveal">
                    <span class="how-step__num" aria-hidden="true"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    <div class="how-step__content">
                        <?php if (!empty($step['title'])): ?>
                            <h3><?= e($step['title']) ?></h3>
                        <?php endif; ?>
                        <p><?= e($step['text']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
