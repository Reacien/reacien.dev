<?php

$items = [];

foreach ([
  ['Home',     '/',         url()],
  ['About',    '/about',    url('about')],
  ['Projects', '/projects', url('projects')],
  ['Insights', '/insights', url('insights')],
  ['Contact',  '/contact',  url('contact')],
] as [$label, $hint, $href]) {
  $items[] = ['group'=>'navigate', 'k'=>strtolower($label), 'label'=>$label, 'hint'=>$hint, 'href'=>$href];
}

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

if ($projects = page('projects')) {
    $labels = ['wip'=>'In Progress', 'completed'=>'Completed', 'archived'=>'Archived'];
    foreach ($projects->children()->listed() as $p) {
        $status = $p->project_status()->value();
        $items[] = [
            'group' => 'projects',
            'k'     => $labels[$status] ?? $status,
            'label' => $p->title()->value(),
            'hint'  => '→ ' . ($labels[$status] ?? $status),
            'href'  => $p->url(),
        ];
    }
}

$items[] = ['group'=>'actions', 'k'=>'theme',  'label'=>'Toggle theme (light ↔ dark)', 'hint'=>'⇧ T',  'js'=>"document.getElementById('theme-toggle')?.click()"];
$items[] = ['group'=>'actions', 'k'=>'accent', 'label'=>'Cycle accent color',          'hint'=>'5 options', 'js'=>"window.rcCycleAccent && window.rcCycleAccent()"];
$items[] = ['group'=>'actions', 'k'=>'email',  'label'=>'Copy email address',           'hint'=>'hi@reacien.dev', 'js'=>"navigator.clipboard?.writeText('hi@reacien.dev')"];

$items[] = ['group'=>'external', 'k'=>'gh',    'label'=>'Github · @Reacien',     'hint'=>'↗', 'href'=>'https://github.com/Reacien',     'external'=>true];
$items[] = ['group'=>'external', 'k'=>'tw',    'label'=>'Twitter · @Reacien_',   'hint'=>'↗', 'href'=>'https://twitter.com/Reacien_',   'external'=>true];
$items[] = ['group'=>'external', 'k'=>'nick',  'label'=>'Nickname',              'hint'=>'↗', 'href'=>'https://mynickname.com/reacien', 'external'=>true];
$items[] = ['group'=>'external', 'k'=>'kofi',  'label'=>'Buy me a coffee',       'hint'=>'↗', 'href'=>'https://ko-fi.com/reacien_',     'external'=>true];
?>

<dialog class="cmdk-overlay" id="cmdk" hidden aria-label="Command Palette">
    <div class="cmdk-panel" role="document">
        <div class="cmdk-input">
            <span class="prompt">&gt;</span>
            <input id="cmdk-q" type="text" placeholder="type to navigate, search, or run..." autocomplete="off" />
            <span class="chip" id="cmdk-count">0 results</span>
        </div>
        <div class="cmdk-list" id="cmdk-list" role="listbox"></div>
        <div class="cmdk-empty" id="cmdk-empty" hidden>no matches</div>
        <div class="cmdk-foot">
            <span><span class="kbd">↑↓</span> navigate</span>
            <span><span class="kbd">↵</span> run</span>
            <span><span class="kbd">esc</span> close</span>
            <span class="spacer" id="cmdk-total"></span>
        </div>
    </div>
</dialog>

<script type="application/json" id="cmdk-data"><?= json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>