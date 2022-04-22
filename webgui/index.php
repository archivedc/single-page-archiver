<?php
require_once '_shared.php';
require_once '_dbinit.php';

$list = $db->query("SELECT time, url AS path, title FROM history WHERE unlisted = 0 ORDER BY time DESC LIMIT 30");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.css">
    <title>Single Page Archive</title>
</head>
<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <form action="queue.php" method="post">
                    <div class="mb-3">
                        <label for="url" class="form-label">Archive URL</label>
                        <input type="url" name="url" class="form-control" id="url">
                    </div>
                    <button type="submit" class="btn btn-primary">Archive</button>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <form action="search.php" method="get">
                    <div class="mb-3">
                        <label for="title" class="form-label">Article Title</label>
                        <input type="text" name="q" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="path" class="form-label">Content Path</label>
                        <input type="text" name="url" class="form-control" id="path">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Title/URL</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list as $r) : ?>
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