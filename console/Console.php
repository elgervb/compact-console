<?php
namespace console;

use compact\handler\AssertHandler;
use compact\handler\ErrorHandler;
use compact\logging\Logger;
use commands\helpers\AliasHelper;
use compact\logging\recorder\impl\BufferedFileRecorder;

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
        new Logger( new BufferedFileRecorder(new \SplFileInfo(__DIR__ . '/../console.log') ), Logger::ALL);
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
	    
	    Logger::get()->logFine("Got command " . $command);
	    // first see if there is an alias
	    $command = $this->getCommand($command);
	    
	    $args = explode(" ", $command);
	    $parts = explode(".", array_shift( $args ) );
	    $className=ucwords($parts[0]);
	    if (count($parts) > 1){
    	    $method=$parts[1];
	    }
	    else{
	        $method = "";
	    }
	       
	    $commandFile = __DIR__ . '/commands/'.ucfirst($className).'.php';
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
	    if (strstr($command, " ")){
	        $parts = explode(" ", $command);
	        $command = trim($parts[0]) . " " . trim(substr($command, strlen($parts[0]) - strlen($command)));
	    }
	    $aliasCommand = AliasHelper::getCommandFor($command);
	    if ($aliasCommand !== null){
	        $command = $aliasCommand;
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
