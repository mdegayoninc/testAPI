<?php
namespace MDegayon\WiseAPI;

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

    public static function getUserEvents($userHash)
    {
        
        $event = false;

        try{

            $response = Request::get( 
                    Connection::API_ADDRESS .'/'.
                    Connection::API_V4 . '/user/'.$eventKeyword.'/wiz')
                ->addHeader(SessionAPI::WISE_TOKEN_HEADER, $this->token)
                ->send(); 

        }catch(Exception $e){
            $event = false;    
        }
        
        $events = $this->createEventsFromResponse($response);

        return $connection;      
        
        
    }
    
    public static function getStream()
    {
        
    }
    
    public static function addMessageToStream()
    {
        
        
    }
    
    private function createEventFromResponse(Httpful\Response $response)
    {
        
    }
    
    
}

?>
