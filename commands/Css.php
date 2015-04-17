<?php

use compact\io\reader\StreamReader;
use compact\io\writer\StreamWriter;
use compact\utils\FormattingUtils;

class Css
{

    private $console;
       
    public function __construct($console)
    {
        $this->console = $console;	
    }
    
    public function minify($cssFile=null){
        $cssFile = $cssFile ? $cssFIle : $this->console->input( "Css file location: " );
        $cssFile = new \SplFileInfo($cssFile);
        
        $css = "";
        $reader = new StreamReader( $cssFile );
        $reader->open();
        while (! $reader->eof())
        {
            $css .= $reader->read(1024);
        }
        $reader->close();
        
        $minified = $this->minifyCss( $css );
        
        $this->console->writeln( "Original: " . FormattingUtils::formatSize( strlen( $css ) ) );
        $this->console->writeln( "Minified: " . FormattingUtils::formatSize( strlen( $minified ) ) );
        $this->console->writeln( "Saved   : " . $this->calcSaving( $css, $minified ) . '%');
        
        $isWrite = $this->console->input( "Do you want to write the minified file to disc? (y/n)" );
        if (strtolower( $isWrite ) === 'y')
        {
            $filename = $cssFile->getPath() . "/" . $cssFile->getBasename( '.css' ) . '.min.css';
            	
            $writer = new StreamWriter( $filename, 'wb' );
            $writer->open();
            $writer->write( $minified );
            $writer->close();
        }
    }
    
    private function minifyCss( $aCss )
    {
        $regex = array("/\/\*(.+?)\*\//ism" => "" , 		/* strip comments */
            "/\t|\s+/" => "$1" , 		/* white space */
            "/\s?([{|}|:|;|,|\(|\)])\s?/" => "$1"); // spaces before and after , : ; { }
    
    
        return preg_replace( array_keys( $regex ), $regex, $aCss );
    }
    
    private function calcSaving( $aOriginal, $aMinified )
    {
        $oSize = strlen( $aOriginal );
        $mSize = strlen( $aMinified );
    
        return round( (($oSize - $mSize) / $oSize) * 100, 2 );
    }
}
