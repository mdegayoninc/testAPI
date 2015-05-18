<?php
namespace MDegayon\RemoteHelper;

use MDegayon\Wiz\WizUser as WizUser;
use MDegayon\WiseAPI\SessionAPI as API;
/**
 * Used to access to API functions related with Wisembly users
 *
 * @author M. Degayon
 */
class WizUserHelper
{
    
    private $api, $user;
    
    public function __construct(API $api, WizUser $user) 
    {
        $this->api = $api;
        $this->user = $user;
    }
    
    /*
     * Function getFirstStream
     *
     * Get the the stream from the user's first event from API
     *
     * @return (MDegayon\Wiz\Stream)
     */    
    public function getFirstStream()
    {
      
        $firstStream = false;
        
        $events = $this->api->getUserEvents($this->user->getHash());
        
        if(sizeof($events) > 0){
            
            //Use API to get the message's stream of the first event 
            $firstEvent = $events[0];
            
            $firstStream = $this->api->getStream($firstEvent->getKeyword());
            
            //and set it as the event's stream
            $firstStream->setOwner($firstEvent);
            
        }
        //Return Stream
        return $firstStream;
    }
    
    /*
     * Function getFirstEvent
     *
     * Get user's first event from API
     *
     * @return (MDegayon\Wiz\WizEvent)
     */  
    public function getFirstEvent()
    {
        $firstEvent = false;
        
        $events = $this->api->getUserEvents($this->user->getHash());
        
        if(sizeof($events) > 0){
            
            //Get first event from user events array
            $firstEvent =  $events[0];
        }
        
        return $firstEvent;        
    }    
}

?>
