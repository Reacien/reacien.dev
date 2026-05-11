<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$heroHeading        = $page->hero_heading()->or('building on the <em>web</em>,<br> one commit at a time.');
$heroLede           = $page->hero_lede();
$selectedWorkLabel  = $page->selected_work_heading()->or('Recent <em>projects</em>');
$selectedWorkLimit  = max(1, (int) $page->selected_work_limit()->or(4)->value());
?>
<?php snippet('header') ?>

<main class="home">

  <?php snippet('boot-overlay') ?>

  <section class="home-hero">
    <div class="home-hero-grid">
      <div class="home-copy">
        <h1><?= $heroHeading ?></h1>

        <?php if ($heroLede->isNotEmpty()): ?>
          <p class="lede"><?= $heroLede->kirbytextinline() ?></p>
        <?php endif; ?>
      </div>

      <div class="home-side">
        <?php snippet('site-key', [
          'siteKeyTitle' => $page->site_key_title(),
          'siteKeyIntro' => $page->site_key_intro(),
        ]) ?>
      </div>
    </div>
  </section>

  <section class="selected-work">
    <div class="selected-work-inner">
      <div class="selected-work-head">
        <h2><?= $selectedWorkLabel ?></h2>
      </div>

      <div class="selected-work-grid">
        <?php
        $projectsPage = page('projects');
        if ($projectsPage):
          $projects = $projectsPage->children()->listed()->sortBy('date', 'desc')->limit($selectedWorkLimit);
          foreach ($projects as $project):
            $statusKey   = $project->project_status()->value();
            $statusLabel = $statusKey === 'wip' ? 'in progress' : $statusKey;
            ?>
            <a class="selected-work-card cropped" href="<?= $project->url() ?>">
              <div class="shot">
                <span class="mono">Screenshot coming soon</span>
              </div>
              <div class="name">
                <?= $project->title()->html() ?>
              </div>
              <?php if ($project->subtitle()->isNotEmpty()): ?>
                <div class="desc">
                  <?= $project->subtitle()->kirbytextinline() ?>
                </div>
              <?php endif; ?>

              <?php if ($statusKey): ?>
                <span class="chip status-<?= esc($statusKey) ?>">
                  <?= esc($statusLabel) ?>
                </span>
              <?php endif; ?>
            </a>
          <?php endforeach;
        endif; ?>
      </div>
    </div>
  </section>

</main>

<?php snippet('footer') ?>
