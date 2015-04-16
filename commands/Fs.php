<?php

class Fs
{

    public function __construct()
    {
    	
    }
    
    public function pwd(){
        return getcwd();
    }
}