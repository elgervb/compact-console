<?php
namespace console\commands;

use compact\hash\HashFactory;
use compact\hash\decoder\Md5Decoder;

class Encode
{
    private $console;
    
    public function __construct( Console $console)
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
            $this->console->writeln($file . " is not a valid file.");
        }
    }
    
    public function dehash($string=null, $method=null){
        $string = $string ? $string : $this->console->input("String:\n");
        $method = $method ? $method : $this->console->input("Hash method [MD5]:\n", "md5");
        
        $time_start = microtime(true);
        $decoder = new MD5Decoder($method, Md5Decoder::DEFAULT_DEPTH, true, 100000);
        $this->console->writeln("Match found! Password = " . $decoder->run($hash));
        
        $this->console->writeln("Executed in " . round((microtime(true) - $time_start), 4) . " sec");
    }
    
    public function hash($string=null, $method=null){
        $string = $string ? $string : $this->console->input("String:\n");
        $method = $method ? $method : $this->console->input("Hash method [MD5]:\n", "md5");
        
        try{
            $encoder = HashFactory::createHash($method);
        
            $this->console->writeln($method);
            $this->console->writeln($encoder->encrypt($string));
        }catch(\Exception $ex){
            $this->console->writeln($ex->getMessage());
        }
    }
}