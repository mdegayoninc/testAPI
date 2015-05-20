<?php
namespace MDegayon\Cache;

use Symfony\Component\Serializer\Serializer;
use Predis\Client;
/**
 *  An application cache system using Regis
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
            
            $value = $this->redis->get($key);
            
            //Check if $value is a serialized object. If so, deserialize it 
            
            $jsonArray = json_decode($value, true);
            if($this->isSerializedObject($jsonArray)){
               $value = $this->deserializeObject($jsonArray);
            }
        }else{
            throw new \Exception('Uninitialized Cache');
        }
        
        return $value;
    }

    public function set($key, $value) 
    {
        if($this->redis && $this->serializer){
            
            if(is_object($value)){
                 
                $this->redis->set($key, $this->serializeObject($value)); 
                
            }else{
                
                $this->redis->set($key, $value);      
            }
        }else{
            throw new \Exception('Uninitialized Cache');
        }      
    }  
    
    private function serializeObject($object)
    {
                
        return $this->serializer->serialize(
                                    array(  RedisCache::SERIALIZED_OBJECT_KEY => $object, 
                                            RedisCache::CLASS_KEY => get_class($object),), 
                                    'json');
    }
    
    private function deserializeObject($jsonArray)
    {
        
        $phpObject = $this->serializer->deserialize
                                    (json_encode($jsonArray[RedisCache::SERIALIZED_OBJECT_KEY]), 
                                     $jsonArray[RedisCache::CLASS_KEY], 
                                     'json');
        
        //TODO : Complex classes like APIStats should have their own serialize/deserialize methods 
        // (using a Serializable interface for example)
        if($jsonArray[RedisCache::CLASS_KEY] == 'MDegayon\WiseAPI\APIStats'){
            
            $data = array();
            foreach ($jsonArray[RedisCache::SERIALIZED_OBJECT_KEY]['data'] as $currentData) {
                
                $data[] = new  \MDegayon\WiseAPI\APIInfoConnection
                                    ($currentData['method'], $currentData['date'], 
                                        $currentData['token'], $currentData['user']);
                
            }
            $phpObject->setData($data);
        }

        return $phpObject;        
    }
    
    private function isSerializedObject( $jsonData )
    {
        
        return  is_array($jsonData) &&
                array_key_exists(RedisCache::SERIALIZED_OBJECT_KEY,$jsonData) && 
                array_key_exists(RedisCache::CLASS_KEY,$jsonData);
    }
}

?>
