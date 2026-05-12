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
    </header>

    <section class="page-body text">
      <?php if ($page->text()->isNotEmpty()): ?>
        <?= $page->text()->kt() ?>
      <?php else: ?>
        <p>Sorry, the page you are looking for could not be found.</p>
      <?php endif; ?>

      <p><a href="<?= $site->url() ?>" class="back-link mono">← Back to home</a></p>
    </section>
  </div>
</main>

<?php snippet('footer') ?>
