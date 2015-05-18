<?php 
require_once __DIR__.'/../vendor/autoload.php';

use MDegayon\Controllers\StreamController as StreamController;
use MDegayon\Controllers\ConnectionLogController as ConnectionLogController;
use MDegayon\Cache\CacheInterface as CacheInterface;
use MDegayon\Cache\SessionCache as SessionCache;


$app = new Silex\Application();

////Set cache 
//$app['cache'] = SessionCache::getInstance();

$app->mount('/', new StreamController());
$app->mount('/connections', new ConnectionLogController());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), 
                array( 'twig.path' => __DIR__.'/../app/Views',
                ));

$app->run();


?>
