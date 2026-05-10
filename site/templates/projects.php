<?php snippet('header') ?>

<main class="page projects">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Projects</p>
      <h1><?= $page->title()->html() ?></h1>
      <?php if ($page->intro()->isNotEmpty()): ?>
        <p class="lede"><?= $page->intro()->kirbytextinline() ?></p>
      <?php endif; ?>
    </header>

    <section class="page-body">
      <ul>
        <?php foreach ($page->children()->listed() as $project): ?>
          <li>
            <a href="<?= $project->url() ?>">
              <?= $project->title()->html() ?>
            </a>
            <?php if ($project->subtitle()->isNotEmpty()): ?>
              <br><small><?= $project->subtitle()->esc() ?></small>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </div>
</main>

<?php snippet('footer') ?>