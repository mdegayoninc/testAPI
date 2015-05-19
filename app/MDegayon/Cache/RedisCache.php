<?php
namespace MDegayon\Cache;

use Symfony\Component\Serializer\Serializer;
use Predis\Client;
/**
 * Description of RedisCache
 *
 * @author Laura
 */
class RedisCache implements \MDegayon\Cache\CacheInterface
{
            
    private static $instance;
    private $redis = false,
            $serializer = false;
    
    public static function getInstance()
    {
       if (  !self::$instance instanceof self)
       {
          self::$instance = new self;
       }
       return self::$instance;
    }
    
    public function init( Client $redis,  Serializer $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }    
    
    public function get($key) 
    {
        
        if($this->redis && $this->serializer){
            
            return $this->redis->get($key, null);
        }else{
            throw new \Exception('Uninitialized Cache');
        }
    }

    public function set($key, $value) 
    {
        if($this->redis && $this->serializer){
            
            $this->redis->set($key, $value);
        }else{
            throw new \Exception('Uninitialized Cache');
        }      
    }    
}

?>
