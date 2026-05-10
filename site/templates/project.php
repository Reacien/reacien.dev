<?php snippet('header') ?>

<main class="page project">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Project</p>
      <h1><?= $page->title()->html() ?></h1>

      <?php if ($page->subtitle()->isNotEmpty()): ?>
        <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
      <?php endif; ?>

      <?php if ($page->project_status()->isNotEmpty()): ?>
        <p class="status chip">
          <?= $page->project_status()->esc() ?>
        </p>
      <?php endif; ?>
    </header>

    <section class="page-body text">
      <?php if ($page->text()->isNotEmpty()): ?>
        <?= $page->text()->kt() ?>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php snippet('footer') ?>