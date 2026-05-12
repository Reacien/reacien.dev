<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$showHero     = $page->show_hero()->toBool(true);
$showSkills   = $page->show_skills()->toBool(true);
$showPassions = $page->show_passions()->toBool(true);

$availabilityLabels = [
  'available'      => 'available',
  'busy'           => 'busy',
  'open_to_offers' => 'open to offers',
];
$availKey  = $page->availability()->value();
$openToWork = $availKey === 'available';

$identityNumber = 'FILE-ID ' . str_pad((string) ($page->num() ?? 1), 3, '0', STR_PAD_LEFT);
$identityYear   = date('Y');

$profileImage = $page->profile_image()->toFile();

$bioFacts = $page->bio_facts()->toStructure();
?>
<?php snippet('header') ?>

<main class="page about">
  <div class="wrap">

    <header class="card identity-card">
      <div class="identity-card-photo<?= $profileImage ? '' : ' is-placeholder' ?>">
        <?php if ($profileImage): ?>
          <img
            src="<?= $profileImage->resize(640)->url() ?>"
            alt="<?= $page->title()->esc() ?>"
            loading="lazy"
            width="<?= $profileImage->width() ?>"
            height="<?= $profileImage->height() ?>">
        <?php else: ?>
          <span class="identity-card-photo-tag mono">[ portrait · <?= $identityYear ?> ]</span>
        <?php endif; ?>
      </div>

      <div class="identity-card-body">
        <div class="identity-card-head">
          <p class="identity-card-meta mono">
            <span><?= esc($identityNumber) ?></span>
            <span class="muted">·</span>
            <span>classified: public</span>
          </p>

          <?php if ($openToWork): ?>
            <span class="status-pill mono">● open to work</span>
          <?php elseif ($availKey): ?>
            <span class="status-pill mono muted">● <?= esc($availabilityLabels[$availKey] ?? $availKey) ?></span>
          <?php endif; ?>
        </div>

        <h1 class="identity-card-title">
          <?php if ($page->display_name()->isNotEmpty()): ?>
            <?= $page->display_name()->esc() ?><span class="dot">.</span>
          <?php else: ?>
            <?= $page->title()->esc() ?><span class="dot">.</span>
          <?php endif; ?>
        </h1>

        <p class="identity-card-tagline mono">
          <?php
          $tagline = [];
          if ($page->role()->isNotEmpty()) {
            $tagline[] = strtolower($page->role()->value());
          }
          if ($page->location()->isNotEmpty()) {
            $tagline[] = strtolower($page->location()->value());
          }
          if ($page->role_since()->isNotEmpty()) {
            $tagline[] = 'est. ' . $page->role_since()->value();
          }
          ?>
          <?= esc(implode(' · ', $tagline)) ?>
        </p>

        <?php if ($bioFacts->count() > 0): ?>
          <hr class="identity-card-sep" aria-hidden="true">
          <dl class="identity-facts">
            <?php foreach ($bioFacts as $fact): ?>
              <?php if ($fact->label()->isNotEmpty() && $fact->value()->isNotEmpty()): ?>
                <div class="identity-fact">
                  <dt class="mono"><?= esc(strtolower($fact->label()->value())) ?></dt>
                  <dd><?= esc($fact->value()->value()) ?></dd>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </dl>
        <?php endif; ?>

        <?php if ($page->currently_working_on()->isNotEmpty() || $page->open_to()->isNotEmpty()): ?>
          <hr class="identity-card-sep" aria-hidden="true">
          <div class="identity-card-now">
            <?php if ($page->currently_working_on()->isNotEmpty()): ?>
              <p class="identity-card-now-line">
                <span class="mono muted">now</span>
                <span><?= $page->currently_working_on()->esc() ?></span>
              </p>
            <?php endif; ?>
            <?php if ($page->open_to()->isNotEmpty()): ?>
              <p class="identity-card-now-line">
                <span class="mono muted">open&nbsp;to</span>
                <span class="identity-card-tags">
                  <?php foreach ($page->open_to()->split(',') as $tag): ?>
                    <span class="chip"><?= html(trim($tag)) ?></span>
                  <?php endforeach; ?>
                </span>
              </p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </header>

    <div class="about-row">
      <?php if ($page->journey()->isNotEmpty() || $page->story_blocks()->isNotEmpty()): ?>
        <section class="card journey-card">
          <p class="card-label mono">my journey</p>
          <h2 class="card-heading">
            <?= $page->intro()->isNotEmpty()
              ? $page->intro()->kirbytextinline()
              : 'From taking things apart to <em>putting them on the internet</em>.' ?>
          </h2>

          <div class="card-body text">
            <?php if ($page->story_blocks()->isNotEmpty()): ?>
              <?= $page->story_blocks()->toBlocks() ?>
            <?php endif; ?>
            <?php if ($page->journey()->isNotEmpty()): ?>
              <?= $page->journey()->kt() ?>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($showSkills && $page->skills()->isNotEmpty()): ?>
        <section class="card skill-matrix">
          <p class="card-label mono">Skill Matrix</p>

          <ul class="skill-list">
            <?php foreach ($page->skills()->toStructure() as $skill): ?>
              <?php
              $level = max(0, min(100, (int) $skill->level()->value()));
              $note = $skill->note() ?? null;
              ?>
              <li class="skill-row">
                <div class="skill-row-head">
                  <span class="skill-name mono"><?= $skill->name()->esc() ?></span>
                  <span class="skill-percent mono"><?= $level ?>%</span>
                </div>
                <div class="skill-bar" role="meter" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= $level ?>">
                  <span class="skill-fill" style="width: <?= $level ?>%"></span>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

          <p class="skill-footnote mono">
            self-assessed. real-world miles may vary.
          </p>
        </section>
      <?php endif; ?>
    </div>

    <?php if ($showPassions && $page->passions_text()->isNotEmpty()): ?>
      <section class="card passions-card">
        <p class="card-label mono">Beyond Code</p>
        <h2 class="card-heading">Two kinds of <em>slow</em>.</h2>
        <div class="card-body text">
          <?= $page->passions_text()->kt() ?>
        </div>
      </section>
    <?php endif; ?>

  </div>
</main>

<?php snippet('footer') ?>