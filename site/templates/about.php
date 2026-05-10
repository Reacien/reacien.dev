<?php snippet('header') ?>

<main class="page about">
  <div class="wrap">
    <header class="page-head">
      <p class="label">About</p>
      <h1><?= $page->title()->html() ?></h1>
    </header>

    <section class="page-body text">
      <?php if ($page->text()->isNotEmpty()): ?>
        <?= $page->text()->kt() ?>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php snippet('footer') ?>