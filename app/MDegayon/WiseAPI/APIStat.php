<?php
namespace MDegayon\WiseAPI;


/**
 * Class encapsulating API usage stats
 *
 * @author Miguel Degayon
 */
class APIStat 
{
    private $method,
            $date,
            $token,
            $user;
    
    public function __construct($method, $date, $token, $user) 
    {
        
        $this->method = $method;
        $this->date = $date;
        $this->token = $token;
        $this->user = $user;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function getUser()
    {
        return $this->user;
    }
}

?>
