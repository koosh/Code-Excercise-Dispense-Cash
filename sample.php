<?php

// assumes composer install and autoloader generation.
require_once 'vendor/autoload.php';

$dispenser = new \CashDispenser\Dispenser([100, 50, 20, 10]);

var_dump($dispenser->defaultOrderDispense(30));

var_dump($dispenser->defaultOrderDispense(80));

var_dump($dispenser->defaultOrderDispense(125));
