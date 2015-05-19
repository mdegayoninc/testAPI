<?php
namespace MDegayon\Controllers;
/**
 * Description of ConnectionLogController
 *
 * @author Miguel Degayon
 */
use Silex\Application;
use Silex\ControllerProviderInterface;
use MDegayon\Cache\CacheInterface;
use MDegayon\WiseAPI\WisemblyAPIConnection as APIConnection;

class ConnectionLogController implements ControllerProviderInterface
{
    
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get(  '/', 
                            array($this, 
                            'showConnections'))->bind('show_connections');
        
        return $controllers;
    }
    
    public function showConnections(Application $app)
    {
        //Get cache from App
        $cache = $app['cache'];
        
        return $app['twig']->render('connections.twig', 
                        array ('connections' => $cache->get(APIConnection::API_STATS_KEY)) );
    }
}

?>
