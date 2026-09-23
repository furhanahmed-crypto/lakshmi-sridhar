<?php
/**
 * Weekly class schedule with WhatsApp enquire CTAs.
 */
$schedule = $schedule ?? require dirname(__DIR__) . '/data/schedule.php';
?>
<section class="section section--cream" id="class-schedule" aria-labelledby="schedule-heading">
    <div class="container">
        <div class="section-intro reveal">
            <p class="eyebrow">Weekly timetable</p>
            <h2 id="schedule-heading" class="section-title">Class Schedule</h2>
            <p class="lede">Choose a convenient location or join us online.</p>
        </div>

        <div class="schedule reveal">
            <div class="schedule__head" aria-hidden="true">
                <span>Location / Mode</span>
                <span>Class Day</span>
                <span></span>
            </div>
            <?php foreach ($schedule as $row): ?>
                <div class="schedule__row">
                    <h3 class="schedule__location"><?= e($row['location']) ?></h3>
                    <p class="schedule__day"><?= e($row['day']) ?></p>
                    <a
                        class="schedule__enquire"
                        href="<?= e(whatsapp_class_url($row['location'], $row['day'])) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Enquire
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="schedule__cta reveal">
            <a class="btn btn--primary" href="<?= e(whatsapp_class_url()) ?>" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                Book a class on WhatsApp
            </a>
        </div>
    </div>
</section>
