<?php

if (!isset($argv[1]) || !is_numeric($argv[1]))
    die('No OPT');

require_once __DIR__ . '/../_config.php';
require_once __DIR__ . '/../_dbinit.php';

$basedatadir = ($websrv_archivedir ?? '/var/www/html/data');
$scandir = $basedatadir . DIRECTORY_SEPARATOR . $argv[1];

function scanfiles($path)
{
    $toret = array();
    $items = array_diff(scandir($path), array('..', '.'));
    foreach ($items as $item)
    {
        $itemp = $path . DIRECTORY_SEPARATOR . $item;
        if (is_dir($itemp))
        {
            $toret = array_merge($toret, scanfiles($itemp));
        }
        elseif (is_file($itemp))
        {
            array_push($toret, $itemp);
        }
    }
    return $toret;
}

$files = scanfiles($scandir);

# FROM: https://stackoverflow.com/a/18850550
function compareFiles($file_a, $file_b)
{
    if (filesize($file_a) != filesize($file_b))
        return false;

    $chunksize = 4096;
    $fp_a = fopen($file_a, 'rb');
    $fp_b = fopen($file_b, 'rb');
        
    while (!feof($fp_a) && !feof($fp_b))
    {
        $d_a = fread($fp_a, $chunksize);
        $d_b = fread($fp_b, $chunksize);
        if ($d_a === false || $d_b === false || $d_a !== $d_b)
        {
            fclose($fp_a);
            fclose($fp_b);
            return false;
        }
    }
 
    fclose($fp_a);
    fclose($fp_b);
          
    return true;
}

foreach ($files as $file)
{
    $rfpath = substr($file, strlen($basedatadir) + 1);
    $hash = hash_file($file_hash_type ?? 'sha256', $file);
    print($rfpath . "\t" . $hash . "\n");
    
    $samehash_entries = $db->query('SELECT id, path FROM files WHERE hash=%s0', $hash);
    $found = false;
    if (count($samehash_entries) > 0)
    {
        foreach($samehash_entries as $she)
        {
            if (compareFiles($file, $basedatadir . DIRECTORY_SEPARATOR . $she['path']))
            {
                if ($basedatadir . DIRECTORY_SEPARATOR . $she['path'] === $file)
                {
                    print("\t=> ON DB!\n");
                    $found = true;
                    break;
                }

                unlink($file);
                symlink($basedatadir . DIRECTORY_SEPARATOR . $she['path'], $file);
                
                $db->insert('files', [
                    'path' => $rfpath,
                    'linkid' => $she['id'],
                ]);
                
                print("\t=> " . $she['id'] . ": " . $she['path'] . "\n");

                $found = true;
                break;       
            }
        }
    }

    if ($found) continue;

    $db->insert('files', [
        'path' => $rfpath,
        'hash' => $hash,
    ]);
    print("\t=> NEW\n");
}