<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Aifrus\METAR\METAR;

$all = explode("\n", file_get_contents('https://metar.vatsim.net/ALL'));

foreach ($all as $key => $line) {
    echo "\r" . $key + 1 . "/" . count($all) . '...';
    try {
        $metar = new METAR($line);
    } catch (\Exception $e) {
        echo "\nException: " . $e->getMessage() . "\nOn line: $line\n";
        exit();
    }
}
echo "done!\n";
