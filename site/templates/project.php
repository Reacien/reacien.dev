<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$statusKey   = $page->project_status()->value();
$statusLabel = match ($statusKey) {
    'wip'       => 'in progress',
    'completed' => 'shipped',
    'archived'  => 'archived',
    'idea'      => 'idea',
    default     => $statusKey,
};

$year  = $page->year()->isNotEmpty() ? $page->year()->value() : null;
$scope = $page->scope()->isNotEmpty() ? $page->scope()->value() : null;

// Action buttons — prefer explicit fields, fall back to scanning `urls`.
$visitUrl = $page->visit_url()->isNotEmpty() ? $page->visit_url()->value() : null;
$repoUrl  = $page->repo_url()->isNotEmpty()  ? $page->repo_url()->value()  : null;

$extraLinks = $page->urls()->isNotEmpty() ? $page->urls()->toStructure() : null;

if (($visitUrl === null || $repoUrl === null) && $extraLinks) {
    foreach ($extraLinks as $link) {
        $label = strtolower($link->label()->value());
        $url   = $link->url()->value();
        if (!$url) continue;
        if ($visitUrl === null && (str_contains($label, 'visit') || str_contains($label, 'site') || str_contains($label, 'live'))) {
            $visitUrl = $url;
        }
        if ($repoUrl === null && (str_contains($label, 'repo') || str_contains($label, 'github') || str_contains($label, 'source'))) {
            $repoUrl = $url;
        }
    }
}

$infoCards = $page->info_cards()->isNotEmpty() ? $page->info_cards()->toStructure() : null;
$gallery   = $page->images();
$cover     = $gallery->first();
$thumbs    = $cover ? $gallery->not($cover) : $gallery;

$prev = $page->prevListed();
$next = $page->nextListed();
?>
<?php snippet('header') ?>

