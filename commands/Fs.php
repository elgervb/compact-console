<?php

use compact\utils\FormattingUtils;
class Fs
{
    private $console;
    
    public function __construct($console)
    {
        $this->console = $console;	
    }
    
    public function cd($dir){
        if (!@chdir($dir)){
            return "Could not change directory to: " . $dir;
        }
    }
    
    public function cleartemp(){
        $tmpDir = sys_get_temp_dir();
        $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator( $tmpDir ),\RecursiveIteratorIterator::CHILD_FIRST);
        
        $nrOfDirs = 0;
        $nrOfFiles = 0;
        $totalSize = 0;
        // show MB's
        /* @var $file \SplFileInfo */
        foreach ($iter as $file)
        {
            if ($file->isDir())
            {
        
                $nrOfDirs++;
            }
            else
            {
                $totalSize += $file->getSize();
                $nrOfFiles++;
            }
        }
        
        $msg = "Found " . FormattingUtils::formatSize($totalSize) . " in " . number_format($nrOfDirs, 0, ',', '.') . " directories and " . number_format($nrOfFiles, 0, ',', '.') . " files.";
        
        $this->console->writeln($msg);
        $input = $this->console->input("Do you want to clear temp dir? (y / n) ");
        
        if ($input !== "y"){
            return;
        }
        
        // remove all files
        foreach ($iter as $file)
        {
            if ($file->isFile())
            {
                $result = @unlink( $file );
                if ($result){
                    $this->console->writeln("Delete file " . $file);
                }
                else{
                    $this->console->writeln("Failed to delete file " . $file);
                }
        
            }
        }
        // remove all dirs
        $iter->rewind();
        /* @var $dir \SplFileInfo */
        foreach ($iter as $dir)
        {
            if ($dir->isDir())
            {
                $result = @rmdir($dir);
                if ($result){
                    $this->console->writeln("Delete dir " . $file);
                }
                else{
                    $this->console->writeln("Failed to delete dir " . $file);
                }
            }
        }
    }
    
    public function ls(){
        $workingDir = $this->pwd();
        $dir = new \DirectoryIterator( $workingDir );
        
        /* @var $file \SplFileInfo */
        foreach ($dir as $file)
        {
            $relative = $this->makeString(str_replace( $workingDir, "", $file ), 50);
            if ($file->isDir())
            {
                $this->console->writeln( $relative );
            }
            else
            {
                $this->console->writeln( $relative . " " . FormattingUtils::formatSize( $file->getSize() ) . "" );
            }
        }
    }
    
    public function pwd(){
        return getcwd();
    }
    
    private function makeString($string, $maxChars){
        $length = strlen($string);
        if ($length === $maxChars){
            return $string;
        }
        else if($length < $maxChars){
            return $string . str_repeat(" ", $maxChars - $length);
        }
        else{
            return substr($string, 0, $maxChars-3) . '...';
        }
    }
}