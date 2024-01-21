<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;
use RPurinton\HTTPS\HTTPSRequest;

$all = HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/ALL']);
$all = explode("\n", $all);
$count = count($all);
$counter = 0;
$metars = [];
foreach ($all as $line) {
    $counter++;
    if ($counter % 100 === 0) echo "\r$counter/$count...";
    $metar = trim($metar);
    if (empty($metar)) continue;
    try {
        $metars[] = new METAR($metar);
    } catch (\Exception $e) {
        echo "Exception\n" . $e->getMessage() . "\nOn line: $metar\n";
    }
}
echo "done!\n";
