<?php require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

require_once __DIR__.'/../classes/StaticFile.php';
use FestivalCloud\StaticFile;

define('BASE_URL', $_ENV['BASE_URL']);
if ('true' !== $_ENV['USE_S3_FILES']) {
    echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/main.css">';
} else {
    $files = new StaticFile();
    echo '<link rel="stylesheet" href="'.$files->getFileLink('styles/bootstrap.min.css').'">';
    echo '<link rel="stylesheet" href="'.$files->getFileLink('styles/main.css').'">';
}?>

