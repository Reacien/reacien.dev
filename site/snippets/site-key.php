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
        'desc' => 'short write-ups on what i’m learning and breaking',
    ],
    [
        'num'  => '03',
        'name' => 'about',
        'href' => url('about'),
        'desc' => 'who i am, what i use, how i got here',
    ],
    [
        'num'  => '04',
        'name' => 'contact',
        'href' => url('contact'),
        'desc' => 'email, socials, and the fastest ways to reach me',
    ],
];
?>

<aside class="site-key" aria-label="Quick tour">
  <span class="crop-tl" aria-hidden="true"></span>
  <span class="crop-tr" aria-hidden="true"></span>
  <span class="crop-bl" aria-hidden="true"></span>

  <header class="site-key-head">
    <p class="label">site key</p>
  </header>

  <h2 class="site-key-title">
    hi, i’m <em>reacien.</em>
  </h2>

  <p class="site-key-intro">
    dutch software developer — shipping practical tools,
    clean uis, and a little bit of automation in the seams.
  </p>

  <hr class="dashed">

  <p class="site-key-section-label label">
    what you'll find here
  </p>

  <ol class="site-key-list">
    <?php foreach ($items as $item): ?>
      <li class="site-key-row">
        <span class="num"><?= $item['num'] ?></span>
        <a href="<?= $item['href'] ?>" class="name">
          <?= $item['name'] ?>
        </a>
        <span class="desc">
          <?= $item['desc'] ?>
        </span>
      </li>
    <?php endforeach; ?>
  </ol>

  <div class="site-key-foot">
    <span class="kbd-note mono">
      pro tip: tap <span class="kbd">K</span> to jump around.
    </span>
  </div>
</aside>