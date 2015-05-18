<?php
namespace MDegayon\Wiz;

use MDegayon\Wiz\Message;
use MDegayon\Wiz\WizEvent;

/**
 * Description of MessageStream
 *
 * @author Laura
 */
class MessageStream 
{
    
    private $messages,
            $wizOwner;
    
    public function __construct(WizEvent $wiz, $messages = array())
    {        
        $this->wizOwner = $wiz;
        $this->messages = $messages;
        
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
