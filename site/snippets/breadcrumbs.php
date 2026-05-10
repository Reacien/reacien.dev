<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Site $site */

$crumbs = $site->breadcrumb();
?>

<?php foreach ($crumbs as $crumb): ?>
    <?php if ($crumb->isHomePage()) continue; ?>

    <span class="sep">/</span>

    <?php if ($crumb->is($page)): ?>
        <span class="here">
            <?= $crumb->title()->html() ?>
        </span>
    <?php else: ?>
        <a href="<?= $crumb->url() ?>" class="crumb">
            <?= $crumb->title()->html() ?>
        </a>
    <?php endif; ?>
<?php endforeach; ?>