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
use Symfony\Component\HttpFoundation\Request as Request;
use \Symfony\Component\HttpKernel\HttpKernelInterface as HttpKernelInterface;
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
        if(!$quote || strlen(trim($quote)) == 0){
            return $app->handle(Request::create('/'), 
                    HttpKernelInterface::SUB_REQUEST, false); 
        }
        
        if(     !(  $app['session']->get('user', false) ) || 
                !(  $app['session']->get('api', false) )    ){
            
            if (! ($this->serverConnect($app)) ){

                //Add error to template
                $app['session']->set('errorMessage', 
                                    'Unable to connect to remote API');
                
                return $app->handle(Request::create('/'), 
                    HttpKernelInterface::SUB_REQUEST, false);  
            }
        }
        
        $user = $app['session']->get('user');
        $api = $app['session']->get('api');

        $userHelper = new \MDegayon\RemoteHelper\WizUserHelper($api, $user); 
        $event = $userHelper->getFirstEvent();
                
        if($event){
            
            $eventHelper = new \MDegayon\RemoteHelper\WizEventHelper($api, $event);
            try{
                $eventHelper->addMessageToStream($quote);
            }catch(\InvalidArgumentException $e){
                //Add error to template
                $app['session']->set('errorMessage', 
                                    'Error while adding quote');
            }
        }
        return $app->handle(Request::create('/'), 
            HttpKernelInterface::SUB_REQUEST, false);
    }
    
    public function showStream(Application $app)
    {
        
        $user = $app['session']->get('user', false);
        $api = $app['session']->get('api', false);
        
        //TODO : Check if session has expired
        //TODO : Handle scenario where only user or api can't be found. Try to connect again
        //TODO : Replace Session by cache         
        if(!$user || !$api){

            if ($this->serverConnect($app)){

                $user = $app['session']->get('user');
                $api = $app['session']->get('api');   
                
            }else{
                //Add error to template;
                return $app['twig']->render('index.twig', array( 
                    'errorMessage' => 'Unable to connect to remote server') );
            }
        }
        
        $userHelper = new \MDegayon\RemoteHelper\WizUserHelper($api, $user); 
        $stream  = $userHelper->getFirstStream();       
        
        $vars = array(  'name' => $stream->getOwner()->getName(),
                        'messages' => $stream->getMessages(),);
        
        if( $app['session']->get('errorMessage', false) ){
            
            $vars['errorMessage'] = $app['session']->get('errorMessage', false);
            $app['session']->remove('errorMessage');
            unset($_SESSION['errorMessage']);
        }
        
        return $app['twig']->render('index.twig', $vars );
    }
    
    private function getHashForConnection()
    {
        
            $conf = new \MDegayon\Conf\Config();       

            return sha1(   $conf->getParam('email'). 
                            $conf->getParam('app_id'). 
                            $conf->getParam('app_secret'));         
        
    }
    
    private function serverConnect(Application $app)
    {
        
        $success = true;
        
        $hash = $this->getHashForConnection();
        $conf = new \MDegayon\Conf\Config();
        $connectionResponse  = API::connect($conf->getParam('email'), 
                                    $conf->getParam('secret'), 
                                    $conf->getParam('app_id'), 
                                    $hash);
        
        if($connectionResponse){
            $app['session']->set('user', $connectionResponse[API::USER_KEY]);
            $app['session']->set('api', $connectionResponse[API::SESSION_API_KEY]);
            
        }else{
            $success = false;
        }
        return $success;
    }
}

?>
