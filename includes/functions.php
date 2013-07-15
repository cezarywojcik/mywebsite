<?php

function getArticle($id) {
    global $mysqli;
    $queryString = "SELECT * FROM blog WHERE blogid=$id";
    $query = $mysqli->query($queryString);
    $row = $query->fetch_assoc();
    $row['timecreated'] = date('F j, Y', strtotime($row['timecreated']));
    return $row;
}

function getArticlePreview($id) {
    $article = getArticle($id);
    $more = "<a href='/blog/$id'>Read More...</a>";
    $article['content'] = preg_replace('/\<preview\>.+/s', $more,
        $article['content']);
    return $article;
}

function getMaxArticleId() {
    global $mysqli;
    $queryString = "SELECT MAX(blogid) FROM blog";
    $query = $mysqli->query($queryString);
    $row = $query->fetch_assoc();
    return $row['MAX(blogid)'];
}
