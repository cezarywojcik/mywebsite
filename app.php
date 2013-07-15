<?php

// ---- [ includes ] ----------------------------------------------------------

require 'config/db.php';
require 'config/slim.php';
require 'config/twig.php';

// ---- [ slim routing ] ------------------------------------------------------

$app->get('/', function() use ($twig) {
    echo $twig->render('home.twig', array(
        'title' => 'Home'));
});

// ---- [ slim run ] ----------------------------------------------------------

$app->run();
