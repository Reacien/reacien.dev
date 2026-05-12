<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$posts = $page->children()->listed()->sortBy('date', 'desc');

// Estimate read time from a post's blocks + excerpt at ~200 wpm.
$readMinutes = function (\Kirby\Cms\Page $post): int {
    $text = ' ' . $post->excerpt()->value();
    $blocks = $post->content()->get('content');
    if ($blocks->isNotEmpty()) {
        foreach ($blocks->toBlocks() as $block) {
            $text .= ' ' . strip_tags((string) $block->toHtml());
        }
    }
    $words = str_word_count(trim($text));
    return max(1, (int) ceil($words / 200));
};

// Build the tag filter chips with live counts.
$tagCounts = [];
foreach ($posts as $post) {
    foreach ($post->tags()->split(',') as $tag) {
        $tag = trim($tag);
        if ($tag === '') continue;
        $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
    }
}
arsort($tagCounts);
?>
<?php snippet('header') ?>

<main class="page insights">
  <div class="wrap">

    <header class="insights-hero about-card">
      <div class="insights-hero-text">
        <p class="label insights-breadcrumb mono">Insights · Notebook</p>
        <h1 class="insights-headline">
          <?= $page->headline()->or("What I'm <em>learning</em>, mostly in public.") ?>
        </h1>
        <?php if ($page->subtitle()->isNotEmpty()): ?>
          <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
        <?php else: ?>
          <p class="lede">
            Short field notes — usually from the exact moment something finally clicks, or breaks. No newsletter, no tracking. Just markdown.
          </p>
        <?php endif; ?>
      </div>

      <aside class="insights-hero-meta mono">
        <span class="hero-meta-chip"><?= $posts->count() ?> post<?= $posts->count() === 1 ? '' : 's' ?></span>
      </aside>
    </header>

    <?php if (count($tagCounts) > 0): ?>
      <nav class="insights-filters" aria-label="Filter posts by tag">
        <span class="filter-label mono">Filter by tag:</span>
        <ul role="list">
          <li>
            <button type="button" class="filter-chip mono is-active" data-filter="all">
              <span class="filter-chip-label">all</span>
            </button>
          </li>
          <?php foreach ($tagCounts as $tag => $count): ?>
            <li>
              <button type="button" class="filter-chip mono" data-filter="tag:<?= esc($tag) ?>">
                <span class="filter-chip-label">#&thinsp;<?= esc($tag) ?></span>
                <span class="filter-chip-count"><?= esc((string) $count) ?></span>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <section class="insights-body">
      <?php if ($posts->count() === 0): ?>
        <p class="insights-empty muted">No insights published yet.</p>
      <?php else: ?>
        <ul class="insights-list">
          <?php foreach ($posts as $post):
            $cover  = $post->cover()->toFile() ?? $post->images()->first();
            $tags   = $post->tags()->isNotEmpty() ? array_map('trim', $post->tags()->split(',')) : [];
            $minutes = $readMinutes($post);
            $filterTokens = ['all'];
            foreach ($tags as $tag) {
                $filterTokens[] = 'tag:' . $tag;
            }
          ?>
            <li class="insight-row" data-filter-tokens="<?= esc(implode(' ', $filterTokens)) ?>">
              <a href="<?= $post->url() ?>" class="insight-row-link">
                <div class="insight-row-image<?= $cover ? '' : ' is-placeholder' ?>">
                  <?php if ($cover): ?>
                    <img
                      src="<?= $cover->resize(400)->url() ?>"
                      alt="<?= $post->title()->esc() ?>"
                      loading="lazy"
                    >
                  <?php else: ?>
                    <span class="row-image-tag mono">[ no image ]</span>
                  <?php endif; ?>
                </div>

                <div class="insight-row-body">
                  <p class="insight-row-meta mono">
                    <?php if ($post->date()->isNotEmpty()): ?>
                      <time datetime="<?= $post->date()->toDate('Y-m-d') ?>">
                        <?= $post->date()->toDate('d M Y') ?>
                      </time>
                      <span class="sep">·</span>
                    <?php endif; ?>
                    <span><?= $minutes ?> min read</span>
                  </p>

                  <h3 class="insight-row-title"><?= $post->title()->html() ?></h3>

                  <?php if ($post->excerpt()->isNotEmpty()): ?>
                    <p class="insight-row-excerpt">
                      <?= $post->excerpt()->kirbytextinline() ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($tags)): ?>
                    <ul class="insight-row-tags" role="list">
                      <?php foreach ($tags as $tag): ?>
                        <li class="insight-tag mono">#&thinsp;<?= html($tag) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </div>

                <span class="insight-row-cta mono" aria-hidden="true">read <span class="arrow">→</span></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <p class="insights-empty muted" data-empty-state hidden>
          No insights match this tag.
        </p>
      <?php endif; ?>
    </section>

    <?php if ($page->footnote()->isNotEmpty()): ?>
      <aside class="insights-footnote">
        <p class="label mono">Working toward</p>
        <div class="insights-footnote-body">
          <?= $page->footnote()->kt() ?>
        </div>
      </aside>
    <?php endif; ?>

  </div>
</main>

<?php snippet('footer') ?>
