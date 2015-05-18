<?php
namespace MDegayon\Cache;

/**
 * Simple cache using session
 *
 * @author Miguel Degayon
 */
class SessionCache implements \MDegayon\Cache\CacheInterface
{
    
    private static $instance;
    
    public function __construct(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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
        return  isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public function set($key, $value) 
    {
        $_SESSION[$key] = $value;
    }
}

?>
