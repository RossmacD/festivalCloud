
<?php require_once __DIR__.'/../classes/StaticFile.php';
use FestivalCloud\StaticFile;

if ('true' !== $_ENV['USE_S3_FILES']) {
    echo '<script type="text/javascript" src="'.BASE_URL.'/scripts/jquery-3.5.1.min.js"></script>';
    echo '<script type="text/javascript" src="'.BASE_URL.'/scripts/bootstrap.min.js"></script>';
    echo '<script type="text/javascript" src="'.BASE_URL.'/scripts/main.js"></script>';
} else {
    $files = new StaticFile();
    echo '<script type="text/javascript" src="'.$files->getFileLink('scripts/jquery-3.5.1.min.js').'"></script>';
    echo '<script type="text/javascript" src="'.$files->getFileLink('scripts/bootstrap.min.js').'"></script>';
    echo '<script type="text/javascript" src="'.$files->getFileLink('scripts/main.js').'"></script>';
}
?>



