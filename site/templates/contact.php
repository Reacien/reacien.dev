<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<main class="page contact">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Contact</p>
      <h1><?= $page->title()->html() ?></h1>
    </header>

    <section class="page-body text">
      <?php if ($page->text()->isNotEmpty()): ?>
        <?= $page->text()->kt() ?>
      <?php endif; ?>

      <?php if ($page->email()->isNotEmpty()): ?>
        <p><strong>Email:</strong> <?= $page->email()->esc() ?></p>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php snippet('footer') ?>