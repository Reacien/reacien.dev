<?php
/** @var \Kirby\Cms\Site $site */
?>

<footer class="colophon">
    <div class="colophon-inner">
        <section class="colophon-col colophon-brand">
            <div class="wordmark">
                reacien<span class="accent">.dev</span>
            </div>
            <p class="blurb">
                Dutch software developr — shipping practical tools, clean UIs, and a little bit of automation in the seams.
            </p>

            <a
                href="https://mynickname.com/reacien"
                class="nick-cert"
                target="_blank"
                rel="noreferrer"
            >
                <img
                    src="<?= url('assets/images/nickname-cert.gif') ?>"
                    alt="Nickname certificate for Reacien"
                    loading="lazy"
                >
            </a>
        </section>

        <section class="colophon-col colophon-elsewhere">
            <h4>Elsewhere</h4>
            <ul>
                <li><a href="https://github.com/Reacien" target="_blank" rel="noreferrer">GitHub</a></li>
                <li><a href="https://twitter.com/Reacien_" target="_blank" rel="noreferrer">Twitter</a></li>
                <li><a href="https://mynickname.com/reacien" target="_blank" rel="noreferrer">Nickname</a></li>
                <li><a href="https://ko-fi.com/reacien_" target="_blank" rel="noreferrer">Buy me a coffee</a></li>
            </ul>
        </section>

        <section class="colophon-col colophon-sitemap">
            <h4>Sitemap</h4>
            <ul>
                <li><a href="<?= $site->url() ?>">Home</a></li>
                <li><a href="<?= url('about') ?>">About</a></li>
                <li><a href="<?= url('projects') ?>">Projects</a></li>
                <li><a href="<?= url('insights') ?>">Insights</a></li>
                <li><a href="<?= url('contact') ?>">Contact</a></li>
            </ul>
        </section>

        <section class="colophone-col colophon-meta">
            <h4>Colophon</h4>
            <p>
                Built with Kirby CMS, hand-tuned CSS, and a little bit of PHP.
                Typeset in Instrument Serif and JetBrains Mono. Hosted in the Cloud.
            </p>
        </section>
    </div>

    <div class="colophon-base">
        <span>&copy; <?= date('Y') ?> <?= $site->title()->escape() ?></span>
        <span class="sep">•</span>
        <span>Made by hand in NL</span>
        <span class="sep">•</span>
        <a href="<?= url('privacy') ?>">Privacy</a>
        <span class="sep">•</span>
        <a href="<?= url('insights/feed.xml') ?>">RSS</a>
        <span class="build">
            <?= option('reacien.build', 'v2-dev') ?>
        </span>
    </div>
</footer>

</body>
</html>