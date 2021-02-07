<?php require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();
define('BASE_URL', 'http://localhost/festivalCloud-scenario-1');
echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/bootstrap.min.css">';
echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/main.css">';
?>

