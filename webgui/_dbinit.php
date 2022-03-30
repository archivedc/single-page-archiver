<?php
require_once '_config.php';
require_once 'vendor/autoload.php';

$db = new MeekroDB($dbhost, $dbuser, $dbpass, $dbname, null, 'utf8mb4');

$db->query('CREATE TABLE IF NOT EXISTS history(id INT NOT NULL auto_increment PRIMARY KEY, time INT NOT NULL, url TEXT NOT NULL) DEFAULT CHARSET = utf8mb4');
$db->query('CREATE TABLE IF NOT EXISTS files(id INT NOT NULL auto_increment PRIMARY KEY, path TEXT NOT NULL, hash TEXT DEFAULT NULL, linkid INT DEFAULT NULL) DEFAULT CHARSET = utf8mb4');
