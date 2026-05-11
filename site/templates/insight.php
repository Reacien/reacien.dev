<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$cover  = $page->cover()->toFile() ?? $page->images()->first();
$tags   = $page->tags()->isNotEmpty() ? array_map('trim', $page->tags()->split(',')) : [];
$blocks = $page->content()->get('content');

// Read-time estimate.
$wordText = ' ' . $page->excerpt()->value();
if ($blocks->isNotEmpty()) {
    foreach ($blocks->toBlocks() as $block) {
        $wordText .= ' ' . strip_tags((string) $block->toHtml());
    }
}
$minutes = max(1, (int) ceil(str_word_count(trim($wordText)) / 200));

$prev = $page->prevListed();
$next = $page->nextListed();
?>
<?php snippet('header') ?>

<main class="page insight">
  <div class="wrap">

    <header class="insight-hero">
      <p class="label insight-breadcrumb mono">
        Insights · <?= esc($page->slug()) ?>
      </p>

      <h1 class="insight-title"><?= $page->title()->html() ?></h1>

      <p class="insight-meta mono">
        <?php if ($page->date()->isNotEmpty()): ?>
          <time datetime="<?= $page->date()->toDate('Y-m-d') ?>">
            <?= $page->date()->toDate('d M Y') ?>
          </time>
          <span class="sep">·</span>
        <?php endif; ?>
        <span><?= $minutes ?> min read</span>
        <?php if (!empty($tags)): ?>
          <span class="sep">·</span>
          <span class="insight-meta-tags">
            <?php foreach ($tags as $tag): ?>
              <span class="meta-tag">#&thinsp;<?= html($tag) ?></span>
            <?php endforeach; ?>
          </span>
        <?php endif; ?>
      </p>

      <?php if ($page->excerpt()->isNotEmpty()): ?>
        <p class="insight-lede"><?= $page->excerpt()->kirbytextinline() ?></p>
      <?php endif; ?>
    </header>

    <?php if ($cover): ?>
      <figure class="insight-cover">
        <img
          src="<?= $cover->resize(1400)->url() ?>"
          alt="<?= $cover->alt()->or($page->title())->esc() ?>"
          loading="lazy"
        >
      </figure>
    <?php endif; ?>

    <?php if ($blocks->isNotEmpty()): ?>
      <section class="insight-body markdown-body">
        <?= $blocks->toBlocks() ?>
      </section>
    <?php endif; ?>

    <?php if ($prev || $next): ?>
      <nav class="insight-pager" aria-label="Insight navigation">
        <?php if ($prev): ?>
          <a href="<?= $prev->url() ?>" class="pager-card pager-prev">
            <span class="pager-direction mono">← previous</span>
            <span class="pager-title"><?= $prev->title()->html() ?></span>
          </a>
        <?php else: ?>
          <span class="pager-card pager-empty"></span>
        <?php endif; ?>

        <?php if ($next): ?>
          <a href="<?= $next->url() ?>" class="pager-card pager-next">
            <span class="pager-direction mono">next →</span>
            <span class="pager-title"><?= $next->title()->html() ?></span>
          </a>
        <?php else: ?>
          <span class="pager-card pager-empty"></span>
        <?php endif; ?>
      </nav>
    <?php endif; ?>

    <footer class="insight-footer">
      <a href="<?= $page->parent()->url() ?>" class="back-link mono">← All insights</a>
    </footer>

  </div>
</main>

<?php snippet('footer') ?>
