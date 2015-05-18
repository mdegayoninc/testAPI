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
     * The the stream from the user's first event
     *
     * @return (MDegayon\Wiz\Stream)
     */    
    public function getFirstStream(){
      
        $firstStream = false;
        
        $events = $this->api->getUserEvents($this->user->getHash());
        
        if(sizeof($events) > 0){
            
            //Create Event Helper
            
            //Get event stream from event's helper 
            
            //and set it as the event's stream
            
            //Return Stream
        }
    }
    
}

?>
