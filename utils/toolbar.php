<?php require_once __DIR__.'/../classes/Auth.php';
use FestivalCloud\Auth;

try {
    $auth = new Auth();
} catch (Exception $ex) {
    exit($ex->getMessage());
}
?>



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

        if ($auth->isAuthenticated()) {
            echo '<a class="nav-link" href="'.BASE_URL.'/views/profile/index.php">Profile</a>';
        } else {
            echo '<a class="nav-link" href="https://ryangraves08-is-my-gmail-password.auth.us-east-1.amazoncognito.com/login?client_id=n2lj9229idf26f6k7sgfbbtoq&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+phone+profile&redirect_uri=http://localhost/festivalCloud-scenario-1/secure_page.php">Login / Register</a>';
        }
        ?>
      </div>
    </div>
  </div>
</nav>
<br>
<br>



