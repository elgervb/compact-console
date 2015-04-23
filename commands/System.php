<?php
namespace console\commands;

use compact\utils\FormattingUtils;
use commands\helpers\AliasHelper;

class System
{
    private $console;
    
    public function __construct($console)
    {
        $this->console = $console;	
    }
    
    public function alias($alias, $command){
        if (strstr($alias, " ") || strstr($alias, ".")){
            $this->console->writeln("Error: the alias cannot cantain spaces or .");
        }
        AliasHelper::add($alias, $command);
    }
    
    public function memory(){
        return FormattingUtils::formatSize(memory_get_usage());
    }
    
    public function pwd(){
        return getcwd();
    }
}