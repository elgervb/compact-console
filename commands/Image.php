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
    
    public function contrast($image, $rate = 5){
        $this->builder($image)->contrast($rate)->save();
    }
    
    public function greyscale($image){
        $this->builder($image)->grayscale()->save();
    }
    
    public function pixelate($image, $rate = 20){
        $this->builder($image)->pixelate($rate)->save();
    }
    
    public function scatter($image, $offset = 20){
        $this->builder($image)->scatter($offset)->save();
    }
}
