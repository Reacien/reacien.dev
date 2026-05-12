<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$email    = $page->email_to()->or($site->public_email())->or('hi@reacien.dev')->value();
$github   = $site->github_url()->or('https://github.com/Reacien')->value();
$twitter  = $site->twitter_url()->or('https://twitter.com/Reacien_')->value();
$kofi     = $site->kofi_url()->or('https://ko-fi.com/reacien_')->value();
$nickname = $site->nickname_url()->or('https://mynickname.com/reacien')->value();

$githubHandle  = '@' . basename(rtrim(parse_url($github, PHP_URL_PATH) ?? '', '/'));
$twitterHandle = '@' . basename(rtrim(parse_url($twitter, PHP_URL_PATH) ?? '', '/'));
$kofiLabel     = preg_replace('#^https?://#', '', rtrim($kofi, '/'));

$responseTime = $page->response_time()->value();

$emailBadge   = $page->email_badge()->or('best for most things')->value();
$emailNote    = $page->email_note()->or('for anything that needs attachments, context, or privacy')->value();
$githubNote   = $page->github_note()->or('issues, PRs, and bug reports that belong in a tracker')->value();
$twitterNote  = $page->twitter_note()->or('quick chatter, replies within a day or so')->value();
$kofiNote     = $page->kofi_note()->or('tip jar — fuels the americano habit')->value();
$notMe        = $page->not_me_disclaimer()->or("any account that isn't listed here isn't mine — even if it looks close.")->value();

$mailtoSubject = 'Hello from reacien.dev';
?>
<?php snippet('header') ?>

