<?php

require_once 'vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader);
