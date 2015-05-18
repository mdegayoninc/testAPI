<?php
namespace MDegayon\Command;

/**
 * Description of GetFirstUserStreamCommande
 *
 * @author Miguel Degayon
 */
class UserFirstStreamCommande implements \MDegayon\Command\CommandInterface
{
    
    private $user, $API;
    
    public function __construct(\MDegayon\Wiz\WizUser $user,
                                \MDegayon\WiseAPI\SessionAPI $API) {
        
        $this->user = $user;
        $this->API = $API;
    }
    
    
    public function execute() {
        
        
    }    
}

?>
