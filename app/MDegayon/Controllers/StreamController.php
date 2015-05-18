<?php
namespace MDegayon\Controllers;
/**
 * Controller responsible of handling the messages stream
 *
 * @author Miguel Degayon
 */
use Silex\Application;
use Silex\ControllerProviderInterface;
use MDegayon\WiseAPI\WisemblyAPIConnection as API;
//use MDegayon\Helper\WizUserHelper as WizUserHelper;
//use \MDegayon\Command\UserFirstStreamCommande as UserFirstStreamCommande;

class StreamController implements ControllerProviderInterface
{
    
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get(  '/', 
                            array($this, 
                            'showStream'))->bind('show_stream');
        
        return $controllers;
    }
    
    public function showStream(Application $app)
    {
        session_start();

        $user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
        $api = isset($_SESSION['api']) ? $_SESSION['api'] : false;
        
        //TODO : Check if session has expired
        //TODO : Handle scenario where only user or api can't be found. Try to connect again
        //TODO : Replace Session by cache 
        
        if(!$user || !$api){

//            die("if");
            
            $hash = $this->getHashForConnection();
            $conf = new \MDegayon\Conf\Config();
            $connectionResponse  = API::connect($conf->getParam('email'), 
                                        $conf->getParam('secret'), 
                                        $conf->getParam('app_id'), 
                                        $hash);
            if($connectionResponse){
                $_SESSION['user'] = $connectionResponse[API::USER_KEY];
                $_SESSION['api'] = $connectionResponse[API::SESSION_API_KEY];
            }else{
               //Some error 
            }
        }
        
        $userHelper = new \MDegayon\RemoteHelper\WizUserHelper($api, $user); 
        $stream  = $userHelper->getFirstStream();
        
        
        return $app['twig']->render('index.twig', array(
            'name' => $stream->getOwner()->getName(),
            'messages' => $stream->getMessages(),
        ));
        
        
//        $userFirstStreamCommand = new UserFirstStreamCommande($user, $api); 
      
        
        return "showStream!";
    }
    
    private function getHashForConnection(){
        
            $conf = new \MDegayon\Conf\Config();       

            return sha1(   $conf->getParam('email'). 
                            $conf->getParam('app_id'). 
                            $conf->getParam('app_secret'));         
        
    }
}

?>
