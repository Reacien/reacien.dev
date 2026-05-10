<?php snippet('header') ?>

<main class="home">

  <?php snippet('boot-overlay') ?>

  <section class="home-hero">
    <div class="home-hero-grid">
      <div class="home-copy">
        <h1>
          building on the <em>web</em>,<br>
          one commit at a time.
        </h1>
      </div>

      <div class="home-side">
        <?php snippet('site-key') ?>
      </div>
    </div>
  </section>

  <section class="selected-work">
    <div class="selected-work-inner">
      <div class="selected-work-head">
        <h2>Recent <em>projects</em></h2>
      </div>

      <div class="selected-work-grid">
        <?php
        $projectsPage = page('projects');
        if ($projectsPage) :
          $projects = $projectsPage->children()->listed()->sortBy('date', 'desc')->limit(4);
          foreach ($projects as $project):
            $status = $project->project_status()->value();
            ?>
            <a class="selected-work-card" href="<?= $project->url() ?>">
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

              <?php if ($status): ?>
                <span class="chip">
                  <?= esc($status) ?>
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