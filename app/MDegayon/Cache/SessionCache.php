<?php
namespace MDegayon\Cache;

use Symfony\Component\HttpFoundation\Session\Session as Session;
/**
 * Simple cache using session
 *
 * @author Miguel Degayon
 */
class SessionCache implements \MDegayon\Cache\CacheInterface
{
    
    private static $instance;
    private $session = false;
    
    public function __construct(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function init(Session $session){
        $this->session = $session;
    }
    
   public static function getInstance(){
      if (  !self::$instance instanceof self)
      {
         self::$instance = new self;
      }
      return self::$instance;
   }    
    
    public function get($key) 
    {
        if($this->session){
            
            return $this->session->get($key, null);
        }else{
            throw new \Exception('Uninitialized Cache');
        }
    }

    public function set($key, $value) 
    {
        if($this->session){
            $this->session->set($key, $value);
        }else{
            throw new \Exception('Uninitialized Cache');
        }
    }
}

?>
