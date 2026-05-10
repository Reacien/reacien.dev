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
                <li><a href="https://github.com/Reacien" target="_blank" rel="noreferrer">→ github · @Reacien</a></li>
                <li><a href="https://twitter.com/Reacien_" target="_blank" rel="noreferrer">→ twitter · @Reacien_</a></li>
                <li><a href="https://mynickname.com/reacien" target="_blank" rel="noreferrer">→ nickname</a></li>
                <li><a href="https://ko-fi.com/reacien_" target="_blank" rel="noreferrer">→ buy me a coffee</a></li>
            </ul>
        </section>

        <!-- Sitemap -->
        <section class="colophon-col colophon-sitemap">
            <h4>sitemap</h4>
            <ul>
                <li><a href="<?= $site->url() ?>">→ home</a></li>
                <li><a href="<?= url('about') ?>">→ about</a></li>
                <li><a href="<?= url('projects') ?>">→ projects</a></li>
                <li><a href="<?= url('insights') ?>">→ insights</a></li>
                <li><a href="<?= url('contact') ?>">→ contact</a></li>
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