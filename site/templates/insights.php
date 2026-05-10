<?php snippet('header') ?>

<main class="page insights">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Insights</p>
      <h1><?= $page->title()->html() ?></h1>
      <?php if ($page->intro()->isNotEmpty()): ?>
        <p class="lede"><?= $page->intro()->kirbytextinline() ?></p>
      <?php endif; ?>
    </header>

    <section class="page-body">
      <ul>
        <?php foreach ($page->children()->listed()->sortBy('date', 'desc') as $post): ?>
          <li>
            <a href="<?= $post->url() ?>">
              <?= $post->title()->html() ?>
            </a>
            <?php if ($post->date()->isNotEmpty()): ?>
              <br><small><?= $post->date()->toDate('d M Y') ?></small>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </div>
</main>

<?php snippet('footer') ?>