<?php

// get array of results from executed prepared statement
function fetch($result) {
    $array = array();
    if($result instanceof mysqli_stmt)
    {
        $result->store_result();
        $variables = array();
        $data = array();
        $meta = $result->result_metadata();
        while($field = $meta->fetch_field())
            $variables[] = &$data[$field->name];
        call_user_func_array(array($result, 'bind_result'), $variables);
        $i=0;
        while($result->fetch())
        {
            $array[$i] = array();
            foreach($data as $k=>$v)
                $array[$i][$k] = $v;
            $i++;
        }
    }
    elseif($result instanceof mysqli_result)
    {
        while($row = $result->fetch_assoc())
            $array[] = $row;
    }

    return $array;
}

// get contents of article
function getArticle($id) {
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
    }
    return $result;
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
    $result = array();
    $queryString = "SELECT MAX(blogid) FROM blog";
    if ($statement = $mysqli->prepare($queryString)) {
        $statement->execute();
        $result = fetch($statement)[0];
        $statement->close();
    }
    return $result['MAX(blogid)'];
}
