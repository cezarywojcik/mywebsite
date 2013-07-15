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

// OTHER
$app->get('/:other+', function() use ($twig) {
    // render
    echo $twig->render('404.twig', array(
        'title' => '404'));
});

// ---- [ slim run ] ----------------------------------------------------------

$app->run();
