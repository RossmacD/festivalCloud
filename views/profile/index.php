<?php
require_once '../../classes/Auth.php';
use FestivalCloud\Auth;

try {
    $auth = new Auth();
} catch (Exception $ex) {
    exit($ex->getMessage());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php require '../../utils/styles.php'; ?>
        <?php require '../../utils/scripts.php'; ?>
    </head>
    <body>
      <?php require '../../utils/toolbar.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/header.php'; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2>Profile details</h2>
                    <?php if ($auth->isAuthenticated()) {
    $user = $auth->getUser(); ?>
    <h3>Name:</h3>
    <p><?php echo $user->name; ?></p>
    <h3>Email:</h3>
    <p><?php echo $user->email; ?></p>
    <h3>Phone Number:</h3>
    <p><?php echo $user->phone_no; ?></p>
    <?php
} else {
        echo 'Not Authorised, log in to view';
    } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/footer.php'; ?>
                </div>
            </div>
        </div>
    </body>
</html>
