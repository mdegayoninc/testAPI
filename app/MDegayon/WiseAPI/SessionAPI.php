<?php
namespace MDegayon\WiseAPI;

use \Httpful\Request as Request;
use MDegayon\WiseAPI\WisemblyAPIConnection as Connection;
use MDegayon\Wiz\WizEvent as Event;
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
        
        $events = false;

        try{

            $response = Request::get( 
                    Connection::API_ADDRESS .'/'.
                    Connection::API_V4 . '/user/'.$userHash.'/wiz')
                ->addHeader(SessionAPI::WISE_TOKEN_HEADER, $this->token)
                ->send(); 

        }catch(Exception $e){
            $response = false;
            $events = false;    
        }
        
        if ($response && 
            $response->code != WisemblyAPIConnection::SUCCESSFUL_REQUEST_CODE){

            throw new InvalidArgumentException
                    ("Error while trying to connect to the API : ");
        }else{
            
            $events = $this->createEventsFromResponse($response);
        }
        

        return $events;      
    }
    
    public function getStream()
    {
        
    }
    
    public function addMessageToStream()
    {
        
        
    }
    
    private function createEventsFromResponse(\Httpful\Response $response)
    {
        $events = array();
        
        foreach($response->body->success->data as $currentRawEvent){
            
            $events[] = $this->parseEvent($currentRawEvent);
        }
        
        return $events;
    }
    
    private function parseEvent($rawEvent)
    {
        return new Event($rawEvent->title, $rawEvent->keyword);
    }
    
    
}

?>
