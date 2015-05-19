<?php
namespace MDegayon\WiseAPI;

use \Httpful\Request as Request;
use MDegayon\Wiz\WizUser as WizUser;
use MDegayon\Cache\CacheInterface as CacheInterface;
use \MDegayon\WiseAPI\APIInfoConnection as APIInfoConnection;


/**
 * Class responsable of connection to the Wisembly API
 *
 * @author Miguel Degayon
 */
class WisemblyAPIConnection
{
        
    private $cache;
    
    const   
            API_STATS_KEY = 'api_stats_key',
            USER_KEY = 'user',
            SESSION_API_KEY = 'api',
            API_ADDRESS = 'https://api.wisembly.com',
            SUCCESSFUL_REQUEST_CODE = 200,
            API_V4 =   'api/4';
    
    public function __construct(CacheInterface $cache) {
        
        $this->cache = $cache;
    }
    
    public function connect($email, $secret, $app_id, $hash)
    {
    
        $response = false;
        $connection = false;
            
        $body =     '{"email" : "'.$email.
                    '","secret":"'.$secret.
                    '","app_id" : "'.$app_id.
                    '", "hash" : "'.$hash.'"}';

        $response = Request::post( 
                WisemblyAPIConnection::API_ADDRESS .'/'.
                WisemblyAPIConnection::API_V4 . '/authentication')
                ->sendsJson()
                ->body($body)
                ->send();
        
        if ($response->code != WisemblyAPIConnection::SUCCESSFUL_REQUEST_CODE){

            throw new InvalidArgumentException
                    ("Error while trying to connect to the API : ");
        }
        $user = $this->createUserFromResponse($response);
        $sessionAPI = $this->createSessionAPI($response);
        
        //TODO: Add an exceptions for each error (Couldn't create user and couldn't create API)
        //TODO: Replace array with some CONNECTION RESPONSE OBJECT including both user and API. 
        //(with also maybe some error messages instead of exceptions in case something went wrong
        
        if($user && $sessionAPI){
            $response = array(  WisemblyAPIConnection::USER_KEY => $user,
                                WisemblyAPIConnection::SESSION_API_KEY => $sessionAPI,);
        }
        
        //Generate stats
        $stats = APIStats::getInstance();
        $stats->addApiDataUsage( 
                new APIInfoConnection(  'connection', 
                                        date(strtotime('now'),'c'),
                                        null,
                                        $user->getName()
                                        ));
        
        return  $response;
    }
    
    public function connectAnonymous($appId, $appSecret)
    {
        
    }    
    
//    TODO : Refactor these two methods (CreateXXXFrom...) might be placed in 
//    some parser classes
    private function createUserFromResponse(\Httpful\Response $response)
    {

        $user = false;
        
        try{
            return new WizUser( $response->body->success->data->user->hash, 
                                 $response->body->success->data->user->name, 
                                 $response->body->success->data->user->email, 
                                 $response->body->success->data->user->company);
           
        }catch (Exception $e){
           
            $user = false;
        }
       
        return $user;
    }
   
    private function createSessionAPI(\Httpful\Response $response)
    {
        $sessionAPI = false;
        
        try{
            $token = $response->body->success->data->token;
            
        }catch(Exception $e){}
        
        $sessionAPI = new \MDegayon\WiseAPI\SessionAPI
                        ($response->body->success->data->token, $this->cache);
        
        return $sessionAPI;
    }
}

?>
