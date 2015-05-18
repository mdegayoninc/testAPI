<?php
namespace MDegayon\Wiz;
/**
 * Wisembly Event 
 *
 * @author Miguel Degayon
 */
class WizEvent
{
    
    private $name, 
            $keyword, 
            $stream;
    
    public function __construct($name, $keyword)
    {
        $this->name = $name;
        $this->keyword = $keyword;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getKeyword()
    {
        return $this->keyword;
    }
    
    public function getStream()
    {
        return $this->stream;
    }
    
    public function setStream($stream)
    {
        $this->stream = $stream;
    }
    
}

?>
