<?php

include 'lib/compact.phar';
include 'lib/imagemanipulation.phar';
include './Console.php';

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