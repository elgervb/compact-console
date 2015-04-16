<?php

use compact\utils\FormattingUtils;
class Fs
{

    public function __construct()
    {
    	
    }
    public function cd($dir){
        if (!@chdir($dir)){
            return "Could not change directory to: " . $dir;
        }
    }
    
    public function cleartemp($console){
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
        
        $console->writeln($msg);
        $input = $console->input("Do you want to clear temp dir? (y / n) ");
        
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
                    $console->writeln("Delete file " . $file);
                }
                else{
                    $console->writeln("Failed to delete file " . $file);
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
                    $console->writeln("Delete dir " . $file);
                }
                else{
                    $console->writeln("Failed to delete dir " . $file);
                }
            }
        }
    }
    
    public function pwd(){
        return getcwd();
    }
}