<?php

header("Content-Type: application/rss+xml; charset=ISO-8859-1");

global $mysqli;

$output = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Cezary Wojcik\'s Blog</title>
    <description>
      The Official Blog of Cezary Wojcik
    </description>
    <link>http://www.cezarywojcik.com/blog</link>
    <copyright>Copyright Cezary Wojcik 2012</copyright>
    <language>en-US</language>
    <atom:link href="http://cezarywojcik.com/rss" rel="self" type="application/rss+xml" />
';

$queryString = "SELECT * FROM blog ORDER BY timeCreated DESC LIMIT 25";
$result = $mysqli->query($queryString);

while ($row = $result->fetch_assoc()) {
    $title = $row['title'];
    $blogid = $row['blogid'];
    $pubDate =  date('D, d M Y H:i:s T', strtotime($row['timecreated']));;
    $link = "http://cezarywojcik.com/blog/$blogid";
    $output .= "    <item>
      <title>$title</title>
      <pubDate>$pubDate</pubDate>
      <link>$link</link>
      <guid>$link</guid>
    </item>
";
}

$output .= '  </channel>
</rss>';

echo $output;
