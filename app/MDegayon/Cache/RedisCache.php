<?php
namespace MDegayon\Cache;

/**
 * Description of RedisCache
 *
 * @author Laura
 */
class RedisCache implements \MDegayon\Cache\CacheInterface{
    
    
    
    private static $instance;
    private $redis = false;
    
    
    public static function getInstance()
    {
       if (  !self::$instance instanceof self)
       {
          self::$instance = new self;
       }
       return self::$instance;
    }
    
    public function init(Session $session)
    {
        $this->session = $session;
    }    
    
    public function get($key) 
    {
        
        if($this->redis){
            
            return $this->redis->get($key, null);
        }else{
            throw new \Exception('Uninitialized Cache');
        }
    }

    public function set($key, $value) 
    {
        if($this->redis){
            $this->redis->set($key, $value);
        }else{
            throw new \Exception('Uninitialized Cache');
        }      
    }    
}

?>
