<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;
use RPurinton\HTTPS\HTTPSRequest;

$all = explode("\n", HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/ALL']));
foreach ($all as $key => $line) {
    echo "\r$key/", count($all);
    try {
        new METAR($line);
    } catch (\Exception $e) {
        echo "Exception\n" . $e->getMessage() . "\nOn line: $line\n";
    }
}
echo "done!\n";
