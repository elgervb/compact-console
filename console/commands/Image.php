<?php
namespace console\commands;

use imagemanipulation\color\Color;
use imagemanipulation\ImageBuilder;
use console\ConsoleException;
use imagemanipulation\filter\ImageFilterFlip;

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
    
    public function brightness($image, $rate = 5){
        $this->builder($image)->brightness($rate)->save();
    }
    
    public function colorize($image, $color = 'FFFFFF', $alpha = null){
        $this->builder($image)->colorize($color, $alpha)->save();
    }
    
    public function comic($image, $opacity = 40){
        $this->builder($image)->comic($opacity)->save();
    }
    
    public function contrast($image, $rate = 5){
        $this->builder($image)->contrast($rate)->save();
    }
    
    public function darken($image, $rate = 5){
        $this->builder($image)->darken($rate)->save();
    }
    
    public function dodge($image, $rate = 50){
        $this->builder($image)->dodge($rate)->save();
    }
    
    public function edgedetect($image){
        $this->builder($image)->edgeDetect()->save();
    }
    
    public function emboss($image){
        $this->builder($image)->emboss()->save();
    }
    
    public function findedges($image){
        $this->builder($image)->findEdges()->save();
    }
    
    public function flip($image, $direction = null){
        if ($direction === 'horizontal'){
            $direction = ImageFilterFlip::FLIP_HORIZONTALLY;
        }
        else if ($direction === "vertical"){
            $direction = ImageFilterFlip::FLIP_VERTICALLY;
        }
        else{
            $direction = ImageFilterFlip::FLIP_BOTH;
        }
        $this->builder($image)->flip($direction)->save();
    }
    
    public function gamma($image, $inupt = 1.0, $output = 1.537){
        $this->builder($image)->gammaCorrection($input, $output)->save();
    }
    
    public function gaussianblur($image){
        $this->builder($image)->gaussianBlur()->save();
    }
    
    public function greyscale($image){
        $this->builder($image)->grayscale()->save();
    }
    
    public function meanremove($image){
        $this->builder($image)->meanremove()->save();
    }
    
    public function motionblur($image){
        $this->builder($image)->motionBlur()->save();
    }
    
    public function negative($image){
        $this->builder($image)->negative()->save();
    }
    
    public function noise($image, $rate = 20){
        $this->builder($image)->noise($rate)->save();
    }
    
    public function opacity($image, $rate = 50){
        $this->builder($image)->opacity($rate)->save();
    }
    
    public function pixelate($image, $rate = 20){
        $this->builder($image)->pixelate($rate)->save();
    }
    
    public function replace($image, $search, $replace){
        $this->builder($image)->pixelate($search, $replace)->save();
    }
    
    public function rotate($image, $degrees, $bgcolor=null){
        $this->builder($image)->pixelate($search, $degrees, $bgcolor)->save();
    }
    
    public function scatter($image, $offset = 20){
        $this->builder($image)->scatter($offset)->save();
    }
    
    public function selective($image){
        $this->builder($image)->selectiveBlur()->save();
    }
    
    public function septia($image, $darken = 15){
        $this->builder($image)->septia($darken)->save();
    }
    
    public function sharpen($image){
        $this->builder($image)->sharpen()->save();
    }
    
    public function smooth($image, $rate = 5){
        $this->builder($image)->smooth($rate)->save();
    }
    
    public function sobel($image){
        $this->builder($image)->sobelEdgeDetect()->save();
    }
    
    public function vignette($image){
        $this->builder($image)->vignette()->save();
    }
}