<main class="page contact">
  <div class="wrap">

    <header class="contact-hero">
      <div class="contact-hero-text">
        <p class="label contact-breadcrumb mono">Contact</p>
        <h1 class="contact-title">
          <?= $page->headline()->or('say <em>hi</em>.') ?>
        </h1>
        <?php if ($page->subtitle()->isNotEmpty()): ?>
          <p class="lede"><?= $page->subtitle()->kirbytextinline() ?></p>
        <?php endif; ?>
      </div>

      <?php if ($responseTime): ?>
        <aside class="contact-status mono">
          <span class="status-dot" aria-hidden="true">●</span>
          responding · <?= esc($responseTime) ?>
        </aside>
      <?php endif; ?>
    </header>

    <div class="contact-grid">
      <section class="contact-channels" aria-label="Contact channels">
        <article class="channel-card channel-email">
          <header class="channel-card-head">
            <h2 class="channel-name">email</h2>
            <?php if ($emailBadge): ?>
              <span class="channel-badge mono"><?= esc($emailBadge) ?></span>
            <?php endif; ?>
          </header>
          <a class="channel-handle mono" href="mailto:<?= esc($email) ?>">
            <?= esc($email) ?>
          </a>
          <?php if ($emailNote): ?>
            <p class="channel-note"><?= esc($emailNote) ?></p>
          <?php endif; ?>
        </article>

        <article class="channel-card">
          <header class="channel-card-head">
            <h2 class="channel-name">github</h2>
          </header>
          <a class="channel-handle mono" href="<?= esc($github) ?>" target="_blank" rel="noreferrer">
            <?= esc($githubHandle) ?>
          </a>
          <?php if ($githubNote): ?>
            <p class="channel-note"><?= esc($githubNote) ?></p>
          <?php endif; ?>
        </article>

        <article class="channel-card">
          <header class="channel-card-head">
            <h2 class="channel-name">twitter / x</h2>
          </header>
          <a class="channel-handle mono" href="<?= esc($twitter) ?>" target="_blank" rel="noreferrer">
            <?= esc($twitterHandle) ?>
          </a>
          <?php if ($twitterNote): ?>
            <p class="channel-note"><?= esc($twitterNote) ?></p>
          <?php endif; ?>
        </article>

        <article class="channel-card channel-kofi">
          <header class="channel-card-head">
            <h2 class="channel-name">buy me a coffee</h2>
          </header>
          <a class="channel-handle mono" href="<?= esc($kofi) ?>" target="_blank" rel="noreferrer">
            <?= esc($kofiLabel) ?>
          </a>
          <?php if ($kofiNote): ?>
            <p class="channel-note"><?= esc($kofiNote) ?></p>
          <?php endif; ?>
        </article>

        <?php if ($notMe): ?>
          <aside class="channel-disclaimer">
            <p class="label mono">not me</p>
            <p class="channel-disclaimer-text"><?= esc($notMe) ?></p>
          </aside>
        <?php endif; ?>
      </section>

      <section class="contact-letter" aria-label="Letter form">
        <form
          class="letter-form"
          action="mailto:<?= esc($email) ?>"
          method="post"
          enctype="text/plain"
          data-letter-form
        >
          <header class="letter-head">
            <span class="letter-tag mono">a letter, of sorts</span>
            <span class="letter-promise mono">ends up in my inbox · never sold</span>
          </header>

          <p class="letter-line">
            <span>Hey Reacien,</span>
          </p>

          <p class="letter-line">
            my name is
            <label class="visually-hidden" for="letter-name">Your name</label>
            <input
              type="text"
              id="letter-name"
              name="name"
              required
              autocomplete="name"
              placeholder="your name"
              class="letter-input"
            >
            and I'd like to talk about a
            <label class="visually-hidden" for="letter-type">Type of message</label>
            <select id="letter-type" name="type" class="letter-input letter-select">
              <option value="" disabled selected>select type…</option>
              <option value="commission">commission</option>
              <option value="collaboration">collaboration</option>
              <option value="bug-report">bug report</option>
              <option value="question">question</option>
              <option value="just-saying-hi">just saying hi</option>
            </select>
            .
          </p>

          <p class="letter-line">Here are the details:</p>
          <label class="visually-hidden" for="letter-body">Message</label>
          <textarea
            id="letter-body"
            name="message"
            class="letter-textarea"
            required
            rows="6"
            placeholder="what’s on your mind…"
            data-letter-body
          ></textarea>

          <p class="letter-line">
            You can reply to
            <label class="visually-hidden" for="letter-email">Your email</label>
            <input
              type="email"
              id="letter-email"
              name="reply_to"
              required
              autocomplete="email"
              placeholder="you@somewhere.dev"
              class="letter-input"
            >
            when you have a moment.
          </p>

          <p class="letter-line letter-signoff">Thanks —</p>
          <p class="letter-line letter-signature"><em>you</em></p>

          <!-- Honeypot: bots will fill this, humans won't. -->
          <input type="text" name="company" tabindex="-1" autocomplete="off" class="letter-honeypot" aria-hidden="true">

          <footer class="letter-foot">
            <button type="submit" class="btn btn-primary mono">
              seal &amp; send <span aria-hidden="true">→</span>
            </button>
            <button
              type="button"
              class="btn btn-ghost mono"
              data-copy-email
              title="Copy email address to clipboard"
            >copy email</button>
            <a
              href="mailto:<?= esc($email) ?>?subject=<?= rawurlencode($mailtoSubject) ?>"
              class="btn btn-ghost mono"
              data-mailto-link
            >open mailto:</a>
            <span class="letter-stats mono" data-letter-stats>
              <span data-letter-count>0</span> chars
              <span class="muted">· screen-reader friendly</span>
            </span>
          </footer>
        </form>
      </section>
    </div>

  </div>
</main>

<script>
(function () {
  var btn = document.querySelector('[data-copy-email]');
  if (!btn) return;
  btn.addEventListener('click', function () {
    var email = '<?= esc($email) ?>';
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(email).then(function () {
        btn.textContent = 'copied!';
        setTimeout(function () { btn.textContent = 'copy email'; }, 2000);
      }).catch(function () {
        fallback(email);
      });
    } else {
      fallback(email);
    }
    function fallback(text) {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.cssText = 'position:fixed;left:-9999px;top:0;opacity:0';
      document.body.appendChild(ta);
      ta.focus();
      ta.select();
      try { document.execCommand('copy'); btn.textContent = 'copied!'; }
      catch (e) { btn.textContent = 'copy failed'; }
      setTimeout(function () { btn.textContent = 'copy email'; }, 2000);
      document.body.removeChild(ta);
    }
  });
})();
</script>

<?php snippet('footer') ?>
