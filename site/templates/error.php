<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<main class="page error">
  <div class="wrap">
    <header class="page-head">
      <p class="label mono">404 &middot; Page not found</p>
      <h1><?= $page->title()->or('Nothing here.')->html() ?></h1>
      <p class="error-lede">
        <?php if ($page->text()->isNotEmpty()): ?>
          <?= $page->text()->kt() ?>
        <?php else: ?>
          That URL doesn&rsquo;t go anywhere &mdash; <em>at least not anymore.</em>
        <?php endif; ?>
      </p>
    </header>

    <section class="page-body">
      <p class="error-suggest">
        This could mean the page was moved, renamed, or never existed in the first place.
        If you followed a link and ended up here, <em>it might be broken on the other end.</em>
        If you typed the URL yourself &mdash; double-check the spelling.
        Either way, feel free to reach out if something seems wrong.
      </p>

      <div class="error-actions">
        <a href="<?= $site->url() ?>" class="back-link mono">&larr; Back to home</a>
        <a href="<?= $site->find('contact')->url() ?>" class="error-link mono">Contact me</a>
        <a href="https://github.com/Reacien" class="error-link mono" target="_blank" rel="noopener noreferrer">GitHub</a>
      </div>
    </section>
  </div>
</main>

<?php snippet('footer') ?>
