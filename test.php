<?php

require 'vendor/autoload.php';

$config = [
    'handler',
    'config',
    'nodes' => [

    ],
    'arguments' => [

    ]
];



$r = new \Reactor\PrimitiveRouter\Router($config);


$c = $r->route($method, '/index');

print_r($c);