<main class="page project">
  <div class="wrap">

    <header class="project-hero">
      <p class="label project-breadcrumb mono">
        Projects · <?= esc($page->slug()) ?>
      </p>

      <div class="project-hero-row">
        <div class="project-hero-text">
          <h1 class="project-title">
            <?= $page->title()->html() ?>
          </h1>

          <?php if ($page->subtitle()->isNotEmpty()): ?>
            <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
          <?php endif; ?>
        </div>

        <div class="project-hero-actions">
          <div class="project-meta-chips">
            <?php if ($statusKey): ?>
              <span class="chip status status-<?= esc($statusKey) ?> mono">
                <?= esc($statusLabel) ?><?php if ($year): ?> · <?= esc($year) ?><?php endif; ?>
              </span>
            <?php endif; ?>
            <?php if ($scope): ?>
              <span class="chip scope mono"><?= esc($scope) ?></span>
            <?php endif; ?>
          </div>

          <div class="project-action-buttons">
            <?php if ($visitUrl): ?>
              <a href="<?= esc($visitUrl) ?>" target="_blank" rel="noreferrer" class="btn btn-primary mono">
                visit site <span aria-hidden="true">→</span>
              </a>
            <?php endif; ?>
            <?php if ($repoUrl): ?>
              <a href="<?= esc($repoUrl) ?>" target="_blank" rel="noreferrer" class="btn mono">repo</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </header>

    <?php
      $stack     = $page->stack()->isNotEmpty() ? $page->stack()->toStructure() : null;
      $decisions = $page->decisions()->isNotEmpty() ? $page->decisions()->kt() : null;
      $retro     = $page->retro()->isNotEmpty() ? $page->retro()->kt() : null;

      $tabs = [];
      if ($page->description()->isNotEmpty()) {
          $tabs[] = ['key' => 'readme', 'label' => 'readme'];
      }
      if ($stack && $stack->count() > 0) {
          $tabs[] = ['key' => 'stack', 'label' => 'stack'];
      }
      if ($decisions) {
          $tabs[] = ['key' => 'decisions', 'label' => 'decisions'];
      }
      if ($retro) {
          $tabs[] = ['key' => 'retro', 'label' => 'retro'];
      }
      $hasTabs = count($tabs) > 1;
    ?>

    <?php if (count($tabs) > 0): ?>
      <section class="project-doc" data-doc-tabs>
        <header class="project-doc-head">
          <p class="project-doc-meta mono">
            <span class="file-icon" aria-hidden="true">#</span>
            README.md
            <span class="muted">·</span>
            <span><?= esc($page->slug()) ?> · main</span>
          </p>

          <?php if ($hasTabs): ?>
            <ul class="project-doc-tabs" role="tablist">
              <?php foreach ($tabs as $i => $tab): ?>
                <li role="presentation">
                  <button
                    type="button"
                    class="doc-tab mono<?= $i === 0 ? ' is-active' : '' ?>"
                    role="tab"
                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                    aria-controls="doc-panel-<?= esc($tab['key']) ?>"
                    data-doc-tab="<?= esc($tab['key']) ?>"
                  ><?= esc($tab['label']) ?></button>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </header>

        <?php if ($page->description()->isNotEmpty()): ?>
          <article
            class="project-doc-panel project-body markdown-body<?= $tabs[0]['key'] === 'readme' ? ' is-active' : '' ?>"
            id="doc-panel-readme"
            role="tabpanel"
            data-doc-panel="readme"
            <?= $tabs[0]['key'] !== 'readme' ? 'hidden' : '' ?>
          >
            <?= $page->description()->kt() ?>
          </article>
        <?php endif; ?>

        <?php if ($stack && $stack->count() > 0): ?>
          <article
            class="project-doc-panel project-doc-stack<?= $tabs[0]['key'] === 'stack' ? ' is-active' : '' ?>"
            id="doc-panel-stack"
            role="tabpanel"
            data-doc-panel="stack"
            <?= $tabs[0]['key'] !== 'stack' ? 'hidden' : '' ?>
          >
            <h2 class="markdown-heading">stack</h2>
            <dl class="stack-table">
              <?php foreach ($stack as $row): ?>
                <?php if ($row->name()->isNotEmpty()): ?>
                  <div class="stack-row">
                    <dt class="stack-name mono"><?= $row->name()->esc() ?></dt>
                    <dd class="stack-version mono"><?= $row->version()->or('—')->esc() ?></dd>
                    <dd class="stack-note"><?= $row->note()->esc() ?></dd>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </dl>
          </article>
        <?php endif; ?>

        <?php if ($decisions): ?>
          <article
            class="project-doc-panel project-body markdown-body<?= $tabs[0]['key'] === 'decisions' ? ' is-active' : '' ?>"
            id="doc-panel-decisions"
            role="tabpanel"
            data-doc-panel="decisions"
            <?= $tabs[0]['key'] !== 'decisions' ? 'hidden' : '' ?>
          >
            <h2 class="markdown-heading">decisions</h2>
            <?= $decisions ?>
          </article>
        <?php endif; ?>

        <?php if ($retro): ?>
          <article
            class="project-doc-panel project-body markdown-body<?= $tabs[0]['key'] === 'retro' ? ' is-active' : '' ?>"
            id="doc-panel-retro"
            role="tabpanel"
            data-doc-panel="retro"
            <?= $tabs[0]['key'] !== 'retro' ? 'hidden' : '' ?>
          >
            <h2 class="markdown-heading">retro</h2>
            <?= $retro ?>
          </article>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <?php if ($infoCards && $infoCards->count() > 0): ?>
      <section class="project-info-cards">
        <?php foreach ($infoCards as $card): ?>
          <?php if ($card->label()->isNotEmpty()): ?>
            <article class="info-card">
              <p class="info-card-label mono"><?= esc(strtoupper($card->label()->value())) ?></p>
              <p class="info-card-value"><?= $card->value()->kirbytextinline() ?></p>
            </article>
          <?php endif; ?>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <?php
      // Render extra links list only if there are leftovers after extracting visit/repo.
      $shownLinks = $extraLinks ? $extraLinks->filter(function ($l) use ($visitUrl, $repoUrl) {
          $u = $l->url()->value();
          return $u && $u !== $visitUrl && $u !== $repoUrl;
      }) : null;
    ?>
    <?php if ($shownLinks && $shownLinks->count() > 0): ?>
      <section class="project-links">
        <h2 class="markdown-heading">More links</h2>
        <ul>
          <?php foreach ($shownLinks as $u): ?>
            <li>
              <a class="project-link" href="<?= esc($u->url()->value()) ?>" target="_blank" rel="noreferrer">
                <span class="project-link-arrow" aria-hidden="true">→</span>
                <span class="project-link-label"><?= esc($u->label()->or($u->url())->value()) ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>
    <?php endif; ?>

    <?php if ($page->testimonial_quote()->isNotEmpty()): ?>
      <figure class="project-testimonial cropped">
        <blockquote><?= $page->testimonial_quote()->kti() ?></blockquote>
        <?php if ($page->testimonial_name()->isNotEmpty() || $page->testimonial_role()->isNotEmpty()): ?>
          <figcaption>
            <?php if ($page->testimonial_name()->isNotEmpty()): ?>
              <strong><?= $page->testimonial_name()->esc() ?></strong>
            <?php endif; ?>
            <?php if ($page->testimonial_role()->isNotEmpty()): ?>
              <span class="muted">· <?= $page->testimonial_role()->esc() ?></span>
            <?php endif; ?>
          </figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>

    <?php if ($gallery->count() > 0): ?>
      <section class="project-gallery">
        <p class="label mono">Gallery</p>
        <div class="gallery-grid">
          <?php if ($cover): ?>
            <a class="gallery-item gallery-hero" href="<?= $cover->url() ?>" target="_blank" rel="noreferrer">
              <img
                src="<?= $cover->resize(1400)->url() ?>"
                alt="<?= $cover->alt()->or($page->title())->esc() ?>"
                loading="lazy"
              >
            </a>
          <?php endif; ?>
          <?php foreach ($thumbs as $img): ?>
            <a class="gallery-item" href="<?= $img->url() ?>" target="_blank" rel="noreferrer">
              <img
                src="<?= $img->resize(800)->url() ?>"
                alt="<?= $img->alt()->or($page->title())->esc() ?>"
                loading="lazy"
              >
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($prev || $next): ?>
      <nav class="project-pager" aria-label="Project navigation">
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

  </div>
</main>

<?php snippet('footer') ?>
