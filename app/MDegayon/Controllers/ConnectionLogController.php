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
use MDegayon\WiseAPI\APIStats as APIStats;

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
        $data = APIStats::getInstance();
        
        return $app['twig']->render('connections.twig', 
                        array ( 'connections' => $data->getData(),
                                'streamLink' => '/testAPI/web/index.php',
                                'connectionsLink' => '',));
    }
}

?>
