<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$projects = $page->children()->listed();

$statusLabels = [
    'wip'       => 'in progress',
    'completed' => 'completed',
    'archived'  => 'archived',
    'idea'      => 'idea',
];

$categoryLabels = [
    'web'         => 'web',
    'tools'       => 'tools',
    'experiments' => 'experiments',
    'automation'  => 'automation',
    'library'     => 'library',
    'other'       => 'other',
];

// Build filter chips — categories first, then statuses, each with a count.
$filterChips = [['key' => 'all', 'label' => 'all', 'count' => $projects->count()]];

foreach ($categoryLabels as $key => $label) {
    $count = $projects->filterBy('category', $key)->count();
    if ($count > 0) {
        $filterChips[] = ['key' => 'category:' . $key, 'label' => $label, 'count' => $count];
    }
}

foreach ($statusLabels as $key => $label) {
    $count = $projects->filterBy('project_status', $key)->count();
    if ($count > 0) {
        $filterChips[] = ['key' => 'status:' . $key, 'label' => $label, 'count' => $count];
    }
}

// Newest first by year, with the listed-num order as the tiebreaker fallback.
$projectsSorted = $projects->sortBy('year', 'desc', 'num', 'asc');
?>
<?php snippet('header') ?>

<main class="page projects">
  <div class="wrap projects-inner">

    <header class="projects-hero">
      <p class="label projects-breadcrumb mono">Projects · Index</p>
      <h1 class="projects-headline">
        <?= $page->headline()->or('From <em>prototype</em> to production.') ?>
      </h1>
      <?php if ($page->subtitle()->isNotEmpty()): ?>
        <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
      <?php else: ?>
        <p class="lede">A running log of things I've built, am building, or still want to build.</p>
      <?php endif; ?>
    </header>

    <?php if (count($filterChips) > 1): ?>
      <nav class="projects-filters" aria-label="Filter projects">
        <ul role="list">
          <?php foreach ($filterChips as $chip): ?>
            <li>
              <button
                type="button"
                class="filter-chip mono<?= $chip['key'] === 'all' ? ' is-active' : '' ?>"
                data-filter="<?= esc($chip['key']) ?>"
              >
                <span class="filter-chip-label"><?= esc($chip['label']) ?></span>
                <span class="filter-chip-count"><?= esc((string) $chip['count']) ?></span>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <section class="projects-body">
      <?php if ($projectsSorted->count() === 0): ?>
        <p class="projects-empty muted">No projects published yet.</p>
      <?php else: ?>
        <ul class="projects-grid" data-projects-grid>
          <?php foreach ($projectsSorted as $project):
            $statusKey    = $project->project_status()->value();
            $statusLabel  = $statusLabels[$statusKey] ?? $statusKey;
            $categoryKey  = $project->category()->value();
            $year         = $project->year()->isNotEmpty() ? $project->year()->value() : null;
            $cover        = $project->images()->first();
            $techTags     = $project->tech()->isNotEmpty() ? $project->tech()->split(',') : [];

            $filterTokens = ['all'];
            if ($statusKey)   $filterTokens[] = 'status:' . $statusKey;
            if ($categoryKey) $filterTokens[] = 'category:' . $categoryKey;
          ?>
            <li
              class="project-card cropped"
              data-filter-tokens="<?= esc(implode(' ', $filterTokens)) ?>"
            >
              <a href="<?= $project->url() ?>" class="card-link">
                <div class="shot<?= $cover ? '' : ' is-placeholder' ?>">
                  <?php if ($cover): ?>
                    <img
                      src="<?= $cover->resize(800)->url() ?>"
                      alt="<?= $project->title()->esc() ?>"
                      loading="lazy"
                    >
                  <?php else: ?>
                    <span class="shot-tag mono">[ project shot ]</span>
                  <?php endif; ?>

                  <?php if ($statusKey): ?>
                    <span class="status-pill mono status-<?= esc($statusKey) ?>">
                      <?= esc($statusLabel) ?>
                    </span>
                  <?php endif; ?>
                </div>

                <div class="card-content">
                  <header class="card-head">
                    <h3 class="card-title mono"><?= $project->title()->html() ?></h3>
                    <?php if ($year): ?>
                      <span class="card-year mono"><?= esc($year) ?></span>
                    <?php endif; ?>
                  </header>

                  <?php if ($project->subtitle()->isNotEmpty()): ?>
                    <p class="card-summary">
                      <?= $project->subtitle()->kirbytextinline() ?>
                    </p>
                  <?php endif; ?>

                  <?php if (!empty($techTags)): ?>
                    <ul class="card-tags" role="list">
                      <?php foreach ($techTags as $tag): ?>
                        <li class="card-tag mono"><?= html(trim($tag)) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <p class="projects-empty-state muted" data-empty-state hidden>
          No projects match this filter.
        </p>
      <?php endif; ?>
    </section>

  </div>
</main>

<?php snippet('footer') ?>
