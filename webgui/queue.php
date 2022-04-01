<?php
ini_set("max_execution_time", 0);
require_once '_config.php';
require_once '_dbinit.php';

if (!isset($_GET['url']) && !isset($_POST['url'])) {
    die('No URL specified');
}

$url = isset($_GET['url']) ? $_GET['url'] : $_POST['url'];

if (!filter_var($url, FILTER_VALIDATE_URL))
{
    die('Not valid URL');
}

$parsed_url = parse_url($url);
if ($parsed_url === false)
    die ('Not valid url');

$utime = time();

exec("sudo docker run --rm -v $hostdir/$utime:/data $image -nc -k -t 5 -p -E -H -e robots=off --no-check-certificate \"$url\"", $output);

$host = $parsed_url['host'];
$path = $parsed_url['path'];

if (!empty($parsed_url['query'])) $path .= '?' . $paesed_url['query'];

$path = '/' . ltrim($path, '/');


# From: https://stackoverflow.com/a/399357
function page_title($url) {
    $fp = file_get_contents($url);
    if (!$fp) 
        return null;

    $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
    if (!$res) 
        return null; 

    // Clean up title: remove EOL's and excessive whitespace.
    $title = preg_replace('/\s+/', ' ', $title_matches[1]);
    $title = trim($title);
    return $title;
}

$inst_path = $path;
$eval_path = mb_strtolower($path);

$title = null;
if (str_ends_with($eval_path, '.html') || str_ends_with($eval_path, '.htm')){
    $title = page_title("data/$utime/$host$path");
}
elseif (str_ends_with($eval_path, '/') && is_file("data/$utime/$host$path" . 'index.html'))
{
    $title = page_title("data/$utime/$host$path" . 'index.html');
}
elseif (is_file("data/$utime/$host$path.html"))
{
    $title = page_title("data/$utime/$host$path.html");
}

$db->insert('history', [
    'time' => strval($utime),
    'url' => "$host$path",
    'title' => $title,
]);

$path = rawurlencode($path);
$path = str_replace('%2F', '/', $path);

exec("screen -d -m php /var/www/html/scripts/link.php $utime");

http_response_code(303);
header("Location: data/$utime/$host$path");
?>