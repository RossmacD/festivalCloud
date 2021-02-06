<?php define('BASE_URL', 'http://localhost/festivalCloud-scenario-1'); ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <div class="container">
    <a class="navbar-brand" href="#">FestivalCloud</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <?php
        require_once 'functions.php';

        echo '<a class="nav-link" href="'.BASE_URL.'/">Home</a>';
        echo '<a class="nav-link" href="'.BASE_URL.'/views/festivals/index.php">Festivals</a>';
        echo '<a class="nav-link" href="'.BASE_URL.'/views/stages/index.php">Stages</a>';
        echo '<a class="nav-link" href="'.BASE_URL.'/views/shows/index.php">Shows</a>';
        echo '<a class="nav-link" href="'.BASE_URL.'/views/performers/index.php">Performers</a>';

        echo '<a class="nav-link" href="'.BASE_URL.'/views/profile/index.php">Profile</a>';
        ?>
      </div>
    </div>
  </div>
</nav>
<br>
<br>



