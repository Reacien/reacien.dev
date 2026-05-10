<?php

$items = [
    [
        'num'  => '01',
        'name' => 'projects',
        'href' => url('projects'),
        'desc' => 'tools, sites & automations — shipped, in-flight, and in the drawer',
    ],
    [
        'num'  => '02',
        'name' => 'insights',
        'href' => url('insights'),
        'desc' => 'short write-ups on what I&rsquo;m learning and breaking',
    ],
    [
        'num'  => '03',
        'name' => 'about',
        'href' => url('about'),
        'desc' => 'who I am, what I use, how I got here',
    ],
    [
        'num'  => '04',
        'name' => 'contact',
        'href' => url('contact'),
        'desc' => 'email, socials, and the fastest ways to reach me',
    ],
];
?>

<aside class="site-key" aria-label="Site key">
  <span class="crop-tl" aria-hidden="true"></span>
  <span class="crop-tr" aria-hidden="true"></span>
  <span class="crop-bl" aria-hidden="true"></span>

  <header class="site-key-head">
    <p class="label">Site key</p>
  </header>

  <h2 class="site-key-title">
    reacien.<em>dev</em>
  </h2>

  <p class="site-key-blurb">
    Four main sections to explore — start wherever you like.
  </p>

  <hr class="dashed">

  <ol class="site-key-list">
    <?php foreach ($items as $item): ?>
      >
        <a href="<?= $item['href'] ?>">
          <span class="num"><?= $item['num'] ?></span>
          <span class="name"><?= $item['name'] ?></span>
          <span class="desc"><?= $item['desc'] ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ol>

  <div class="site-key-foot">
    <span class="muted">Pro tip: tap K to jump around.</span>
  </div>
</aside>