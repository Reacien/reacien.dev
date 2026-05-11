<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Site $site */
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?php
        $metaTitle       = $page->content()->get('meta_title')->or($page->title());
        $metaDescription = $page->content()->get('meta_description')->or($page->description())->or($site->description());
        $ogImageFile     = $page->content()->get('og_image')->toFile();
        $ogImageUrl      = $ogImageFile ? $ogImageFile->url() : null;
        $fullTitle       = $page->isHomePage()
            ? $metaTitle->value()
            : $metaTitle->value() . ' | ' . $site->title()->value();
    ?>

    <title><?= esc($fullTitle) ?></title>

    <meta name="description" content="<?= $metaDescription->escape() ?>">
    <link rel="canonical" href="<?= $page->url() ?>">

    <link rel="icon" type="image/svg+xml" href="<?= url('assets/images/logo-light.svg') ?>" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/svg+xml" href="<?= url('assets/images/logo-dark.svg') ?>" media="(prefers-color-scheme: dark)">

    <meta property="og:site_name" content="<?= $site->title()->escape() ?>">
    <meta property="og:title" content="<?= esc($fullTitle) ?>">
    <meta property="og:description" content="<?= $metaDescription->escape() ?>">
    <meta property="og:url" content="<?= $page->url() ?>">
    <meta property="og:type" content="<?= $page->isHomePage() ? 'website' : 'article' ?>">
    <?php if ($ogImageUrl): ?>
    <meta property="og:image" content="<?= esc($ogImageUrl) ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="<?= $ogImageUrl ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= esc($fullTitle) ?>">
    <meta name="twitter:description" content="<?= $metaDescription->escape() ?>">
    <?php if ($ogImageUrl): ?>
    <meta name="twitter:image" content="<?= esc($ogImageUrl) ?>">
    <?php endif; ?>

    <?php if ($page->isHomePage()): ?>
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'Person',
        'name'     => 'Reacien',
        'url'      => $site->url(),
        'jobTitle' => 'Software Developer',
        'sameAs'   => [
            'https://github.com/Reacien',
            'https://twitter.com/Reacien_',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT) ?>
    </script>
    <?php endif; ?>

    <?php
        $tplName = $page->template()->name();
        $tplCss  = 'assets/css/templates/' . $tplName . '.css';
        $tplJs   = 'assets/js/templates/'  . $tplName . '.js';
        $rootDir = $kirby->root('index');

        $cssFiles = ['assets/css/global.css'];
        if (file_exists($rootDir . '/' . $tplCss)) {
            $cssFiles[] = $tplCss;
        }

        $jsFiles = ['assets/js/chrome.js', 'assets/js/cmdk.js'];
        if (file_exists($rootDir . '/' . $tplJs)) {
            $jsFiles[] = $tplJs;
        }
    ?>

    <?= css($cssFiles) ?>

    <?php if ($page->isHomePage()): ?>
        <?= js('assets/js/boot.js', ['defer' => true]) ?>
    <?php endif; ?>

    <?= js($jsFiles, ['defer' => true]) ?>

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