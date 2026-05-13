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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php
    $metaTitle       = $page->content()->get('meta_title')->or($page->title());
    $metaDescription = $page->content()->get('meta_description')
        ->or($page->content()->get('excerpt'))
        ->or($page->content()->get('description'))
        ->or($site->description());
    $ogImageFile     = $page->content()->get('og_image')->toFile()
        ?? $site->content()->get('default_og_image')->toFile();
    $ogImageUrl      = $ogImageFile ? $ogImageFile->url() : null;
    $fullTitle       = $page->isHomePage()
        ? $metaTitle->value()
        : $metaTitle->value() . ' | ' . $site->title()->value();
    ?>

    <title><?= esc($fullTitle) ?></title>

    <meta name="description" content="<?= $metaDescription->escape() ?>">
    <link rel="canonical" href="<?= $page->url() ?>">

    <link rel="icon" type="image/x-icon" href="/static/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/static/favicon/site.webmanifest">

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

    <?php
    $identityName     = $site->identity_name()->or('Reacien')->value();
    $identityRole     = $site->identity_role()->or('Software Developer')->value();
    $identityLocation = $site->identity_location()->value();
    $sameAs           = array_values(array_filter([
        $site->github_url()->or('https://github.com/Reacien')->value(),
        $site->twitter_url()->or('https://twitter.com/Reacien_')->value(),
        $site->nickname_url()->value(),
        $site->kofi_url()->value(),
    ]));

    $jsonLd = [];

    if ($page->isHomePage()) {
        $jsonLd[] = array_filter([
            '@context'    => 'https://schema.org',
            '@type'       => 'Person',
            'name'        => $identityName,
            'url'         => $site->url(),
            'jobTitle'    => $identityRole,
            'homeLocation' => $identityLocation ? [
                '@type' => 'Place',
                'name'  => $identityLocation,
            ] : null,
            'sameAs'      => $sameAs ?: null,
        ]);
        $jsonLd[] = array_filter([
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $site->title()->value(),
            'url'      => $site->url(),
            'inLanguage' => 'en',
        ]);
    } elseif ($page->template()->name() === 'insight') {
        $datePublished = $page->date()->isNotEmpty() ? $page->date()->toDate('c') : null;
        $jsonLd[] = array_filter([
            '@context'      => 'https://schema.org',
            '@type'         => 'BlogPosting',
            'headline'      => $page->title()->value(),
            'description'   => $metaDescription->value() ?: null,
            'datePublished' => $datePublished,
            'dateModified'  => gmdate('c', $page->modified()),
            'image'         => $ogImageUrl,
            'url'           => $page->url(),
            'mainEntityOfPage' => $page->url(),
            'author'        => [
                '@type' => 'Person',
                'name'  => $identityName,
                'url'   => $site->url(),
            ],
            'publisher'     => [
                '@type' => 'Person',
                'name'  => $identityName,
                'url'   => $site->url(),
            ],
            'keywords' => $page->tags()->isNotEmpty() ? implode(', ', array_map('trim', $page->tags()->split(','))) : null,
        ]);
    } elseif ($page->template()->name() === 'project') {
        $jsonLd[] = array_filter([
            '@context'    => 'https://schema.org',
            '@type'       => 'CreativeWork',
            'name'        => $page->title()->value(),
            'description' => $metaDescription->value() ?: null,
            'image'       => $ogImageUrl,
            'url'         => $page->url(),
            'dateCreated' => $page->year()->isNotEmpty() ? $page->year()->value() : null,
            'creator'     => [
                '@type' => 'Person',
                'name'  => $identityName,
                'url'   => $site->url(),
            ],
            'keywords' => $page->tech()->isNotEmpty() ? implode(', ', array_map('trim', $page->tech()->split(','))) : null,
        ]);
    }
    ?>
    <?php if (!empty($jsonLd)): ?>
        <?php foreach ($jsonLd as $ld): ?>
            <script type="application/ld+json">
                <?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>
            </script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php
    $tplName = strtolower($page->template()->name());
    $tplCss  = 'assets/css/templates/' . $tplName . '.css';
    $tplJs   = 'assets/js/templates/'  . $tplName . '.js';
    $rootDir = $kirby->root('index');

    $cssFiles = ['assets/css/global.css'];
    if (file_exists($rootDir . '/' . $tplCss)) {
        $cssFiles[] = $tplCss;
    }
    // Kirby may not resolve the correct template name on error pages,
    // so explicitly force-load error.css whenever this is the 404 page.
    if ($page->isErrorPage()) {
        $errorCss = 'assets/css/templates/error.css';
        if (!in_array($errorCss, $cssFiles) && file_exists($rootDir . '/' . $errorCss)) {
            $cssFiles[] = $errorCss;
        }
    }

    $jsFiles = ['assets/js/boot.js', 'assets/js/chrome.js', 'assets/js/cmdk.js'];
    if (file_exists($rootDir . '/' . $tplJs)) {
        $jsFiles[] = $tplJs;
    }
    ?>

    <?= css(array_map(fn($file) => $file . '?v=' . filemtime($kirby->root('index') . '/' . $file), $cssFiles)) ?>

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
                    window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ?
                    'dark' :
                    'light';
            }

            try {
                const savedAccent = localStorage.getItem('rc-accent');
                const valid = ['green', 'red', 'blue', 'magenta', 'mono'];
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

    <?php snippet('boot-overlay') ?>

    <a class="skip-link" href="#main-content">Skip to main content</a>

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
                        spellcheck="false">

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
                    data-theme-trigger>
                    <span class="theme-icon" data-theme-icon>☀</span>
                </button>
            </div>
        </div>
    </header>

    <?php snippet('cmd-palette') ?>

    <div id="main-content" tabindex="-1"></div>