<?php

// get array of results from executed prepared statement
function fetch($result) {
    $array = array();
    if($result instanceof mysqli_stmt) {
        $result->store_result();
        $variables = array();
        $data = array();
        $meta = $result->result_metadata();
        while($field = $meta->fetch_field()) {
            $variables[] = &$data[$field->name];
        }
        call_user_func_array(array($result, 'bind_result'), $variables);
        $i=0;
        while($result->fetch()) {
            $array[$i] = array();
            foreach($data as $k=>$v) {
                $array[$i][$k] = $v;
            }
            $i++;
        }
    } else if($result instanceof mysqli_result) {
        while($row = $result->fetch_assoc())
            $array[] = $row;
    }
    return $array;
}

// find whether article exists
function articleExists($id) {
    global $mysqli;
    $exists = false;
    $queryString = "SELECT title FROM blog WHERE blogid=?";
    if ($statement = $mysqli->prepare($queryString)) {
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = fetch($statement);
        if (count($result) > 0) {
            $exists = true;
        }
    }
    return $exists;
}

// get contents of article
function getArticle($id) {
    if (!articleExists($id)) {
        return false;
    }
    global $mysqli;
    $result = array();
    $queryString = "SELECT * FROM blog WHERE blogid=?";
    if ($statement = $mysqli->prepare($queryString)) {
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = fetch($statement)[0];
        $statement->close();
        $result['timecreated'] = date('F j, Y',
            strtotime($result['timecreated']));
        if (substr($result['content'], 0, 3) === "MD-") { // markdown tag
            include_once("includes/CWMarkdown.php");
            $md = new CWMarkdown(substr($result['content'], 3,
                strlen($result['content'])));
            $result['content'] =$md->getHTMLString();
        }
        $result['content'] .= "<div class='article-footer'>~</div>";
        $prevId = $result['blogid']-1;
        $nextId = $result['blogid']+1;
        $nav = "";
        if ($prevId != 0) {
            $nav .= "<a class='prev' href='/blog/$prevId'>";
            $nav .= "Previous Post</a>";
        }
        if ($nextId <= getMaxArticleId()) {
            $nav .= "<a class='next' href='/blog/$nextId'>";
            $nav .= "Next Post</a>";
        }
        $result['nav'] = $nav;
    }
    return $result;
}

// get contents of an article preview
function getArticlePreview($id) {
    $article = getArticle($id);
    $more = "<a href='/blog/$id'>Read More...</a>";
    $article['content'] = preg_replace('/\<preview\>.+/s', $more,
        $article['content']);
    $article['nav'] = "";
    return $article;
}

// get latest article id
function getMaxArticleId() {
    global $mysqli;
    $result = array();
    $queryString = "SELECT MAX(blogid) FROM blog";
    if ($statement = $mysqli->prepare($queryString)) {
        $statement->execute();
        $result = fetch($statement)[0];
        $statement->close();
        return $result['MAX(blogid)'];
    }
    return 0;
}

// get article of blog posts
function getBlogPostList() {
    global $mysqli;
    $content = "<ul>";
    $queryString = "SELECT * FROM blog ORDER BY timecreated DESC";
    if ($statement = $mysqli->prepare($queryString)) {
        $statement->execute();
        $posts = fetch($statement);
        foreach($posts as $post) {
            $id = $post['blogid'];
            $title = $post['title'];
            $date = date('F j, Y', strtotime($post['timecreated']));
            $content .= "<li>$date - <a href='/blog/$id'>$title</a></li>";
        }
        $statement->close();
    }
    $content .= "</ul>";
    $content .= '<a href="http://feedburner.google.com/fb/a/mailverify?uri=CezaryWojciksBlog&amp;loc=en_US">Subscribe to My Blog by Email</a>';
    $result = array(
        'title' => 'My Blog Posts',
        'content' => $content);
    return $result;
}
