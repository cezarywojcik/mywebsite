<?php

// ---- [ includes ] ----------------------------------------------------------

require 'config/db.php';
require 'config/slim.php';
require 'config/twig.php';

// ---- [ slim routing ] ------------------------------------------------------

$app->get('/', function() use ($twig) {
    echo $twig->render('index.twig');
});

// ---- [ slim run ] ----------------------------------------------------------

$app->run();
