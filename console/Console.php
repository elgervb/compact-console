<?php
namespace console;

use compact\handler\AssertHandler;
use compact\handler\ErrorHandler;
use compact\logging\Logger;
use console\commands\helpers\AliasHelper;
use compact\logging\recorder\impl\FileRecorder;

class Console
{
    const VERSION = "0.0.1";
    
    /**
     * Create a new console
     */
    public function __construct()
    {
        date_default_timezone_set( 'UTC' );
        
        \compact\ClassLoader::create();
        
        AssertHandler::enable();
        new ErrorHandler(- 1, true, true, './error.log');
        new Logger( new FileRecorder(new \SplFileInfo(__DIR__ . '/../console.log') ), Logger::ALL);
        new ExceptionHandler();
        
        Logger::get()->logFine("created new logger");
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
	    
	    Logger::get()->logFine("Got original command " . $command);
	    // first see if there is an alias
	    $command = $this->getCommand($command);
	    
	    // explode into a class- and method name
	    $args = explode(" ", $command);
	    $parts = explode(".", array_shift( $args ) );
	    $className='\\console\\commands\\'.ucfirst($parts[0]);
	    if (count($parts) > 1){
    	    $method=$parts[1];
	    }
	    else{
	        $method = "";
	    }
	       
	    $commandFile = realpath(__DIR__.'/..') . $className.'.php';
	    Logger::get()->logFinest("Check command file " . $commandFile);
	    
	    if($className && $method && is_file($commandFile) ){
	        try{
	           include_once($commandFile);
	           
    	       $class = new $className($this);
               if (method_exists($class, $method)){
                   
                   $result = call_user_func_array(array($class, $method), $args);
                   
                   if (is_scalar($result)){
                       $this->writeln($result);
                       $this->writeln("");
                   }
               }
               else{
                   $this->writeln("Could not find command " . $command . ' in ' . $className);
               }
	        }catch(\Exception $ex){
	            $this->writeln($ex->getMessage());
	            $this->writeln($ex->getTraceAsString());
	            $this->writeln("\n");
	        }
	    }else{
	        $this->writeln("Could not find command " . $command);
	    }
	}
	
	/**
	 * received a command and checks for an alias (if present). When the alias was found, the mapped command is returned
	 * @param unknown $command
	 * @return Ambigous <string, NULL>
	 */
	private function getCommand($command){
	    $arguments = "";
	    $match = preg_match("/(.*) (.*)/i", $command, $matches);
	    
	    $search = $command;
	    if ($match){
	        $search = $matches[1];
	        if (count($matches) > 2){
	           $arguments = $matches[2];
	        }
	    }
	    Logger::get()->logFinest("Check for alias " . $search);
	    $aliasCommand = AliasHelper::getCommandFor($search);
	    if ($aliasCommand !== null){
	        Logger::get()->logFine("Found command " . $aliasCommand . " for alias " . $command . " with args" .$arguments);
	        $command = $aliasCommand . " " . $arguments;
	    }
	    
	    return $command;
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
