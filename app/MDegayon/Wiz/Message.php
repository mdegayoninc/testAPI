<?php
namespace MDegayon\Wiz;

/**
 * Message sent to Event stream
 *
 * @author Miguel Degayon
 */
class Message {
    
    private $date, 
            $quote;
    
    public function __construct($date, $quote) 
    {
        $this->date = $date;
        $this->quote = $quote;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getQuote()
    {
        return $this->quote;
    }
    
}

?>
