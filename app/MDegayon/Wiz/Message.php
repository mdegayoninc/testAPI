<?php
namespace MDegayon\Wiz;

/**
 * Message sent to Event stream
 *
 * @author Miguel Degayon
 */
class Message {
    
    private $date, 
            $hash,
            $user,
            $via,
            $quote;
    
    const VIA_DEFAULT = 'web';
    
    public function __construct($date, $quote, $hash, $via, $user = null) 
    {
        $this->date = $date;
        $this->quote = $quote;
        $this->hash = $hash;
        $this->user = $user;
        $this->via = $via;
    }
    
    public function getVia()
    {
        return $this->via;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getQuote()
    {
        return $this->quote;
    }
    
    public function getHash()
    {
        return $this->hash;
    }
    
    public function getUser()
    {
        return $this->user;
    }    
    
}

?>
