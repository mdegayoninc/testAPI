<?php
namespace MDegayon\Conf;

/**
 * Application config
 *
 * @author Miguel Degayon
 */
class Config {

    private $params = array(
        
        'app_id' => 'miguel',
        'app_secret' => 'wisembly',
        'email' => 'mdegayon@gmail.com',
        'secret' => 'chachadita',
    );
    
    public function getParam($key){
        
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }
    
}

?>
