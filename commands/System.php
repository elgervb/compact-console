<?php

use compact\utils\FormattingUtils;
class System
{
    private $console;
    
    public function __construct($console)
    {
        $this->console = $console;	
    }
    
    public function memory(){
        return FormattingUtils::formatSize(memory_get_usage());
    }
    
    public function pwd(){
        return getcwd();
    }
}