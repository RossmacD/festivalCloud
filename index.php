<?php require_once __DIR__.'/classes/Auth.php';

use FestivalCloud\Auth;

define('APP_ROOT', __DIR__); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <?php require 'utils/styles.php'; ?>
        <?php require 'utils/scripts.php'; ?>
    </head>
    <body>
      <?php require 'utils/toolbar.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                  <br>
                    <h2>Welcome to the Cloud Festivals Website</h2>
                    <?php try {
    $auth = new Auth();
    if (!$auth->isAuthenticated()) {
        echo '<p>You are viewing as a guest, login to view more</p>';
    }
} catch (Exception $ex) {
    exit($ex->getMessage());
}?>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php require 'utils/footer.php'; ?>
                </div>
            </div>
        </div>
    </body>
</html>
