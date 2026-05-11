<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$email    = $page->email_to()->or($site->public_email())->or('hi@reacien.dev')->value();
$github   = $site->github_url()->or('https://github.com/Reacien')->value();
$twitter  = $site->twitter_url()->or('https://twitter.com/Reacien_')->value();
$nickname = $site->nickname_url()->or('https://mynickname.com/reacien')->value();
$kofi     = $site->kofi_url()->or('https://ko-fi.com/reacien_')->value();
?>
<?php snippet('header') ?>

<main class="page contact">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Contact</p>
      <h1><?= $page->title()->html() ?></h1>

      <?php if ($page->subtitle()->isNotEmpty()): ?>
        <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
      <?php endif; ?>
    </header>

    <?php if ($page->intro()->isNotEmpty()): ?>
      <section class="page-body text contact-intro">
        <?= $page->intro()->kt() ?>
      </section>
    <?php endif; ?>

    <section class="contact-channels">
      <?php if ($email): ?>
        <a class="contact-channel cropped" href="mailto:<?= esc($email) ?>">
          <span class="label mono">Email</span>
          <span class="value"><?= esc($email) ?></span>
          <span class="contact-channel-arrow" aria-hidden="true">→</span>
        </a>
      <?php endif; ?>

      <?php if ($github): ?>
        <a class="contact-channel cropped" href="<?= esc($github) ?>" target="_blank" rel="noreferrer">
          <span class="label mono">GitHub</span>
          <span class="value">@Reacien</span>
          <span class="contact-channel-arrow" aria-hidden="true">↗</span>
        </a>
      <?php endif; ?>

      <?php if ($twitter): ?>
        <a class="contact-channel cropped" href="<?= esc($twitter) ?>" target="_blank" rel="noreferrer">
          <span class="label mono">Twitter</span>
          <span class="value">@Reacien_</span>
          <span class="contact-channel-arrow" aria-hidden="true">↗</span>
        </a>
      <?php endif; ?>

      <?php if ($kofi): ?>
        <a class="contact-channel cropped" href="<?= esc($kofi) ?>" target="_blank" rel="noreferrer">
          <span class="label mono">Ko-fi</span>
          <span class="value">buy me a coffee</span>
          <span class="contact-channel-arrow" aria-hidden="true">↗</span>
        </a>
      <?php endif; ?>

      <?php if ($nickname): ?>
        <a class="contact-channel cropped" href="<?= esc($nickname) ?>" target="_blank" rel="noreferrer">
          <span class="label mono">Nickname</span>
          <span class="value">on Nickname</span>
          <span class="contact-channel-arrow" aria-hidden="true">↗</span>
        </a>
      <?php endif; ?>
    </section>

    <?php if ($page->description()->isNotEmpty()): ?>
      <section class="page-body text contact-description">
        <?= $page->description()->kt() ?>
      </section>
    <?php endif; ?>

  </div>
</main>

<?php snippet('footer') ?>
