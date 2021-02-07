<?php require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();
define('BASE_URL', $_ENV['BASE_URL']);
echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/bootstrap.min.css">';
echo '<link rel="stylesheet" href="'.BASE_URL.'/styles/main.css">';
?>

