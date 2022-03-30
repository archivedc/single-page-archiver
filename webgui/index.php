<?php
require_once '_dbinit.php';

$list = $db->query("SELECT time, url FROM history ORDER BY time DESC LIMIT 30");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Page Archive</title>
</head>
<body>
    <form action="queue.php" method="post">
        <div>
            <label for="url">Archive URL</label>
            <input type="url" name="url" id="url">
        </div>
        <input type="submit" value="Archive">
    </form>
    <ul>
        <?php foreach ($list as $entry) : ?>
            <li>
                <a href="data/<?= $entry['time'] ?>/<?= $entry['url'] ?>"><?= $entry['url'] ?> - <?= date('Y/m/d H:i:s', $entry['time']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>