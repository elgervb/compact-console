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
    
    public function base64file($file, $exportFile=null){
        if (is_file($file)){
            if (!$exportFile){
                $exportFile = $file . ".base64";
            }
        
            $this->console->writeln("Exporting file " . $exportFile);
            $fp = fopen( $exportFile, "wb");
            fwrite($fp, base64_encode(file_get_contents($file)));
            fclose($fp);
        }
        else{
            $this->console->writeln($file . " is not a valid file. Please check path.");
        }
    }
}