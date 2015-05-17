<?php
namespace MDegayon\Controllers;
/**
 * Controller responsible of handling the messages stream
 *
 * @author Miguel Degayon
 */
use Silex\Application;
use Silex\ControllerProviderInterface;

class StreamController implements ControllerProviderInterface
{
    
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get(  '/', 
                            array($this, 
                            'showStream'))->bind('foro_show');
        
        return $controllers;
    }
    
    public function showStream(Application $app)
    {
        echo "test!";
    }
}

?>
