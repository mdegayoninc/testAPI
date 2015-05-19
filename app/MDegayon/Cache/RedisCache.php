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
            
    const   SERIALIZED_OBJECT_KEY = 'serializedObject',
            CLASS_KEY = 'objectClass';    
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
            
            $value = $this->redis->get($key, null);
            
            //Check if $value is a serialized object. If so, deserialize it 
            
            $jsonArray = json_decode($value, true);
            if($this->isSerializedObject($jsonArray)){
               $value = $this->deserializeObject($jsonArray);
            }
        }else{
            throw new \Exception('Uninitialized Cache');
        }
    }

    public function set($key, $value) 
    {
         is_object();
         
        if($this->redis && $this->serializer){
            
            $this->redis->set($key, $value);
        }else{
            throw new \Exception('Uninitialized Cache');
        }      
    }  
    
    private function serializeObject($object){
                
        return $this->serializer->serialize(
                                array(  RedisCache::SERIALIZED_OBJECT_KEY => $object, 
                                        RedisCache::CLASS_KEY => get_class($object),), 
                                'json');
    }
    
    private function deserializeObject($jsonArray){
                
//        $jsonArray = json_decode($jsonData, true);
        
        return $this->serializer->deserialize
                                    (json_encode($jsonArray[RedisCache::SERIALIZED_OBJECT_KEY]), 
                                     $jsonArray[RedisCache::CLASS_KEY], 
                                     'json');
        
    }
    
    private function isSerializedObject( $jsonData ){
        
        return  array_key_exists(RedisCache::SERIALIZED_OBJECT_KEY,$jsonData) && 
                array_key_exists(RedisCache::CLASS_KEY,$jsonData);
    }
}

?>
