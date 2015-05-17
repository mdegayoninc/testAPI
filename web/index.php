<?php 

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/jarl/', function () use ($app) {
    return 'jarl';
});


$app->get('/', function () use ($app) {
    return 'Index';
});

$app->run();

?>
