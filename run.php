<?php

include 'lib/compact.phar';
include './Console.php';

$c = new Console();
$c->writeln("PHP Console");

// create endless loop and receive commands
while (true){
    $input = $c->input("", "exit");
    
    // check if we should exit
    if ($input === "exit" || $input === "quit"){
        exit();
    }
    
    // run the command
    $c->run($input);
}