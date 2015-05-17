<?php
namespace MDegayon\Controllers;
/**
 * Description of ConnectionLogController
 *
 * @author Miguel Degayon
 */
use Silex\Application;
use Silex\ControllerProviderInterface;

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
        return "showConnections";
    }
}

?>
