<?php
ini_set("max_execution_time", 0);
require_once '_config.php';
require_once '_dbinit.php';

if (!isset($_GET['url']) && !isset($_POST['url'])) {
    die('No URL specified');
}

$url = isset($_GET['url']) ? $_GET['url'] : $_POST['url'];

$parsed_url = parse_url($url);
if ($parsed_url === false)
    die ('Not valid url');

$utime = time();

exec("sudo docker run --rm -v $hostdir/$utime:/data $image -nc -k -t 5 -p -E -H -e robots=off --no-check-certificate \"$url\"", $output);
exec("screen -d -m php /var/www/html/scripts/link.php $utime");

$host = $parsed_url['host'];
$path = $parsed_url['path'];

if (!empty($parsed_url['query'])) $path .= '?' . $paesed_url['query'];

$path = '/' . ltrim($path, '/');

$db->insert('history', [
    'time' => strval($utime),
    'url' => "$host$path",
]);

$path = rawurlencode($path);
$path = str_replace('%2F', '/', $path);

http_response_code(303);
header("Location: data/$utime/$host$path");
?>