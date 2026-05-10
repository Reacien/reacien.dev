<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Site $site */
?>

<!doctype html>
<html lang="en">

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

    <script>
    (() => {
      const root = document.documentElement;

      try {
        const savedTheme = localStorage.getItem('rc-theme');
        if (savedTheme === 'light' || savedTheme === 'dark') {
          root.dataset.theme = savedTheme;
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          root.dataset.theme = 'dark';
        } else {
          root.dataset.theme = 'light';
        }
      } catch {
        root.dataset.theme =
          window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
      }

      try {
        const savedAccent = localStorage.getItem('rc-accent');
        const valid = ['green','red','blue','magenta','mono'];
        if (savedAccent && valid.includes(savedAccent)) {
          root.dataset.accent = savedAccent;
        } else {
          root.dataset.accent = 'green';
        }
      } catch {
        root.dataset.accent = 'green';
      }
    })();
    </script>
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
            <div class="cmdk-inline">
            <span class="prompt">❯❯</span>

            <input
                type="search"
                class="cmdk-inline-input cmdk-hint-touch-hide"
                data-cmdk-inline
                placeholder="type to navigate"
                autocapitalize="off"
                autocomplete="off"
                spellcheck="false"
            >

            <span class="kbd mono cmdk-hint-touch-hide">
                <span class="cmdk-mod">ctrl</span> + shift + k
            </span>

            <button type="button" class="cmdk-inline-touch" data-cmdk-trigger>
                cmd
            </button>
            </div>

            <button
                class="theme-btn"
                type="button"
                data-theme-trigger
            >
                <span class="theme-icon" data-theme-icon>☀</span>
            </button>
        </div>
    </div>
</header>

<?php snippet('cmd-palette') ?>