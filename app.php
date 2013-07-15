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
    echo $twig->render('blogpost.twig', array(
        'title' => 'Blog',
        'article' => getBlogPostList()));
});

// BLOG POST
$app->get('/blog/:id', function($id) use ($twig) {
    // latest blog post
    $article = getArticle($id);
    // render
    echo $twig->render('blogpost.twig', array(
        'title' => $article['title'],
        'article' => $article));
});

// ---- [ slim run ] ----------------------------------------------------------

$app->run();
