<?php snippet('header') ?>

<main class="page insight">
  <div class="wrap">

    <header class="page-head">
      <p class="label">Insight</p>

      <?php if ($page->date()->isNotEmpty()): ?>
        <time class="insight-date" datetime="<?= $page->date()->toDate('Y-m-d') ?>">
          <?= $page->date()->toDate('d M Y') ?>
        </time>
      <?php endif; ?>

      <h1><?= $page->title()->html() ?></h1>

      <?php if ($page->tags()->isNotEmpty()): ?>
        <ul class="insight-tags" role="list">
          <?php foreach ($page->tags()->split(',') as $tag): ?>
            ><?= html($tag) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </header>

    <?php if ($img = $page->image()): ?>
      <figure class="insight-cover">
        <img
          src="<?= $img->resize(1200)->url() ?>"
          alt="<?= $page->title()->esc() ?>"
          loading="lazy"
        >
      </figure>
    <?php endif; ?>

    <section class="page-body insight-body">
      <?php
      $blocks = $page->content()->get('content');
      if ($blocks->isNotEmpty()):
        echo $blocks->toBlocks();
      endif;
      ?>
    </section>

    <footer class="insight-footer">
      <a href="<?= $page->parent()->url() ?>" class="back-link">← All insights</a>
    </footer>

  </div>
</main>

<?php snippet('footer') ?>