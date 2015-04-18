<?php

use imagemanipulation\ImageBuilder;

class Image
{

    private $console;
    private $builder;
    
    public function __construct($console)
    {
    	$this->console = $console;
    	$this->builder = new ImageBuilder();
    }
}
