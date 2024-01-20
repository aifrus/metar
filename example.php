<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

if (!isset($argv[1])) die("Usage: php example.php <ICAO>\n");
$metar = METAR::fetch($argv[1]);
print_r($metar);
