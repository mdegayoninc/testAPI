<?php 
require_once __DIR__.'/../vendor/autoload.php';

use MDegayon\Controllers\StreamController as StreamController;
use MDegayon\Controllers\ConnectionLogController as ConnectionLogController;
use MDegayon\Cache\CacheInterface as CacheInterface;
use MDegayon\Cache\SessionCache as SessionCache;


$app = new Silex\Application();


$app->mount('/', new StreamController());
$app->mount('/connections', new ConnectionLogController());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), 
                array( 'twig.path' => __DIR__.'/../app/Views',
                ));

////Set up cache 
$cache = SessionCache::getInstance();
$cache->init($app['session']);

//Init cache stats
if(! $cache->get(MDegayon\WiseAPI\WisemblyAPIConnection::API_STATS_KEY)){
    
    $cache->set(MDegayon\WiseAPI\WisemblyAPIConnection::API_STATS_KEY, array());
}
$app['cache'] = $cache;

$app->run();


?>
