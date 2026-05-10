<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Site $site */
?>

<!doctype html>
<html lang="en" data-theme="light" data-accent="green">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>
        <?= $page->isHomePage() ? $site->title()->escape() : $page->title()->escape() . ' | ' . $site->title()->escape() ?>
    </title>

    <meta name="description" content="<?= $page->description()->or($site->description())->escape() ?>">

    <?= css([
        'assets/css/global.css',
        'assets/css/templates/' . $page->template()->name() . '.css'
    ]) ?>

    <?php if ($page->isHomePage()): ?>
        <?= js('assets/js/boot.js') ?> 
    <?php endif; ?>

    <?= js([
        'assets/js/chrome.js',
        'assets/js/cmdk.js',
        'assets/js/templates/' . $page->template()->name() . '.js'
    ], ['defer' => true]) ?>
</head>
<body>

<header class="chrome">
    <div class="chrome-inner">
        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="<?= $site->url() ?>" class="root">
                <span class="root-text">
                    <span class="root-main">reacien</span><span class="accent">.dev</span>
                </span>
            </a>

            <?php snippet('breadcrumbs') ?>
        </nav>

        <div class="chrome-right">
            <button class="cmdk-trigger" type="button" data-cmdk-trigger aria-label="Open command palette">
                <span class="prompt">&gt;</span>
                <span class="placeholder">Type to navigate</span>
                <span class="kbd">K</span>
            </button>

            <button class="theme-btn" type="button" id="theme-toggle-inline" aria-label="Toggle theme">
                <span class="theme-icon">◎</span>
            </button>
        </div>
    </div>
</header>

<?php snippet('cmd-palette') ?>