<?php
require_once '_config.php';
require_once '_shared.php';
require_once '_dbinit.php';

$sr = null;
if (isset($_GET['q']) && !empty($_GET['q']))
    $sr = $db->query('SELECT url AS path, title, time FROM history WHERE title LIKE %ss', $_GET['q']);
elseif (isset($_GET['url']) && !empty($_GET['url']))
    $sr = $db->query("SELECT path, time FROM files WHERE path = %s", $_GET['url']);
else
    die('Not valid search');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.css">
    <title>Search Archives</title>
</head>
<body>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Title/URL</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sr as $r) : ?>
               <tr>
                   <td>
                        <a href="data/<?= $r['time'] ?>/<?= str_replace('%2F', '/', rawurldecode($r['path'])) ?>">
                            <?= xss($r['title'] ?? $r['path']) ?>
                        </a>
                    </td>
                    <td><?=xss(date('Y/m/d H:i:s', $r['time']))?></td>
               </tr> 
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>