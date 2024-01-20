<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$metar = METAR::fetch('KJFK');
print_r($metar);
