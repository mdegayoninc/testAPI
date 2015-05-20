<?php
namespace MDegayon\WiseAPI;

use MDegayon\Cache\CacheInterface as Cache;

/**
 * Class encapsulating API stats. Save API usage to the application cache system
 *
 * @author Miguel Degayon
 */
class APIStats
{
    
    private static $instance;
    private $cache = false,
            $dataArray = array();
    
    
    public function init(Cache $cache)
    {
        
        $this->cache = $cache;
       
        //Check if stats already exist in cache. 
        $cachedAPIStat = $this->cache->get(WisemblyAPIConnection::API_STATS_KEY);        
        
        if($cachedAPIStat){
            
            $this->dataArray = $cachedAPIStat->dataArray;
            
        }else{

            $this->cache->set(WisemblyAPIConnection::API_STATS_KEY, $this);
        }
    }
    
    public static function getInstance()
    {
      if (  !self::$instance instanceof self)
      {
         self::$instance = new self;
      }
      return self::$instance;
   }    
   
    public function addApiDataUsage( \MDegayon\WiseAPI\APIInfoConnection $data)
    {       
       //Add data to stats
       $this->dataArray[] = $data;
       
       //Update stats from cache
       $this->cache->set(WisemblyAPIConnection::API_STATS_KEY, $this);
   }
   
    public function getData(){
       
       return $this->dataArray;
    }
   
    public function setData($data)
    {
       $this->dataArray = $data;
    }
    
}

?>
