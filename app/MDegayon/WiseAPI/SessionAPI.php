<?php
namespace MDegayon\WiseAPI;

use \Httpful\Request as Request;
use MDegayon\WiseAPI\WisemblyAPIConnection as Connection;
/**
 * SessionAPI for logged in users
 *
 * @author Miguel Degayon
 */
class SessionAPI 
{

    private $token;

    const WISE_TOKEN_HEADER = 'Wisembly-Token';
    
    
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getUserEvents($userHash)
    {
        
        $event = false;

        try{

            $response = Request::get( 
                    Connection::API_ADDRESS .'/'.
                    Connection::API_V4 . '/user/'.$userHash.'/wiz')
                ->addHeader(SessionAPI::WISE_TOKEN_HEADER, $this->token)
                ->send(); 

        }catch(Exception $e){
            $event = false;    
        }
        
        $events = $this->createEventsFromResponse($response);

        return $connection;      
        
        
    }
    
    public function getStream()
    {
        
    }
    
    public function addMessageToStream()
    {
        
        
    }
    
    private function createEventFromResponse(Httpful\Response $response)
    {
        var_dump($response);
        die("Events from response");
        
    }
    
    
}

?>
