<?php
namespace MDegayon\Cache;

/**
 *
 * @author Miguel Degayon
 */
interface CacheInterface {
    
    public function get($key);    
    public function set($key, $value);
}

?>
