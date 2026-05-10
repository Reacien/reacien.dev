<?php snippet('header') ?>

<main class="home">

  <?php snippet('boot-overlay') ?>

  <section class="home-hero">
    <div class="video-container" aria-hidden="true">
      <video class="video-bg" autoplay loop muted playsinline>
        <source src="<?= url('assets/video/light-version.mp4') ?>" type="video/mp4">
      </video>
    </div>

    <div class="home-hero-grid">
      <div class="home-copy">
        <p class="label">reacien.dev · est. 2025</p>
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
    <div class="selected-work-head">
      <p class="label">Selected work</p>
      <h2>Recent projects</h2>
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
  </section>

</main>

<?php snippet('footer') ?>