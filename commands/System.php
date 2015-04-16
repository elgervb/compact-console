<?php

use compact\utils\FormattingUtils;
class System
{

    public function __construct()
    {
    	
    }
    
    public function memory(){
        return FormattingUtils::formatSize(memory_get_usage());
    }
    
    public function pwd(){
        return getcwd();
    }
}