<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;
use RPurinton\HTTPS\HTTPSRequest;

$interested = ['KBGR', 'KPWM', 'KSFM', 'KMHT', 'KPSM', 'KBOS'];

$all = explode("\n", HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/ALL']));
foreach ($all as $key => $line) {
    echo "\r$key/", count($all) . '...';
    try {
        $metar = new METAR($line);
        if (in_array($metar->stationIdentifier, $interested)) echo "\n$metar\n";
        if ($metar->winds->variableFrom) echo "\n$metar\n";
    } catch (\Exception $e) {
        echo "\nException: " . $e->getMessage() . "\nOn line: $line\n";
    }
}
echo "done!\n";
