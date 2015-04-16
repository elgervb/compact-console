<?php

include 'lib/compact.phar';
include './Console.php';

$c = new Console();
$input = $c->input("Your message");
$c->writeln($input);