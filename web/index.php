<?php 
require_once __DIR__.'/../vendor/autoload.php';

use MDegayon\Controllers\StreamController as StreamController;
use MDegayon\Controllers\ConnectionLogController as ConnectionLogController;
use MDegayon\Cache\CacheInterface as CacheInterface;
use MDegayon\Cache\SessionCache as SessionCache;
use MDegayon\Cache\RedisCache as RedisCache;


$app = new Silex\Application();

//Binding routes with controllers
$app->mount('/', new StreamController());
$app->mount('/connections', new ConnectionLogController());


//Registering session, templating and serialization providers
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SerializerServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), 
                array( 'twig.path' => __DIR__.'/../app/Views',));

// Set up cache (RedisCache)
Predis\Autoloader::register();

$cache = RedisCache::getInstance();
$cache->init(new Predis\Client(), $app['serializer']);
$app['cache'] = $cache;

// Set up cache (SessionCache)
/*
$cache = SessionCache::getInstance();
$cache->init($app['session']);
$app['cache'] = $cache;
*/

//Set up stats
$stats = MDegayon\WiseAPI\APIStats::getInstance();
$stats->init($cache);

$app->run();


?>
