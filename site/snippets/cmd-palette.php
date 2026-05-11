<?php
/** @var \Kirby\Cms\Site $site */

$publicEmail = $site->public_email()->or('hi@reacien.dev')->value();
$github      = $site->github_url()->or('https://github.com/Reacien')->value();
$twitter     = $site->twitter_url()->or('https://twitter.com/Reacien_')->value();
$nickname    = $site->nickname_url()->or('https://mynickname.com/reacien')->value();
$kofi        = $site->kofi_url()->or('https://ko-fi.com/reacien_')->value();

$items = [];

// Navigation
foreach ([
    ['Home',     '/',       url()],
    ['About',    '/about',  url('about')],
    ['Projects', '/projects', url('projects')],
    ['Insights', '/insights', url('insights')],
    ['Contact',  '/contact',  url('contact')],
] as [$label, $hint, $href]) {
    $items[] = [
        'group' => 'navigate',
        'k'     => strtolower($label),
        'label' => $label,
        'hint'  => $hint,
        'href'  => $href,
    ];
}

// Latest insights
if ($insights = page('insights')) {
    foreach ($insights->children()->listed()->sortBy('date', 'desc')->limit(3) as $post) {
        $items[] = [
            'group' => 'insights',
            'k'     => 'post',
            'label' => $post->title()->value(),
            'hint'  => $post->date()->toDate('d M Y'),
            'href'  => $post->url(),
        ];
    }
}

// Projects by status
if ($projects = page('projects')) {
    $labels = ['wip' => 'in progress', 'completed' => 'completed', 'archived' => 'archived'];
    foreach ($projects->children()->listed() as $p) {
        $status = $p->project_status()->value();
        $statusLabel = $labels[$status] ?? $status;
        $items[] = [
            'group' => 'projects',
            'k'     => $statusLabel,
            'label' => $p->title()->value(),
            'hint'  => '→ ' . $statusLabel,
            'href'  => $p->url(),
        ];
    }
}

// Actions
$items[] = [
    'group' => 'actions',
    'k'     => 'theme',
    'label' => 'theme: toggle light/dark',
    'hint'  => '⇧ T',
    'js'    => "window.rcToggleTheme && window.rcToggleTheme()",
];

$accentOptions = [
    'green'   => 'accent: green',
    'red'     => 'accent: red',
    'blue'    => 'accent: blue',
    'magenta' => 'accent: magenta',
    'mono'    => 'accent: mono',
];

foreach ($accentOptions as $key => $label) {
    $items[] = [
        'group' => 'actions',
        'k'     => 'accent ' . $key,
        'label' => $label,
        'hint'  => 'set accent to ' . $key,
        'js'    => "window.rcSetAccent && window.rcSetAccent('{$key}')",
    ];
}

$items[] = [
    'group' => 'actions',
    'k'     => 'accent cycle',
    'label' => 'accent: cycle through options',
    'hint'  => 'shortcut',
    'js'    => "window.rcCycleAccent && window.rcCycleAccent()",
];

$items[] = [
    'group' => 'actions',
    'k'     => 'email',
    'label' => 'copy email address',
    'hint'  => $publicEmail,
    'js'    => "navigator.clipboard?.writeText(" . json_encode($publicEmail, JSON_UNESCAPED_SLASHES) . ")",
];

$items[] = [
    'group' => 'actions',
    'k'     => 'boot replay',
    'label' => 'boot: replay intro sequence',
    'hint'  => 'rerun boot overlay',
    'js'    => "window.rcReplayBoot && window.rcReplayBoot()",
];

// External
if ($github)   { $items[] = ['group' => 'external', 'k' => 'gh',   'label' => 'github · @Reacien',   'hint' => '↗', 'href' => $github,   'external' => true]; }
if ($twitter)  { $items[] = ['group' => 'external', 'k' => 'tw',   'label' => 'twitter · @Reacien_', 'hint' => '↗', 'href' => $twitter,  'external' => true]; }
if ($nickname) { $items[] = ['group' => 'external', 'k' => 'nick', 'label' => 'nickname',            'hint' => '↗', 'href' => $nickname, 'external' => true]; }
if ($kofi)     { $items[] = ['group' => 'external', 'k' => 'kofi', 'label' => 'buy me a coffee',     'hint' => '↗', 'href' => $kofi,     'external' => true]; }

?>

<div class="overlay" id="cmdk" hidden>
    <div class="cmd-panel" role="dialog" aria-modal="true" aria-labelledby="cmdk-label">
        <div class="cmd-input">
            <span class="prompt">❯❯</span>
            <input
                id="cmdk-q"
                type="text"
                placeholder="type to navigate…"
                autocapitalize="off"
                autocomplete="off"
                spellcheck="false"
                aria-label="Command palette"
            >
            <span class="kbd mono">esc</span>
        </div>

        <div id="cmdk-empty" class="cmd-empty" hidden>
            <p>0 results</p>
            <p class="muted">no matches</p>
        </div>

        <div id="cmdk-list" class="cmd-list" role="listbox" aria-label="Commands"></div>

        <div class="cmd-foot">
            <span id="cmdk-count" class="mono">0 results</span>
            <span class="sep">·</span>
            <span id="cmdk-total" class="mono">0 commands</span>
            <span class="spacer"></span>

            <span class="mono">
                <span class="kbd">↑↓</span>
                navigate
            </span>

            <span class="mono">
                <span class="kbd">↵</span>
                run
            </span>

            <span class="mono">
                <span class="kbd">esc</span>
                close
            </span>
        </div>
    </div>

    <script type="application/json" id="cmdk-data">
        <?= json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>
    </script>
</div>