<?php

class Encode
{
    private $console;
    
    public function __construct($console)
    {
    	$this->console = $console;
    }
    
    public function base64($content=null){
        if($content === null){
            $content = $this->console->input("String:\n");
        }
        
        $this->console->writeln(base64_encode($content));
    }
}