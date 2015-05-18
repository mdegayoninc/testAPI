<?php
namespace MDegayon\RemoteHelper;

use MDegayon\WiseAPI\SessionAPI as API;
/**
 * Used to access to API functions related with Wisembly Streams
 *
 * @author Miguel Degayon
 */
class WizEventHelper 
{
      
    private $api, $event;
    
    public function __construct(API $api, \MDegayon\Wiz\WizEvent $event) 
    {
        $this->api = $api;
        $this->event = $event;
    }
    
    /*
     * Function getFirstStream
     *
     * Get the the stream from the user's first event from API
     *
     * @return (MDegayon\Wiz\Stream)
     */    
    public function addMessageToStream($txtQuote)
    {      
        $quote  = $this->createMessage($txtQuote);
                    
        $this->api->addMessageToStream($this->event, $quote);   
    }
    
    private function createMessage($txtQuote)
    {
        $messageDate = strtotime("now");
        date_default_timezone_set("UTC");
        
        $date = date("c", $messageDate);
        $via = \MDegayon\Wiz\Message::VIA_DEFAULT;
        $hash = $this->calcQuoteHash($txtQuote, $via);

        
        return new \MDegayon\Wiz\Message($date, $txtQuote, $hash, $via) ;        
    }
    
    private function calcQuoteHash($txtQuote, $via){
        
        return 'qWeRtYu';
    }
     
}

?>
