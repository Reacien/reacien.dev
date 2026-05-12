<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<main class="page error">
  <div class="wrap">
    <header class="page-head">
      <p class="label mono">Error · 404</p>
      <h1><?= $page->title()->or('Page not found')->html() ?></h1>
      <p class="error-lede"><?php if ($page->text()->isNotEmpty()): ?><?= $page->text()->kt() ?><?php else: ?>That URL doesn't go anywhere — <em>at least not anymore.</em><?php endif; ?></p>
    </header>

    <section class="page-body">
      <p class="error-suggest">If something looks broken, feel free to reach out.</p>

      <div class="error-actions">
        <a href="<?= $site->url() ?>" class="back-link mono">← Back to home</a>
        <a href="<?= $site->find('contact')->url() ?>" class="error-link mono">✉ Contact me</a>
        <a href="https://github.com/Reacien" class="error-link mono" target="_blank" rel="noopener noreferrer">⌥ GitHub</a>
      </div>
    </section>
  </div>
</main>

<?php snippet('footer') ?>
