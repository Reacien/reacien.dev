<?php
/** @var \Kirby\Cms\Site $site */

$version = option('reacien.version', 'v0.0.0');
$commit  = option('reacien.commit', 'dev');
?>

<footer class="colophon">
    <div class="colophon-inner">
        <!-- Brand / blurb -->
        <section class="colophon-col colophon-brand">
            <div class="wordmark">
                reacien<em>.dev</em>
            </div>
            <p class="blurb">
                a dutch software developer — shipping practical tools,
                clean uis, and a little bit of automation in the seams.
            </p>
        </section>

        <!-- Elsewhere -->
        <section class="colophon-col colophon-elsewhere">
            <h4>elsewhere</h4>
            <ul>
                <li><a href="https://github.com/Reacien" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>    
                    <span class="footer-link-label">github · @Reacien</span>
                </a></li>
                <li><a href="https://twitter.com/Reacien_" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>     
                    <span class="footer-link-label">twitter · @Reacien_</span>
                </a></li>
                <li><a href="https://mynickname.com/reacien" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>     
                    <span class="footer-link-label">nickname</span>
                </a></li>
                <li><a href="https://ko-fi.com/reacien_" class="footer-link" target="_blank" rel="noreferrer">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>     
                    <span class="footer-link-label">buy me a coffee</span>
                </a></li>
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
                <li><a href="<?= $site->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">about</span>
                </a></li>
                <li><a href="<?= $site->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">projects</span>
                </a></li>
                <li><a href="<?= $site->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">insights</span>
                </a></li>
                <li><a href="<?= $site->url() ?>" class="footer-link">
                    <span class="footer-link-arrow" aria-hidden="true">→</span>
                    <span class="footer-link-label">contact</span>
                </a></li>
            </ul>
        </section>

         <!-- Colophon text -->
        <section class="colophone-col colophon-meta">
            <h4>colophon</h4>
            <p class="colophon-text">
                typeset in instrument serif & jetbrains mono. built with kirby cms
                & hand-tuned css. hosted in the cloud.
            </p>
        </section>
    </div>

    <div class="colophon-base">
        <span>&copy; <?= date('Y') ?> reacien.dev</span>
        <span class="sep">/</span>
        <span>made by hand in nl</span>
        <span class="sep">/</span>
        <a href="<?= url('privacy') ?>">privacy</a>

        <span class="build">
        <?= $version ?>
        <span class="sep-dot">•</span>
        commit <?= $commit ?>
        </span>
  </div>
</footer>

</body>
</html>