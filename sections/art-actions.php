<?php
/**
 * Shared social action icons for artwork listings.
 * @var array $item Artwork array
 */
$share_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . ($_SERVER['REQUEST_URI'] ?? '/');
$share_title = $item['title'] ?? SITE_NAME;
?>
<div class="art-actions" role="group" aria-label="Share and follow">
    <button type="button" class="art-actions__btn" data-share data-share-url="<?= e($share_url) ?>" data-share-title="<?= e($share_title) ?>" title="Share">
        <i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
        <span>Share</span>
    </button>
    <a class="art-actions__btn" href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener noreferrer" title="Follow">
        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
        <span>Follow</span>
    </a>
    <a class="art-actions__btn" href="<?= e(WHATSAPP_URL) ?>" target="_blank" rel="noopener noreferrer" title="Subscribe for updates">
        <i class="fa-regular fa-bell" aria-hidden="true"></i>
        <span>Subscribe</span>
    </a>
</div>
