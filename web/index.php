<?php 
require_once __DIR__.'/../vendor/autoload.php';


use MDegayon\Controllers\StreamController as StreamController;
use MDegayon\Controllers\ConnectionLogController as ConnectionLogController;


$app = new Silex\Application();

$app->mount('/', new StreamController());
$app->mount('/connections', new ConnectionLogController());



$app->run();

?>