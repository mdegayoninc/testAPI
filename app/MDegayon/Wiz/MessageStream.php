<?php
namespace MDegayon\Wiz;

use MDegayon\Wiz\Message as Message;
use MDegayon\Wiz\WizEvent as WizEvent;

/**
 * Description of MessageStream
 *
 * @author Laura
 */
class MessageStream 
{
    
    private $messages,
            $wizOwner;
    
    public function __construct($messages = array() , WizEvent $wiz = null)
    {        
        $this->wizOwner = $wiz;
        $this->messages = $messages;
        
    }
    
    public function setOwner(WizEvent $event)
    {
        $this->wizOwner = $event;
    }
    
    public function addMessage(Message $msg)
    {
        $this->messages[] = $msg;
        
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
    
    
}

?>
