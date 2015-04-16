<?php

class Console
{
    /**
     * Create a new console
     */
    public function __construct()
    {
        date_default_timezone_set( 'UTC' );
        
        compact\ClassLoader::create();
    }
    
    /**
     * Ask the user for input
     *
     * @param $aMessage string The message to show to the user
     * @param $aDefault string fall back to the default when the user leaves it empty
     */
    public function input( $aMessage = null, $aDefault = "" )
	{
		if ($aMessage != null)
		{
			$this->write( $aMessage . " " );
		}
		
		$result = trim( fgets( STDIN ) );
		
		return empty( $result ) ? $aDefault : $result;
	}
	
	/**
	 * Run a command an show the result in the console
	 * @param string $command
	 */
	public function run($command){
	    
	    $parts = explode(".", $command);
	    $className=ucwords($parts[0]);
	    $method=$parts[1];
	    
	    if($className && $method && @include_once('commands/'.$className.'.php') ){
	        $class = new $className();
               if (method_exists($class, $method)){
                   $result = $class->$method();
                   
                   if (is_scalar($result)){
                       $this->writeln($result);
                       $this->writeln("");
                   }
               }
               else{
                   $this->writeln($className . ' exists, but ' . $method . ' not');
               }
	    }else{
	        $this->writeln("Could not find command " . $command);
	    }
	   
	}
    
    /**
     * Write a message to the console
     * @param string $aMsg
     * @return Console
     */
    public function write( $aMsg )
    {
        fwrite( STDOUT, $aMsg );
        
        return $this;
    }
    
    /**
     * Write a message to the console
     * @param unknown $aMsg
     * @return Console
     */
    public function writeln( $aMsg )
    {
        $this->write( $aMsg . "\n" );
        return $this;
    }
}
