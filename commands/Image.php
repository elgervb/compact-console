<?php

use imagemanipulation\color\Color;
use imagemanipulation\ImageBuilder;

class Image
{

    private $console;
    private $builder;
    
    public function __construct($console)
    {
    	$this->console = $console;
    }
    
    private function builder($image){
        $fileName = null;
        if (is_file($image)){
            $fileName = $image;
        }
        else if(is_file(getcwd() . DIRECTORY_SEPARATOR . $image)){
            $fileName = getcwd() . DIRECTORY_SEPARATOR . $image;
        }
        else{
            throw new ConsoleException("File not found " . $image);
        }
        
        return new ImageBuilder(new \SplFileInfo($fileName));
    }
    
    
    public function greyscale($image){
        $this->builder($image)->grayscale()->save();
    }
}
