<?php

require_once __DIR__ . '/vendor/autoload.php';

use Aifrus\METAR\METAR;

$metar = METAR::fetch('LFRL');
print_r($metar);
