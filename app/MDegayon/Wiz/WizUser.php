<?php
namespace MDegayon\Wiz;

/**
 * Class Representing an user of Wisembly Application
 *
 * @author Laura
 */
class WizUser
{
    
    private $hash,      
            $name,
            $email,
            $company;
    
    public function __construct($hash, $name, $email,$company) 
    {
        $this->hash = $hash;
        $this->name = $name;
        $this->email = $email;
        $this->company = $company;
    }
    
    public function getHash()
    {
        return $this->hash;
    }
    public function getName()
    {
        return $this->name;
    }
}
?>
