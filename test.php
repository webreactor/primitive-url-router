<?php

require 'vendor/autoload.php';

$config = [
    'handler',
    'config' => 'index lovely config',
    'nodes' => [
        'news' => ['config' => 'news lovely config',],
        'catalog' => ['config' => 'catalog lovely config',],
    ],
];



$r = new \Reactor\PrimitiveRouter\Router();

$c = $r->routeStr('/news', $config);

print_r($c);
