<?php snippet('header') ?>

<main class="page error">
  <div class="wrap">
    <header class="page-head">
      <p class="label">Error</p>
      <h1>Page not found</h1>
    </header>

    <section class="page-body text">
      <p>Sorry, the page you are looking for could not be found.</p>
      <p><a href="<?= $site->url() ?>">Back to home</a></p>
    </section>
  </div>
</main>

<?php snippet('footer') ?>