<?php
/** @var \Kirby\Cms\Site $site */

$blurb    = $site->footer_blurb()->or('a dutch software developer — shipping practical tools, clean uis, and a little bit of automation in the seams.');
$colophon = $site->footer_colophon()->or('typeset in instrument serif &amp; jetbrains mono. built with kirby cms &amp; hand-tuned css. hosted in the cloud.');

$github   = $site->github_url()->or('https://github.com/Reacien')->value();
$twitter  = $site->twitter_url()->or('https://twitter.com/Reacien_')->value();
$nickname = $site->nickname_url()->or('https://mynickname.com/reacien')->value();
$kofi     = $site->kofi_url()->or('https://ko-fi.com/reacien_')->value();
?>

<footer class="colophon">
    <div class="colophon-inner">
        <!-- Brand / blurb -->
        <section class="colophon-col colophon-brand">
            <div class="wordmark">
                reacien<em>.dev</em>
            </div>
            <p class="blurb"><?= $blurb->kirbytextinline() ?></p>
        </section>

        <!-- Elsewhere -->
        <section class="colophon-col colophon-elsewhere">
            <h4>elsewhere</h4>
            <ul>
                <?php if ($github): ?>
                <li><a href="<?= esc($github) ?>" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">github · @Reacien</span>
                </a></li>
                <?php endif; ?>
                <?php if ($twitter): ?>
                <li><a href="<?= esc($twitter) ?>" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">twitter · @Reacien_</span>
                </a></li>
                <?php endif; ?>
                <?php if ($nickname): ?>
                <li><a href="<?= esc($nickname) ?>" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">nickname</span>
                </a></li>
                <?php endif; ?>
                <?php if ($kofi): ?>
                <li><a href="<?= esc($kofi) ?>" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">buy me a coffee</span>
                </a></li>
                <?php endif; ?>
            </ul>
        </section>

        <!-- Sitemap -->
        <section class="colophon-col colophon-sitemap">
            <h4>sitemap</h4>
            <ul>
                <li><a href="<?= $site->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">home</span>
                </a></li>
                <li><a href="<?= page('about')?->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">about</span>
                </a></li>
                <li><a href="<?= page('projects')?->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">projects</span>
                </a></li>
                <li><a href="<?= page('insights')?->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">insights</span>
                </a></li>
                <li><a href="<?= page('contact')?->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">contact</span>
                </a></li>
            </ul>
        </section>

         <!-- Colophon text -->
        <section class="colophon-col colophon-meta">
            <h4>colophon</h4>
            <p class="colophon-text"><?= $colophon->kirbytextinline() ?></p>
        </section>
    </div>

</footer>

<?php snippet('status-bar') ?>

</body>
</html>
