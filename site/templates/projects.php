<?php snippet('header') ?>

<main class="page projects">
  <div class="wrap projects-inner">

    <header class="projects-hero">
      <p class="label">projects</p>
      <h1><?= $page->title()->html() ?></h1>

      <?php if ($page->intro()->isNotEmpty()): ?>
        <p class="lede"><?= $page->intro()->kirbytextinline() ?></p>
      <?php else: ?>
        <p class="lede">
          tools, sites & automations — shipped, in-flight, and in the drawer.
        </p>
      <?php endif; ?>
    </header>

    <section class="projects-body">
      <ul class="projects-grid">
        <?php
        $projects = $page->children()->listed()->sortBy('date', 'desc');
        foreach ($projects as $project):
          $status = $project->project_status()->value();
          $statusLabel = $status;
          if ($status === 'wip') $statusLabel = 'in progress';
          ?>
          <li class="project-card">
            <a href="<?= $project->url() ?>" class="card-link">
              <div class="shot stripes">
                <span class="mono">[shot]</span>
              </div>

              <div class="card-content">
                <h3><?= $project->title()->html() ?></h3>

                <?php if ($project->subtitle()->isNotEmpty()): ?>
                  <p class="summary">
                    <?= $project->subtitle()->kirbytextinline() ?>
                  </p>
                <?php endif; ?>
              </div>
            </a>

            <?php if ($status): ?>
              <span class="status status-<?= esc($status) ?>">
                <?= $statusLabel ?>
                <?php if ($project->year()->isNotEmpty()): ?>
                  · <?= $project->year()->esc() ?>
                <?php endif; ?>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

  </div>
</main>

<?php snippet('footer') ?>