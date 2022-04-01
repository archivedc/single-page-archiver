<?php
require_once '_dbinit.php';

$list = $db->query("SELECT time, url, title FROM history ORDER BY time DESC LIMIT 30");

function xss(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8', false);
}
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
                <a href="data/<?= $entry['time'] ?>/<?= str_replace('%2F', '/', rawurldecode($entry['url'])) ?>">
                    <?= xss($entry['title'] ?? $entry['url']) ?>
                </a>
                (<a href="https://<?= $entry['url'] ?>" title="<?= xss($entry['url']) ?>">original</a>)
                - <?= xss(date('Y/m/d H:i:s', $entry['time'])) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>