<?php

// ---- [ includes ] ----------------------------------------------------------

require 'includes/config/db.php';
require 'includes/config/slim.php';
require 'includes/config/twig.php';
require 'includes/functions.php';

// ---- [ slim routing ] ------------------------------------------------------

// HOME
$app->get('/', function() use ($twig) {
    // latest blog post
    $article = getArticlePreview(getMaxArticleId());
    // render
    echo $twig->render('home.twig', array(
        'title' => 'Home',
        'article' => $article));
});

// BLOG
$app->get('/blog', function() use ($twig) {
    // render
    echo $twig->render('basic.twig', array(
        'title' => 'Blog',
        'article' => getBlogPostList()));
});

// BLOG POST
$app->get('/blog/:id', function($id) use ($twig) {
    // latest blog post
    $article = getArticle($id);
    // render
    if (!$article) {
        echo $twig->render('404.twig');
    } else {
        echo $twig->render('blogpost.twig', array(
            'title' => $article['title'],
            'article' => $article));
    }
});

// ABOUT
$app->get('/about', function() use ($twig) {
    // render
    echo $twig->render('about.twig', array(
        'title' => 'About Me'));
});

// CONTACT
$app->get('/contact', function() use ($twig) {
    // render
    echo $twig->render('contact.twig', array(
        'title' => 'Contact Me'));
});
$app->post('/contact', function() use ($twig) {
    // handle php form
    require 'includes/contact.php';
    // render
    echo $twig->render('contact.twig', array(
        'title' => 'Contact Me',
        'output' => $output,
        'name' => $name,
        'email' => $email,
        'message' => $message));
});

// GALLERY
$app->get('/gallery', function() use ($twig) {
    // render
    echo $twig->render('gallery.twig', array(
        'title' => 'Gallery'));
});

// RSS FEED
$app->get('/rss', function() {
    require 'includes/rss.php';
});

// OTHER
$app->get('/:other+', function($other) use ($twig) {
    // projects
    $notFound = true;
    if (count($other) === 2 && $other[1] === "") {
        $arg = $other[0];
        $notFound = false;
        if ($arg === "darkpassage") {
            require 'darkpassage/index.html';
        } else if ($arg === "gameoflife2") {
            require 'gameoflife2/index.html';
        } else if ($arg === "ratboard") {
            require 'ratboard/index.php';
        } else if ($arg === "barchartd3js") {
            require 'barchartd3js/index.html';
        } else if ($arg === "webciv") {
            require 'webciv/index.html';
        } else {
            $notFound = true;
        }
    }
    if ($notFound) { // not found
        echo $twig->render('404.twig');
    }
});

// ---- [ slim run ] ----------------------------------------------------------

$app->run();
