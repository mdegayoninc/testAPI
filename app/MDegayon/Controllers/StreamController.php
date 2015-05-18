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
    
    
    //TODO -> On the way to become a fat controller. REFACTOR ??
    //It doesn't need a serverConnect method. That logic should be elsewhere.
    //A RemoteDB encapsulating all that stuff, maybe.
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get(  '/', 
                            array($this, 
                            'showStream'))->bind('show_stream');
        
        $controllers->post(  '/', 
                            array($this, 
                            'addQuote'))->bind('add_qoute');
        
        return $controllers;
    }
    
    public function addQuote(Application $app)
    {
        
        //Handle form        
        $request = $app['request_stack']->getCurrentRequest();
        
        $quote = $request->get('quote');
        
        //If there's no quote, we don't need to go any further. Just show stream
        if(!$quote){
            
            return $this->showStream($app);  
        }
        
        session_start();
        if(!isset($_SESSION['user']) || !isset($_SESSION['api'])){
            
            if (! ($this->serverConnect()) ){

                //Add error to template
            }
        }
        
        $user = $_SESSION['user'];
        $api = $_SESSION['api'];
        
        $userHelper = new \MDegayon\RemoteHelper\WizUserHelper($api, $user); 
        $event = $userHelper->getFirstEvent();
        
        if($event){
            
            $eventHelper = new \MDegayon\RemoteHelper\WizEventHelper($api, $event);
            try{
                $eventHelper->addMessageToStream($quote);
            }catch(Exeption $e){
                //Add error to template
            }
        }
        
        return $this->showStream($app);
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

            if ($this->serverConnect()){
                
                $user = $_SESSION['user'];
                $api = $_SESSION['api'];
             
            }else{
                
            }
        }
        
        $userHelper = new \MDegayon\RemoteHelper\WizUserHelper($api, $user); 
        $stream  = $userHelper->getFirstStream();       
        
        return $app['twig']->render('index.twig', array(
            'name' => $stream->getOwner()->getName(),
            'messages' => $stream->getMessages(),
        ));
        
        
//        $userFirstStreamCommand = new UserFirstStreamCommande($user, $api); 
      
        
//        return "showStream!";
    }
    
    private function getHashForConnection(){
        
            $conf = new \MDegayon\Conf\Config();       

            return sha1(   $conf->getParam('email'). 
                            $conf->getParam('app_id'). 
                            $conf->getParam('app_secret'));         
        
    }
    
    private function serverConnect(){

        $success = true;
        
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
            $success = false;
        }                
    }
}

?>
