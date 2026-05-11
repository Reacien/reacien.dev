<?php
/** @var \Kirby\Cms\Site $site */

$build       = reacienBuildInfo();
$short       = $build['short'];
$run         = $build['run'];
$timestamp   = $build['timestamp'] ?? null;
$lastPush    = $timestamp ? reacienRelativeTime($timestamp) : null;
$lastPushIso = $timestamp ? gmdate('c', $timestamp) : null;

$madeBy = $site->footer_made_by()->or('made by hand in nl')->value();

// Repo + commit URLs (link to GitHub when public; harmless 404 otherwise).
$github   = $site->github_url()->or('https://github.com/Reacien')->value();
$repoBase = rtrim($github, '/') . '/reacien.dev';
$commitUrl = $build['sha'] ? $repoBase . '/commit/' . $build['sha'] : $repoBase;

// Server time / locale (JS replaces with the visitor's local time on load).
$serverTz   = date_default_timezone_get();
$serverTime = date('H:i');
$serverAbbr = date('T');
?>

<aside class="footer-bar mono" aria-label="Site status">
    <div class="footer-bar-credits">
        <span>&copy; <?= date('Y') ?> reacien.dev</span>
        <span class="dot-sep" aria-hidden="true">·</span>
        <span><?= esc($madeBy) ?></span>
    </div>

    <ul class="footer-bar-status">
        <li class="footer-bar-item status-ok">
            <span class="status-dot" aria-hidden="true">●</span>
            all systems nominal
        </li>

        <li class="footer-bar-item">
            <a href="<?= esc($commitUrl) ?>" target="_blank" rel="noreferrer">
                build<?php if ($run): ?> <span class="build-run">#<?= esc($run) ?></span><?php endif; ?>
                <span class="build-sha">[<?= esc($short) ?>]</span>
            </a>
        </li>

        <?php if ($lastPush): ?>
            <li class="footer-bar-item">
                last push ·
                <time
                    datetime="<?= esc($lastPushIso) ?>"
                    data-relative-time="<?= esc($timestamp) ?>"
                ><?= esc($lastPush) ?></time>
            </li>
        <?php endif; ?>

        <li class="footer-bar-item">
            <a href="<?= esc($repoBase) ?>" target="_blank" rel="noreferrer">/source</a>
        </li>
    </ul>

    <div class="footer-bar-clock">
        <span
            class="clock-time"
            data-live-clock
            data-clock-tz="<?= esc($serverTz) ?>"
            aria-live="off"
        ><?= esc($serverTime) ?></span>
        <span class="clock-tz" data-live-clock-tz><?= esc($serverAbbr) ?></span>
        <span class="dot-sep" aria-hidden="true">·</span>
        <span class="clock-locale">NL</span>
    </div>
</aside>
