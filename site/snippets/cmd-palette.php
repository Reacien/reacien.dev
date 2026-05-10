<?php

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
    'label' => 'Toggle theme (light ↔ dark)',
    'hint'  => '⇧ T',
    'js'    => "document.getElementById('theme-toggle')?.click()",
];
$items[] = [
    'group' => 'actions',
    'k'     => 'accent',
    'label' => 'Cycle accent color',
    'hint'  => '5 options',
    'js'    => "window.rcCycleAccent && window.rcCycleAccent()",
];
$items[] = [
    'group' => 'actions',
    'k'     => 'email',
    'label' => 'Copy email address',
    'hint'  => 'hi@reacien.dev',
    'js'    => "navigator.clipboard?.writeText('hi@reacien.dev')",
];

// External
$items[] = ['group' => 'external', 'k' => 'gh',   'label' => 'github · @Reacien',   'hint' => '↗', 'href' => 'https://github.com/Reacien',   'external' => true];
$items[] = ['group' => 'external', 'k' => 'tw',   'label' => 'twitter · @Reacien_', 'hint' => '↗', 'href' => 'https://twitter.com/Reacien_', 'external' => true];
$items[] = ['group' => 'external', 'k' => 'nick', 'label' => 'nickname',            'hint' => '↗', 'href' => 'https://mynickname.com/reacien', 'external' => true];
$items[] = ['group' => 'external', 'k' => 'kofi', 'label' => 'buy me a coffee',     'hint' => '↗', 'href' => 'https://ko-fi.com/reacien_', 'external' => true];

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
            <span class="mono">↑↓ navigate</span>
            <span class="mono">↵ run</span>
            <span class="mono">esc close</span>
        </div>
    </div>

    <script type="application/json" id="cmdk-data">
        <?= json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
</div>