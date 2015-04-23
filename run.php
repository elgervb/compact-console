<?php
use console\Console;

require 'lib/compact.phar';
require 'lib/imagemanipulation.phar';
require 'console/Console.php';

$c = new Console();
$c->writeln("PHP Console version " . Console::VERSION . "\n\n");

// create endless loop and receive commands
while (true){
    
    $input = $c->input(getcwd().">", "exit");
    
    // check if we should exit
    if ($input === "exit" || $input === "quit"){
        exit();
    }
    
    // run the command
    $c->run($input);
}