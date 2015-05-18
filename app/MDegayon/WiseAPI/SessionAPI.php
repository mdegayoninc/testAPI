<?php
namespace MDegayon\WiseAPI;

use \Httpful\Request as Request;
use MDegayon\WiseAPI\WisemblyAPIConnection as Connection;
use MDegayon\Wiz\WizEvent as Event;
use MDegayon\Wiz\Message as Message;
use MDegayon\Wiz\MessageStream as MessageStream;
/**
 * SessionAPI for logged in users
 *
 * @author Miguel Degayon
 */
class SessionAPI 
{
    //TODO: Most functions looks almost the same. I'm repeating myself here. REFACTOR

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

            throw new \InvalidArgumentException
                    ("Error while trying to connect to the API : ");
        }else{
            
            $events = $this->createEventsFromResponse($response);
        }
        

        return $events;      
    }
    
    public function getStream($eventKeyword)
    {
        
        $stream = false;

        try{
            
            $response = Request::get( 
                    Connection::API_ADDRESS .'/'.
                    Connection::API_V4 . '/event/'.$eventKeyword.'/stream')
                ->addHeader(SessionAPI::WISE_TOKEN_HEADER, $this->token)
                ->send(); 

        }catch(Exception $e){
            $response = false;
            $stream = false;    
        }
        
        if ($response && 
            $response->code != WisemblyAPIConnection::SUCCESSFUL_REQUEST_CODE){

            throw new \InvalidArgumentException
                    ("Error while trying to connect to the API : ");
        }else{
            
            $stream = $this->createStreamFromResponse($response);
        }
        

        return $stream;          
        
    }
    
    public function addMessageToStream(Event $event, Message $message)
    {
        $userName = $message->getUser() ? '"'.$message->getUser().'"' : 'null';
        $body = '{"quoteCreate" : {"hash" : "'.$message->getHash().
                                '", "quote" : "'.$message->getQuote().
                                '", "anonymous":true, '.
                                '"username" : '.$userName.
                                ', "via" : "'.$message->getVia().'"} }';


        $response = Request::post( 
                WisemblyAPIConnection::API_ADDRESS .'/'.
                WisemblyAPIConnection::API_V4 . '/event/'.$event->getKeyword().'/quotes')
                ->addHeader(SessionAPI::WISE_TOKEN_HEADER, $this->token)
                ->sendsJson()
                ->body($body)
                ->send();
        
        if ($response->code != WisemblyAPIConnection::SUCCESSFUL_REQUEST_CODE){

            throw new \InvalidArgumentException
                    ("Error while trying to connect to the API : ");
        }
        
    }
    
    private function createStreamFromResponse(\Httpful\Response $response)
    {
        $messages = array();
                
        foreach($response->body->success->data as $currentRawMessage){
            
            $messages[] = $this->parseMessage($currentRawMessage);
        }
        
        return new MessageStream($messages);
    }
    
    private function parseMessage($rawMessage)
    {

        return new Message( $rawMessage->published_at, 
                            $rawMessage->quote, 
                            $rawMessage->hash, 
                            $rawMessage->via);
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
